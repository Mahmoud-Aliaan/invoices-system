<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoices_details;
use App\Models\invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\section;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\addinvoice;
use Illuminate\Support\Facades\Mail;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;

// use App\Mail\OrderShipped;
use App\Notifications\add_invoice;
use Illuminate\Support\Facades\Notification;


class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $invoices = invoices::all();
        return view ('invoices.invoices' ,  compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections= section::all();
        return  view('invoices.add_invoice', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        
         invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            
            'section_id' => $request->Section,
             'product' => $request->product,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,     
            'Rate_VAT' => $request->Rate_VAT,
            'Value_VAT' => $request->Value_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = invoices::latest()->first()->id;
            invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        // Notification db
        //  لو حبيت تبعت الاشعارات للشخص ال عمل اضاف الفاتورة فقط
        // $user = User::find(Auth::user()->id); 
        
        $user = User::get();
        $invoices= Invoices::latest()->first();       
  
        Notification::send($user, new add_invoice($invoices));

        // notification mail
            // $user= User::first();
            // Mail::to('mahmoud.aliaan27@gmail.coom')->send(new addinvoice($invoice_id));
            // return response('sending');
            // toastr()->success('Data has been saved successfully!');
            toastr()->success('Data has been saved successfully!', 'Congrats');
            // session()->flash('Add', 'تم اضافة الفاتورة بنجاح');

            return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id )
    {
        $invoice = invoices::where('id', $id)->first();
        return view ('invoices.status_update' , compact('invoice'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = invoices::where('id', $id)->first();
        $sections = section::all();
        return view ('invoices.edit_invoice' , compact('invoice', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoices = invoices::findOrfail($request->invoice_id);

        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,        
            'section_id' => $request->Section,
             'product' => $request->product,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,     
            'Rate_VAT' => $request->Rate_VAT,
            'Value_VAT' => $request->Value_VAT,
            'Total' => $request->Total,           
            'note' => $request->note,
        ]);
        
        session()->flash('edit','تم تعديل الفاتوره بنجاج');
       return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        // return $request;
       $id = $request->invoice_id ;
       $invoice = invoices::where('id' , $id)->first();
        $detelse = invoice_attachments::where('invoice_id', $id)->first();

        $tab_archive= $request->tab_archive;
        if(!$tab_archive== 2){
        if(!empty($detelse->invoice_number)){

            Storage::disk('public_uploads')->deleteDirectory($detelse->invoice_number);

        }
        $invoice->forcedelete();
        session()->flash('delete_invoice');
        return redirect('/invoice');
    }
    else{
        $invoice->delete();
        session()->flash('archive_invoice');
        return redirect('/invoice');
    }


    //    Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
    //    session()->flash('delete','تم حذف المرفق بنجاج');
    //    return back();
      
    }

    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        
        return json_encode($products);
    }

    



    public function Invoice_Paid(){
        $invoices = invoices::where('Value_Status',1)->get();
        return view('invoices.Invoice_Paid', compact('invoices'));
    }
    public function Invoice_UnPaid(){
        $invoices = invoices::where('Value_Status',2)->get();
        return view('invoices.Invoice_UnPaid', compact('invoices'));
    }
    public function Invoice_Partial(){
        $invoices = invoices::where('Value_Status',3)->get();
        return view('invoices.Invoice_Partial', compact('invoices'));
    }

    public function Print_invoice($id){
        $invoice= invoices::where('id' , $id)->first();
        return view ('invoices.Print_invoice' , compact('invoice'));
    }

    public function export() 
    {
        return Excel::download(new InvoicesExport, 'Invoices.xlsx');
    }

    public function markAsRead(){
        
        // $unread_one= Notification::where('id',$id)->get;
        // if($unread_one->unreadNotifications){
        //    $unread_one->markAsRead();
        // };
        $user_unreadnotifcation= Auth::user()->unreadNotifications;
        if( $user_unreadnotifcation){
            $user_unreadnotifcation->markAsRead();
            return back();
        }
      
    }

    // public function markAsReadID($id){
    //     $user_unreadnotifcation= Notification::where('id',$id)->find();
    //     if( $user_unreadnotifcation){
    //         $user_unreadnotifcation->unreadNotifications()->update(['read_at' => now()]);
    //         // $user_unreadnotifcation->markAsRead();
           
    //     }
    // }
}
