<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\tourcms\Models\Order;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\RevenueDataTable;

use Carbon\Carbon;
use Illuminate\Support\Str;
use budisteikul\tourcms\Helpers\GeneralHelper;

class RevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RevenueDataTable $dataTable,Request $request)
    {
        $date = $request->input('date');

        if($date=="") $date = date('Y-m');

        $newDateTime = Carbon::parse($date."-01");
        $tahun = Str::substr($newDateTime, 0,4);
        $bulan = Str::substr($newDateTime, 5,2);
        $bulan = GeneralHelper::digitFormat($bulan,2);
        return $dataTable->with([
                'tahun' => $tahun,
                'bulan' => $bulan
           ])->render('tourcms::revenue.index',['tahun' => $tahun,
                'bulan' => $bulan]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $date = $request->input('date');
        return view('tourcms::revenue.create',['date'=>$date]);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $app =  $request->input('app');
        $amount =  $request->input('amount');
        $date = $request->input('date');

        if($app==1)
        {
            $trans_id = 29;
            $note = 'Revenue - MARKETPLACE : '. number_format($amount, 0, ',', '.');
            $status = 1;
        }
        else if($app==2)
        {
            $trans_id = 48;
            $note = 'Revenue - WEBSITE : '. number_format($amount, 0, ',', '.');
            $status = 1;
        }
        else
        {
            $trans_id = 49;
            $note = 'Revenue - OFFLINE : '. number_format($amount, 0, ',', '.');
            $status = 1;
        }

        $transaction = new fin_transactions;
        $transaction->category_id = $trans_id;
        $transaction->date = $date;
        $transaction->amount = $amount;
        $transaction->status = $status;
        $transaction->save();

        $json_trans_id[] = [
            'trans_id' => $transaction->id
        ];   

        $json[] = [
            'trans_id' => $json_trans_id
        ];

        $order = new Order;
        $order->type = 'revenue';
        $order->date =  $date;
        $order->total = $amount;
        $order->note = $note;
        $order->transactions = json_encode($json);
        $order->save();

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
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

         $order = Order::findOrFail($id);

        foreach(json_decode($order->transactions) as $transaction)
        {
            foreach($transaction->trans_id as $aaa)
            {
                $fin_transactions = fin_transactions::where('id',$aaa->trans_id)->delete();
            }

            
        }
        $order->delete();
    }
}
