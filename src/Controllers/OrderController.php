<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\tourcms\Models\Order;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\OrderDataTable;
use budisteikul\tourcms\Models\Product;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('tourcms::order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    public function create_jnft()
    {
        return view('tourcms::order.create-jnft');
    }

    public function create_jmft()
    {
        return view('tourcms::order.create-jmft');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $app =  $request->input('app');
        if($app==1)
        {
            $date =  $request->input('date');
            $guide =  $request->input('guide');
            $pax =  $request->input('pax');

            $guide = fin_categories::where('id',$guide)->first();

            $total_guide = 150000 * $pax;
            $total_cost = 250000 * $pax;

            $total = $total_cost + $total_guide;

            

           

            $transaction = new fin_transactions;
            $transaction->category_id = $guide->id;
            $transaction->date = $date;
            $transaction->amount = $total_guide;
            $transaction->status = 0;
            $transaction->save();

            $json[] = [
                'id' => $transaction->id
            ];

            $transaction = new fin_transactions;
            $transaction->category_id = 15;
            $transaction->date = $date;
            $transaction->amount = $total_cost;
            $transaction->status = 0;
            $transaction->save();

            $json[] = [
                'id' => $transaction->id
            ];

            $order = new Order;
            $order->pax = $pax;
            $order->date = $date;
            $order->tour = 'Jogja Night Food Tour';
            $order->guide = $guide->name;
            $order->total = $total;
            $order->transactions = json_encode($json);
            $order->save();

        }

        if($app==2)
        {
            $date =  $request->input('date');
            $guide =  $request->input('guide');
            $pax =  $request->input('pax');

            $guide = fin_categories::where('id',$guide)->first();

            $total_guide = 150000 * $pax;
            $total_cost = 150000 * $pax;

            $total = $total_cost + $total_guide;

            

           

            $transaction = new fin_transactions;
            $transaction->category_id = $guide->id;
            $transaction->date = $date;
            $transaction->amount = $total_guide;
            $transaction->status = 0;
            $transaction->save();

            $json[] = [
                'id' => $transaction->id
            ];

            $transaction = new fin_transactions;
            $transaction->category_id = 15;
            $transaction->date = $date;
            $transaction->amount = $total_cost;
            $transaction->status = 0;
            $transaction->save();

            $json[] = [
                'id' => $transaction->id
            ];

            $order = new Order;
            $order->pax = $pax;
            $order->date = $date;
            $order->tour = 'Jogja Morning Food Tour';
            $order->guide = $guide->name;
            $order->total = $total;
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
         foreach(json_decode($order->transactions) as $transaction)
         {
            fin_transactions::where('id',$transaction->id)->delete();
         }
         $order->delete();
    }
}
