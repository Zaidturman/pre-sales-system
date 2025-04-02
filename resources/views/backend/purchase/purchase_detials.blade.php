@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تفاصيل فاتورة المشتريات</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">تفاصيل الفاتورة رقم {{ $purchases[0]->purchase_no }}</h4>
                        <br>

                        <!-- عرض اسم التاجر -->
                        <h5>اسم المورد: {{ $purchases[0]->supplier->name }}</h5> <!-- اسم المورد -->

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المنتج</th>
                                    <th>الكمية</th>
                                    <th>سعر الوحدة</th>
                                    <th>السعر الإجمالي</th>
                                    <th>الوصف</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $purchase)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $purchase['product']['name'] }}</td>
                                    <td>{{ $purchase->buying_qty }}</td>
                                    <td>{{ $purchase->unit_price }}</td>
                                    <td>{{ $purchase->buying_price }}</td>
                                    <td>{{ $purchase->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h5>المدفوعات:</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>حالة الدفع</th>
                                    <th>المبلغ المدفوع</th>
                                    <th>المبلغ المستحق</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->paid_status)) }}</td>
                                    <td>{{ $payment->paid_amount }}</td>
                                    <td>{{ $payment->due_amount }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
