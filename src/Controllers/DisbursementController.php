<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use budisteikul\toursdk\Models\Disbursement;
use budisteikul\toursdk\Models\Vendor;
use budisteikul\tourcms\DataTables\DisbursementDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisbursementController extends Controller
{
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

    public function search($id)
    {

        $data = [
                "data" => "111",
                "value" => "222",
            ];
        return response()->json([
                    $data
                ]);
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDisbursementRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDisbursementRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Disbursement  $disbursement
     * @return \Illuminate\Http\Response
     */
    public function show(Disbursement $disbursement)
    {
        //
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
    public function update(UpdateDisbursementRequest $request, Disbursement $disbursement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Disbursement  $disbursement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disbursement $disbursement)
    {
        //
    }
}
