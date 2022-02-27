<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use budisteikul\toursdk\Models\Disbursement;
use budisteikul\toursdk\Models\Vendor;
use budisteikul\toursdk\Models\Shoppingcart;
use budisteikul\toursdk\Helpers\OyHelper;
use budisteikul\tourcms\DataTables\DisbursementDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisbursementController extends Controller
{

    public static function get_transaction_id()
    {
        $uuid = "DISB-". date('YmdHis') . rand(10,99);
        while( Disbursement::where('transaction_id','=',$uuid)->first() ){
            $uuid = "DISB-". date('YmdHis') . rand(10,99);
        }
        return $uuid;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DisbursementDataTable $dataTable)
    {
        return $dataTable->render('tourcms::disbursement.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
         return view('tourcms::disbursement.create');
    }

    public function search($id,Request $request)
    {
        if($id=="vendor")
        {
            $query = $request->input('query');

            $response = array();

            $vendors = Vendor::whereLike('name', $query )->get();

            foreach($vendors as $vendor)
            {
                $response[] = array("data"=> $vendor->id,"value"=>$vendor->name);
            }
            
            return response()->json([
                    'suggestions' => $response
                ]);
        }
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDisbursementRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $vendor_id =  $request->input('vendor_id');
        $amount =  $request->input('amount');
        $reference =  $request->input('reference');

        if($amount<10000) $amount = 10000;
        $vendor = Vendor::findOrFail($vendor_id);

        $transaction_id = $this->get_transaction_id();

        $disbursement = new Disbursement();
        $disbursement->transaction_id = $transaction_id;
        $disbursement->vendor_id = $vendor->id;
        $disbursement->vendor_name = $vendor->name;
        $disbursement->amount = $amount;
        $disbursement->reference = $reference;
        $disbursement->bank_code = $vendor->bank_code;
        $disbursement->account_number = $vendor->account_number;
        $disbursement->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Disbursement  $disbursement
     * @return \Illuminate\Http\Response
     */
    public function show(Disbursement $disbursement)
    {
        return view('tourcms::disbursement.show',['disbursement'=>$disbursement]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Disbursement  $disbursement
     * @return \Illuminate\Http\Response
     */
    public function edit(Disbursement $disbursement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDisbursementRequest  $request
     * @param  \App\Models\Disbursement  $disbursement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disbursement $disbursement)
    {

        $data = OyHelper::createDisbursement($disbursement);
        $disbursement->status = 1;
        $disbursement->save();
        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Disbursement  $disbursement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disbursement $disbursement)
    {
        $disbursement->delete();
    }
}
