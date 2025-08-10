<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>كشف حساب العميل</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url("{{ public_path('fonts/DejaVuSans.ttf') }}") format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: 'DejaVu Sans';
            src: url("{{ public_path('fonts/DejaVuSans-Bold.ttf') }}") format('truetype');
            font-weight: bold;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12pt;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
            line-height: 1.5;
            color: #000;
        }

        .letterhead {
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
            position: relative;
        }

        .letterhead .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 120px;
        }

        .letterhead .title {
            text-align: center;
        }

        .letterhead .title h1 {
            margin: 0;
            font-size: 22pt;
            color: #333;
        }

        .letterhead .title p {
            margin: 5px 0 0;
            font-size: 14pt;
            color: #666;
        }

        .document-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .document-info .info-box {
            border: 1px solid #ccc;
            padding: 10px 15px;
            width: 48%;
        }

        .document-info .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 14pt;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .document-info .info-box p {
            margin: 8px 0;
        }

        .section-title {
            background: #f5f5f5;
            padding: 8px 15px;
            border-right: 4px solid #333;
            font-size: 14pt;
            margin: 25px 0 15px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11pt;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .currency {
            font-family: 'DejaVu Sans', sans-serif;
            direction: ltr;
            display: inline-block;
            font-weight: bold;
        }

        .currency::before {
            content: "₪";
            font-family: 'DejaVu Sans', sans-serif;
            margin-right: 3px;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .signature-area {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 250px;
            border-top: 1px solid #333;
            padding-top: 10px;
            text-align: center;
            margin-top: 60px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10pt;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .status {
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 10pt;
            font-weight: bold;
        }

        .status-full_paid {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .status-partial_paid {
            background: #fff8e1;
            color: #ff8f00;
            border: 1px solid #ffe0b2;
        }

        .status-full_due {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <div class="letterhead">
        <div class="title">
            <h1>كشف حساب العميل</h1>
            <p>شركة تطوير للتكنولوجيا - نظام أي بوينت للمبيعات</p>
        </div>
    </div>

    <div class="document-info">
        <div class="info-box">
            <h3>معلومات العميل</h3>
            <p><strong>اسم العميل:</strong> {{ $customer->name }}</p>
            <p><strong>رقم الجوال:</strong> {{ $customer->mobile_no }}</p>
            <p><strong>رقم الهوية/الضريبة:</strong> {{ $customer->tax_number ?? 'غير متوفر' }}</p>
        </div>
        <div class="info-box">
            <h3>معلومات المستند</h3>
            <p><strong>رقم المرجع:</strong> CLR-{{ date('Ymd') }}-{{ rand(100, 999) }}</p>
            <p><strong>تاريخ التقرير:</strong> {{ date('Y-m-d') }}</p>
            <p><strong>الصفحة:</strong> 1 من 1</p>
        </div>
    </div>

    <div class="section-title">ملخص الحساب</div>

    <table>
        <thead>
            <tr>
                <th style="width: 25%;">البند</th>
                <th style="width: 25%;">إجمالي الفواتير</th>
                <th style="width: 25%;">إجمالي المدفوعات</th>
                <th style="width: 25%;">الرصيد المتبقي</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>إجمالي الحركات</td>
                <td><span class="currency">{{ number_format($payments->sum('total_amount'), 2) }}</span></td>
                <td><span class="currency">{{ number_format($payments->sum('paid_amount'), 2) }}</span></td>
                <td><span class="currency">{{ number_format($payments->sum('due_amount'), 2) }}</span></td>
            </tr>
        </tbody>
    </table>

    @foreach ($payments as $payment)
        @if ($payment->invoice)
            <div class="section-title">تفاصيل الفاتورة رقم: {{ $payment->invoice->invoice_no }}</div>

            <p>
                <strong>تاريخ الفاتورة:</strong> {{ $payment->invoice->date }} |
                <strong>حالة الدفع:</strong>
                @if ($payment->paid_status == 'full_paid')
                    <span class="status status-full_paid">مدفوعة بالكامل</span>
                @elseif($payment->paid_status == 'partial_paid')
                    <span class="status status-partial_paid">مدفوعة جزئياً</span>
                @elseif($payment->paid_status == 'full_due')
                    <span class="status status-full_due">دين كامل</span>
                @endif
            </p>

            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 45%;">المنتج/الخدمة</th>
                        <th style="width: 15%;">الكمية</th>
                        <th style="width: 15%;">سعر الوحدة</th>
                        <th style="width: 20%;">المجموع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payment->invoice->invoice_details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->product->name ?? 'منتج محذوف' }}</td>
                            <td>{{ $detail->selling_qty }}</td>
                            <td><span class="currency">{{ number_format($detail->unit_price, 2) }}</span></td>
                            <td><span class="currency">{{ number_format($detail->selling_price, 2) }}</span></td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4" style="text-align: left;"><strong>إجمالي الفاتورة:</strong></td>
                        <td><strong><span
                                    class="currency">{{ number_format($payment->total_amount, 2) }}</span></strong>
                        </td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="4" style="text-align: left;"><strong>المبلغ المدفوع:</strong></td>
                        <td><strong><span
                                    class="currency">{{ number_format($payment->paid_amount, 2) }}</span></strong></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="4" style="text-align: left;"><strong>الرصيد المتبقي:</strong></td>
                        <td><strong><span class="currency">{{ number_format($payment->due_amount, 2) }}</span></strong>

                        </td>
                    </tr>
                </tbody>
            </table>
          
@php

$id = $payment->invoice->id;
    // جلب الدفعات الجزئية المرتبطة بهذه الفاتورة فقط
   $partialPayments = \App\Models\PartialPayment::whereHas('invoices', function($query) use ($id) {
            $query->where('invoice_id', $id);
        })
        ->with(['invoices' => function($query) use ($id) {
            $query->where('invoice_id', $id);
        }])
        ->orderBy('payment_date', 'asc')
        ->get();
@endphp



@if($partialPayments->isNotEmpty())
    <div style="margin-top: 20px;">
        <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 5px; font-size: 14pt;"> سجل الدفعات: للفاتورة {{ $payment->invoice->invoice_no }} </h4>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt;">
            <thead>
                <tr style="background-color: #f5f5f5;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">#</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">رقم الدفعة</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">تاريخ الدفعة</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">المبلغ</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">طريقة الدفع</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partialPayments as $index => $partial)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">PAY-{{ str_pad($partial->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ date('d/m/Y', strtotime($partial->payment_date)) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><span class="currency">{{ number_format($partial->amount, 2) }}</span></td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                        @if($partial->payment_method == 'cash_shekel')
                            نقدي شيكل
                        @elseif($partial->payment_method == 'cash_dinar')
                            نقدي دينار
                        @else
                            شيك
                        @endif
                    </td>
                </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f9f9f9;">
                    <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: right;">إجمالي الدفعات:</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><span class="currency">{{ number_format($partialPayments->sum('amount'), 2) }}</span></td>
                    <td style="border: 1px solid #ddd; padding: 8px;"></td>
                </tr>
            </tbody>
        </table>
    </div>
@else
    <div style="margin-top: 15px; color: #777;">
        لا توجد دفعات مسجلة لهذه الفاتورة.
    </div>
@endif

     
    @endif
    @endforeach


    <div class="footer">
        <p>هذا المستند تم إنشاؤه تلقائياً عبر نظام أي بوينت للمبيعات</p>
        <p>شركة تطوير للتكنولوجيا | هاتف: 0592660704 | البريد الإلكتروني: info@tatweer.it.com</p>
        <p>تاريخ الطباعة: {{ date('Y-m-d H:i') }}</p>
    </div>

</body>

</html>
