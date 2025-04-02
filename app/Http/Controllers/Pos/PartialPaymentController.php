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
        ]);

        $payment = PartialPayment::create($request->all());

        // تحديث الفواتير
        $this->applyPaymentToInvoices($payment);
        $notification = array(
            'message' => 'Payment added and applied successfully.',
            'alert-type' => 'success'
        );
        $customers = Customer::latest()->get();

        return view('backend.customer.customer_all', compact('customers'))->with($notification);

    }
    protected function applyPaymentToInvoices(PartialPayment $payment)
    {
        $invoices = Payment::where('customer_id', $payment->customer_id)
            ->where('due_amount', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $amountToApply = $payment->amount;

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
