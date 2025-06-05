<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\tourcms\Models\Order;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\RevenueDataTable;

class RevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RevenueDataTable $dataTable)
    {
        return $dataTable->render('tourcms::revenue.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tourcms::revenue.create');
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $app =  $request->input('app');
        $amount =  $request->input('amount');
        $date = date('Y-m-d');

        if($app==1)
        {
            $trans_id = 23;
            $note = 'Revenue - AIRBNB : '. number_format($amount, 0, ',', '.');
            $status = 1;
        }
        else if($app==2)
        {
            $trans_id = 49;
            $note = 'Revenue - PARTNER : '. number_format($amount, 0, ',', '.');
            $status = 1;
        }
        else if($app==3)
        {
            $trans_id = 24;
            $note = 'Revenue - VIATOR : '. number_format($amount, 0, ',', '.');
            $status = 1;
            $date = date("Y-m-d",strtotime("-1 month"));
            $date = substr($date,0,7).'-'.date("t", strtotime($date));
        }
        else if($app==4)
        {
            $trans_id = 25;
            $note = 'Revenue - GETYOURGUIDE : '. number_format($amount, 0, ',', '.');
            $status = 1;
            $date = date("Y-m-d",strtotime("-1 month"));
            $date = substr($date,0,7).'-'.date("t", strtotime($date));
        }
        else
        {
            $trans_id = 27;
            $note = 'Revenue - WEBSITE : '. number_format($amount, 0, ',', '.');
            $status = 1;
            $date = date("Y-m-d",strtotime("-1 month"));
            $date = substr($date,0,7).'-'.date("t", strtotime($date));
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
