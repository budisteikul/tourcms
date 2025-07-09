<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use Barryvdh\DomPDF\Facade as PDF;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use budisteikul\tourcms\Helpers\AccHelper;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->input('id');
        $date = $request->input('date');
        if($date=="") $date = date('Y-m');

        $year = substr($date,0,4);
        $month = substr($date,5,2);

        $month_name = date("F", strtotime($year.'-'.$month.'-01'));
        $date_name = $month_name .' '. $year;

        $guide = fin_categories::where('id',$id)->first();
        $guide_name = $guide->name;

        $salary = AccHelper::order_guide($guide->id,$month,$year,"order");
        $cash_advance = AccHelper::order_guide($guide->id,$month,$year,"cash_advance");

        $pdf = PDF::setOptions(['tempDir' =>  storage_path(),'fontDir' => storage_path(),'fontCache' => storage_path(),'isRemoteEnabled' => true])->loadView('tourcms::salary.index',['guide_name'=>$guide_name,'date_name'=>$date_name,'cash_advance' => $cash_advance, 'salary' => $salary])->setPaper('a4', 'portrait');

        return $pdf->download('Revenue-Sharing-'. $id .'-'. $month .'-'.$year.'.pdf');


        //return view('tourcms::salary.index',['guide_name'=>$guide_name,'date_name'=>$date_name,'cash_advance' => $cash_advance, 'salary' => $salary]);
    }

    public function index2(Request $request)
    {
        $id = $request->input('id');
        $date = $request->input('date');
        if($date=="") $date = date('Y-m');

        $year = substr($date,0,4);
        $month = substr($date,5,2);

        $month_name = date("F", strtotime($year.'-'.$month.'-01'));
        $date_name = $month_name .' '. $year;

        $category = fin_categories::where('id',$id)->first();
        $guide_name = $category->name;

        $transaction = fin_transactions::where('category_id',$category->id)->whereYear('date',$year)->whereMonth('date',$month);

        $total = $transaction->sum('amount');
        $ca = AccHelper::ca($category->id,$month,$year);
        $ca2 =  AccHelper::ca($category->id,$month,$year,"order");

        $total = $total - $ca->total;
        $total = number_format($total, 0, ',', '.');

        $jalan = number_format($transaction->count(), 0, ',', '.');

        $transactions = $transaction->orderBy('date')->get();

        
        $pdf = PDF::setOptions(['tempDir' =>  storage_path(),'fontDir' => storage_path(),'fontCache' => storage_path(),'isRemoteEnabled' => true])->loadView('tourcms::salary.index',['guide_name'=>$guide_name,'date_name'=>$date_name,'total'=>$total,'jalan'=>$jalan,'transactions'=>$transactions,'ca'=>$ca,'ca2'=>$ca2])->setPaper('a4', 'portrait');

        return $pdf->download('Revenue-Sharing-'. $id .'-'. $month .'-'.$year.'.pdf');
        

        //return view('tourcms::salary.index',['guide_name'=>$guide_name,'date_name'=>$date_name,'total'=>$total,'jalan'=>$jalan,'transactions'=>$transactions,'ca'=>$ca,'ca2'=>$ca2]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
         
    }
}
