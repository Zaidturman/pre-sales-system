<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Customer;
use App\Models\User;

use Auth;
use Illuminate\Support\Carbon;
use DB;



class InvoiceController extends Controller
{

    public function search(Request $request) {
        $query = Invoice::with('payment.customer');
    
        if ($request->has('search_item') && !empty($request->search_item)) {
            $searchItem = $request->search_item;
    
            // البحث في تفاصيل الفاتورة بناءً على اسم المنتج
            $query->whereHas('invoice_details.product', function ($q) use ($searchItem) {
                $q->where('name', 'like', "%{$searchItem}%");
            });
        }
    
        $allData = $query->get();
    
        return view('backend.invoice.invoice_all', compact('allData'));
    }
    public function autocomplete(Request $request) {
        $query = $request->input('query');
    
        $products = Product::where('name', 'LIKE', "%{$query}%")->limit(5)->get();
    
        $output = '';
        if(count($products) > 0){
            foreach($products as $product){
                $output .= '<a href="#" class="dropdown-item">'.$product->name.'</a>';
            }
        } else {
            $output .= '<a href="#" class="dropdown-item text-muted">لا يوجد نتائج</a>';
        }
    
        return response()->json($output);
    }
  public function edit($id)
{
    $invoice = Invoice::with(['invoice_details.product', 'payment'])->find($id);

    if (!$invoice) {
        return redirect()->back()->with('error', 'الفاتورة غير موجودة');
    }

    $customers = Customer::all();
    $products = Product::all();
    $paymentMethods = ['نقدي شيكل', 'نقدي دينار', 'شيك', 'حوالة بنكية'];

    return view('backend.invoice.invoice_edit', compact(
        'invoice', 
        'customers', 
        'products',
        'paymentMethods'
    ));
}
 public function update(Request $request, $id)
{
    // التحقق من صحة المدخلات
    $request->validate([
        'invoice_no' => 'required|string|max:255',
        'date' => 'required|date',
        'payment_method' => 'required|in:نقدي شيكل,نقدي دينار,شيك,حوالة بنكية',
        'discount_amount' => 'nullable|numeric|min:0',
        'product_id' => 'required|array',
        'product_id.*' => 'exists:products,id',
        'selling_qty' => 'required|array',
        'selling_qty.*' => 'numeric|min:1',
        'unit_price' => 'required|array',
        'unit_price.*' => 'numeric|min:0',
        'paid_status' => 'required|in:full_paid,partial_paid,full_due',
        'paid_amount' => 'nullable|numeric|min:0'
    ]);

    // استرجاع الفاتورة
    $invoice = Invoice::findOrFail($id);

    DB::transaction(function () use ($request, $invoice, $id) {
        // تحديث الفاتورة الرئيسية
        $invoice->update([
            'date' => $request->date,
            'payment_method' => $request->payment_method,
            'discount_amount' => $request->discount_amount ?? 0,
        ]);

        $totalAmount = 0;
        $existingDetails = $invoice->invoice_details->pluck('id')->toArray();
        $updatedDetails = $request->input('existing_details', []);

        // تحديث أو إنشاء تفاصيل الفاتورة
        foreach ($request->product_id as $index => $productId) {
            $sellingPrice = $request->selling_qty[$index] * $request->unit_price[$index];
            $totalAmount += $sellingPrice;

            if (isset($updatedDetails[$index])) {
                // تحديث السجل الموجود
                InvoiceDetail::where('id', $updatedDetails[$index])->update([
                    'product_id' => $productId,
                    'selling_qty' => $request->selling_qty[$index],
                    'unit_price' => $request->unit_price[$index],
                    'selling_price' => $sellingPrice,
                ]);
            } else {
                // إنشاء سجل جديد
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $productId,
                    'selling_qty' => $request->selling_qty[$index],
                    'unit_price' => $request->unit_price[$index],
                    'selling_price' => $sellingPrice,
                    'date' => $request->date,
                    'status' => '1'
                ]);
            }
        }

        // حذف العناصر المحذوفة
        $toDelete = array_diff($existingDetails, $updatedDetails);
        if (!empty($toDelete)) {
            InvoiceDetail::whereIn('id', $toDelete)->delete();
        }

        // تحديث بيانات الدفع
        $payment = Payment::where('invoice_id', $invoice->id)->firstOrNew();
        $payment->invoice_id = $invoice->id;
        $payment->customer_id = $request->customer_id;
        $payment->paid_status = $request->paid_status;
        $payment->discount_amount = $request->discount_amount ?? 0;
        $payment->total_amount = $totalAmount - ($request->discount_amount ?? 0);

        if ($request->paid_status == 'full_paid') {
            $payment->paid_amount = $totalAmount - ($request->discount_amount ?? 0);
            $payment->due_amount = 0;
        } elseif ($request->paid_status == 'partial_paid') {
            $payment->paid_amount = $request->paid_amount;
            $payment->due_amount = ($totalAmount - ($request->discount_amount ?? 0)) - $request->paid_amount;
        } else {
            $payment->paid_amount = 0;
            $payment->due_amount = $totalAmount - ($request->discount_amount ?? 0);
        }
        $payment->save();

        // تحديث تفاصيل الدفع
        if ($request->paid_status == 'partial_paid' || $request->paid_status == 'full_paid') {
            $paymentDetail = PaymentDetail::updateOrCreate(
                ['invoice_id' => $invoice->id],
                [
                    'date' => $request->date,
                    'current_paid_amount' => $payment->paid_amount
                ]
            );
        }
    });

    return redirect()->route('invoice.all')->with('success', 'تم تحديث الفاتورة بنجاح');
}
        
        

           public function InvoiceAll()
    {
        $allData = Invoice::orderBy('date', 'desc')->orderBy('id', 'desc')->where('status', '1')->get();
        return view('backend.invoice.invoice_all', compact('allData'));
    }

    public function InvoiceAdd()
    {
        $category = Category::all();
        $customer = Customer::all();
        $products = Product::all();
        $invoice_data = Invoice::orderBy('id', 'desc')->first();
        if ($invoice_data == null) {
            $firstReg = '0';
            $invoice_no = $firstReg + 1;
        } else {
            $invoice_data = Invoice::orderBy('id', 'desc')->first()->invoice_no;
            $invoice_no = $invoice_data + 1;
        }

        date_default_timezone_set('Asia/Hebron');
        $date = date('Y-m-d');
        return view('backend.invoice.invoice_add', compact('invoice_no', 'category', 'date', 'customer', 'products'));
    }

    public function InvoiceStore(Request $request)
    {
                if ($request->category_id == null) {
                return redirect()->back()->with('error', 'لم يتم اختيار أي فئة');
            }

            if ($request->paid_amount > $request->estimated_amount) {
                return redirect()->back()->with('error', 'المبلغ المدفوع أكبر من الإجمالي');
            }

                $invoice = new Invoice();
                $invoice->invoice_no = $request->invoice_no;
                $invoice->date = date('Y-m-d', strtotime($request->date));
                $invoice->description = $request->description;
                $invoice->payment_method = $request->payment_method; // إضافة طريقة الدفع
                $invoice->discount_amount = $request->discount_amount ?? 0; // إضافة الخصم
                $invoice->status = '1';
                $invoice->created_by = Auth::user()->id;

                DB::transaction(function () use ($request, $invoice) {
                    if ($invoice->save()) {
                        $count_category = count($request->category_id);
                        for ($i = 0; $i < $count_category; $i++) {

                            if ($request->selling_qty[$i] == null && $request->unit_price[$i] == null) {
                                $notification = array(
                                    'message' => 'Null value detected',
                                    'alert-type' => 'error'
                                );

                                return redirect()->back()->with($notification);
                            } else {

                                $invoice_details = new InvoiceDetail();
                                $invoice_details->date = date('Y-m-d', strtotime($request->date));
                                $invoice_details->invoice_id = $invoice->id;
                                $invoice_details->category_id = $request->category_id[$i];
                                $invoice_details->product_id = $request->product_id[$i];
                                $invoice_details->selling_qty = $request->selling_qty[$i];
                                $invoice_details->unit_price = $request->unit_price[$i];
                                $invoice_details->selling_price = $request->selling_price[$i];
                                $invoice_details->status = '1';
                                $invoice_details->save();
                            }
                        }

                        if ($request->customer_id == '0') {
                            $customer = new Customer();
                            $customer->name = $request->name;
                            $customer->mobile_no = $request->mobile_no;
                            $customer->email = $request->email;
                            $customer->save();
                            $customer_id = $customer->id;
                        } else {
                            $customer_id = $request->customer_id;
                        }

                        $payment = new Payment();
                        $payment_details = new PaymentDetail();
            $payment->invoice_id = $invoice->id;
            $payment->customer_id = $request->customer_id == '0' ? $customer_id : $request->customer_id;
            $payment->paid_status = $request->paid_status;
            $payment->discount_amount = $request->discount_amount ?? 0;
            $payment->total_amount = $request->estimated_amount - ($request->discount_amount ?? 0);

                        if ($request->paid_status == 'full_paid') {
                            $payment->paid_amount = $request->estimated_amount;
                            $payment->due_amount = '0';
                            $payment_details->current_paid_amount = $request->estimated_amount;
                        } elseif ($request->paid_status == 'full_due') {
                            $payment->paid_amount = '0';
                            $payment->due_amount = $request->estimated_amount;
                            $payment_details->current_paid_amount = '0';
                        } elseif ($request->paid_status == 'partial_paid') {
                            $payment->paid_amount = $request->paid_amount;
                            $payment->due_amount = $request->estimated_amount - $request->paid_amount;
                            $payment_details->current_paid_amount = $request->paid_amount;
                        }
                        $payment->save();

                        $payment_details->invoice_id = $invoice->id;
                        $payment_details->date = date('Y-m-d', strtotime($request->date));
                        $payment_details->save();
                    }
                });
       
        $notification = array(
            'message' => 'Invoice Data Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('invoice.all')->with($notification);
    }


    public function InvoiceDelete($invoice_no)
    {
        $invoice = Invoice::where('invoice_no', $invoice_no)->first();
        if ($invoice) {
            InvoiceDetail::where('invoice_id', $invoice->id)->delete();
            Payment::where('invoice_id', $invoice->id)->delete();
            PaymentDetail::where('invoice_id', $invoice->id)->delete();
            $invoice->delete();
            $notification = array(
                'message' => 'Invoice Deleted Successfully',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Invoice Not Found',
                'alert-type' => 'error',
            );
        }
        return redirect()->back()->with($notification);
    }

    public function PendingList()
    {
        $allData = Invoice::orderBy('date', 'desc')->orderBy('id', 'desc')->where('status', '0')->get();
        return view('backend.invoice.invoice_pending', compact('allData'));
    }

    public function DeleteInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        InvoiceDetail::where('invoice_id', $invoice->id)->delete();
        Payment::where('invoice_id', $invoice->id)->delete();
        PaymentDetail::where('invoice_id', $invoice->id)->delete();

        $notification = array(
            'message' => 'Invoice Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function InvoiceApprove($id)
    {
      $invoice = Invoice::with('invoice_details')->FindOrFail($id);
    $paymentMethods = ['نقدي شيكل', 'نقدي دينار', 'شيك', 'حوالة بنكية'];
    return view('backend.invoice.invoice_approve', compact('invoice', 'paymentMethods'));
    }

    public function ApprovalStore(Request $request, $id)
    {
        foreach ($request->selling_qty as $key => $val) {
            $invoice_details = InvoiceDetail::where('id', $key)->first();
            $product = Product::where('id', $invoice_details->product_id)->first();

            if ($product->quantity < $request->selling_qty[$key]) {
                $notification = array(
                    'message' => 'Sorry you are approving Maximum value',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        }

        $invoice = Invoice::FindOrFail($id);
        $invoice->updated_by = Auth::user()->id;
        $invoice->status = '1';

        DB::transaction(function () use ($request, $invoice, $id) {
            foreach ($request->selling_qty as $key => $val) {
                $invoice_details = InvoiceDetail::where('id', $key)->first();
                $invoice_details->status = '1';
                $invoice_details->save();

                $product = Product::where('id', $invoice_details->product_id)->first();

                $product->quantity = ((float)$product->quantity) - ((float)$request->selling_qty[$key]);
                $product->save();
            }

            $invoice->save();
        });

        $notification = array(
            'message' => 'Invoice Approved Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('invoice.pending')->with($notification);
    }

    public function PrintInvoiceList()
    {
        $allData = Invoice::orderBy('date', 'desc')->orderBy('id', 'desc')->where('status', '1')->get();
        return view('backend.invoice.invoice_print_list', compact('allData'));
    }

    public function PrintInvoice($id)
    {
        $invoice = invoice::with('invoice_details')->FindOrFail($id);
        // الحصول على معرف المستخدم الذي أنشأ الفاتورة
        $createdById = $invoice->created_by;
        // استرجاع المستخدم الذي أنشأ الفاتورة
        $creator = User::find($createdById);
        // الحصول على اسم المستخدم
            // تعريف طرق الدفع المتاحة
    $paymentMethods = [
        'cash_shekel' => 'نقدي شيكل',
        'cash_dinar' => 'نقدي دينار',
        'check' => 'شيك',
        'bank_transfer' => 'حوالة بنكية'
    ];
    
    // الحصول على اسم طريقة الدفع بالعربية
    $invoice->payment_method_name = $paymentMethods[$invoice->payment_method] ?? $invoice->payment_method;
    
        $creatorName =  $creator->name;
        return view('backend.pdf.invoice_pdf', compact('invoice', 'creatorName'));
    }

    public function DailyInvoiceReport()
    {
        return view('backend.invoice.daily_invoice_report');
    }

   public function DailyInvoicePdf(Request $request)
{
    $start_date = date('Y-m-d', strtotime($request->start_date));
    $end_date = date('Y-m-d', strtotime($request->end_date));
    
    $allData = Invoice::whereBetween('date', [$start_date, $end_date])
        ->where('status', '1')
        ->with(['payment', 'invoice_details.product'])
        ->get();
        
    // حساب إجمالي المبيعات والخصومات
    $totalSales = $allData->sum(function($invoice) {
        return $invoice->payment->total_amount + $invoice->discount_amount;
    });
    
    $totalDiscount = $allData->sum('discount_amount');
    
    // حساب المبيعات حسب طريقة الدفع
    $paymentMethodsSummary = [
        'نقدي شيكل' => 0,
        'نقدي دينار' => 0,
        'شيك' => 0,
        'حوالة بنكية' => 0
    ];
    
    foreach ($allData as $invoice) {
        if (isset($paymentMethodsSummary[$invoice->payment_method])) {
            $paymentMethodsSummary[$invoice->payment_method] += 
                $invoice->payment->total_amount + $invoice->discount_amount;
        }
    }

    return view('backend.pdf.daily_invoice_report_pdf', compact(
        'allData',
        'start_date',
        'end_date',
        'totalSales',
        'totalDiscount',
        'paymentMethodsSummary'
    ));
}
}
