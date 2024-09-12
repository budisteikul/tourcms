<?php
namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use budisteikul\tourcms\Models\Product;
use budisteikul\tourcms\Models\ShoppingcartProduct;
use budisteikul\tourcms\Classes\ReportClass;

class ReportMonthlyController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');
        $newDateTime = Carbon::parse($date."-01");
        $tahun = Str::substr($newDateTime, 0,4);
        $bulan = Str::substr($newDateTime, 5,2);

        //$tahun = $request->input('year');
        if($date=="") $tahun = date("Y");
        //$bulan = $request->input('month');
        if($date=="") $bulan = date("m");

        
        $products = ShoppingcartProduct::with('shoppingcart')
        ->WhereHas('shoppingcart', function($query) {
                 $query->where('booking_status','CONFIRMED');
            })->select('title')->whereMonth('date',$bulan)->whereYear('date',$tahun)->groupBy('title')->get();

        $products_count = array();
        foreach($products as $product)
        {
            $count = ReportClass::traveller_product_per_month($product->title,$bulan,$tahun);
            $products_count[] = (object)[
                'title' => $product->title,
                'count' => $count,
            ];
        }
        
        $products = (object)$products_count;

        for($i=1;$i <= date("t",strtotime($tahun."-".$bulan."-01"));$i++)
        {
            $tgl[] = $i;
            $traveller[] = ReportClass::traveller_per_day($i,$bulan,$tahun);
        }
         

        
        return view('tourcms::fin.report.monthly',
            [
                'bulan'=>$bulan,
                'tahun'=>$tahun,
                'products'=>$products,
                'tgl'=>$tgl,
                'traveller' => $traveller
            ]);
    }
}
?>