@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">جميع مستحقات الموردين</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{route('credit.customer.pdf')}}" class="btn btn-secondary waves-effect waves-light" style="float:right;" target="_blank"><i class="fa fa-print"></i> طباعة ديون الموردين</a>
                        <br><br>

                        <h4 class="card-title">بيانات جميع الديون</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الزبون</th>
                                    <th>رقم الفاتورة</th>
                                    <th>التاريخ</th>
                                    <th>المبلغ المستحق</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($allData as $key =>$item)
                                <tr>
                                    <td>{{( $key+1 )}}</td>
                                    <td>{{( $item['supplier']['name'] )}}</td>
                                    <td>#{{( $item['purchase']['purchase_no'] )}}</td>
                                    <td>{{( date('d-m-Y',strtotime($item['purchase']['date'])) )}}</td>
                                    <td>{{ $item->due_amount }}</td>
                                    <td>
                                        <a href="{{route('supplier.edit.invoice',$item->purchase_id)}}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>

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