<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\PurchasePayment;

use Auth;
use Illuminate\Support\Carbon;

class SupplierController extends Controller
{
    public function SupplierAll(){
        // $suppliers = Supplier:: all();
        $suppliers = Supplier:: latest()->get();
        return view('backend.supplier.supplier_all',compact('suppliers'));
    }  
    
    public function SupplierAdd(){
        return view('backend.supplier.supplier_add');
    }
    public function SupplierView($id){
        $supplier = Supplier::FindOrFail($id);
        $Payments = PurchasePayment::where('supplier_id', $id)
            ->whereIn('paid_status', ['full_due', 'partial_paid', 'full_paid'])
            ->get();

        return view('backend.supplier.supplier_view', compact('supplier', 'Payments'));

    }
    public function SupplierCredit(){

        $allData = PurchasePayment::whereIn('paid_status', ['full_due', 'partial_paid'])
            ->with('supplier')
            ->get();
        return view('backend.supplier.supplier_credit',compact('allData'));
   
    }
    public function SupplierEditInvoice($purchase_id)
    {
        $payment = PurchasePayment::where('purchase_id', $purchase_id)->first();
        return view('backend.supplier.edit_supplier_invoice', compact('payment'));
    }
    public function SupplierUpdateInvoice(Request $request, $invoice_id)
    {
         if ($request->new_paid_amount < $request->paid_amount) {
            $notification = array(
                'message' => 'Sorry You Paid Maximum Value',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        } else {
            $payment = PurchasePayment::where('purchase_id', $invoice_id)->first();
            $payment->paid_status = $request->paid_status;

            if ($request->paid_status == 'full_paid') {
                $payment->paid_amount = PurchasePayment::where('purchase_id', $invoice_id)->first()['paid_amount'] + $request->new_paid_amount;
                $payment->due_amount = '0';
                // $payment->paid_status = 'full_paid';
            } elseif ($request->paid_status == 'partial_paid') {
                $payment->paid_amount = PurchasePayment::where('purchase_id', $invoice_id)->first()['paid_amount'] + $request->paid_amount;
                $payment->due_amount = PurchasePayment::where('purchase_id', $invoice_id)->first()['due_amount'] - $request->paid_amount;
            } else {
                $notification = array(
                    'message' => 'Invalid Data',
                    'alert-type' => 'error'
                );

                return redirect()->back()->with($notification);
                dd('Invalid Data');
            }
            // dd(Payment::where('invoice_id',$invoice_id)->first()['due_amount']);
            $payment->purchase_id = $invoice_id;
            $payment->save();

            $notification = array(
                'message' => 'Invoice Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('credit.supplier')->with($notification);
        } 
    }
    public function CreditSupplier()
    {
        $allData = PurchasePayment::whereIn('paid_status', ['full_due', 'partial_paid'])->get();
        return view('backend.supplier.supplier_credit', compact('allData'));
    }
    public function SupplierStore(Request $request){
        Supplier::insert([
            'name'=>$request->name,
            'mobile_no'=>$request->mobile_no,
            'email'=>$request->email,
            'address'=>$request->address,
            'created_by'=>Auth::user()->id,
            'created_at'=>Carbon::now(),
        ]);

        $notification=array(
            'message'=>'Supplier Inserted Successfully',
            'alert-type'=>'success'
        );

        return redirect()->route('supplier.all')->with($notification);
    }

    public function SupplierEdit($id){
        $supplier = Supplier::FindOrFail($id);
        return view('backend.supplier.supplier_edit',compact('supplier'));
    }

    public function SupplierUpdate(Request $request){
        $supplier_id=$request->id;
        Supplier::FindOrFail($supplier_id)->update([
            'name'=>$request->name,
            'mobile_no'=>$request->mobile_no,
            'email'=>$request->email,
            'address'=>$request->address,
            'updated_by'=>Auth::user()->id,
            'updated_at'=>Carbon::now(),
        ]);

        $notification=array(
            'message'=>'Supplier Updated Successfully',
            'alert-type'=>'success'
        );

        return redirect()->route('supplier.all')->with($notification);
    }

    public function DeleteSupplier($id){
        Supplier::FindOrFail($id)->delete();
        
        $notification = array(
            'message' => 'Supplier Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
