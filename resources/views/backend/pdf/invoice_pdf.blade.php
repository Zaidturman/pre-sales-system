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
                                        <h4 class="float-end font-size-16"><strong>رقم الفاتورة:
                                                {{ $invoice->invoice_no }}</strong></h4>
                                        <h3 class="text-center mb-4">
                                            <img src="{{ asset('backend/assets/images/logo-light.png') }}" alt="logo"
                                                height="60" class="mb-2" /><br>
                                            فحم الزين
                                        </h3>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <address class="mb-4">
                                                <strong>معلومات المتجر:</strong><br>
                                                نوبا - الخليل<br>
                                                حسن الطرمان: 0568190719<br>
                                                تحسين الطرمان: 0595109779
                                            </address>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <address>
                                                <strong>معلومات الفاتورة:</strong><br>
                                                التاريخ: {{ date('d-m-Y', strtotime($invoice->date)) }}<br>
                                                حالة الدفع:
                                                @if ($invoice->paid_status == 'full_paid')
                                                    <span class="badge bg-success">مدفوعة بالكامل</span>
                                                @elseif($invoice->paid_status == 'partial_paid')
                                                    <span class="badge bg-warning">مدفوعة جزئياً</span>
                                                @else
                                                    <span class="badge bg-danger">غير مدفوعة</span>
                                                @endif
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $payment = App\Models\Payment::where('invoice_id', $invoice->id)->first();
                            @endphp

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="p-2 bg-light">
                                        <h4 class="font-size-16"><strong>معلومات الزبون</strong></h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th>اسم الزبون</th>
                                                    <th class="text-center">رقم الهاتف</th>
                                                    <th class="text-center">الايميل</th>
                                                    <th class="text-center">الملاحظات</th>
                                                    <th class="text-center">طريقة الدفع</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $payment['customer']['name'] ?? 'غير محدد' }}</td>
                                                    <td class="text-center">
                                                        {{ $payment['customer']['mobile_no'] ?? 'غير محدد' }}</td>
                                                    <td class="text-center">
                                                        {{ $payment['customer']['email'] ?? 'غير محدد' }}</td>
                                                    <td class="text-center">{{ $invoice->description ?? 'لا يوجد' }}</td>
                                                    <td class="text-center">{{ $invoice->payment_method_name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2 bg-light">
                                        <h4 class="font-size-16"><strong>تفاصيل الفاتورة</strong></h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th class="text-center">الفئة</th>
                                                    <th class="text-center">المنتج</th>
                                                    <th class="text-center">الكمية</th>
                                                    <th class="text-center">سعر الوحدة</th>
                                                    <th class="text-center">الإجمالي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total_sum = 0;
                                                @endphp
                                                @foreach ($invoice['invoice_details'] as $key => $details)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td class="text-center">
                                                            {{ $details['category']['name'] ?? 'غير محدد' }}</td>
                                                        <td class="text-center">
                                                            {{ $details['product']['name'] ?? 'منتج محذوف' }}</td>
                                                        <td class="text-center">{{ $details->selling_qty }}</td>
                                                        <td class="text-center">
                                                            ₪{{ number_format($details->unit_price, 2) }}</td>
                                                        <td class="text-center">
                                                            ₪{{ number_format($details->selling_price, 2) }}</td>
                                                    </tr>
                                                    @php
                                                        $total_sum += $details->selling_price;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="p-2 bg-light">
                                        <h4 class="font-size-16"><strong>ملخص الفاتورة</strong></h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td class="text-end"><strong>المبلغ الإجمالي:</strong></td>
                                                    <td class="text-center">₪{{ number_format($total_sum, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end"><strong>الخصم:</strong></td>
                                                    <td class="text-center">
                                                        ₪{{ number_format($payment->discount_amount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end"><strong>المبلغ المدفوع:</strong></td>
                                                    <td class="text-center">₪{{ number_format($payment->paid_amount, 2) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end"><strong>المبلغ المتبقي:</strong></td>
                                                    <td class="text-center">₪{{ number_format($payment->due_amount, 2) }}
                                                    </td>
                                                </tr>
                                                <tr class="bg-light">
                                                    <td class="text-end"><strong>المبلغ النهائي:</strong></td>
                                                    <td class="text-center">
                                                        <strong>₪{{ number_format($payment->total_amount, 2) }}</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @php
                                $customer_name = $payment['customer']['name'] ?? 'الزبون';
                                $message = "مرحباً {$customer_name} 👋،%0A";
                                $message .= 'معك فحم الزين 🔥%0A';
                                $message .= 'إليك تفاصيل الفاتورة الخاصة بك:%0A';
                                $message .= 'رقم الفاتورة: ' . $invoice->invoice_no . '%0A';
                                $message .= 'التاريخ: ' . date('d-m-Y', strtotime($invoice->date)) . '%0A';
                                $message .= '----------------------------%0A';

                                foreach ($invoice['invoice_details'] as $key => $details) {
                                    $message .= $key + 1 . '- ' . ($details['product']['name'] ?? 'منتج محذوف') . '%0A';
                                    $message .=
                                        'الكمية: ' .
                                        $details->selling_qty .
                                        ' × ' .
                                        number_format($details->unit_price, 2) .
                                        '₪ = ' .
                                        number_format($details->selling_price, 2) .
                                        '₪%0A';
                                }

                                $message .= '----------------------------%0A';
                                $message .= 'المبلغ الكلي: ' . number_format($payment->total_amount, 2) . '₪%0A';
                                $message .= 'الخصم: ' . number_format($payment->discount_amount, 2) . '₪%0A';
                                $message .= 'المدفوع: ' . number_format($payment->paid_amount, 2) . '₪%0A';
                                $message .= 'المتبقي: ' . number_format($payment->due_amount, 2) . '₪%0A';
                                $message .= 'شكراً لتعاملكم معنا 😊';
                            @endphp

                            <div class="d-print-none mt-4">
                                <div class="float-end">
                                    <a href="https://wa.me/{{ $payment['customer']['mobile_no'] ?? '' }}?text={{ $message }}"
                                        target="_blank" class="btn btn-success waves-effect waves-light me-2">
                                        <i class="fab fa-whatsapp"></i> إرسال عبر الواتساب
                                    </a>
                                    <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light">
                                        <i class="fa fa-print"></i> طباعة الفاتورة
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
