<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\invoicesArchiveController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\invoiceRebortsController;

use App\Mail\tesmail;
use Illuminate\Support\Facades\Mail;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});



Auth::routes();

 //Auth::routes(['register' => false ]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

 Route::resource('invoice', InvoicesController::class);

//  Route::get('/send', function(){
    
//     Mail::to('mahmoud.aliaan27@gmail.coom')->send(new tesmail());
//     return response('sendeng');

//  });

 Route::resource('section', SectionController::class);

 Route::resource('Products', ProductsController::class);
 Route::get('/sections/{id}' ,[InvoicesController::class, 'getproducts'] );

Route::get('export_invoices', [InvoicesController::class, 'export']);

 Route::get('/edit_invoice/{id}' ,[InvoicesController::class, 'edit'] );
 Route::get('Status_show/{id}' , [InvoicesController::class, 'show'])->name('Status_show');
 Route::post('Status_Update/{id}', [InvoicesDetailsController::class , 'Update'])->name('Status_Update');

 route::get('/Print_invoice/{id}', [InvoicesController::class , 'Print_invoice']);

 Route::resource('Archive', invoicesArchiveController::class);

Route::get('Invoice_Paid',[InvoicesController::class, 'Invoice_Paid']);

Route::get('Invoice_UnPaid',[InvoicesController::class, 'Invoice_UnPaid']);

Route::get('Invoice_Partial',[InvoicesController::class, 'Invoice_Partial']);

 Route::post('InvoiceAttachments', [InvoiceAttachmentsController::class , 'store']);
 Route::get('/InvoicesDetails/{id}' ,[InvoicesDetailsController::class, 'edit'] );
 Route::get('/View_file/{invoice_number}/{file_name}' ,[InvoicesDetailsController::class, 'Open_file'] );
 Route::get('download/{invoice_number}/{file_name}' ,[InvoicesDetailsController::class, 'get_file'] );
 Route::post('delete_file' ,[InvoicesDetailsController::class, 'destroy'] )->name('delete_file');

// invoice

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
   
});

Route::get('invoice_reborts',[invoiceRebortsController::class ,'index'] );

Route::post('Search_invoices',[invoiceRebortsController::class ,'Search_invoices'] );

Route::get('customers_reborts',[invoiceRebortsController::class ,'customers_reborts'] );

Route::post('Search_customers_reborts',[invoiceRebortsController::class ,'Search_customers_reborts'] );

Route::get('markAsRead', [InvoicesController::class, 'markAsRead'])->name('markAsRead');

// Route::get('markAsRead/{id}', [InvoicesController::class, 'markAsRead'])->name('markAsRead');

Route::get('/{page}',[AdminController::class,'index'] );