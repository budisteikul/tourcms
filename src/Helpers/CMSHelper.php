<?php
namespace budisteikul\tourcms\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use budisteikul\toursdk\Helpers\GeneralHelper;

class CMSHelper {

	public static function cache_saldo_forget($date)
    {
                $start_year = Str::substr($date, 0,4);
                $start_month = Str::substr($date, 5,2);

                $newDateTime = Carbon::parse($date)->subMonths(1);
                $tahun = Str::substr($newDateTime, 0,4);
                $bulan = Str::substr($newDateTime, 5,2);

                for($i=$start_year;$i<=$tahun;$i++)
                {
                    $xbulan = $start_month;
                    if($i!=$start_year) $xbulan = 1;

                    $ybulan = $bulan;
                    if($i!=date('Y')) $ybulan = 12;

                    for($j=$xbulan;$j<=$ybulan;$j++)
                    {
                            Cache::forget('_finLastMonthSaldo_'. $i .'_'. GeneralHelper::digitFormat($j,2));
                    }
                }
    }

}
?>