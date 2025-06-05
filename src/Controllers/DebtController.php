<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\tourcms\Models\Order;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\DebtDataTable;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DebtDataTable $dataTable)
    {
        return $dataTable->render('tourcms::debt.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guides = json_decode(config('site.guides'));
        return view('tourcms::debt.create',['guides'=>$guides]);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $app =  $request->input('app');
        $amount =  $request->input('amount');
        $date = $request->input('date');

        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $guide = fin_categories::where('id',$app)->first();
        $note = 'Cash advance - '. $guide->name .' : '. number_format($amount, 0, ',', '.');

        $order = new Order;
        $order->type = 'cash_advance';
        $order->date =  $date;
        $order->guide =  $guide->id;
        $order->total = $amount;
        $order->note = $note;
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

        
        $order->delete();
    }
}
