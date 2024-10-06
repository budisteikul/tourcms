<?php
namespace budisteikul\tourcms\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

use budisteikul\tourcms\Models\ShoppingcartPayment;

class ReportPaymentController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');
        $newDateTime = Carbon::parse($date."-01");
        $tahun = Str::substr($newDateTime, 0,4);
        $bulan = Str::substr($newDateTime, 5,2);

        if($date=="") $tahun = date("Y");
        if($date=="") $bulan = date("m");

        

        $payments = ShoppingcartPayment::whereHas('shoppingcart', function ($query) use ($bulan,$tahun) {
                        return $query->whereHas('shoppingcart_products', function ($query) use ($bulan,$tahun) {
                            return $query->whereYear('date',$tahun)->whereMonth('date',$bulan);
                        });//->where('booking_status','CONFIRMED');
                    })->where('net','>',0)->get();

        

        return view('tourcms::fin.report.payment',[
            'bulan'=>$bulan,
            'tahun'=>$tahun,
            'payments'=>$payments
        ]);
    }
}
?>