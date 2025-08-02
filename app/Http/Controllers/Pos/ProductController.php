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

class ProductController extends Controller
{
    public function ProductAll()
    {
        $products = Product::latest()->get();
        return view('backend.product.product_all', compact('products'));
    }

    public function ProductAdd()
    {

        $category = Category::all();
        $unit = Unit::all();
        return view('backend.product.product_add', compact('category', 'unit'));
    }

    public function ProductStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
            'descr' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $validated['name'],
            'unit_id' => $validated['unit_id'],
            'category_id' => $validated['category_id'],
            'quantity' => 0,
            'price' => $validated['price'] ?? 0,
            'descr' => $validated['descr'] ?? null,
            'image_url' => $imagePath ? asset('storage/' . $imagePath) : null,
            'created_by' => Auth::id(),
            'created_at' => now(),
        ]);

        $notification = [
            'message' => 'تم إضافة المنتج بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('product.all')->with($notification);
    }

    public function ProductEdit($id)
    {
        $category = Category::all();
        $unit = Unit::all();
        $product = Product::FindOrFail($id);
        return view('backend.product.product_edit', compact('product', 'category', 'unit'));
    }

    public function ProductUpdate(Request $request)
    {
        $product_id = $request->id;
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::FindOrFail($product_id)->update([
            'name' => $request->name,
            'unit_id' => $request->unit_id,
            'category_id' => $request->category_id,
            'price' => $validated['price'] ?? 0,
            'descr' => $validated['descr'] ?? null,
            'image_url' => $imagePath ? asset('storage/' . $imagePath) : null,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Supplier Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('product.all')->with($notification);
    }

    public function DeleteProduct($id)
    {
        Product::FindOrFail($id)->delete();

        $notification = array(
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
