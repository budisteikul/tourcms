<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\tourcms\Models\Order;
use budisteikul\tourcms\Helpers\AccHelper;
use budisteikul\tourcms\Models\fin_transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\PettyCashDataTable;

class PettyCashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PettyCashDataTable $dataTable)
    {
        return $dataTable->render('tourcms::pettycash.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function saldo()
    {
        $pettycash = 10000000;
        $fin_transactions = fin_transactions::where('status',0)->get();
        $total = $fin_transactions->sum('amount');
        $pettycash_saldo = $pettycash - $total;

        $disabled = 'disabled';
        $text = 'Top up';
        if($pettycash_saldo < $pettycash)
        {
            $disabled = '';
            $text .= ' '. number_format($total, 0, ',', '.');
        }
        $button = '<button type="button" id="submit" class="btn btn-success" onclick="SET_DONE();" '.$disabled.'>'.$text.'</button>';


        $guides = json_decode(config('site.guides'));
        $held_saldo = 0;
        $tahun = date('Y');
        $bulan = date('m');
        foreach($guides as $guide)
        {
            $salary = AccHelper::total_per_month($guide->id,$tahun,$bulan,false);
            $ca = AccHelper::ca($guide->id,$bulan,$tahun);
            $salary_ca = $salary - $ca->total;
            $held_saldo += $salary_ca;
        }
        
        $total_saldo = $pettycash_saldo+$held_saldo;

        return response()->json([
                    "id" => "1",
                    "pettycash_saldo" => number_format($pettycash_saldo, 0, ',', '.'),
                    "button" => $button,
                    "held_saldo" => number_format($held_saldo, 0, ',', '.'),
                    "total_saldo" => number_format($total_saldo, 0, ',', '.')
                ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bank_fee = 0;

        $fin_transactions = fin_transactions::where('status',0)->get();
        $total = $fin_transactions->sum('amount');

        foreach($fin_transactions as $fin_transaction)
        {
            $fin_transaction->status = 1;
            $fin_transaction->save();
            $json_trans_id[] = [
                'trans_id' => $fin_transaction->id
            ];
        }
        
        $fin_transactions = new fin_transactions();
        $fin_transactions->category_id = 47;
        $fin_transactions->date = date('Y-m-d');
        $fin_transactions->amount = $bank_fee;
        $fin_transactions->status = 1;
        $fin_transactions->save();
        
        $json_bank_id[] = [
            'bank_id' => $fin_transactions->id
        ];


        $json[] = [
            'trans_id' => $json_trans_id,
            'bank_id' => $json_bank_id
        ];

        $order = new Order;
        $order->type = 'pettycash';
        $order->date = date('Y-m-d');
        //$order->tour = 'Petty Cash';
        $order->total = $total + $bank_fee;
        $order->note = 'Petty Cash - Top up : '. number_format($total, 0, ',', '.') .' - Bank Fee : '. number_format($bank_fee, 0, ',', '.'); 
        $order->transactions = json_encode($json);
        $order->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success',
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
        //print_r($order);
        
        //fin_transactions::where('id',$order->pax)->delete();

        foreach(json_decode($order->transactions) as $transaction)
        {
            foreach($transaction->trans_id as $aaa)
            {
                $fin_transactions = fin_transactions::where('id',$aaa->trans_id)->first();
                if($fin_transactions)
                {
                    $fin_transactions->status = 0;
                    $fin_transactions->save();
                }
            }

            foreach($transaction->bank_id as $aaa)
            {
                fin_transactions::where('id',$aaa->bank_id)->delete();
            }
            //$fin_transactions = fin_transactions::where('id',$transaction->id)->first();
            //if($fin_transactions)
            //{
                //$fin_transactions->status = 0;
                //$fin_transactions->save();
            //}
        }
        $order->delete();
        
    }
}
