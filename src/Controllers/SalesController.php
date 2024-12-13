<?php

namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use budisteikul\tourcms\Models\Shoppingcart;
use budisteikul\tourcms\Models\ShoppingcartProduct;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade as PDF;
use budisteikul\tourcms\Helpers\AccHelper;

class SalesController extends Controller
{
    
    
    public function index(Request $request)
    {
        
        $tahun = $request->input('year');
        $action = $request->input('action');
        if($tahun=="") $tahun = date("Y");
        
        $fin_categories_revenues = fin_categories::where('type','Revenue')->where('parent_id',0)->orderBy('name')->get();

        $fin_categories_expenses = fin_categories::where('type','Expenses')->where('parent_id',0)->orderBy('name')->get();
        
        $fin_categories_cogs = fin_categories::where('type','Cost of Goods Sold')->where('parent_id',0)->orderBy('name')->get();
        
        if($action=="pdf")
        {
            $pdf = PDF::setOptions(['tempDir' =>  storage_path(),'fontDir' => storage_path(),'fontCache' => storage_path(),'isRemoteEnabled' => true])->loadView('tourcms::fin.pdf.profitloss', [
                'fin_categories_revenues'=>$fin_categories_revenues,
                'fin_categories_expenses'=>$fin_categories_expenses,
                'fin_categories_cogs'=>$fin_categories_cogs,
                'tahun'=>$tahun
            ])->setPaper('legal', 'landscape');

            return $pdf->download('ProfitLoss-'.$tahun.'.pdf');
        }

        return view('tourcms::fin.sales.profitloss',
            [
                'fin_categories_revenues'=>$fin_categories_revenues,
                'fin_categories_expenses'=>$fin_categories_expenses,
                'fin_categories_cogs'=>$fin_categories_cogs,
                'tahun'=>$tahun
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
