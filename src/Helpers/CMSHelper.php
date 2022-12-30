<?php
namespace budisteikul\tourcms\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use budisteikul\toursdk\Helpers\GeneralHelper;

use budisteikul\toursdk\Models\ShoppingcartProduct;
use Ramsey\Uuid\Uuid;

class CMSHelper {

	public static function cache_saldo_forget($date)
    {
                $newDateTime = Carbon::parse($date);
                $start_year = Str::substr($newDateTime, 0,4);
                $start_month = Str::substr($newDateTime, 5,2);

                $tahun_now = date('Y');
                $bulan_now = date('m');
                //print_r($bulan);

                for($i=$start_year;$i<=$tahun_now;$i++)
                {
                    $xbulan = $start_month;
                    if($i!=$start_year) $xbulan = 1;

                    $ybulan = $bulan_now;
                    if($i!=date('Y')) $ybulan = 12;

                    for($j=$xbulan;$j<=$ybulan;$j++)
                    {
                            Cache::forget('_saldo_'. $i .'_'. GeneralHelper::digitFormat($j,2));
                            //print_r('_saldo_'. $i .'_'. GeneralHelper::digitFormat($j,2) .'<br />');
                    }
                }
    }

    public static function reset_session_beta()
    {
        $shoppingcart_products = ShoppingcartProduct::whereHas('shoppingcart', function ($query) {
                return $query->where('booking_status','CONFIRMED');
        })->whereNotNull('date')->where('date', '<', date('Y-m-d H:i:s') )->orderBy('date', 'DESC')->get();
        foreach($shoppingcart_products as $shoppingcart_product)
        {
            $shoppingcart_product->shoppingcart->session_id = Uuid::uuid4()->toString();
            $shoppingcart_product->shoppingcart->save();
        }
    }

}
?>