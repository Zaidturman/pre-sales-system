<?php

namespace App\Http\Controllers\pos;

use Carbon\Carbon;


use App\Models\Salary;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class EmployeeAttendanceController extends Controller
{
    public function index()
    {
        $attendanceData = EmployeeAttendance::with('employee')->get();
        return view('backend.attendance.index', compact('attendanceData'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('backend.attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {

        //dd($request->all());
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'arrival_time' => 'nullable|date_format:H:i',
            'departure_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,excused',
            'count' => 'nullable|numeric|min:0', // للتحقق من عدد الساعات أو الكيلوات
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $workType = $employee->worktype;



        // حساب ساعات أو الكيلوات العمل
        $workHours = 0;

        if ($workType === 'kelos') {
            $workHours = $request->count; // عدد الكيلوات المدخلة

            // استرجاع الشهر والسنة
            $month = Carbon::parse($request->date)->format('m-Y');
            $monthstore = Carbon::parse($request->date)->format('m');
            $year = Carbon::parse($request->date)->format('Y');

            // البحث عن سجل الراتب الحالي للشهر والسنة المحددين
            $salary = Salary::where('employee_id', $request->employee_id)
                ->where('month', $month)
                ->first();

            if ($salary) {
                // إذا كان هناك سجل للشهر، قم بتحديثه بجمع القيمة الحالية مع القيمة الجديدة
                $updatedWorkHours = $salary->work_hours + $workHours;
                $updatedSalaryValue = $updatedWorkHours * $employee->balance;

                $salary->update([
                    'work_hours' => $updatedWorkHours,
                    'salary_value' => $updatedSalaryValue,
                    'salaryremaning_value' => $updatedSalaryValue - $salary->salarypaid_value,
                ]);
            } else {
                // إذا لم يكن هناك سجل للشهر، قم بإنشاء سجل جديد
                $salaryValue = $workHours * $employee->balance;

                Salary::create([
                    'employee_id' => $request->employee_id,
                    'month' => $month,
                    'work_hours' => $workHours,
                    'salary_value' => $salaryValue,
                    'salary_status' => 'unPaid',
                    'advance' => 0,
                    'salarypaid_value' => 0,
                    'salaryremaning_value' => $salaryValue,
                ]);
            }

            // تخزين سجل الحضور
            EmployeeAttendance::create([
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'status' => $request->status,
                'count' => $workHours,
            ]);
        } elseif ($request->arrival_time && $request->departure_time) {

            EmployeeAttendance::create($request->all());

            $employee = Employee::find($request->employee_id);
            $workType = $employee->work_type;

            $workHours = 0;
            if ($request->arrival_time && $request->departure_time) {
                $arrival = Carbon::createFromFormat('H:i', $request->arrival_time);
                $departure = Carbon::createFromFormat('H:i', $request->departure_time);
                $workHours = $departure->diffInHours($arrival);
            }

            $month = Carbon::parse($request->date)->format('m-Y');
            $monthstore = Carbon::parse($request->date)->format('m');
            $year = Carbon::parse($request->date)->format('Y');

            $totalWorkHoursForMonth = EmployeeAttendance::where('employee_id', $request->employee_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $monthstore)
                ->sum(DB::raw('TIMESTAMPDIFF(HOUR, arrival_time, departure_time)'));

            $salaryRecord = Salary::where('employee_id', $request->employee_id)
                ->where('month', $month)
                ->first();

            $salary_value = 0;
            if ($employee->worktype == 'hours') {
                $salary_value = $totalWorkHoursForMonth * $employee->balance;
            } else if ($employee->worktype == 'days') {
                $totalWorkDaysForMonth = EmployeeAttendance::where('employee_id', $request->employee_id)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $monthstore)
                    ->count();
                $totalworkDaysForMonth = EmployeeAttendance::where('employee_id', $request->employee_id)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $monthstore)
                    ->count();
                $salary_value = $totalWorkDaysForMonth * $employee->balance;
            } else if ($employee->worktype == 'months') {
                $salary_value = $employee->balance;
            } else if ($employee->worktype == 'Contractor') {
                $salary_value = $employee->balance;
            }

            if ($salaryRecord) {

                $salaryRecord->update([
                    'work_hours' => $totalWorkHoursForMonth,
                    'salary_status' => $salaryRecord->salary_status,
                    'salary_value' =>  $salary_value,
                    'advance' => $salaryRecord->advance,
                    'salarypaid_value' => $salaryRecord->salarypaid_value,
                    'salaryremaning_value' => $salary_value - $salaryRecord->salarypaid_value,

                ]);
            } else {
                $salaryStatus = 'unPaid';
                $advance = 0;
                $salarypaid_value = 0;
                $slaryremaning_value = 0;
                Salary::create([
                    'employee_id' => $request->employee_id,
                    'month' => $month,
                    'work_hours' => $totalWorkHoursForMonth,
                    'salary_value' => $salary_value,
                    'salary_status' => $salaryStatus,
                    'advance' => $advance,
                    'salarypaid_value' => $salarypaid_value,
                    'salaryremaning_value' => $salary_value - $salarypaid_value,
                ]);
            }





            // error code 
            /*  $arrival = Carbon::createFromFormat('H:i', $request->arrival_time);
            $departure = Carbon::createFromFormat('H:i', $request->departure_time);

            if ($departure->lt($arrival)) {
                return back()->withErrors(['departure_time' => 'وقت المغادرة يجب أن يكون بعد وقت الوصول.']);
            }

            $workHours = $departure->diffInHours($arrival); // حساب ساعات العمل
            EmployeeAttendance::create([
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'arrival_time' => $request->arrival_time,
                'departure_time' => $request->departure_time,
                'status' => $request->status,
                'work_hours' => $workHours,
            ]);
            // حساب الرواتب
            $month = Carbon::parse($request->date)->format('m-Y');
            $monthstore = Carbon::parse($request->date)->format('m');
            $year = Carbon::parse($request->date)->format('Y');

            $totalWorkHoursForMonth = EmployeeAttendance::where('employee_id', $request->employee_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $monthstore)
                ->sum('work_hours');

            $salaryRecord = Salary::where('employee_id', $request->employee_id)
                ->where('month', $month)
                ->first();

            $salaryValue = 0;

            if ($workType === 'hours') {
                $salaryValue = $totalWorkHoursForMonth * $employee->balance;
            } elseif ($workType === 'days') {
                $totalWorkDaysForMonth = EmployeeAttendance::where('employee_id', $request->employee_id)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $monthstore)
                    ->count();
                $salaryValue = $totalWorkDaysForMonth * $employee->balance;
            } elseif ($workType === 'months' || $workType === 'Contractor') {
                $salaryValue = $employee->balance;
            } elseif ($workType === 'kelos') {
                $salaryValue = $totalWorkHoursForMonth * $employee->balance; // احتساب الراتب بناءً على الكيلوات
            }
            // تحديث أو إنشاء سجل الراتب
            if ($salaryRecord) {
                $salaryRecord->update([
                    'work_hours' => $totalWorkHoursForMonth,
                    'salary_status' => $salaryRecord->salary_status,
                    'salary_value' => $salaryValue,
                    'advance' => $salaryRecord->advance,
                    'salarypaid_value' => $salaryRecord->salarypaid_value,
                    'salaryremaning_value' => $salaryValue - $salaryRecord->salarypaid_value,
                ]);
            } else {
                Salary::create([
                    'employee_id' => $request->employee_id,
                    'month' => $month,
                    'work_hours' => $totalWorkHoursForMonth,
                    'salary_value' => $salaryValue,
                    'salary_status' => 'unPaid',
                    'advance' => 0,
                    'salarypaid_value' => 0,
                    'salaryremaning_value' => $salaryValue,
                ]);
            } */
        }

        // تخزين سجل الحضور





        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
    }
}
