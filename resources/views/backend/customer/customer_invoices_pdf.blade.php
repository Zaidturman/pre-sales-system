<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>فواتير الزبون</title>
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
            font-size: 14px;
            direction: rtl;
            text-align: right;
            margin: 20px;
            line-height: 1.8;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
        }

        .header h2 {
            color: #0056b3;
            margin: 0;
            font-weight: bold;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .invoice-header {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>فواتير العميل: {{ $customer->name }}</h2>
        <p>رقم الجوال: {{ $customer->mobile_no }}</p>
    </div>

    @foreach ($payments as $payment)
        @if ($payment->invoice)
            <div class="invoice-header">
                <h3>فاتورة رقم: {{ $payment->invoice->invoice_no }} - التاريخ: {{ $payment->invoice->date }}</h3>
                <p>
                    <strong>حالة الدفع:</strong>
                    @if ($payment->paid_status == 'full_paid')
                        مدفوعة بالكامل
                    @elseif($payment->paid_status == 'partial_paid')
                        مدفوعة جزئيًا
                    @elseif($payment->paid_status == 'full_due')
                        دين كامل
                    @else
                        {{ $payment->paid_status }}
                    @endif
                </p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>سعر الوحدة</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($payment->invoice->invoice_details as $detail)
                        <tr>

                            <td>{{ $detail->product->name ?? 'منتج محذوف' }}</td>
                            <td>{{ $detail->selling_qty }}</td>
                            <td>₪{{ number_format($detail->selling_price, 2) }}</td>
                            <td>₪{{ number_format($detail->selling_qty * $detail->selling_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table style="width: 300px; margin: 15px 0; float: left;">
                <tr>
                    <td><strong>الإجمالي:</strong></td>
                    <td>₪{{ number_format($payment->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>المدفوع:</strong></td>
                    <td>₪{{ number_format($payment->paid_amount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>المتبقي:</strong></td>
                    <td>₪{{ number_format($payment->due_amount, 2) }}</td>
                </tr>
            </table>
            <div style="clear: both;"></div>
            <hr>
        @endif
    @endforeach

    <div class="footer">
        تم إنشاء هذا التقرير تلقائيًا - نظام إدارة المبيعات
    </div>
</body>

</html>
