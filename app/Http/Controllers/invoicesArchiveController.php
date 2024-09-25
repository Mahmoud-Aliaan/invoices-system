<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoices; 

class invoicesArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = invoices::onlyTrashed()->get();
        return view('invoices.archive_invoices', compact('invoices')) ;
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->invoice_id;
        $un_Archive= invoices::withTrashed()->where('id' , $id)->restore();
        session()->flash('restre.invoices');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $DT_invoices= invoices::withTrashed()->where('id' , $request->invoice_id)->first();
        $DT_invoices->forceDelete();
        session()->flash('Delete.invoices');
        return back();
    }
}
