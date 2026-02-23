<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\tourcms\Models\Order;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\OrderDataTable;

use Carbon\Carbon;
use Illuminate\Support\Str;
use budisteikul\tourcms\Helpers\GeneralHelper;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrderDataTable $dataTable,Request $request)
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
           ])->render('tourcms::order.index',['tahun' => $tahun,
                'bulan' => $bulan]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    public function create_jnft()
    {
        $guides = json_decode(config('site.guides'));
        return view('tourcms::order.create-jnft',['guides'=>$guides]);
    }

    public function create_short()
    {
        $guides = json_decode(config('site.guides'));
        return view('tourcms::order.create-short',['guides'=>$guides]);
    }

    public function create_jmft()
    {
        $guides = json_decode(config('site.guides'));
        return view('tourcms::order.create-jmft',['guides'=>$guides]);
    }

    public function create_tat()
    {
        return view('tourcms::order.create-tat');
    }

    public function create_dft()
    {
        return view('tourcms::order.create-dft');
    }

    public function create_uft()
    {
        return view('tourcms::order.create-uft');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $app =  $request->input('app');
        
        if($app==6)
            {
                $date =  $request->input('date');
                $guide =  $request->input('guide');
                $pax =  $request->input('pax');
                $additional =  $request->input('additional');

                $modal_tour = 80000;
                $fee_guide = 80000;
                $tour = "Jogja Short Food Tour";
                $guide = fin_categories::where('id',$guide)->first();

                $total_guide = $fee_guide * $pax;
                $total_cost =  $modal_tour * $pax;
                $total = 0;

                //additional
                if($additional>0)
                {
                    $total = $total + $additional;
                
                    $transaction = new fin_transactions;
                    $transaction->category_id = 53;
                    $transaction->date = $date;
                    $transaction->amount = $additional;
                    $transaction->status = 0;
                    $transaction->save();

                    $json_trans_id[] = [
                        'trans_id' => $transaction->id
                    ];
                }

                $note = $tour.' - '. $pax .'pax';
                $total += $total_cost + $total_guide;

                //guide
                $transaction = new fin_transactions;
                $transaction->category_id = $guide->id;
                $transaction->date = $date;
                $transaction->amount = $total_guide;
                $transaction->status = 0;
                $transaction->save();

                $json_trans_id[] = [
                    'trans_id' => $transaction->id
                ];

                //modal
                $transaction = new fin_transactions;
                $transaction->category_id = 15;
                $transaction->date = $date;
                $transaction->amount = $total_cost;
                $transaction->status = 0;
                $transaction->save();

                $json_trans_id[] = [
                    'trans_id' => $transaction->id
                ];

                $json[] = [
                    'trans_id' => $json_trans_id
                ];

                $order = new Order;
                $order->type = 'order';
                $order->date = $date;
                $order->guide = $guide->id;
                $order->tour = $tour;
                $order->pax = $pax;
                $order->fee = $total_guide;
                $order->cost = $total_cost;
                $order->total = $total;
                $order->note = $note;
                $order->transactions = json_encode($json);
                $order->save();

            }

        if($app==1 || $app==2)
        {
            $date =  $request->input('date');
            $guide =  $request->input('guide');
            $pax =  $request->input('pax');
            $additional =  $request->input('additional');

            $guide = fin_categories::where('id',$guide)->first();



            if($app==1)
            {

                $duty_fee = 0;
                $guide_settings = json_decode(config('site.guides'));
                foreach($guide_settings as $guide_setting)
                {
                    if($guide->id==$guide_setting->id)
                    {
                        $total_guide = $guide_setting->fee * $pax;
                        $duty_fee = $guide_setting->duty_fee;
                    }
                }

                
                $total_cost = 250000 * $pax;
                if($pax>=5) $total_cost = 200000 * $pax;

                $total = $total_cost + $total_guide;
                $tour = "Jogja Night Food Tour";
                $pax = $pax;

                if($duty_fee>0)
                {
                    $total = $total + $duty_fee;
                    $total_guide = $total_guide + $duty_fee;
                }

                if($additional>0)
                {
                    $total = $total + $additional;
                
                    $transaction = new fin_transactions;
                    $transaction->category_id = 53;
                    $transaction->date = $date;
                    $transaction->amount = $additional;
                    $transaction->status = 0;
                    $transaction->save();

                    $json_trans_id[] = [
                        'trans_id' => $transaction->id
                    ];
                }
                //$note = $tour.' - '. $guide->name .' - '. $pax .'pax - '. number_format($total, 0, ',', '.');
                $note = $tour.' - '. $pax .'pax';
            }

            if($app==2)
            {
                if($guide->id==55 || $guide->id==56)
                {
                    $total_guide = 100000 * $pax;
                }
                else
                {
                    $total_guide = 150000 * $pax;
                }

                $total_cost = 150000 * $pax;
                if($pax>=3) $total_cost = 100000 * $pax;

                $total = $total_cost + $total_guide;
                $tour = "Jogja Morning Food Tour";
                $pax = $pax;
                if($additional>0)
                {
                    $total = $total + $additional;
                
                    $transaction = new fin_transactions;
                    $transaction->category_id = 53;
                    $transaction->date = $date;
                    $transaction->amount = $additional;
                    $transaction->status = 0;
                    $transaction->save();

                    $json_trans_id[] = [
                        'trans_id' => $transaction->id
                    ];
                }
                //$note = $tour.' - '. $guide->name.' - '. $pax .'pax - '. number_format($total, 0, ',', '.');
                $note = $tour.' - '. $pax .'pax';
            }
            

            

            $transaction = new fin_transactions;
            $transaction->category_id = $guide->id;
            $transaction->date = $date;
            $transaction->amount = $total_guide;
            $transaction->status = 0;
            $transaction->save();

            $json_trans_id[] = [
                'trans_id' => $transaction->id
            ];

            $transaction = new fin_transactions;
            $transaction->category_id = 15;
            $transaction->date = $date;
            $transaction->amount = $total_cost;
            $transaction->status = 0;
            $transaction->save();

            $json_trans_id[] = [
                'trans_id' => $transaction->id
            ];

            

            $json[] = [
                'trans_id' => $json_trans_id
            ];

            $order = new Order;
            $order->type = 'order';
            $order->date = $date;
            $order->guide = $guide->id;
            $order->tour = $tour;
            $order->pax = $pax;
            $order->fee = $total_guide;
            $order->cost = $total_cost;
            $order->total = $total;
            $order->note = $note;
            $order->transactions = json_encode($json);
            $order->save();

        }

        if($app==3)
        {
            $date =  $request->input('date');
            $pax =  $request->input('pax');
            $additional =  $request->input('additional');

            $cost = 375000 * $pax;
            $total = $cost;
            $tour = "Taman Anyar Tour";

            if($additional>0)
            {
                $total = $cost + $additional;
                
                $transaction = new fin_transactions;
                $transaction->category_id = 52;
                $transaction->date = $date;
                $transaction->amount = $additional;
                $transaction->status = 0;
                $transaction->save();

                $json_trans_id[] = [
                    'trans_id' => $transaction->id
                ];
            }

            $note = $tour.' - '. $pax .'pax - '. number_format($total, 0, ',', '.');
            
            $transaction = new fin_transactions;
            $transaction->category_id = 42;
            $transaction->date = $date;
            $transaction->amount = $cost;
            $transaction->status = 0;
            $transaction->save();

            $json_trans_id[] = [
                'trans_id' => $transaction->id
            ];

            

            $json[] = [
                'trans_id' => $json_trans_id
            ];

            $order = new Order;
            $order->type = 'order';
            $order->date = $date;
            $order->tour = $tour;
            $order->pax = $pax;
            $order->total = $total;
            $order->note = $note;
            $order->transactions = json_encode($json);
            $order->save();
        }

        if($app==4)
        {
            $date =  $request->input('date');
            $pax =  $request->input('pax');
            $additional =  $request->input('additional');
            
            $fee_guide = 200000;
            if($pax<2)
            {
                $guide = $fee_guide;
            }
            else
            {
                $guide = $fee_guide + (($pax - 2 + 1) * 100000);
            }

            $cost = 200000 * $pax;
            $commision = 50000 * $pax;
            $total = $cost + $guide + $commision;

            $tour = "Denpasar Food Tour";
            
            if($additional>0)
            {
                $total = $total + $additional;
                
                $transaction = new fin_transactions;
                $transaction->category_id = 52;
                $transaction->date = $date;
                $transaction->amount = $additional;
                $transaction->status = 0;
                $transaction->save();

                $json_trans_id[] = [
                    'trans_id' => $transaction->id
                ];
            }

            $note = $tour.' - '. $pax .'pax - '. number_format($total, 0, ',', '.');

            $transaction = new fin_transactions;
            $transaction->category_id = 44;
            $transaction->date = $date;
            $transaction->amount = $guide;
            $transaction->status = 0;
            $transaction->save();

            $json_trans_id[] = [
                'trans_id' => $transaction->id
            ];

            $transaction = new fin_transactions;
            $transaction->category_id = 43;
            $transaction->date = $date;
            $transaction->amount = $commision;
            $transaction->status = 0;
            $transaction->save();

            $json_trans_id[] = [
                'trans_id' => $transaction->id
            ];

            $transaction = new fin_transactions;
            $transaction->category_id = 45;
            $transaction->date = $date;
            $transaction->amount = $cost;
            $transaction->status = 0;
            $transaction->save();

            $json_trans_id[] = [
                'trans_id' => $transaction->id
            ];

           

            $json[] = [
                'trans_id' => $json_trans_id
            ];

            $order = new Order;
            $order->type = 'order';
            $order->date = $date;
            $order->tour = $tour;
            $order->pax = $pax;
            $order->total = $total;
            $order->note = $note;
            $order->transactions = json_encode($json);
            $order->save();
        }

        if($app==5)
        {
            $date =  $request->input('date');
            $pax =  $request->input('pax');
            $additional =  $request->input('additional');
            $rate =  $request->input('rate');
            
            if($rate==1)
            {
                $cost = 350000 * $pax;
            }
            else
            {
                $cost = 500000 * $pax;
            }

            $total = $cost;

            $tour = "Ubud Food Tour";
            
            if($additional>0)
            {
                $total = $total + $additional;
                
                $transaction = new fin_transactions;
                $transaction->category_id = 52;
                $transaction->date = $date;
                $transaction->amount = $additional;
                $transaction->status = 0;
                $transaction->save();

                $json_trans_id[] = [
                    'trans_id' => $transaction->id
                ];
            }

            $note = $tour.' - '. $pax .'pax - '. number_format($total, 0, ',', '.');

            $transaction = new fin_transactions;
            $transaction->category_id = 39;
            $transaction->date = $date;
            $transaction->amount = $cost;
            $transaction->status = 0;
            $transaction->save();

            $json_trans_id[] = [
                'trans_id' => $transaction->id
            ];

            

            $json[] = [
                'trans_id' => $json_trans_id
            ];

            $order = new Order;
            $order->type = 'order';
            $order->date = $date;
            $order->tour = $tour;
            $order->pax = $pax;
            $order->total = $total;
            $order->note = $note;
            $order->transactions = json_encode($json);
            $order->save();
        }

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
    public function destroy(Order $order)
    {
         if($order->type=="order")
         {
         foreach(json_decode($order->transactions) as $transaction)
         {
            foreach($transaction->trans_id as $aaa)
            {
                fin_transactions::where('id',$aaa->trans_id)->delete();
            }
            //foreach($transaction->bank_id as $aaa)
            //{
                //fin_transactions::where('id',$aaa->bank_id)->delete();
            //}
         }
         }
         else
         {
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
         }
         }
         
         $order->delete();
    }
}
