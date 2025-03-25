<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\tourcms\Models\CloseOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\CloseOutV2DataTable;
use budisteikul\tourcms\Models\Product;
use Illuminate\Support\Facades\Cache;

class CloseOutV2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CloseOutV2DataTable $dataTable)
    {
        
        return $dataTable->render('tourcms::closeout.indexV2');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
        $status =  $request->input('status');
        
        if($status==1)
        {
            
            $closeout = CloseOut::where('date',$date)->where('bokun_id',$bokun_id)->delete();
            
        }
        else if($status==3)
        {
            Cache::forget('_bokunCalendarForever_'. config('site.currency') .'_'. env("BOKUN_LANG") .'_'. substr($date,0,4) .'_'. substr($date,5,2) .'_'. $bokun_id);
        }
        else
        {
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
        }
        

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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CloseOut  $closeOut
     * @return \Illuminate\Http\Response
     */
    public function destroy(CloseOut $closeout)
    {
        
    }
}
