<?php

namespace App\Http\Controllers;
use App\Models\invoices;
use App\Models\section;
use Illuminate\Http\Request;

class invoiceRebortsController extends Controller
{
   // start invoiceReborts 

   public function index(){

    return view('reborts.invoiceReborts');
   }

   public function Search_invoices(Request $request){

    $rdio= $request->rdio;
   if($rdio==1){

      // في حالة عدم تحديد تاريخ
      if ($request->type && $request->start_at =='' && $request->end_at =='') {
            
         $invoices = invoices::select('*')->where('Status','=',$request->type)->get();
         $type = $request->type;
         return view('reborts.invoiceReborts',compact('type'))->withDetails($invoices);
      }
      
      // في حالة تحديد تاريخ استحقاق
      else {
         
        $start_at = date($request->start_at);
        $end_at = date($request->end_at);
        $type = $request->type;
        
        $invoices = invoices::whereBetween('invoice_Date',[$start_at,$end_at])->where('Status','=',$request->type)->get();
        return view('reborts.invoiceReborts',compact('type','start_at','end_at'))->withDetails($invoices);
        
      }

   }
   
   // في البحث برقم الفاتورة
   else {
        
      $invoices = invoices::select('*')->where('invoice_number','=',$request->invoice_number)->get();
      return view('reborts.invoiceReborts')->withDetails($invoices);
      
  }
      // End invoiceReborts 
   }

      // start customers_reborts 

   public function customers_reborts(){
      $sections= section::all();
      return view('reborts.customers_reborts',compact('sections'));
   }

   public function Search_customers_reborts(Request $request){
      
         // في حالة البحث بدون التاريخ

      if($request->Section &&  $request->product && $request->start_at =='' && $request->end_at ==''){

         $invoices = invoices::select('*')->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
         $sections= section::all();
         return view('reborts.customers_reborts',compact('sections'))->withDetails($invoices);
      }
      else{

         // في حالة البحث بالتاريخ

         $start_at = date($request->start_at);
         $end_at = date($request->end_at);
       
        $invoices = invoices::whereBetween('invoice_Date',[$start_at,$end_at])->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
        $sections= section::all();
        return view('reborts.customers_reborts',compact('sections','start_at','end_at'))->withDetails($invoices);

      }
      
   }

   
}
