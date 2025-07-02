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

    protected function applyPaymentToInvoices(PartialPayment $payment, $netAmount)
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
    }

}
