@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- Search Form -->
        <div class="row mb-3">
            <div class="col-12">
                <form method="GET" action="" class="d-flex align-items-center">
            <!-- الحفاظ على الـ params الحالية -->
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            
            <input type="text" name="search" class="form-control me-2" placeholder="ابحث عن اسم المنتج..." value="{{ request('search') }}" style="max-width: 300px;">
            <button type="submit" class="btn btn-primary">بحث</button>
        </form>
            </div>
        </div>
        <!-- Search Results Summary -->
        @if(isset($searchSummary))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <strong>نتائج البحث عن المنتج:</strong> <span class="text-primary">{{ $searchSummary['item_name'] }}</span><br>
                    <strong>عدد مرات الشراء:</strong> {{ $searchSummary['count'] }}<br>
                    <strong>إجمالي الكمية:</strong> {{ $searchSummary['total_qty'] }}<br>
                    <strong>إجمالي المبلغ:</strong> ₪{{ $searchSummary['total_amount'] }}<br>
                    <strong>الموردون:</strong> {{ implode('، ', $searchSummary['suppliers']) }}
                </div>
            </div>
        </div>
        @endif

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تقرير المشتريات اليومية</h4>
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
                                            <!-- Additional address or information if needed -->
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>تقرير المشتريات اليومية</strong></h3>
                                        <span class="btn btn-info" style="margin-left: 10px;">{{ date('d-m-Y', strtotime($start_date)) }}</span>
                                        <span class="btn btn-success">{{ date('d-m-Y', strtotime($end_date)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->

                        @php
                        $grand_total = 0;
                        @endphp

                        @foreach($groupedData as $purchase_no => $purchases)
                        @php
                        $invoice_total = 0;
                        @endphp

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                    <h3 class="font-size-16"><strong>فاتورة رقم: {{ $purchase_no }} /<span  class="font-size-16 text-danger "> المورد : {{ $purchases->first()->supplier->name }}</span></strong></h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>رقم المشتريات</th>
                                                    <th>التاريخ</th>
                                                    <th>اسم المنتج</th>
                                                    <th>الكمية</th>
                                                    <th>سعر الوحدة</th>
                                                    <th>المبلغ الاجمالي</th>

                                                </tr>
                                                
                                            </thead>
                                            <tbody>
                                                @foreach($purchases as $key => $item)
                                                @php
                                                $payment = $item->purchasePayments->first();
                                                @endphp
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $item->purchase_no }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                                    <td>{{ $item['product']['name'] }}</td>
                                                    <td>{{ $item->buying_qty }} {{ $item['product']['unit']['name'] }}</td>
                                                    <td>{{ $item->unit_price }}</td>
                                                    <td>{{ $item->buying_price }}</td>
                                                    <td>{{ $payment ? $payment->paid_status : 'N/A' }}</td>
                                                </tr>
                                                @php
                                                $invoice_total += $item->buying_price;
                                                @endphp
                                                @endforeach
                                                <tr>
                                                    <td colspan="6" class="text-center"><strong>المجموع الفرعي للفاتورة</strong></td>
                                                    <td colspan="3" class="text-end">₪{{ $invoice_total }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-center">حالة الدفع</td>
                                                    <td colspan="3" class="text-end"> {{ $payment ? $payment->paid_status : 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-center"> المبلغ المتبقي</td>
                                                    <td colspan="3" class="text-end"> {{ $payment ? $payment->due_amount : 'N/A' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                        $grand_total += $invoice_total;
                        @endphp

                        @endforeach

                        <div class="row">
                            <div class="col-12 text-end">
                                <h3 class="font-size-16"><strong>المبلغ الاجمالي لجميع الفواتير: ₪{{ $grand_total }}</strong></h3>
                            </div>
                        </div>

                        <div class="d-print-none">
                            <div class="float-end">
                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

@endsection