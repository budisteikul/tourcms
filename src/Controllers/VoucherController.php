<?php

namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use budisteikul\toursdk\Models\Voucher;
use budisteikul\toursdk\Models\Product;
use budisteikul\tourcms\DataTables\VoucherDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VoucherDataTable $dataTable)
    {
        return $dataTable->render('tourcms::voucher.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('tourcms::voucher.create',['products'=>$products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVoucherRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:vouchers,code',
            'amount' => 'required|integer',
            'is_percentage' => 'required',
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $code =  $request->input('code');
        $amount =  $request->input('amount');
        $is_percentage =  $request->input('is_percentage');

        $voucher = new Voucher();
        $voucher->code = strtoupper($code);
        $voucher->amount = $amount;
        $voucher->is_percentage = $is_percentage;
        $voucher->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit(Voucher $voucher)
    {
        return view('tourcms::voucher.edit',['voucher'=>$voucher]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVoucherRequest  $request
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voucher $voucher)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:vouchers,code,'.$voucher->id,
            'amount' => 'required|integer',
            'is_percentage' => 'required',
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $code =  $request->input('code');
        $amount =  $request->input('amount');
        $is_percentage =  $request->input('is_percentage');

        $voucher->code = strtoupper($code);
        $voucher->amount = $amount;
        $voucher->is_percentage = $is_percentage;
        $voucher->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
    }
}
