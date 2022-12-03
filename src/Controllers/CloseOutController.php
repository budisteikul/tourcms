<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\toursdk\Models\CloseOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\CloseOutDataTable;

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
        return view('tourcms::closeout.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCloseOutRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|unique:close_outs,date',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $date =  $request->input('date');

        $closeout = new CloseOut();
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
        return view('tourcms::closeout.edit',['closeout'=>$closeout]);
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
        $validator = Validator::make($request->all(), [
            'date' => 'required|unique:close_outs,date,'.$closeout->id,
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $date =  $request->input('date');
        
        $closeout->date = $date;
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
