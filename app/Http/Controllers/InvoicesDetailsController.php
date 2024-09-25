<?php

namespace App\Http\Controllers;

use App\Models\invoices_details;
use App\Models\invoices; 
use App\Models\invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
    public function show(Request $request)
    {
        
       
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
         $invoices= invoices::where('id', $id)->first();
         $detelse= invoices_details::where('id_Invoice', $id)->get();
          $attachments= invoice_attachments::where('invoice_id', $id)->get();
       
        
        return view('invoices.InvoicesDetails' , compact('invoices', 'detelse' , 'attachments'  ));

        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id , Request $request) 
    {
        // return $id;
      $invoice= invoices::findOrfail( $id);

      if($request->Status === 'مدفوعة'){
        $invoice->update([
            'Value_Status' => 1,
            'Status' =>$request->Status,
            'Payment_Date'=>$request->Payment_Date,
        ]);
            
            invoices_details::create([
                'id_Invoice' =>$request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' =>$request->Status,
                'Value_Status' => 1,
                'Payment_Date'=>$request->Payment_Date,
                'note' => $request->note,
                'user' => (Auth::user()->name),

        ]);

      }else{
        $invoice->update([
            'Value_Status' => 3,
            'Status' =>$request->Status,
            'Payment_Date'=>$request->Payment_Date,
        ]);
            
            invoices_details::create([
                'id_Invoice' =>$request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' =>$request->Status,
                'Value_Status' => 3,
                'Payment_Date'=>$request->Payment_Date,
                'note' => $request->note,
                'user' => (Auth::user()->name),

        ]);
        
      };
      session()->flash('update_status');
        return redirect('/invoice');
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request )
    {
       $invoices= invoice_attachments::findOrfail($request->id_file);
       Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
       session()->flash('delete','تم حذف المرفق بنجاج');
       return back();
    }   

    public function Open_file($invoice_number,$file_name)
    {
        
        $files= Storage::disk('public_uploads')->path($invoice_number.'/'.$file_name);
        return response()->file($files);
    }

    public function get_file($invoice_number,$file_name)
    {
        
        $contents= Storage::disk('public_uploads')->path($invoice_number.'/'.$file_name);
        return response()->download($contents);
    } 

    
}
