<?php

namespace App\Http\Controllers\pos;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PartialPayment;
use App\Models\Payment;
use Illuminate\Http\Request;

class PartialPaymentController extends Controller
{
    public function create($id)
    {

        // يمكنك التحقق من وجود العميل وتضمينه في البيانات التي سترسل إلى العرض

        $customer = Customer::findOrFail($id);


        return view('backend.customer.add_pind', compact('customer'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash_shekel,cash_dinar,check'
        ]);

        // تعيين القيم العربية للعرض
        $paymentMethods = [
            'cash_shekel' => 'نقدي شيكل',
            'cash_dinar' => 'نقدي دينار',
            'check' => 'شيك'
        ];

        $netAmount = $request->amount - ($request->discount_amount ?? 0);

        $payment = PartialPayment::create([
            'customer_id' => $request->customer_id,
            'amount' => $request->amount,
            'discount_amount' => $request->discount_amount ?? 0,
            'net_amount' => $netAmount,
            'payment_method' => $request->payment_method, // تخزين القيمة الإنجليزية
            'payment_date' => $request->payment_date,
            'notes' => $request->notes
        ]);

        $this->applyPaymentToInvoices($payment, $netAmount);

        $notification = [
            'message' => 'تم إضافة الدفعة بنجاح وتطبيقها على الفواتير.',
            'alert-type' => 'success'
        ];

        return redirect()->route('customer.all')->with($notification);
    }
    public function edit($id)
    {
        $payment = PartialPayment::findOrFail($id);
        $customer = $payment->customer;
        return view('backend.customer.edit_partial_payment', compact('payment', 'customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash_shekel,cash_dinar,check'
        ]);

        $partialPayment = PartialPayment::findOrFail($id);
        $oldNetAmount = $partialPayment->net_amount;
        $newNetAmount = $request->amount - ($request->discount_amount ?? 0);

        // معالجة الفواتير أولاً
        $this->updateInvoicePayments($partialPayment, $oldNetAmount, $newNetAmount);

        // ثم تحديث بيانات الدفعة
        $partialPayment->update([
            'amount' => $request->amount,
            'discount_amount' => $request->discount_amount ?? 0,
            'net_amount' => $newNetAmount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('customer.all')->with([
            'message' => 'تم تحديث الدفعة بنجاح.',
            'alert-type' => 'success'
        ]);
    }

    protected function handlePaymentUpdate(PartialPayment $payment, $oldAmount, $newAmount, $difference)
    {
        if ($difference == 0) {
            return; // لا يوجد تغيير في المبلغ
        }

        // استرجاع المبلغ القديم أولاً
        $this->reverseFullPayment($payment, $oldAmount);

        // تطبيق المبلغ الجديد
        $this->applyPaymentToInvoices($payment, $newAmount);
    }

    protected function reverseFullPayment(PartialPayment $payment, $amountToReverse)
    {
        // نسترجع المبلغ بنفس الترتيب العكسي للدفع
        $invoices = Payment::where('customer_id', $payment->customer_id)
            ->where('paid_amount', '>', 0)
            ->orderBy('created_at', 'desc') // من الأحدث إلى الأقدم
            ->get();

        foreach ($invoices as $invoice) {
            if ($amountToReverse <= 0) break;

            $reverseAmount = min($amountToReverse, $invoice->paid_amount);
            $invoice->paid_amount -= $reverseAmount;
            $invoice->due_amount += $reverseAmount;
            $invoice->save();

            $amountToReverse -= $reverseAmount;
        }
    }

    protected function updateInvoicePayments(PartialPayment $payment, $oldAmount, $newAmount)
    {
        // 1. استرجاع المبلغ القديم بالكامل
        $this->reversePaymentFromInvoices($payment, $oldAmount);

        // 2. تطبيق المبلغ الجديد بالكامل
        $this->applyPaymentToInvoices($payment, $newAmount);
    }

    protected function reverseFromLatestInvoice($customerId, $amountToReverse)
    {
        // الحصول على آخر فاتورة مدفوعة (من الأحدث إلى الأقدم)
        $latestPaidInvoice = Payment::where('customer_id', $customerId)
            ->where('paid_amount', '>', 0)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latestPaidInvoice) {
            // لا نسمح بخصم أكثر من المبلغ المدفوع في الفاتورة
            $reverseAmount = min($amountToReverse, $latestPaidInvoice->paid_amount);

            $latestPaidInvoice->paid_amount -= $reverseAmount;
            $latestPaidInvoice->due_amount += $reverseAmount;
            $latestPaidInvoice->save();
        }
    }




    protected function adjustInvoicesAfterUpdate(PartialPayment $payment, $difference)
    {
        // 1. أولاً نسترجع جميع الفواتير المرتبطة بهذه الدفعة سابقاً
        $affectedInvoices = $payment->invoicePayments()->with('invoice')->get();

        // 2. نرجع المبالغ المدفوعة إلى الفواتير الأصلية
        foreach ($affectedInvoices as $invoicePayment) {
            $invoice = $invoicePayment->invoice;
            $invoice->due_amount += $invoicePayment->amount;
            $invoice->paid_amount -= $invoicePayment->amount;
            $invoice->save();

            // حذف سجل التوزيع القديم
            $invoicePayment->delete();
        }

        // 3. نطبق المبلغ الجديد للدفعة (بعد التعديل) على الفواتير
        $this->applyPaymentToInvoices($payment, $payment->net_amount);
    }

    protected function applyPaymentToInvoices(PartialPayment $payment, $amount)
    {
        $invoices = Payment::where('customer_id', $payment->customer_id)
            ->where('due_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($invoices as $invoice) {
            if ($amount <= 0) break;

            $paymentAmount = min($amount, $invoice->due_amount);
            $invoice->due_amount -= $paymentAmount;
            $invoice->paid_amount += $paymentAmount;
            $invoice->save();

            $amount -= $paymentAmount;
        }
    }

    protected function reversePaymentFromInvoices(PartialPayment $payment, $amount)
    {
        $invoices = Payment::where('customer_id', $payment->customer_id)
            ->where('paid_amount', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($invoices as $invoice) {
            if ($amount <= 0) break;

            $reverseAmount = min($amount, $invoice->paid_amount);
            $invoice->paid_amount -= $reverseAmount;
            $invoice->due_amount += $reverseAmount;
            $invoice->save();

            $amount -= $reverseAmount;
        }
    }

    /*   protected function applyPaymentToInvoices(PartialPayment $payment, $netAmount)
    {
        $invoices = Payment::where('customer_id', $payment->customer_id)
            ->where('due_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $amountToApply = $netAmount;

        foreach ($invoices as $invoice) {
            if ($amountToApply <= 0) break;

            $paymentAmount = min($amountToApply, $invoice->due_amount);
            $invoice->due_amount -= $paymentAmount;
            $invoice->paid_amount += $paymentAmount;

            $invoice->save();

            $amountToApply -= $paymentAmount;
        }
    } */
}
