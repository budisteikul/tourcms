<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\tourcms\Models\Order;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\DebtDataTable;

use Carbon\Carbon;
use Illuminate\Support\Str;
use budisteikul\tourcms\Helpers\GeneralHelper;
use budisteikul\tourcms\Helpers\AccHelper;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DebtDataTable $dataTable,Request $request)
    {
        $date = $request->input('date');

        if($date=="") $date = date('Y-m');

        $newDateTime = Carbon::parse($date."-01");
        $tahun = Str::substr($newDateTime, 0,4);
        $bulan = Str::substr($newDateTime, 5,2);
        $bulan = GeneralHelper::digitFormat($bulan,2);

        $guides = json_decode(config('site.guides'));

        return $dataTable->with([
                'tahun' => $tahun,
                'bulan' => $bulan
           ])->render('tourcms::debt.index',['tahun' => $tahun,
                'bulan' => $bulan,'guides' => $guides]);
    }

    public function fee(Request $request)
    {
        $date = $request->input('date');

        if($date=="") $date = date('Y-m');

        $newDateTime = Carbon::parse($date."-01");
        $tahun = Str::substr($newDateTime, 0,4);
        $bulan = Str::substr($newDateTime, 5,2);
        $bulan = GeneralHelper::digitFormat($bulan,2);

        $guides = json_decode(config('site.guides'));

        $aaa = '';
        foreach($guides as $guide)
        {
            $total = AccHelper::total_per_month($guide->id,$tahun,$bulan,false);
            $order = AccHelper::count_per_month($guide->id,$tahun,$bulan,false);
            $ca = AccHelper::ca($guide->id,$bulan,$tahun);
            $total = $total - $ca->total;

            $aaa .= '
    <div class="col-sm-3">
        <div class="col-sm-12 justify-content-left">
            <div class="row border-bottom p-2">
                <div class="col-md-auto ">
                    <b>Guide :</b> '.$guide->name.'
                </div>
            </div>
            <div class="row border-bottom p-2">
                
                <div class="col-md-auto ">
                    <b>Total :</b> IDR '. number_format($total, 0, ',', '.') .'
                </div>
            </div>
        </div>
    </div>';
        }

        $view = '<div class="row mt-4">'.$aaa.'</div>';
        return response()->json([
                    "id" => "1",
                    "view" => $view
                ]);
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
        $note = $request->input('note');

        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $guide = fin_categories::where('id',$app)->first();
        
        $order = new Order;
        $order->type = 'cash_advance';
        $order->date =  $date;
        $order->guide =  $guide->id;
        $order->total = $amount * -1;
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
