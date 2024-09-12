<?php
namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use budisteikul\tourcms\Classes\FinClass;
use budisteikul\tourcms\Helpers\GeneralHelper;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $fin_date_start = FinClass::first_date_transaction();
        $fin_date_end = date('Y-m-d') .' 23:59:00';

        $tahun = $request->input('year');

        if($tahun=="") $tahun = date("Y");
        
        
        $ibulan = 12;
        if(date('Y') == $tahun) $ibulan = date('m');
        
        for($i=1;$i <= $ibulan; $i++)
        {
            if(strtotime(date($tahun.'-'.$i.'-01 23:59:00')) < strtotime($fin_date_start))
            {
                $total = 0;
            }
            else if(date('Y-m')==$tahun.'-'.GeneralHelper::digitFormat($i,2))
            {
                $a = FinClass::calculate_saldo_akhir($tahun,$i-1);
                $revenue_per = FinClass::total_per_month_by_type('Revenue',$tahun,GeneralHelper::digitFormat($i,2));
                $cogs_per = FinClass::total_per_month_by_type('Cost of Goods Sold',$tahun,GeneralHelper::digitFormat($i,2));
                $gross_margin = $revenue_per - $cogs_per;
                $total_expenses = FinClass::total_per_month_by_type('Expenses',$tahun,GeneralHelper::digitFormat($i,2));
                $total = $a + $gross_margin - $total_expenses;
            }
            else
            {
                $total = FinClass::calculate_saldo_akhir($tahun,$i);
            }

            $saldo[] = $total;
            $bulan[] = $i;
        }
        

        
        return view('tourcms::fin.report.asset',
            [
                'tahun'=>$tahun,
                'saldo'=>$saldo,
                'bulan'=>$bulan,
            ]);
    }
}
?>