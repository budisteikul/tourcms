<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;

use budisteikul\tourcms\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\DataTables\InvestmentDataTable;

use Carbon\Carbon;
use Illuminate\Support\Str;
use budisteikul\tourcms\Helpers\GeneralHelper;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InvestmentDataTable $dataTable,Request $request)
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
           ])->render('tourcms::investment.index',['tahun' => $tahun,
                'bulan' => $bulan]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tourcms::investment.create');
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portfolio' => 'required|integer',
            'return' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }

        $date =  $request->input('date');
        $portfolio =  $request->input('portfolio');
        $return =  $request->input('return');
        $total = $portfolio - $return;


        $investment = new Investment;
        $investment->portfolio = $portfolio;
        $investment->date = $date;
        $investment->return = $return;
        $investment->investment = $total;
        $investment->save();

        return response()->json([
                    "id" => "1",
                    "message" => 'Success'
                ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Investment $investment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Investment $investment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Investment $investment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         $investment = Investment::findOrFail($id);
         $investment->delete();
    }
}
