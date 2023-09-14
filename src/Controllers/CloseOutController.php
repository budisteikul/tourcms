<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\toursdk\Models\CloseOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\CloseOutDataTable;
use budisteikul\toursdk\Models\Product;

class CloseOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CloseOutDataTable $dataTable)
    {
        return $dataTable->render('tourcms::closeout.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('tourcms::closeout.create',['products'=>$products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCloseOutRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bokun_id =  $request->input('bokun_id');
        $date =  $request->input('date');

        $validator = Validator::make($request->all(), [
            'bokun_id' => 'required|unique:close_outs,bokun_id,NULL,id,date,'.$date,
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
        
        $closeout = new CloseOut();
        $closeout->bokun_id = $bokun_id;
        $closeout->date = $date;
        $closeout->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CloseOut  $closeOut
     * @return \Illuminate\Http\Response
     */
    public function show(CloseOut $closeOut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CloseOut  $closeOut
     * @return \Illuminate\Http\Response
     */
    public function edit(CloseOut $closeout)
    {
        $products = Product::orderBy('name')->get();
        return view('tourcms::closeout.edit',['closeout'=>$closeout,'products'=>$products]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCloseOutRequest  $request
     * @param  \App\Models\CloseOut  $closeOut
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CloseOut $closeout)
    {
        $date =  $request->input('date');
        $bokun_id =  $request->input('bokun_id');

        $validator = Validator::make($request->all(), [
            'bokun_id' => 'required|unique:close_outs,bokun_id,'.$closeout->id.',id,date,'.$date,
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        
        
        $closeout->date = $date;
        $closeout->bokun_id = $bokun_id;
        $closeout->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CloseOut  $closeOut
     * @return \Illuminate\Http\Response
     */
    public function destroy(CloseOut $closeout)
    {
        $closeout->delete();
    }
}
