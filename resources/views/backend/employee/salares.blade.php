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

                        <h4 class="card-title">بيانات جميع الموظفين</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الموظف</th>
                                    <th>الراتب</th>
                                    <th>نوع العمل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allData as $key => $employee)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->balance }}</td>
                                    <td>{{ $employee->worktype }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Sums by Work Type -->
                        <div class="mt-4">
                            <h4>إجمالي الرصيد حسب نوع العمل:</h4>
                            <ul>
                                @foreach($balancesByWorktype as $balance)
                                <li>{{ $balance->worktype }}: ₪{{ $balance->total_balance }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Total Balance Sum -->
                        <div class="mt-4">
                            <h4>إجمالي الرصيد لجميع الموظفين: ₪{{ $totalBalance }}</h4>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

@endsection