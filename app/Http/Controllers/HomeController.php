<?php

namespace App\Http\Controllers;
use App\Models\invoices;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        //=================احصائية نسبة تنفيذ الحالات======================

        $countAll= invoices::count();

        $invoices_1 = invoices::where('Value_Status',1)->count();
        $nespa_1 = $invoices_1/$countAll*100;

        $invoices_2 = invoices::where('Value_Status',2)->count();
        $nespa_2 = $invoices_2/$countAll*100;

        $invoices_3 =  invoices::where('Value_Status',3)->count();
        $nespa_3= $invoices_3/$countAll*100 ;





        $chartjs = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['اجمالى الفواتير','الفواتير  المدفوعه','الفواتير الغير مدفوعه','الفواتير  المدفوعه جزئيا'])    
        ->datasets([
            [
                "label" => "نسبه الفواتير",
               
                
                
                'backgroundColor' => ['#75C2F6','#CBFFA9','#FF90BB','#FF8551'],
               
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => [100, $nespa_1 , $nespa_2 ,$nespa_3]
            ],
           
                 
           
            


        ]);
        
        return view('home', compact('chartjs'));

    }
}
