@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">الفاتورة</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="invoice-title">
                                    <h4 class="float-end font-size-16"><strong>رقم الفاتورة # {{ $invoice->invoice_no }}</strong></h4>
                                    <h3>
                                        <!-- <img src="{{ asset('backend/assets/images/logo-light.png') }}" alt="logo" height="100" /> -->
                                        فحم الزين
                                    </h3>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6">
                                        <address>
                                            نوبا و الخليل<br>
                                            حسن الطرمان :0568190719<br>
                                            تحسين الطرمان :0595109779
                                        </address>
                                    </div>
                                    <div class="col-6 mt-4 text-end">
                                        <address>
                                            <strong>تاريخ الفاتورة:</strong><br>
                                            {{ date('d-m-Y',strtotime($invoice->date)) }}<br><br>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                        $payment = App\Models\Payment::where('invoice_id',$invoice->id)->first();
                        @endphp

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>فاتورة الزبون</strong></h3>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td><strong>اسم الزبون</strong></td>
                                                        <td class="text-center"><strong>رقم الهاتف</strong></td>
                                                        <td class="text-center"><strong>الايميل</strong>
                                                        <td class="text-center"><strong>الملاحظات</strong>
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $payment['customer']['name']?? Null}}</td>
                                                        <td class="text-center">{{ $payment['customer']['mobile_no']?? Null }}</td>
                                                        <td class="text-center">{{ $payment['customer']['email']?? Null }}</td>
                                                        <td class="text-center">{{ $invoice->description }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>عناصر الفاتورة</strong></h3>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td><strong>Sl </strong></td>
                                                        <td class="text-center"><strong>الفئة</strong></td>
                                                        <td class="text-center"><strong>اسم المنتج</strong>
                                                        </td>
                                                        <td class="text-center"><strong>المخزون</strong>
                                                        </td>
                                                        <td class="text-center"><strong>الكمية</strong>
                                                        </td>
                                                        <td class="text-center"><strong>سعر الوحدة </strong>
                                                        </td>
                                                        <td class="text-center"><strong>المبلغ الاجمالي</strong>
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                    $total_sum = '0';
                                                    @endphp
                                                    @foreach($invoice['invoice_details'] as $key => $details)
                                                    <tr>
                                                        <td class="text-center">{{ $key+1 }}</td>
                                                        <td class="text-center">{{ $details['category']['name'] }}</td>
                                                        <td class="text-center">{{ $details['product']['name'] }}</td>
                                                        <td class="text-center">{{ $details['product']['quantity'] }}</td>
                                                        <td class="text-center">{{ $details->selling_qty }}</td>
                                                        <td class="text-center">{{ $details->unit_price }}</td>
                                                        <td class="text-center">{{ $details->selling_price }}</td>
                                                    </tr>
                                                    @php
                                                    $total_sum += $details->selling_price;
                                                    @endphp
                                                    @endforeach

                                                    <tr>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line text-center">
                                                            <strong>المبلغ</strong>
                                                        </td>
                                                        <td class="thick-line text-end">₪{{ $total_sum }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>الخصم</strong>
                                                        </td>
                                                        <td class="no-line text-end">₪{{ $payment->discount_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>المبلغ المدفوع</strong>
                                                        </td>
                                                        <td class="no-line text-end">₪{{ $payment->paid_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>المبلغ المستحق</strong>
                                                        </td>
                                                        <td class="no-line text-end">₪{{ $payment->due_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>المبلغ الاجمالي</strong>
                                                        </td>
                                                        <td class="no-line text-end">
                                                            <h4 class="m-0">₪{{ $payment->total_amount }}</h4>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- زر ارسال الفاتورة عبر الواتساب -->
                                        <div class="d-print-none">
                                            <div class="float-end">
                                                <a href="https://wa.me/{{ $payment['customer']['mobile_no'] }}?text=مرحبا،%0Aإليك%20الفاتورة%20رقم%20{{ $invoice->invoice_no }}%20التي%20قمت%20بإصدارها%20بتاريخ%20{{ date('d-m-Y',strtotime($invoice->date)) }}%0Aالمبلغ%20الإجمالي%20₪{{ $payment->total_amount }}" target="_blank" class="btn btn-success waves-effect waves-light">
                                                    <i class="fab fa-whatsapp"></i> إرسال عبر الواتساب
                                                </a>
                                            </div>
                                        </div>
                                        <div class="d-print-none mr-5">
                                            <div class="float-end">
                                                <a href="javascript:window.print()" class="btn btn-primery waves-effect waves-light"><i class="fa fa-print"></i></a>
                                            </div>
                                        </div>
                
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

@endsection
