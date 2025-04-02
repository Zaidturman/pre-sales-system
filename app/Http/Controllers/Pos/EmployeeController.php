<?php

namespace App\Http\Controllers\Pos;

use Carbon\Carbon;

use App\Models\Salary;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allData = Employee::get();

        return view(
        'backend.employee.index', compact('allData'));

    }
    public function create()
    {
        return view('backend.employee.create');
    }

    // Store a newly created employee in storage
    public function store(Request $request)
    {
        $employee = new Employee();
        $employee->name = $request->name;
        $employee->balance = $request->balance;
        $employee->worktype = $request->worktype;
        $employee->startdate = $request->startdate;
        $employee->jobtype = $request->jobtype;
        $employee->save();
        $startdate=$request->startdate;
        $Month= Carbon::parse($startdate)->format('m-Y');

        $salary=new Salary();
        $salary->employee_id = $employee->id;
        $salary->month = $Month;
        $salary->work_hours = 0; 
        $salary->advance = 0; 
        $salary->salary_value = 0; 
        $salary->salary_status ='unPaid'; 
        $salary->salarypaid_value = 0; 
        $salary->salaryremaning_value = 0; 
        $salary->save();
        

        return redirect()->route('employee.index')->with('success', 'Employee added successfully');
    }

    // Show the form for editing the specified employee
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('backend.employee.edit', compact('employee'));
    }

    // Update the specified employee in storage
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->name = $request->name;
        $employee->balance = $request->balance;
        $employee->worktype = $request->worktype;
        $employee->startdate = $request->startdate;
        $employee->jobtype = $request->jobtype;
        $employee->save();

        return redirect()->route('employee.index')->with('success', 'Employee updated successfully');
    }

    // Remove the specified employee from storage
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employee.index')->with('success', 'Employee deleted successfully');
    }

    public function salare()
    {
        $allData = Employee::all();

        // Calculate the sum of all balances
        $totalBalance = Employee::sum('balance');
        $balancesByWorktype = Employee::select('worktype', \DB::raw('SUM(balance) as total_balance'))
        ->groupBy('worktype')
        ->get();

        // Pass data to the view
        return view( 'backend.employee.salares', compact('allData',
        'totalBalance', 'balancesByWorktype'));
    }
    public function presenceabsence()
    {
        return view('backend.employee.presenceabsence');
    }
    public function report()
    {
        return view('backend.employee.report');
    }

  
    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }
    public function DailyEmployeePdf(Request $request)
    {
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));
        $allData = Employee::whereBetween('startdate', [$start_date, $end_date])->get();
        return view('backend.pdf.daily_employee_report_pdf', compact('allData', 'start_date', 'end_date'));
    }

  
}
