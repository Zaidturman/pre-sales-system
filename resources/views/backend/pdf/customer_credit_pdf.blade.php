@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تقرير ديون الزبون</h4>
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
                                    <h3>فحم الزين</h3>
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
                                </div>
                            </div>
                        </div>

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
                                                        <td><strong>اسم الزبون</strong></td>
                                                        <td class="text-center"><strong>اجمالي المبلغ المستحق</strong></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $total_due = [];
                                                        $grand_total = 0;  // لتخزين المجموع الكلي
                                                    @endphp

                                                    <!-- عرض جميع المدفوعات بدون تحقق من المبلغ -->
                                                    @foreach($allData as $item)
                                                        @php
                                                            $customerName = $item->customer ? $item->customer->name : 'غير معروف';
                                                            if (!isset($total_due[$customerName])) {
                                                                $total_due[$customerName] = 0;
                                                            }
                                                            $total_due[$customerName] += $item->due_amount;
                                                            $grand_total += $item->due_amount;  // إضافة المبلغ إلى المجموع الكلي
                                                        @endphp
                                                    @endforeach

                                                    @foreach($total_due as $customer => $amount)
                                                        <tr>
                                                            <td class="text-center">{{ $customer }}  </td>
                                                            <td class="text-center">₪{{ number_format($amount, 2) }}</td>
                                                        </tr>
                                                    @endforeach

                                                    <!-- عرض المجموع الكلي مع تغيير اللون في الخلفية -->
                                                    <tr class="total-row">
                                                        <td class="text-center"><strong>المجموع الكلي</strong></td>
                                                        <td class="text-center"><strong>₪{{ number_format($grand_total, 2) }}</strong></td>
                                                    </tr>
                                                    
                                                
                                                </tbody>
                                            </table>
                                        </div>

                                        @php
                                            $date = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
                                        @endphp
                                        <i>Printing Time : {{ $date->format('F j, Y, g:i a') }}</i>

                                        <div class="d-print-none">
                                            <div class="float-end">
                                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

@endsection

<!-- إضافة هذا الجزء داخل الـ <style> -->
@section('style')
<style>
    .total-row {
        background-color: #f8d7da; /* اللون الخلفي المميز */
        color: #721c24; /* اللون النصي للمجموع */
    }
</style>
@endsection
