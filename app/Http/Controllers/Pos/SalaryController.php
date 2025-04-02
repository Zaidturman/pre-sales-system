<?php

namespace App\Http\Controllers\pos;

use Carbon\Carbon;

use App\Models\Salary;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        // التحقق من وجود الموظف
        $employee = Employee::findOrFail($id);

        // جلب تفاصيل الرواتب الخاصة بالموظف
        $salaryDetails = Salary::where('employee_id', $id)->get();

        // عرض الصفحة مع تمرير بيانات الموظف وتفاصيل الراتب
        return view('backend.employee.employeesalary', [
            'salaryDetails' => $salaryDetails,
            'employeeId' => $id,
            'employee' => $employee
        ]);
    }

    /**
     * تسجيل سلفة جديدة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'advance_amount' => 'numeric',
            'advance_month' => 'date_format:Y-m',
        ]);

        $inputMonth = $request->input('advance_month');
        $formattedMonth = Carbon::createFromFormat('Y-m', $inputMonth)->format('m-Y');

        $salaryRecord = Salary::where('employee_id', $request->input('employee_id'))
            ->where('month', $formattedMonth)
            ->first();

        if ($salaryRecord) {
            $salaryRecord->update(
                [
                    'salarypaid_value' => $salaryRecord->salarypaid_value + $request->input('advance_amount'),
                    'salaryremaning_value' => $salaryRecord->salary_value - ($salaryRecord->salarypaid_value + $request->input('advance_amount')),
                    'advance' => $request->input('advance_amount'),
                    'salary_status' => 'Partial',
                ]
            );
        } else {
            Salary::create([
                'employee_id' => $request->input('employee_id'),
                'month' => $request->input('advance_month'),
                'work_hours' => 0,
                'advance' => $request->input('advance_amount'),
                'salary_status' => 'Partial', // يمكن تغيير الحالة وفقًا للمتطلبات
                'salary_value' => 0, // يمكنك ضبط هذه القيمة وفقًا لحاجتك
                'salarypaid_value' =>  $request->input('advance_amount'), // يمكنك ضبط هذه القيمة وفقًا لحاجتك
                'salaryremaning_value' => 0, // يمكنك ضبط هذه القيمة وفقًا لحاجتك
            ]);
        }

        // إعادة توجيه إلى الصفحة السابقة مع رسالة نجاح
        return redirect()->back()->with('success', 'تم تسجيل السلفة بنجاح.');
    }

    public function updateStatus(Request $request, $id)
    {
        $salary = Salary::findOrFail($id);
        if ($request->input('salary_status') == 'Paid') {
            $salary->update([
                'salary_status' => $request->input('salary_status'),
                'salarypaid_value' => $salary->salary_value,
                'salaryremaning_value' => 0,
            ]);
        } else if ($request->input('salary_status') == 'unPaid') {
            $salary->update([
                'salary_status' => $request->input('salary_status'),
                'salarypaid_value' => 0,
                'salaryremaning_value' => $salary->salary_value,
            ]);
        } else if ($request->input('salary_status') == 'Partial') {
            $salary->update([
                'salary_status' => $request->input('salary_status'),
                'salarypaid_value' => $request->input('salary_status') === 'Partial' ? $request->input('partial_salary') : $salary->salarypaid_value,
                'salaryremaning_value' => $salary->salary_value - $request->input('partial_salary'),
            ]);
        } else {
            $salary->update([
                'salary_status' => $request->input('salary_status'),

            ]);
        }


        return redirect()->back()->with('success', 'تم تحديث حالة الراتب بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salary $salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        //
    }
}
