<?php
namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use budisteikul\tourcms\Models\Product;
use budisteikul\tourcms\Models\ShoppingcartProduct;
use budisteikul\tourcms\Helpers\ReportHelper;

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
            $count = ReportHelper::traveller_product_per_month($product->title,$bulan,$tahun);
            $products_count[] = (object)[
                'title' => $product->title,
                'count' => $count,
            ];
        }
        

        $products = (object)$products_count;

        for($i=1;$i <= date("t",strtotime($tahun."-".$bulan."-01"));$i++)
        {
            $tgl[] = $i;
            $traveller[] = ReportHelper::traveller_per_day($i,$bulan,$tahun);
        }
        
        $traveler_booking_per_months = ReportHelper::traveler_booking_per_month($bulan,$tahun);
        $bookings = ReportHelper::booking_per_month($bulan,$tahun);


        $guides = json_decode(config('site.guides'));
        
        return view('tourcms::fin.report.monthly',
            [
                'bulan'=>$bulan,
                'tahun'=>$tahun,
                'products'=>$products,
                'tgl'=>$tgl,
                'traveller' => $traveller,
                'guides' => $guides,
                'bookings' => $bookings,
                'traveler_booking_per_months' => $traveler_booking_per_months
            ]);
    }
}
?>