<?php

namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        //print_r("aaaa");
        return view('tourcms::refund.index');
    }

    
    
}
