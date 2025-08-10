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
                                        <h4 class="float-end font-size-16"><strong>رقم الفاتورة #
                                                {{ $invoice->invoice_no }}</strong></h4>
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
                                                {{ date('d-m-Y', strtotime($invoice->date)) }}<br><br>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $payment = App\Models\Payment::where('invoice_id', $invoice->id)->first();
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
                                                            <td class="text-center"><strong>طريقة الدفع</strong>
                                                            </td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $payment['customer']['name'] ?? null }}</td>
                                                            <td class="text-center">
                                                                {{ $payment['customer']['mobile_no'] ?? null }}</td>
                                                            <td class="text-center">
                                                                {{ $payment['customer']['email'] ?? null }}</td>
                                                            <td class="text-center">{{ $invoice->description }}</td>
                                                            <td class="text-center">{{ $invoice->payment_method_name }}</td>
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
                                                        @foreach ($invoice['invoice_details'] as $key => $details)
                                                            <tr>
                                                                <td class="text-center">{{ $key + 1 }}</td>
                                                                <td class="text-center">
                                                                    {{ $details['category']['name'] ?? '' }}</td>
                                                                <td class="text-center">{{ $details['product']['name'] }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $details['product']['quantity'] }}</td>
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
                                                            <td class="no-line text-end">₪{{ $payment->discount_amount }}
                                                            </td>
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
                                            @php
                                                $customer_name = $payment['customer']['name'] ?? 'الزبون';
                                                $message = "مرحباً {$customer_name} 👋،%0A";
                                                $message .= 'معك فحم الزين 🔥%0A';
                                                $message .= 'إليك تفاصيل الفاتورة الخاصة بك:%0A';
                                                $message .= 'رقم الفاتورة: ' . $invoice->invoice_no . '%0A';
                                                $message .=
                                                    'التاريخ: ' . date('d-m-Y', strtotime($invoice->date)) . '%0A';
                                                $message .= '----------------------------%0A';

                                                foreach ($invoice['invoice_details'] as $key => $details) {
                                                    $message .= $key + 1 . '- ' . $details['product']['name'] . '%0A';
                                                    $message .=
                                                        'الكمية: ' .
                                                        $details->selling_qty .
                                                        ' × ' .
                                                        $details->unit_price .
                                                        '₪ = ' .
                                                        $details->selling_price .
                                                        '₪%0A';
                                                }

                                                $message .= '----------------------------%0A';
                                                $message .= 'المبلغ الكلي: ' . $payment->total_amount . '₪%0A';
                                                $message .= 'الخصم: ' . $payment->discount_amount . '₪%0A';
                                                $message .= 'المدفوع: ' . $payment->paid_amount . '₪%0A';
                                                $message .= 'المتبقي: ' . $payment->due_amount . '₪%0A';
                                                $message .= 'شكراً لتعاملكم معنا 😊';
                                            @endphp

                                            <!-- زر ارسال الفاتورة عبر الواتساب -->
                                            <div class="d-print-none">
                                                <div class="float-end"><a
                                                        href="https://wa.me/{{ $payment['customer']['mobile_no'] }}?text={{ $message }}"
                                                        target="_blank" class="btn btn-success waves-effect waves-light">
                                                        <i class="fab fa-whatsapp"></i> إرسال كافة التفاصيل عبر الواتساب
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-print-none mr-5">
                                                <div class="float-end">
                                                    <a href="javascript:window.print()"
                                                        class="btn btn-primery waves-effect waves-light"><i
                                                            class="fa fa-print"></i></a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <!-- قسم الدفعات المرتبطة بالفاتورة -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">سجل الدفعات</h4>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم الدفعة</th>
                                <th>تاريخ الدفعة</th>
                                <th>المبلغ</th>
                                <th>الخصم</th>
                                <th>المبلغ الصافي</th>
                                <th>طريقة الدفع</th>
                                <th>الفاتورة المطبقة</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partialPayments as $key => $payment)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>PAY-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ date('d/m/Y', strtotime($payment->payment_date)) }}</td>
                                <td>₪{{ number_format($payment->amount, 2) }}</td>
                                <td>₪{{ number_format($payment->discount_amount, 2) }}</td>
                                <td>₪{{ number_format($payment->net_amount, 2) }}</td>
                                <td>
                                    @if($payment->payment_method == 'cash_shekel')
                                        نقدي شيكل
                                    @elseif($payment->payment_method == 'cash_dinar')
                                        نقدي دينار
                                    @else
                                        شيك
                                    @endif
                                </td>
                                <td>
                                    @foreach($payment->invoices as $invoice)
                                        الفاتورة #{{ $invoice->invoice_no }}<br>
                                        (₪{{ number_format($invoice->pivot->amount, 2) }})
                                    @endforeach
                                </td>
                                <td>{{ $payment->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                            
                            @if($partialPayments->isEmpty())
                            <tr>
                                <td colspan="9" class="text-center">لا توجد دفعات مسجلة لهذه الفاتورة</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- إضافة زر لإضافة دفعة جديدة -->
               
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
