<?php

namespace budisteikul\tourcms\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use budisteikul\tourcms\Classes\FinClass;
use budisteikul\tourcms\Helpers\GeneralHelper;
use Barryvdh\DomPDF\Facade as PDF;

use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class LaporanController extends Controller
{
    
    public function pdf($tahun)
    {
        if($tahun=="") $tahun = date("Y");

        //LABA RUGI ==========================================================================
        
        $fin_categories_revenues = fin_categories::where('type','Revenue')->where('parent_id',0)->orderBy('name')->get();

        $fin_categories_expenses = fin_categories::where('type','Expenses')->where('parent_id',0)->orderBy('name')->get();
        
        $fin_categories_cogs = fin_categories::where('type','Cost of Goods Sold')->where('parent_id',0)->orderBy('name')->get();

        $pdf = PDF::setOptions(['tempDir' =>  storage_path(),'fontDir' => storage_path(),'fontCache' => storage_path(),'isRemoteEnabled' => true])->loadView('tourcms::fin.pdf.profitloss', [
                'fin_categories_revenues'=>$fin_categories_revenues,
                'fin_categories_expenses'=>$fin_categories_expenses,
                'fin_categories_cogs'=>$fin_categories_cogs,
                'tahun'=>$tahun
            ])->setPaper('legal', 'landscape');

        $content = $pdf->download()->getOriginalContent();
        Storage::disk('local')->put('pdf/laporan/labarugi-'. $tahun .'.pdf', $content);
        //LABA RUGI ==========================================================================


        //NERACA ==========================================================================
        $capital = FinClass::capital();
        $debt = FinClass::debt();
        $retained_earnings = FinClass::calculate_saldo_akhir($tahun-1,12);

        $revenue = 0;
        for($i=1;$i<=12;$i++)
        {
            $revenue += FinClass::total_per_month_by_type('Revenue',$tahun,$i);
        }
        
        $cogs = 0;
        for($i=1;$i<=12;$i++)
        {
            $cogs += FinClass::total_per_month_by_type('Cost of Goods Sold',$tahun,$i);
        }

        $expenses = 0;
        for($i=1;$i<=12;$i++)
        {
            $expenses += FinClass::total_per_month_by_type('Expenses',$tahun,$i);
        }
        
        $cash = 0;
        $accounts_receivable = 0;
        $earning = $revenue - $cogs - $expenses;

        $cash = $capital + $debt + $retained_earnings + $earning;
        if($earning<0)
        {
            $accounts_receivable = $earning * -1;
            $earning = 0;
        }
        

        $total_asset = $cash + $accounts_receivable;
        $total_liabilities_and_equity =  $capital+$earning+$retained_earnings;

        $pdf = PDF::setOptions(['tempDir' =>  storage_path(),'fontDir' => storage_path(),'fontCache' => storage_path(),'isRemoteEnabled' => true])->loadView('tourcms::fin.pdf.neraca', [
                'tahun'=>$tahun,
                'cash'=>$cash,
                'retained_earnings'=>$retained_earnings,
                'capital'=>$capital,
                'debt'=>$debt,
                'accounts_receivable'=>$accounts_receivable,
                'earning'=>$earning,
                'total_asset'=>$total_asset,
                'total_liabilities_and_equity'=>$total_liabilities_and_equity,
            ])->setPaper('a4', 'portrait');

        $content = $pdf->download()->getOriginalContent();
        Storage::disk('local')->put('pdf/laporan/neraca-'. $tahun .'.pdf', $content);
        //NERACA ==========================================================================

        //PP23 ==========================================================================
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
        $pdf = PDF::setOptions(['tempDir' =>  storage_path(),'fontDir' => storage_path(),'fontCache' => storage_path(),'isRemoteEnabled' => true])->loadView('tourcms::fin.pdf.tax', [
                'tahun'=>$tahun,
                'data'=>$data
            ])->setPaper('a4', 'portrait');
        $content = $pdf->download()->getOriginalContent();
        Storage::disk('local')->put('pdf/laporan/pp23-'. $tahun .'.pdf', $content);
        //PP23 ==========================================================================

        $oMerger = PDFMerger::init();
        $oMerger->addPDF(Storage::disk('local')->path('pdf/laporan/labarugi-'. $tahun .'.pdf'), 'all');
        $oMerger->addPDF(Storage::disk('local')->path('pdf/laporan/neraca-'. $tahun .'.pdf'), 'all');
        $oMerger->addPDF(Storage::disk('local')->path('pdf/laporan/pp23-'. $tahun .'.pdf'), 'all');
        $oMerger->merge();
        $oMerger->save(Storage::disk('local')->path('pdf/laporan/LaporanKeuangan-'. $tahun .'.pdf'));
        return response()->download(Storage::disk('local')->path('pdf/laporan/LaporanKeuangan-'. $tahun .'.pdf'));
    }
    
}
