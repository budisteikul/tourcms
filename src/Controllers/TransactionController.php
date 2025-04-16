<?php

namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use budisteikul\tourcms\DataTables\TransactionsDataTable;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use budisteikul\tourcms\Models\Order;
use budisteikul\tourcms\Helpers\AccHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use budisteikul\tourcms\Helpers\GeneralHelper;

class TransactionController extends Controller
{
    public function get_payment()
    {
        $fin_transactions = fin_transactions::where('status',0)->get();
        $total = $fin_transactions->sum('amount');
        return view('tourcms::fin.transactions.payment',['fin_transactions'=>$fin_transactions,'total'=>$total]);
    }

    public function post_payment(Request $request)
    {
        
        $trans_id = $request->input('trans_id');

        foreach($trans_id as $id)
        {
            $fin_transactions = fin_transactions::find($id);
            $fin_transactions->status = 1;
            $fin_transactions->save();
        }

        $fin_transactions = new fin_transactions();
        $fin_transactions->category_id = 47;
        $fin_transactions->date = date('Y-m-d');
        $fin_transactions->amount = 2500;
        $fin_transactions->status = 1;
        $fin_transactions->save();
        
        

        return response()->json([
                    "id" => "1",
                    "message" => 'Success',
                ]);
    }


    public function index(TransactionsDataTable $dataTable,Request $request)
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
           ])->render('tourcms::fin.transactions.index',['tahun' => $tahun,
                'bulan' => $bulan]);
    }

  
    public function create()
    {
        $fin_categories = AccHelper::getCategories();
		
        return view('tourcms::fin.transactions.create',['fin_categories'=>$fin_categories]);
    }

 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
          	'category_id' => 'required',
			'date' => 'required',
			'amount' => 'required',
       	]);
        
       	if ($validator->fails()) {
            $errors = $validator->errors();
			return response()->json($errors);
       	}
		
		$category_id =  $request->input('category_id');
		$date =  $request->input('date');
		$amount =  $request->input('amount');
		
		$fin_transactions = new fin_transactions();
		$fin_transactions->category_id = $category_id;
		$fin_transactions->date = $date;
		$fin_transactions->amount = $amount;
        if(AccHelper::get_type($category_id)!="Cost of Goods Sold")
        {
            $fin_transactions->status = 1;
        }
		$fin_transactions->save();

		return response()->json([
					"id" => "1",
					"message" => 'Success',
                    "log_date" => $date
				]);
    }

   
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $fin_categories = AccHelper::getCategories();
		$fin_transactions = fin_transactions::with('categories')->findOrFail($id);
		if($fin_transactions->amount<0) $fin_transactions->amount = $fin_transactions->amount * -1;
        return view('tourcms::fin.transactions.edit',['fin_transactions'=>$fin_transactions,'fin_categories'=>$fin_categories]);
    }

  
    public function update(Request $request, $id)
    {
        /*
        $action =  $request->input('action');
        if($action=="set_status")
        {
            $fin_transactions = fin_transactions::findOrFail($id);
            $fin_transactions->status = 1;
            $fin_transactions->save();
            return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
        }
        */

        $validator = Validator::make($request->all(), [
          	'category_id' => 'required',
			'date' => 'required',
			'amount' => 'required',
       	]);
        
       	if ($validator->fails()) {
            $errors = $validator->errors();
			return response()->json($errors);
       	}
		
		$category_id =  $request->input('category_id');
		$date =  $request->input('date');
		$amount =  $request->input('amount');
		
		$fin_transactions = fin_transactions::findOrFail($id);
		$fin_transactions->category_id = $category_id;
		$fin_transactions->date = $date;
		$fin_transactions->amount = $amount;
        if(AccHelper::get_type($category_id)!="Cost of Goods Sold")
        {
            $fin_transactions->status = 1;
        }
		$fin_transactions->save();
		
        

		return response()->json([
					"id" => "1",
					"message" => 'Success'
				]);
    }

    public function destroy($id)
    {
        $transaction = fin_transactions::find($id);
        fin_transactions::find($id)->delete();
    }
}
