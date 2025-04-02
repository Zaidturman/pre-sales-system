@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">جميع ديون الزبائن</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{route('credit.customer.pdf')}}" class="btn btn-secondary waves-effect waves-light" style="float:right;" target="_blank"><i class="fa fa-print"></i> طباعة ديون الزبائن</a>
                        <br><br>

                        <h4 class="card-title">بيانات جميع الديون</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم الفاتورة</th>
                                    <th>التاريخ</th>
                                    <th>المبلغ المستحق</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($allData as $key => $item)
                                @if($item->due_amount != 0) <!-- تأكد من أن due_amount لا يساوي 0 -->
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>#{{ $item['invoice']['invoice_no'] }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item['invoice']['date'])) }}</td>
                                    <td>{{ $item->due_amount }}</td>
                                    <td>
                                        <a href="{{ route('customer.edit.invoice', $item->invoice_id) }}" class="btn btn-info sm" title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="{{ route('customer.invoice.details.pdf', $item->invoice_id) }}" class="btn btn-primary sm" title="Customer Invoice Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endif
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