@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">جميع الموظفين</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{ route('employee.create') }}" class="btn btn-secondary waves-effect waves-light" style="float:right;">اضافة موظف</a>
                        <br><br>

                        <h4 class="card-title">بيانات جميع الموظفين</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الراتب</th>
                                    <th>نوع العمل</th>
                                    <th>تاريخ البدء</th>
                                    <th>نوع الوظيفة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($allData as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->balance }}</td>
                                    <td>{{ $item->worktype }}</td>
                                    <td>{{ $item->startdate }}</td>
                                    <td>{{ $item->jobtype }}</td>
                                    <td>
                                        <a href="{{ route('employee.edit', $item->id) }}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>

                                        <a href="{{ route('employee.delete', $item->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
                                        <a href="{{ route('employee.salarydetails', $item->id) }}" class="btn btn-success  sm" title="info Data" id="info"><i class="fas fa-money-bill-wave"></i></a>



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