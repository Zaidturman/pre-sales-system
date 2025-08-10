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
        $customer = Customer::findOrFail($id);
        
        // جلب الفواتير التي لها مستحقات
        $invoices = Payment::where('customer_id', $id)
            ->where('due_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('backend.customer.add_pind', compact('customer', 'invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash_shekel,cash_dinar,check',
            'invoice_id' => 'nullable|exists:invoices,id' // إضافة اختيار الفاتورة
        ]);

        // حساب المبلغ الصافي
        $netAmount = $request->amount - ($request->discount_amount ?? 0);

        // إنشاء الدفعة
        $payment = PartialPayment::create([
            'customer_id' => $request->customer_id,
           
            'amount' => $request->amount,
            'discount_amount' => $request->discount_amount ?? 0,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
        ]);

        // تطبيق الدفعة على الفواتير
        $this->applyPaymentToInvoices($payment, $netAmount );

        return redirect()->route('customer.all')->with([
            'message' => 'تم إضافة الدفعة بنجاح',
            'alert-type' => 'success'
        ]);
    }

    public function edit($id)
    {
        $payment = PartialPayment::findOrFail($id);
        $customer = $payment->customer;
        
        // جلب الفواتير المتاحة لهذا الزبون
        $invoices = Payment::where('customer_id', $customer->id)
            ->where('due_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('backend.customer.edit_partial_payment', compact('payment', 'customer', 'invoices'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash_shekel,cash_dinar,check',
            'invoice_id' => 'nullable|exists:invoices,id'
        ]);

        $payment = PartialPayment::findOrFail($id);
        $oldNetAmount = $payment->net_amount;
        $newNetAmount = $request->amount - ($request->discount_amount ?? 0);

        // استرجاع المبلغ القديم من الفواتير
        $this->reversePaymentFromInvoices($payment, $oldNetAmount);

        // تحديث بيانات الدفعة
        $payment->update([
            'invoice_id' => $request->invoice_id,
            'amount' => $request->amount,
            'discount_amount' => $request->discount_amount ?? 0,
            'net_amount' => $newNetAmount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
        ]);

        // تطبيق المبلغ الجديد على الفواتير
        $this->applyPaymentToInvoices($payment, $newNetAmount);

        return redirect()->route('customer.all')->with([
            'message' => 'تم تحديث الدفعة بنجاح',
            'alert-type' => 'success'
        ]);
    }

    public function destroy($id)
    {
        $payment = PartialPayment::findOrFail($id);
        $netAmount = $payment->net_amount;

        // استرجاع المبلغ من الفواتير
        $this->reversePaymentFromInvoices($payment, $netAmount);

        // حذف الدفعة
        $payment->delete();

        return redirect()->route('customer.all')->with([
            'message' => 'تم حذف الدفعة بنجاح',
            'alert-type' => 'success'
        ]);
    }

    // ============ الوظائف المساعدة ============

    protected function applyPaymentToInvoices(PartialPayment $payment, $amount)
{
    $appliedInvoices = collect(); // لتخزين الفواتير التي تم تطبيق الدفعة عليها
    
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

        // ربط الدفعة بالفاتورة مع المبلغ المطبق
$payment->invoices()->attach($invoice->invoice_id, [
    'amount' => $paymentAmount,
    'created_at' => now(),
    'updated_at' => now(),
]);        $appliedInvoices->push($invoice->invoice_id);

        $amount -= $paymentAmount;
    }

   
}

  protected function reversePaymentFromInvoices(PartialPayment $payment, $amount)
{
    // استرجاع المبلغ من الفواتير المرتبطة بهذه الدفعة
    $invoices = $payment->invoices()->orderBy('created_at', 'desc')->get();

    foreach ($invoices as $invoice) {
        if ($amount <= 0) break;

        $pivotAmount = $invoice->pivot->amount;
        $reverseAmount = min($amount, $pivotAmount);

        $paymentInvoice = Payment::where('invoice_id', $invoice->id)->first();
        if ($paymentInvoice) {
            $paymentInvoice->paid_amount -= $reverseAmount;
            $paymentInvoice->due_amount += $reverseAmount;
            $paymentInvoice->save();
        }

        // تحديث المبلغ في جدول العلاقة
        if ($pivotAmount == $reverseAmount) {
            $payment->invoices()->detach($invoice->id);
        } else {
            $payment->invoices()->updateExistingPivot($invoice->id, [
                'amount' => $pivotAmount - $reverseAmount
            ]);
        }

        $amount -= $reverseAmount;
    }
}
}