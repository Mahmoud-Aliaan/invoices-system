<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\section;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $sections = section::all();
        $products= products::all();
        return view('Products.Products',compact('sections','products'));
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
    public function store(Request $request)
    {
        $validated = $request->validate([

            'Product_name' => 'required|unique:products|max:255',
            'description' => 'required',
        ],[
            'Product_name.required' =>'يرجي ادخال اسم المنتج',
            'Product_name.unique' =>'اسم المنتج مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',

        
        ]);
        products::create([
            'Product_name' => $request->Product_name,
            'section_id' => $request->section_id,
            'description' => $request->description,
        ]);
        session()->flash('Add', 'تم اضافة المنتج بنجاح ');
        return redirect('/Products');
    }

    /**
     * Display the specified resource.
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = section::where('section_name', $request->section_name)->first()->id;

        $Products = Products::findOrFail($request->pro_id);
 
        $Products->update([
        'Product_name' => $request->Product_name,
        'description' => $request->description,
        'section_id' => $id,
        ]);
 
        session()->flash('Edit', 'تم تعديل المنتج بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $Products = Products::findOrFail($request->pro_id);
         $Products->delete();
         session()->flash('delete', 'تم حذف المنتج بنجاح');
         return back();
    }
}
