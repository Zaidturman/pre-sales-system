<?php

namespace App\Http\Controllers\Pos;

use App\Models\User;

use App\Models\Expense;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ExpenseCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ExpenseAll(){
        $allData=Expense::with('category')->orderBy('date','desc')->get();
        return view('backend.expense.expense_all',compact('allData'));
    }
   
    public function category()
    {
        $categories  = ExpenseCategory::withCount('expenses')->get();
        return view('backend.expense.categort_expense',compact('categories'));
    }
    public function printList()
    {
        $allData= Expense::with('category')->orderBy('date','desc')->get();
        return view('backend.expense.print_list',compact('allData'));
    }
    public function printExpense($id){
        $expense=Expense::with('category')->FindOrFail($id);
        $createdById= $expense->created_by;
        $creator=User::find($createdById);
        $creatorName=$creator->name;
        return view('backend.pdf.Expense_pdf',compact('expense', 'creatorName'));

    }
    public function DailyExpenseReport()
    {
        return view('backend.expense.daily_expense_report');
    }
    public function DailyExpensePdf(Request $request){
        $start_date=date('Y-m-d',strtotime($request->start_date));
        $end_date=date('Y-m-d',strtotime($request->end_date));
        $allData=Expense::whereBetween ('date',[$start_date,$end_date])->get();
        return view('backend.pdf.daily_expense_report_pdf',compact('allData','start_date','end_date'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories=ExpenseCategory::all();
        return view ('backend.expense.create',compact('categories'));

    }

    public function store(Request $request){
         /* $request->validate([
            'name'=>'required|string|max:255',
            'date'=>'required|date',
            'amount'=>'required',
            'detials'=>'nullable|string',
            'category_id'=>'nullable|integer|exists:categories,id',
        ]);  */
        Expense::insert([
            'name'=>$request->name,
            'date'=>$request->date,
            'amount'=>$request->amount,
            'detials'=>$request->detials,
            'category_id' => $request->category_id,
            'created_at'=>Carbon::now(),
            'created_by' => Auth::user()->id

        ]);
        $notification = array(
            'message'=>'Expense Inserted Successfully',
            'alert-type'=>'success'
        );

        return redirect()->route('expense.all')->with($notification);
    }
    public function createcategory()
    {
        return view('backend.expense.create_catogery');
    }

    public function storecategory(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string|max:255'
        ]);
        ExpenseCategory::insert([
            'name'=>$request->name,
            'description'=>$request->description,
            'created_at'=>Carbon::now(),

        ]);

        $notification=array(
            'message'=>'Category Inserted Successfully',
            'alert-type'=>'success'
        );

        return redirect()->route('expense.category')->with($notification);
    }
    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $categories = ExpenseCategory::all(); // جلب جميع الفئات لإظهارها في القائمة المنسدلة
        return view('backend.expense.edit', compact('expense', 'categories'));    }

    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات المدخلة
      /*   $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'category_id' => 'required|integer|exists:expense_categories,id',
            'notes' => 'nullable|string',
        ]); */

        // جلب المصروف المراد تعديله
        $expense = Expense::findOrFail($id);

        // تحديث بيانات المصروف
        $expense->update([
            'name' => $request->name,
            'date' => $request->date,
            'amount' => $request->amount,
            'detials' => $request->detials,
            'category_id' => $request->category_id,
        ]);

        // إعداد رسالة النجاح
        $notification = array(
            'message' => 'Expense Updated Successfully',
            'alert-type' => 'success'
        );

        // إعادة التوجيه مع رسالة النجاح
        return redirect()->route('expense.all')->with($notification);
    }


    public function editcategory($id){
        $category = ExpenseCategory::findOrFail($id);
        return view('backend.expense.edit_category',compact('category'));
    }
    public function updatecategory(Request $request ,$id){
        $request->vaidate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string|max:255'
        ]);
        $category=ExpenseCategory::findOrFail($id);
        $category ->update([
            'name'=> $request->name,
            'description'=> $request->description,
            'updated_at'=> Corbon::now(),
        ]);
        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('expenses.category')->with($notification);
    }
    public function deletecategory($id){
        $category = ExpenseCategory::findOrFail($id);
        $category->delete();
        $notification=array(
            'message'=>'Category Deleted Successfully',
            'alert-type'=>'success',
        );
        return redirect()->route('expense.all')->with($notification);
    }
    /**
     * Store a newly created resource in storage.
     */
    

    /**
     * Display the specified resource.
     */
    public function deleteExpense($id)
    {
        // البحث عن المصروف المطلوب باستخدام الـ ID
        $expense = Expense::findOrFail($id);

        // حذف المصروف
        $expense->delete();

        // إعداد رسالة النجاح
        $notification = array(
            'message' => 'Expense Deleted Successfully',
            'alert-type' => 'success',
        );

        // إعادة التوجيه إلى صفحة المصاريف مع عرض رسالة النجاح
        return redirect()->route('expense.all')->with($notification);
    }

    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
 

    /**
     * Update the specified resource in storage.
     */
  

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
