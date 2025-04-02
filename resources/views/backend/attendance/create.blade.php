@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">إضافة حضور</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">سجل حضور الموظف</h4><br>

                        <form method="POST" action="{{ route('attendance.store') }}">
                            @csrf

                            <div class="mb-3 row">
                                <label for="employee_id" class="col-sm-2 col-form-label">الموظف</label>
                                <div class="col-sm-10">
                                    <select name="employee_id" id="employee_id" class="form-select">
                                        <option selected disabled>اختر موظفاً</option>
                                        @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" data-worktype="{{ $employee->worktype }}">
                                            {{ $employee->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="arrival_time" class="col-sm-2 col-form-label">التاريح </label>
                                <div class="col-sm-10">
                                    <input type="date" name="date" class="form-control">
                                </div>
                            </div>
                            <div id="time-fields">
                                <div class="mb-3 row">
                                    <label for="arrival_time" class="col-sm-2 col-form-label">وقت الوصول</label>
                                    <div class="col-sm-10">
                                        <input type="time" name="arrival_time" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="departure_time" class="col-sm-2 col-form-label">وقت المغادرة</label>
                                    <div class="col-sm-10">
                                        <input type="time" name="departure_time" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div id="kilometers-field" class="mb-3 row" style="display: none;">
                                <label for="kilometers" class="col-sm-2 col-form-label">عدد الكيلوات</label>
                                <div class="col-sm-10">
                                    <input type="number" name="count" id="kilometers" class="form-control">
                                </div>
                            </div>

                            <div id="Contractor-field" class="mb-3 row" style="display: none;">
                                <label for="Contractor" class="col-sm-2 col-form-label">عدد الساعات</label>
                                <div class="col-sm-10">
                                    <input type="number" name="work_hours" id="Contractor" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="status" class="col-sm-2 col-form-label">الحالة</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-select">
                                        <option value="present">حاضر</option>
                                        <option value="absent">غائب</option>
                                        <option value="late">متأخر</option>
                                        <option value="excused">مُعذر</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">حفظ</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('employee_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const worktype = selectedOption.getAttribute('data-worktype');
        const timeFields = document.getElementById('time-fields');
        const kilometersField = document.getElementById('kilometers-field');
        const ContractorField = document.getElementById('Contractor-field');


        if (worktype === 'kelos') {
            timeFields.style.display = 'none';
            kilometersField.style.display = 'flex';
            ContractorField.style.display = 'none';

        } else if (worktype === 'Contractor') {
            timeFields.style.display = 'none';
            kilometersField.style.display = 'none';
            ContractorField.style.display = 'flex';


        } else {
            timeFields.style.display = 'block';
            kilometersField.style.display = 'none';
            ContractorField.style.display = 'none';

        }
    });
</script>
@endsection