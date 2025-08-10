<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\PartialPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Image;
use Mpdf\Mpdf;



class CustomerController extends Controller
{



public function generateInvoicesPDF($id)
{
    $customer = Customer::findOrFail($id);

    $payments = Payment::where('customer_id', $id)
        ->with([
            'invoice' => function($query) {
                $query->with('invoice_details.product');
            },
            'partialPayments' => function($query) {
                $query->with('invoices.invoice') // تأكد من تحميل الفاتورة المرتبطة
                      ->orderBy('payment_date', 'asc');
            }
        ])
        ->orderBy('created_at', 'desc')
        ->get();

    $filename = 'كشف_حساب_' . str_replace(['/', '\\'], '-', $customer->name) . '.pdf';

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'orientation' => 'P',
        'default_font' => 'dejavusans',
        'default_font_size' => 12,
        'directionality' => 'rtl'
    ]);

    $html = view('backend.customer.customer_invoices_pdf', compact('customer', 'payments'))->render();
    $mpdf->WriteHTML($html);

    return $mpdf->Output($filename, 'I');
}
    public function CustomerAll()
    {
        $customers = Customer::latest()->get();
        return view('backend.customer.customer_all', compact('customers'));
    }

    public function search(Request $request)
    {
        $query = Customer::query();
        if ($request->address) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }
        $customers = $query->get();
        return view('backend.customer.customer_all', compact('customers'));
    }

    public function CustomerAllWithFilter()
    {
        $customers = Customer::latest()->get();
        return view('backend.customer.customer_all_withFilter', compact('customers'));
    }
    public function getCustomers(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Custom filters
        $name = $request->get('name');
        $email = $request->get('email');
        $address = $request->get('address');

        // Total records
        $totalRecords = Customer::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Customer::select('count(*) as allcount')
            ->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($email, function ($query) use ($email) {
                return $query->where('email', 'like', '%' . $email . '%');
            })
            ->when($address, function ($query) use ($address) {
                return $query->where('address', 'like', '%' . $address . '%');
            })
            ->count();

        // Fetch records
        $records = Customer::orderBy($columnName, $columnSortOrder)
            ->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($email, function ($query) use ($email) {
                return $query->where('email', 'like', '%' . $email . '%');
            })
            ->when($address, function ($query) use ($address) {
                return $query->where('address', 'like', '%' . $address . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $i = $start + 1;

        foreach ($records as $record) {
            $id = $record->id;
            $name = $record->name;
            $image = '<img src="' . asset($record->customer_image) . '" style="width:60px;height:50px;">';
            $email = $record->email;
            $address = $record->address;

            $actions = '
        <a href="' . route('customer.edit', $id) . '" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>
        <a href="' . route('customer.delete', $id) . '" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
        <a href="' . route('customer.view', $id) . '" class="btn btn-primary sm" title="Customer Invoice Details"><i class="fas fa-eye"></i></a>
        <a href="' . route('partialpayments.create', $id) . '" class="btn btn-success sm" title="Customer pind Details"><i class="fas fa-plus"></i></a>
        ';

            $data_arr[] = array(
                "id" => $i++,
                "name" => $name,
                "image" => $image,
                "email" => $email,
                "address" => $address,
                "actions" => $actions
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function CustomerAdd()
    {
        return view('backend.customer.customer_add');
    }

    public function CustomerStore(Request $request)
    {
        if ($request->hasFile('customer_image')) {

            $image = $request->file('customer_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            Image::make($image)->resize(200, 200)->save('upload/customer/' . $name_gen);
            $save_url = 'upload/customer/' . $name_gen;
            Customer::insert([
                'customer_image' => $save_url,
            ]);
        }
        Customer::insert([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Customer Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('customer.all')->with($notification);
    }
    public function CustomerStorePOS(Request $request)
    {

        // التحقق من البيانات الواردة
        $request->validate([
            'name' => 'required|string|max:255',

        ]);

        // حفظ الزبون في قاعدة البيانات
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->mobile_no = "";
        $customer->email = "";
        $customer->address = "";
        $customer->created_by = Auth::user()->id;
        $customer->created_at = Carbon::now();

        $customer->save(); // حفظ الزبون

        // إرسال رسالة النجاح
        $notification = [
            'message' => 'تم إضافة الزبون بنجاح!',
            'alert-type' => 'success',
        ];

        // إعادة التوجيه إلى نفس الصفحة مع رسالة النجاح
        return redirect()->back()->with($notification);
    }
    public function CustomerEdit($id)
    {
        $customer = Customer::FindOrFail($id);
        return view('backend.customer.customer_edit', compact('customer'));
    }
    public function CustomerView($id)
    {
        $customer = Customer::FindOrFail($id);
        $partialpayments = PartialPayment::where('customer_id', $id)->get();
        $Payments = Payment::where('customer_id', $id)
            ->whereIn('paid_status', ['full_due', 'partial_paid', 'full_paid'])
            ->get();

        return view('backend.customer.customer_view', compact('customer', 'Payments', 'partialpayments'));
    }
    public function CustomerUpdate(Request $request)
    {
        $customer_id = $request->id;
        if ($request->file('customer_image')) {
            $image = $request->file('customer_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            Image::make($image)->resize(200, 200)->save('upload/customer/' . $name_gen);
            $save_url = 'upload/customer/' . $name_gen;
            Customer::FindOrFail($customer_id)->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'address' => $request->address,
                'customer_image' => $save_url,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Customer Updated With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('customer.all')->with($notification);
        } else {
            Customer::FindOrFail($customer_id)->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'address' => $request->address,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Customer Updated Without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('customer.all')->with($notification);
        }
    }

    public function DeleteCustomer($id)
    {
        $customers = Customer::FindOrFail($id);
        if ($customers->customer_image) {

            $img = $customers->customer_image;
            unlink($img);
        }

        Customer::FindOrFail($id)->delete();

        $notification = array(
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function CreditCustomer()
    {
        $allData = Payment::whereIn('paid_status', ['full_due', 'partial_paid'])
            ->where('due_amount', '>', 0) // استبعاد الفواتير التي due_amount = 0
            ->get();
        return view('backend.customer.customer_credit', compact('allData'));
    }

    public function CreditCustomerPdf()
    {
        // جلب كل المدفوعات التي تحتوي على ديون (أي أن المبلغ المستحق أكبر من 0)
        $allData = Payment::where('due_amount', '>', 0)->get();

        return view('backend.pdf.customer_credit_pdf', compact('allData'));
    }


    public function CustomerEditInvoice($invoice_id)
    {
        $payment = Payment::where('invoice_id', $invoice_id)->first();
        return view('backend.customer.edit_customer_invoice', compact('payment'));
    }

    public function CustomerUpdateInvoice(Request $request, $invoice_id)
    {
        if ($request->new_paid_amount < $request->paid_amount) {
            $notification = array(
                'message' => 'Sorry You Paid Maximum Value',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        } else {
            $payment = Payment::where('invoice_id', $invoice_id)->first();
            $payment_details = new PaymentDetail();
            $payment->paid_status = $request->paid_status;

            if ($request->paid_status == 'full_paid') {
                $payment->paid_amount = Payment::where('invoice_id', $invoice_id)->first()['paid_amount'] + $request->new_paid_amount;
                $payment->due_amount = '0';
                // $payment->paid_status = 'full_paid';
                $payment_details->current_paid_amount = $request->nwe_paid_amount;
            } elseif ($request->paid_status == 'partial_paid') {
                $payment->paid_amount = Payment::where('invoice_id', $invoice_id)->first()['paid_amount'] + $request->paid_amount;
                $payment->due_amount = Payment::where('invoice_id', $invoice_id)->first()['due_amount'] - $request->paid_amount;
                $payment_details->current_paid_amount = $request->paid_amount;
            } else {
                $notification = array(
                    'message' => 'Invalid Data',
                    'alert-type' => 'error'
                );

                return redirect()->back()->with($notification);
                dd('Invalid Data');
            }
            // dd(Payment::where('invoice_id',$invoice_id)->first()['due_amount']);
            $payment->save();
            $payment_details->invoice_id = $invoice_id;
            $payment_details->date = date('Y-m-d', strtotime($request->date));
            $payment_details->updated_by = Auth::user()->id;
            $payment_details->save();

            $notification = array(
                'message' => 'Invoice Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('credit.customer')->with($notification);
        }
    }

    public function CustomerInvoiceDetails($invoice_id)
    {
        $payment = Payment::where('invoice_id', $invoice_id)->first();
        return view('backend.pdf.invoice_details_pdf', compact('payment'));
    }

    public function PaidCustomer()
    {
        $allData = Payment::where('paid_status', '!=', 'full_due')
            ->with(['Customer', 'paymentDetails'])
            ->get();

        return view('backend.customer.customer_paid', compact('allData'));
    }

    public function PaidCustomerPrintPdf()
    {
        $allData = Payment::where('paid_status', '!=', 'full_due')->get();
        return view('backend.pdf.customer_paid_pdf', compact('allData'));
    }

    public function CustomerWiseReport()
    {
        $customers = Customer::all();
        return view('backend.customer.customer_wise_report', compact('customers'));
    }

    public function CustomerWiseCreditReport(Request $request)
    {
        $allData = Payment::where('customer_id', $request->customer_id)->whereIn('paid_status', ['full_due', 'partial_paid'])->with('invoice')->get();
        $customer = Customer::where('id', $request->customer_id)->first();


        return view('backend.pdf.customer_wise_credit_pdf', compact('allData', 'customer'));
    }

    public function CustomerWisePaidReport(Request $request)
    {
        $allData = Payment::where('customer_id', $request->customer_id)->where('paid_status', '!=', 'full_due')->with('invoice')->get();
        $customer = Customer::where('id', $request->customer_id)->first();

        return view('backend.pdf.customer_wise_paid_pdf', compact('allData', 'customer'));
    }
}
