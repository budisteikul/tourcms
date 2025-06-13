<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use budisteikul\tourcms\Helpers\WiseHelper;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $currency = $request->input('currency');
        if($currency=="") $currency = 'USD';
        //$tw = new WiseHelper();
        //$form = $tw->getAccountRequirements($currency);
        
        //print_r(json_decode($form));

        return view('tourcms::refund.index',['currency'=>$currency]);
    }

    
    
}
