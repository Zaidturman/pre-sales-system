<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Unit;
use Auth;
use Illuminate\Support\Carbon;

class StockController extends Controller
{
    public function store(Request $request)
    {
        // جلب المنتج من قاعدة البيانات باستخدام product_id
        $product = Product::find($request->product_id);

        // تحقق من وجود المنتج
        if ($product) {
            // إضافة الكمية الجديدة إلى الكمية الحالية
            $product->quantity += $request->quantity;

            // حفظ التحديثات في قاعدة البيانات
            $product->save();

            $notification = array(
                'message' => 'Data saved Successfully',
                'alert-type' => 'success'
            );
        } else {
            $notification = array(
                'message' => 'Product not found',
                'alert-type' => 'error'
            );
        }

        return redirect()->back()->with($notification);
        }
    
    public function StockAddproduct()
    {
        $category = Category::all();
        $allData = Product::orderBy('category_id', 'asc')->get();
        return view('backend.stock.stock_addproduct', compact('allData', 'category'));
    }
    public function StockReport(){
        $allData = Product::orderBy('category_id','asc')->get();
        return view('backend.stock.stock_report',compact('allData'));
    }
    public function updateStockQuantity(Request $request,$id){
        $product = Product::findOrFail($id);
        $product->quantity =$request->input('quantity');
        $product->save();
        $notification = array(
            'message' => 'تم تحديث كمية المنتج بنجاح',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    public function zeroStockQuantity($id){
        $product = Product::findOrFail($id);
        $product->quantity = 0.0;
        $product->save();
        $notification = array(
            'message' => 'تم تصفير كمية المنتج بنجاح',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    public function StockReportPdf(){
        $allData = Product::orderBy('category_id','asc')->get();
        return view('backend.pdf.stock_report_pdf',compact('allData'));
    }

    public function StockSupplierReport(){
        $suppliers = Supplier::all();
        $category = Category::all();
        return view('backend.stock.stock_supplier_report',compact('suppliers','category'));
    }
    public function SupplierWisePdf(Request $request)
    {
        $allData = Product::orderBy('supplier_id', 'asc')->orderBy('category_id', 'asc')->where('supplier_id', $request->supplier_id)->get();
        return view('backend.pdf.supplier_wise_report_pdf', compact('allData'));
    }

    public function ProductWisePdf(Request $request)
    {
        $allData = Product::where('category_id', $request->category_id)->where('id', $request->product_id)->first();
        return view('backend.pdf.product_wise_report_pdf', compact('allData'));
    }
}