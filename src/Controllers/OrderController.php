<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\tourcms\Models\Order;
use budisteikul\tourcms\Models\ShoppingcartProduct;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\OrderDataTable;

use Carbon\Carbon;
use Illuminate\Support\Str;
use budisteikul\tourcms\Helpers\GeneralHelper;
use Illuminate\Support\Facades\DB;

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

    public function getGuests()
    {
        $guests = ShoppingcartProduct::with(['shoppingcart' => function ($query) {
                    $query = $query->with(['shoppingcart_questions' => function ($query) {
                        return $query->where('question_id','firstName')->orWhere('question_id','lastName');
                    }]);
                 }])
                 ->whereHas('shoppingcart', function ($query) {
                    return $query->where('booking_status','CONFIRMED')->doesntHave('orders');
                 })->whereDate('date', '<=', Carbon::now())->whereDate('date', '>=', Carbon::now()->addDays(-1))->whereNotNull('date')->orderBy('date')->orderBy('id')->get();
        return $guests;
    }

    public function getMoment()
    {
        $dt = Carbon::now();
        $moment = 'moment("'. Carbon::now()->format('Y-m-d') .'"),moment("'.Carbon::now()->addDays(-1)->format('Y-m-d').'")';
        return $moment;
    }

    public function create_jnft()
    {
        $guides = json_decode(config('site.guides'));
        
        $guests = self::getGuests();
        $moment = self::getMoment();
        return view('tourcms::order.create-jnft',['app'=>1, 'guides'=>$guides,'guests'=>$guests,'moment'=>$moment]);
    }

    public function create_short()
    {
        $guides = json_decode(config('site.guides'));
        $guests = self::getGuests();
        $moment = self::getMoment();
        return view('tourcms::order.create-short',['app'=>3, 'guides'=>$guides,'guests'=>$guests,'moment'=>$moment]);
    }

    public function create_jmft()
    {
        $guides = json_decode(config('site.guides'));
        $guests = self::getGuests();
        $moment = self::getMoment();
        return view('tourcms::order.create-jmft',['app'=>2, 'guides'=>$guides,'guests'=>$guests,'moment'=>$moment]);
    }

    public function create_smt()
    {
        $guests = self::getGuests();
        $moment = self::getMoment();
        return view('tourcms::order.create-smt',['app'=>999, 'guests'=>$guests,'moment'=>$moment]);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $app =  $request->input('app');
        
        if($app==999)
        {
            
            
            $validator = Validator::make($request->all(), [
                'guests' => 'required',
                'total' => 'required|integer|gt:0',
                'additional' => 'required|integer'
            ]);
        
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors);
            }

            $date =  $request->input('date');
            $tour =  $request->input('tour');
            $guests =  $request->input('guests');
            $additional =  $request->input('additional');
            $total =  $request->input('total');


            if($tour==1)
            {
                $supplier_id = 62;
                $additional_id = 60;
                $tour_text = "Semarang Food Tour";
            }
            else
            {
                $supplier_id = 45;
                $additional_id = 52;
                $tour_text = "Bali Food Tour";
            }

            //Hitung pax
            $pax = 0;
            foreach($guests as $guest)
            {
                $array_guest = explode("|",$guest);
                $pax += $array_guest[2];
            }

            
            

            if($additional>0)
            {
                $total += $additional;
                
                $transaction = new fin_transactions;
                $transaction->category_id = $additional_id;
                $transaction->date = $date;
                $transaction->amount = $additional;
                $transaction->status = 0;
                $transaction->save();

                $json_trans_id[] = [
                    'trans_id' => $transaction->id
                ];
            }

            
            $transaction = new fin_transactions;
            $transaction->category_id = $supplier_id;
            $transaction->date = $date;
            $transaction->amount = $total;
            $transaction->status = 0;
            $transaction->save();

            $json_trans_id[] = [
                'trans_id' => $transaction->id
            ];

            $json[] = [
                'trans_id' => $json_trans_id
            ];

            $note = $tour_text.' - '. $pax .'pax - '. number_format($total, 0, ',', '.');

            $order = new Order;
            $order->type = 'order';
            $order->date = $date;
            $order->tour = $tour;
            $order->pax = $pax;
            $order->total = $total;
            $order->note = $note;
            $order->transactions = json_encode($json);
            $order->save();

            $order_id = $order->id;
            foreach($guests as $guest)
            {
                $array_guest = explode("|",$guest);
                DB::table('orders_shoppingcarts')->insert(['order_id'=>$order_id,'shoppingcart_id'=>$array_guest[3],"note"=>$array_guest[0]." - ".$array_guest[1]." ".$array_guest[2],"created_at" => now(),
    "updated_at" => now()]);
            }
        }

        // Short Food Tour
        if($app==3)
            {
            
            $guiding_fee = 80000;
            $cost_fee = 80000;

            $validator = Validator::make($request->all(), [
                'guests' => 'required'
            ]);
        
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors);
            }
            
            $date =  $request->input('date');
            $guide =  $request->input('guide');
            $additional =  $request->input('additional');
            $guests =  $request->input('guests');

            //Hitung pax
            $pax = 0;
            foreach($guests as $guest)
            {
                $array_guest = explode("|",$guest);
                $pax += $array_guest[2];
            }
            
            
            
            $guide = fin_categories::where('id',$guide)->first();
            

            $total_guide = $guiding_fee * $pax;
            $total_cost = $cost_fee * $pax;
            
            $total = $total_cost + $total_guide;
            $tour = "Jogja Short Food Tour";
            $note = $tour .' - '. $pax .'pax';

            //Fee tak terduga
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

            $order_id = $order->id;
            foreach($guests as $guest)
            {
                $array_guest = explode("|",$guest);
                DB::table('orders_shoppingcarts')->insert(['order_id'=>$order_id,'shoppingcart_id'=>$array_guest[3],"note"=>$array_guest[0]." - ".$array_guest[1]." ".$array_guest[2],"created_at" => now(),
    "updated_at" => now()]);
            }


        }

        //Full Night Food Tour
        if($app==1)
        {
            $validator = Validator::make($request->all(), [
                'guests' => 'required'
            ]);
        
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors);
            }
            
            $date =  $request->input('date');
            $guide =  $request->input('guide');
            $additional =  $request->input('additional');
            $guests =  $request->input('guests');

            //Hitung pax dan gyg
            $pax = 0;
            $gyg_count = 0;
            foreach($guests as $guest)
            {
                $array_guest = explode("|",$guest);
                $pax += $array_guest[2];
                if($array_guest[1]=="GetYourGuide")
                {
                    $gyg_count += $array_guest[2];
                }
            }
            
            //prepare fee guide
            $duty_fee = 0;
            $guiding_fee = 0;
            $guide = fin_categories::where('id',$guide)->first();
            $guide_settings = json_decode(config('site.guides'));
            foreach($guide_settings as $guide_setting)
            {
                if($guide->id==$guide_setting->id)
                {
                    $guiding_fee = $guide_setting->fee;
                    $duty_fee = $guide_setting->duty_fee;
                    $level_id = $guide_setting->level_id;
                }
            }

            $pengurang_gyg = 0;
            $pengurang_gyg_text = "";
            if($level_id==10)
            {
                if($gyg_count<=4)
                {
                    $pengurang_gyg = 30000 * $gyg_count;
                }
                $pengurang_gyg_text = " (GYG : ". $gyg_count .")";
            }
            $total_guide = ($guiding_fee * $pax) + $duty_fee - $pengurang_gyg;

                    
            $total_cost = 225000 * $pax;
            $total = $total_cost + $total_guide;
            $tour = "Jogja Night Food Tour";
            $text_pengurang_gyg = "";
            $note = $tour .' - '. $pax .'pax'. $pengurang_gyg_text;

            //Fee tak terduga
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


            
            $order_id = $order->id;
            foreach($guests as $guest)
            {
                $array_guest = explode("|",$guest);
                DB::table('orders_shoppingcarts')->insert(['order_id'=>$order_id,'shoppingcart_id'=>$array_guest[3],"note"=>$array_guest[0]." - ".$array_guest[1]." ".$array_guest[2],"created_at" => now(),
    "updated_at" => now()]);
            }

        }

        if($app==2)
        {
            $guiding_fee = 150000;
            $cost_fee = 150000;

            $validator = Validator::make($request->all(), [
                'guests' => 'required'
            ]);
        
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors);
            }
            
            $date =  $request->input('date');
            $guide =  $request->input('guide');
            $additional =  $request->input('additional');
            $guests =  $request->input('guests');

            //Hitung pax
            $pax = 0;
            foreach($guests as $guest)
            {
                $array_guest = explode("|",$guest);
                $pax += $array_guest[2];
            }
            
            
            
            $guide = fin_categories::where('id',$guide)->first();
            

            $total_guide = $guiding_fee * $pax;
            $total_cost = $cost_fee * $pax;
            
            $total = $total_cost + $total_guide;
            $tour = "Jogja Morning Food Tour";
            $note = $tour .' - '. $pax .'pax';

            //Fee tak terduga
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

            $order_id = $order->id;
            foreach($guests as $guest)
            {
                $array_guest = explode("|",$guest);
                DB::table('orders_shoppingcarts')->insert(['order_id'=>$order_id,'shoppingcart_id'=>$array_guest[3],"note"=>$array_guest[0]." - ".$array_guest[1]." ".$array_guest[2],"created_at" => now(),
    "updated_at" => now()]);
            }

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
