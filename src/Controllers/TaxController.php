<?php

namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use budisteikul\tourcms\Classes\FinClass;
use budisteikul\tourcms\Helpers\GeneralHelper;
use Barryvdh\DomPDF\Facade as PDF;
class TaxController extends Controller
{
	public function index(Request $request)
    {

        $tahun = $request->input('year');
        $action = $request->input('action');
        if($tahun=="") $tahun = date("Y");

        $data = new \stdClass();

        $data->month = [];
        $data->month_text = [];
        $data->revenue = [];
        $data->tax = [];

        $data->dpp_total = 0;
        $data->pph_total = 0;

        for($i=1;$i <= 12; $i++)
        {

            $revenue = FinClass::total_per_month_by_type('Revenue',$tahun,$i);
            $data->month[$i] = $tahun .'-'. GeneralHelper::digitFormat($i,2) .'-01';
            $data->month_text[$i] = date('F', mktime(0, 0, 0, $i, 10)) .' '. $tahun;
            $data->revenue[$i] = number_format($revenue, 0, ',', '.');

            $pp23 = $revenue * 0.5 / 100;
            if(date('Y-m-01',strtotime($data->month[$i])) >= date('Y-m-01')) $pp23 = 0;
            $data->tax[$i] = number_format($pp23, 0, ',', '.');

            $data->dpp_total += $revenue;
            $data->pph_total += $pp23;
        }

        $data->dpp_total = number_format($data->dpp_total, 0, ',', '.');
        $data->pph_total = number_format($data->pph_total, 0, ',', '.');

        if($action=="pdf")
        {
        $pdf = PDF::setOptions(['tempDir' =>  storage_path(),'fontDir' => storage_path(),'fontCache' => storage_path(),'isRemoteEnabled' => true])->loadView('tourcms::fin.pdf.tax', [
                'tahun'=>$tahun,
                'data'=>$data
            ])->setPaper('a4', 'portrait');

        return $pdf->download('Tax-'.$tahun.'.pdf');
        }

        return view('tourcms::fin.sales.tax',
            [
                'tahun'=>$tahun,
                'data'=>$data
            ]);
    }

    
}

?>