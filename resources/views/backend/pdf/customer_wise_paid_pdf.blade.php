@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تقرير المدفوعات حسب الزبون</h4>

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
                                    <div class="col-6  text-end">
                                        <address>

                                            @if($customer)
                                            <strong>اسم الزبون: </strong> {{ $customer->name }}<br>
                                            <strong>رقم الهاتف: </strong> {{ $customer->mobile_no }}<br>
                                            <strong>العنوان: </strong> {{ $customer->	address }}<br>


                                            @else
                                            <p>لا توجد معلومات للزبون.</p>
                                            @endif
                                        </address>
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
                                                            <td><strong># </strong></td>
                                                            <td class="text-center"><strong>اسم الزبون</strong></td>
                                                            <td class="text-center"><strong>رقم الفاتورة</strong>
                                                            </td>
                                                            <td class="text-center"><strong>التاريخ</strong>
                                                            </td>
                                                            <td class="text-center"><strong>المبلغ المستحق</strong>
                                                            </td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                        @php
                                                        $total_due = '0';
                                                        @endphp
                                                        @foreach($allData as $key => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $key+1 }}</td>
                                                            <td class="text-center">{{ $item['customer']['name'] }}</td>
                                                            <td class="text-center">#{{ $item['invoice']['invoice_no'] }}</td>
                                                            <td class="text-center">{{ $item['invoice']['date'] }}</td>
                                                            <td class="text-center">{{ $item->due_amount }}</td>
                                                        </tr>
                                                        @php
                                                        $total_due += $item->due_amount;
                                                        @endphp
                                                        @endforeach
                                                        <tr>
                                                            <td class="no-line"></td>
                                                            <td class="no-line"></td>
                                                            <td class="no-line"></td>
                                                            <td class="no-line text-center">
                                                                <strong>اجمالي المبلغ المستحق</strong>
                                                            </td>
                                                            <td class="no-line text-end">
                                                                <h4 class="m-0">₪{{ $total_due }}</h4>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            @php
                                            $date = new DateTime('now',new DateTimeZone('Asia/Dhaka'));
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