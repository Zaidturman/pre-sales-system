@extends('admin.admin_master')

@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تفاصيل راتب الموظف</h4>
                </div>
            </div>
        </div>

        <!-- Salary Details Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">اسم الموظف</h4>
                        <h1>{{$employee->name}}</h1><br>

                        <h4 class="card-title">تفاصيل الرواتب</h4><br>
                        <table class="table table-bordered dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>الشهر</th>
                                    @if ($employee->worktype === 'kelos')
                                    <th> عدد الكيلوات</th>

                                    @else
                                    <th>ساعات العمل</th>

                                    @endif
                                    <th>السلفة</th>
                                    <th>الراتب</th>
                                    <th>المبلغ المدفوع </th>
                                    <th>المبلغ المتبقي </th>
                                    <th>حالة الراتب</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salaryDetails as $detail)
                                <tr>
                                    <td>{{ $detail->month }}</td>
                                    <td>{{ $detail->work_hours }}</td>
                                    <td>{{ $detail->advance }}</td>
                                    <td>{{ $detail->salary_value}}</td>
                                    <td>{{ $detail->salarypaid_value}}</td>
                                    <td>{{ $detail->salaryremaning_value}}</td>
                                    <td>
                                        <form action="{{ route('salary-details.updateStatus', $detail->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="salary_status" class="form-select" onchange="this.form.submit()">
                                                <option value='unPaid' {{ $detail->salary_status == 'unPaid' ? 'selected' : '' }}>لم يتم دفعه</option>
                                                <option value="Paid" {{ $detail->salary_status == 'Paid' ? 'selected' : '' }}>مدفوع بالكامل</option>
                                                <option value="Partial" {{ $detail->salary_status == 'Partial' ? 'selected' : '' }}>جزئي</option>
                                                <option value="Delayed" {{ $detail->salary_status == 'Delayed' ? 'selected' : '' }}>مؤجل</option>
                                            </select>
                                        </form>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Advance Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">تسجيل سلفة جديدة</h4><br>
                        <form action="{{ route('salary-details.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="advance_amount" class="form-label">مبلغ السلفة</label>
                                <input type="number" name="advance_amount" id="advance_amount" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="advance_month" class="form-label">الشهر</label>
                                <input type="month" name="advance_month" id="advance_month" class="form-control" required>
                            </div>
                            <input type="hidden" name="employee_id" value="{{ $employeeId }}">
                            <button type="submit" class="btn btn-success">تسجيل السلفة</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    function togglePartialSalaryInput(detailId) {
        var statusSelect = document.getElementById('salary_status_' + detailId);
        var partialSalaryInput = document.getElementById('partial_salary_' + detailId);

        if (statusSelect.value === 'Partial') {
            partialSalaryInput.style.display = 'inline';
        } else {
            partialSalaryInput.style.display = 'none';
            partialSalaryInput.value = ''; // إفراغ الحقل إذا تم تغييره إلى حالة أخرى
        }
    }
</script>
@endsection