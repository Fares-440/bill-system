<?php

namespace App\Http\Controllers;

use App\products;
use App\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:المنتجات', ['only' => ['index']]);
        $this->middleware('permission:اضافة منتج', ['only' => ['store']]);
        $this->middleware('permission:تعديل منتج', ['only' => ['update']]);
        $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);
    }
    public function index()
    {
        $products = products::all();
        $sections = Sections::all();
        return view('products.products')->with([
            'sections' => $sections,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'productname' => 'required|max:255',
            'description' => 'required',
            'section_id' => 'required',
        ], [
            'productname.required' => 'يرجى ادخال اسم المنتج',
            'description.required' => 'يرجى ادخال الملاحظة',
        ]);

        products::create([
            'product_name' => $request->productname,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);
        Session::flash('Add', 'تم اضافة المنتج بنجاح');
        return redirect(route('products'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'productname' => 'required|max:255',
            'description' => 'required',
            'section_id' => 'required',
        ], [
            'productname.required' => 'يرجى ادخال اسم المنتج',
            'description.required' => 'يرجى ادخال الملاحظة',
        ]);

        $product = products::find($request->input('id'));
        $product->update([
            'product_name' => $request->productname,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);
        Session::flash('edit', 'تم تعديل المنتج بنجاح');
        return redirect(route('products'));
    }

    public function destroy(Request $request)
    {
        products::find($request->id)->delete();
        Session::flash('delete', 'تم حذف المنتج بنجاح');
        return redirect(route('products'));
    }
}
