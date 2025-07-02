<?php

namespace App\Http\Controllers\Pos;

use Auth;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function PurchaseAll(){
        $allData = Purchase::orderBy('date','desc')->orderBy('id','desc')->get();
        return view('backend.purchase.purchase_all',compact('allData'));
    }

    public function PurchaseAdd(){
        $supplier=Supplier::all();
        $category=Category::all();
        $unit=Unit::all();
        return view('backend.purchase.purchase_add',compact('supplier','category','unit'));
    }

    public function PurchaseStore(Request $request)
    {
        if ($request->category_id == null) {
            $notification = array(
                'message' => 'Sorry No Category information found',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        } else {
            if (isset($request->paid_amount) && $request->paid_amount > array_sum($request->buying_price)) {
                $notification = array(
                    'message' => 'Sorry Paid Amount is greater than total price',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            } else {
                DB::transaction(function() use ($request) {
                    $total_amount = 0;
                    $count_category = count($request->category_id);

                    for ($i = 0; $i < $count_category; $i++) {
                        if ($request->buying_qty[$i] == null || $request->unit_price[$i] == null) {
                            $notification = array(
                                'message' => 'Null value detected',
                                'alert-type' => 'error'
                            );
                            return redirect()->back()->with($notification);
                        } else {
                            $purchase = new Purchase();
                            $purchase->date = date('Y-m-d', strtotime($request->date[$i]));
                            $purchase->purchase_no = $request->purchase_no[$i];
                            $purchase->supplier_id = $request->supplier_id[$i];
                            $purchase->category_id = $request->category_id[$i];
                            $purchase->product_id = $request->product_id[$i];
                            $purchase->buying_qty = $request->buying_qty[$i];
                            $purchase->unit_price = $request->unit_price[$i];
                            $purchase->buying_price = $request->buying_price[$i];
                            $purchase->description = $request->description[$i];
                            $purchase->created_by = Auth::user()->id;
                            $purchase->status = '0';

                            $purchase->save();

                            $total_amount += $request->buying_price[$i];
                        }
                    }

                    $payment = new PurchasePayment();
                    $payment->purchase_id = $purchase->id; 
                    $payment->supplier_id = $purchase->supplier_id;
                    $payment->paid_status = $request->paid_status;
                    $payment->total_amount = $total_amount;

                    if ($request->paid_status == 'full_paid') {
                        $payment->paid_amount = $total_amount;
                        $payment->due_amount = '0';
                    } elseif ($request->paid_status == 'full_due') {
                        $payment->paid_amount = '0';
                        $payment->due_amount = $total_amount;
                    } elseif ($request->paid_status == 'partial_paid') {
                        $paid_amount = $request->paid_amount ?? 0;
                        $payment->paid_amount = $paid_amount;
                        $payment->due_amount = $total_amount - $paid_amount;
                    }

                    $payment->save();
                });

                // Success notification
                $notification = array(
                    'message' => 'Purchase Data Inserted Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('purchase.all')->with($notification);
            }
        }
    }
    public function PurchaseDelete($purchase_no){
            $purchases= Purchase::where('purchase_no',$purchase_no)->get();
            if($purchases->isNotEmpty()){
                foreach($purchases as $purchase){
                    PurchasePayment::where('purchase_id',$purchase->id)->delete();
                    $purchase->delete();
                }
                $notification = array(
                    'message' => 'Purchase Invoice Deleted Successfully',
                    'alert-type' => 'success'
                );
            }else {
                $notification = array(
                    'message' => 'Purchase Invoice Not Found',
                    'alert-type' => 'error'
                );
            }
            return redirect()->route('purchase.all')->with($notification);

        
    }

    public function PurchaseDetials($purchase_no) {
        $purchases = Purchase::with(['purchasePayments', 'supplier'])->where('purchase_no', $purchase_no)->get();
        $payments = [];
        foreach ($purchases as $purchase) {
            foreach ($purchase->purchasePayments as $payment) {
                $payments[] = $payment;
            }
        }
        return view('backend.purchase.purchase_detials', compact('purchases', 'payments'));
    }
    
    public function DeletePurchase($id){
        Purchase::FindOrFail($id)->delete();

        $notification = array(
            'message' => 'Purchase Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function PurchasePending(){
        $allData = Purchase::orderBy('date','desc')->orderBy('id','desc')->where('status','0')->get();
        return view('backend.purchase.purchase_pending',compact('allData'));
    }

    public function PurchaseApprove($id){
        $purchase=Purchase::FindOrFail($id);
        $product=Product::where('id',$purchase->product_id)->first();
        $purchase_qty=((float)($purchase->buying_qty))+((float)($product->quantity));
        $product->quantity = $purchase_qty;

        if($product->save()){
            Purchase::FindOrFail($id)->update([
                'status' => '1',
            ]);

            $notification = array(
                'message' => 'Purchase Approved Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('purchase.all')->with($notification);
        }
    }

    public function DailyPurchaseReport(){
        return view('backend.purchase.daily_purchase_report');
    }

  public function DailyPurchasePdf(Request $request){
    $start_date = date('Y-m-d',strtotime($request->start_date));
    $end_date = date('Y-m-d',strtotime($request->end_date));
    $allData = Purchase::with(['product.unit', 'purchasePayments', 'supplier'])
        ->whereBetween('date',[$start_date,$end_date])
        ->where('status','1')
        ->get();
    $groupedData = $allData->groupBy('purchase_no');

    $searchSummary = null;
    if ($request->filled('search')) {
        $searchTerm = $request->input('search');
        $filtered = $allData->filter(function($item) use ($searchTerm) {
            return stripos($item->product->name, $searchTerm) !== false;
        });
        if ($filtered->count() > 0) {
            $total_qty = $filtered->sum('buying_qty');
            $total_amount = $filtered->sum('buying_price');
            $suppliers = $filtered->pluck('supplier.name')->unique()->toArray();
            $searchSummary = [
                'item_name' => $searchTerm,
                'count' => $filtered->count(),
                'total_qty' => $total_qty,
                'total_amount' => $total_amount,
                'suppliers' => $suppliers,
            ];
        } else {
            $searchSummary = [
                'item_name' => $searchTerm,
                'count' => 0,
                'total_qty' => 0,
                'total_amount' => 0,
                'suppliers' => [],
            ];
        }
    }

    return view('backend.pdf.daily_purchase_report_pdf', compact('allData', 'groupedData', 'start_date', 'end_date', 'searchSummary'));
}
}
