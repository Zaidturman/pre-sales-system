@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">سجل الحضور والغياب</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{ route('attendance.create') }}" class="btn btn-secondary waves-effect waves-light" style="float:right;">اضافة حضور</a>
                        <br><br>

                        <h4 class="card-title">بيانات الحضور والغياب</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الموظف</th>
                                    <th>التاريخ</th>

                                    <th>وقت الوصول</th>
                                    <th>وقت المغادرة</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($attendanceData as $key => $attendance)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $attendance->employee->name }}</td>
                                    <td>{{ $attendance->date }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->arrival_time)->format('h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->departure_time)->format('h:i A') }}</td>
                                    <td>{{ ucfirst($attendance->status) }}</td>
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