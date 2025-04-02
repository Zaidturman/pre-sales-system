@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">طباعة جميع الفواتير</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                    <a href="{{route('invoice.add')}}" class="btn btn-secondary waves-effect waves-light" style="float:right;">اضافة فاتورة</a>
                    <br><br>

                        <h4 class="card-title">بيانات جميع الفواتير</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم الفاتورة
                                </th>
                                                                <th>اسم الزبون</th>

                                <th>التاريخ</th>
                                <th>الملاحظات</th>
                                <th>المبلغ</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>


                            <tbody>
                            @foreach($allData as $key =>$item)
                            <tr>
                                <td>{{( $key+1 )}}</td>
                                <td>#{{( $item->invoice_no )}}</td>
                                <td>{{( $item['payment']['customer']['name']?? 'N/A' )}}</td>

                                <td>{{ date('d-m-Y',strtotime($item->date)) }}</td>
                                <td>{{( $item->description )}}</td>

                                <td>$ {{( $item['payment']['total_amount'] )}}</td>
                                <td>
                                    <a href="{{route('print.invoice',$item->id)}}" class="btn btn-info sm" title="Print Invoice"><i class="fas fa-print"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->


    </div> <!-- container-fluid -->
</div>

@endsection
