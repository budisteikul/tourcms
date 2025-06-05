<?php
namespace budisteikul\tourcms\Helpers;

use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use budisteikul\tourcms\Models\Order;
use budisteikul\tourcms\Helpers\GeneralHelper;
use budisteikul\tourcms\Models\Shoppingcart;
use budisteikul\tourcms\Models\Product;
use budisteikul\tourcms\Models\ShoppingcartProduct;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class AccHelper {
    
    public static function capital()
    {
        return self::total_by_type('Capital');
    }

    public static function debt()
    {
        return self::total_by_type('Debt');
    }

    public static function first_date_transaction()
    {
        $fin_transactions = fin_transactions::orderBy('date')->first();
        return $fin_transactions->date;
    }

    public static function get_type($id)
    {
        $fin_categories = fin_categories::where('id',$id)->first();
        return $fin_categories->type;
    }

    public static function ca($guide_id,$month,$year)
    {
        $data = new \stdClass();
        $total = 0;
        $cas = Order::where('type','cash_advance')->where('guide',$guide_id)->whereMonth('date',$month)->whereYear('date',$year)->get();
        foreach($cas as $ca)
        {
            $total += $ca->total;
        }
        $data->ca = $cas;
        $data->total = $total;
        return $data;
    }

    public static function total_by_type($type)
    {
        $total = 0;
        $fin_categories = fin_categories::where('type',$type)->get();
        foreach($fin_categories as $fin_categorie)
        {
            $total += fin_transactions::where('category_id',$fin_categorie->id)->where('status',1)->sum('amount');
        }
        return $total;
    }

    

    public static function count_per_year($category_id,$year){
          $total = 0;
          $total = fin_transactions::where('category_id',$category_id)->where('status',1)->whereYear('date',$year)->count();
          return $total;
    }

    public static function count_per_month($category_id,$year,$month,$status=true){
          $total = 0;
          if($status)
          {
            $total = fin_transactions::where('category_id',$category_id)->where('status',1)->whereYear('date',$year)->whereMonth('date',$month)->count();
          }
          else
          {
            $total = fin_transactions::where('category_id',$category_id)->whereYear('date',$year)->whereMonth('date',$month)->count();
          }
          
          return $total;
        
    }

    public static function haveTransaction($id)
    {
        $status = false;
        if(fin_transactions::where('category_id',$id)->first()) return true;
        return $status;
    }

    public static function getCategories($notIn=false,$id=null)
    {
        if($notIn && $id!=null)
        {
            $allChild = self::getChild($id);
            $categories = fin_categories::where('id','<>',$id)->whereNotIn('id',$allChild)->get();
        }
        else
        {
            $categories = fin_categories::get();
        }
        
        foreach($categories as $category)
        {
            $category->name = self::nameCategory($category->id,'-');
        }
        return $categories->sortBy('name');
    }

    public static function getParent($id)
    {
        $status = true;
        $array = array();

        if($id==0) return $array;

        while($status)
        {
            $category = fin_categories::where('id',$id)->first();
            array_push($array,$category->id);
            if($category->parent_id>0)
            {
                $id = $category->parent_id;
            }
            else
            {
                $status = false;
            }
        }
        return $array;
    }

    public static function getChild($id)
    {
        $array = array();
        array_push($array,$id);
        $array = self::getChild_($id,$array);
        return $array;
    }

    public static function getChild_($id,$array)
    {
        $categories = fin_categories::where('parent_id',$id)->get();
        foreach($categories as $category)
        {
             array_push($array,$category->id);
             $a = array();
             if(count($category->child))
             {
                $a = self::getChild_($category->id,$a);
                $array = array_merge($array,$a);
             }
        }
        return $array;
    }

    public static function structure($id)
    {
        $categories = fin_categories::where('parent_id',$id)->orderBy('name')->get();
        foreach($categories as $category)
        {
             print("<ul>");
             print('<li class="parent_li">');
             print('<span>'.$category->name.'</span>');
             if(count($category->child))
             {
                self::structure($category->id);
             }
             print("</li>");
             print("</ul>");
        }
    }

    public static function nameCategory($id,$separator)
    {
        $strlen = strlen($separator) + 1;
        $id = fin_categories::find($id);
        if(isset($id))
        {
            $array_cat = self::getParent($id->id);
            $categories = fin_categories::findMany($array_cat)->sortBy(function($model) use ($array_cat) {
                return array_search($model->getKey(), array_reverse($array_cat));
            });

            $string = "";
            foreach($categories as $category )
            {
                $string .= " ".$separator." ". $category->name;
            }
            return substr($string,$strlen);
        }
        else
        {
            return "Doesn't have category";
        }
    }

    

    public static function select_yearmonth_form($tahun,$bulan)
    {
        
        $fin_date_start = self::first_date_transaction();
        
        $start_year = Str::substr($fin_date_start, 0,4);
        $start_month = Str::substr($fin_date_start, 5,2);

        
        $tahun_now = date('Y');
        $bulan_now = date('m');

        $option = '';
        for($i=$tahun_now;$i>=$start_year;$i--)
        {
            $xbulan = $start_month;
            if($i!=$start_year) $xbulan = 1;

            $ybulan = $bulan_now;
            if($i!=date('Y')) $ybulan = 12;

            for($j=$ybulan;$j>=$xbulan;$j--)
            {
                $jbulan = GeneralHelper::digitFormat($j,2);
                if($i .'-'. GeneralHelper::digitFormat($jbulan,2) == $tahun .'-'. $bulan)
                {
                    $option .= '<option value="'.$i .'-'. $jbulan.'" selected>'.date('F', mktime(0, 0, 0, $jbulan, 10)).' '.$i.'</option>';
                }
                else
                {
                    $option .= '<option value="'.$i .'-'. $jbulan.'">'. date('F', mktime(0, 0, 0, $jbulan, 10)) .' '.$i.'</option>';
                }
                
            }
        }

        $string = '
                   <form class="form-inline mb-4" method="GET">
                    <div class="form-group">
                        <label class="mr-2" for="date">Date</label>
                        <select name="date" class="form-control mr-2" id="date_filter">'.$option.'</select>
                        <button id="filter" type="submit" class="btn btn-primary"> Apply</button>
                    </div>
                   </form>
                   ';
        return $string;
    }

    public static function select_year_form($tahun)
    {
        
        $fin_date_start = self::first_date_transaction();
        
        $start_year = Str::substr($fin_date_start, 0,4);

        $tahun_now = date('Y');

        $option = '';
        for($i=$tahun_now;$i>=$start_year;$i--)
        {
            if($i==$tahun)
            {
                $option .= '<option value="'.$i .'" selected>'.$i.'</option>';
            }
            else 
            {
                $option .= '<option value="'.$i .'">'.$i.'</option>';
            }
            
        }

        
        $string = '
                   <form class="form-inline mb-4" method="GET">
                    <div class="form-group">
                        <label class="mr-2" for="date">Year</label>
                        <select name="year" class="form-control mr-2" id="year_filter">'.$option.'</select>
                        <button id="filter" type="submit" class="btn btn-primary"> Apply</button>
                    </div>
                   </form>
                   ';
        return $string;
    }

    
    
    public static function calculate_saldo($tahun,$bulan)
    {
                $fin_date_start = self::first_date_transaction();

                $start_year = Str::substr($fin_date_start, 0,4);
                $start_month = Str::substr($fin_date_start, 5,2);

                $newDateTime = Carbon::parse($start_year."-".$start_month."-01")->subMonths(1);
                $tahun_past = Str::substr($newDateTime, 0,4);
                $bulan_past = Str::substr($newDateTime, 5,2);

                $tahun_req = $tahun;
                $bulan_req = $bulan;

                $newDateTime = Carbon::parse($tahun."-".$bulan."-01")->subMonths(1);
                $tahun = Str::substr($newDateTime, 0,4);
                $bulan = Str::substr($newDateTime, 5,2);
                
                $total = 0;
                for($i=$start_year;$i<=$tahun;$i++)
                {
                    $xbulan = $bulan_past;
                    $ybulan = $bulan;
                    
                    if(($tahun_req>$start_year))
                    {
                        if($i==$start_year)
                        {
                            $xbulan = $bulan_past;
                            $ybulan = 12;
                        }
                        else if($i<$tahun_req)
                        {
                            $xbulan = 1;
                            $ybulan = 12;
                        }
                        else
                        {   
                            $xbulan = 1;
                            $ybulan = $bulan;
                        }
                    }

                    
                    for($j=$xbulan;$j<=$ybulan;$j++)
                    {
                                $jbulan = GeneralHelper::digitFormat($j,2);
                                $revenue_per = self::total_per_month_by_type('Revenue',$i,$jbulan);
                                $cogs_per = self::total_per_month_by_type('Cost of Goods Sold',$i,$jbulan);
                                $gross_margin = $revenue_per - $cogs_per;
                                $total_expenses = self::total_per_month_by_type('Expenses',$i,$jbulan);
                    
                                $profit_loss = $gross_margin - $total_expenses;
                                $total += $profit_loss;
                    }
                }
                
                return round($total);
    }

    public static function calculate_saldo_akhir($tahun,$bulan)
    {
                $fin_date_start = self::first_date_transaction();

                $start_year = Str::substr($fin_date_start, 0,4);
                $start_month = Str::substr($fin_date_start, 5,2);

                $newDateTime = Carbon::parse($start_year."-".$start_month."-01");
                $tahun_past = Str::substr($newDateTime, 0,4);
                $bulan_past = Str::substr($newDateTime, 5,2);

                $tahun_req = $tahun;
                $bulan_req = $bulan;

                $newDateTime = Carbon::parse($tahun."-".$bulan."-01");
                $tahun = Str::substr($newDateTime, 0,4);
                $bulan = Str::substr($newDateTime, 5,2);
                
                $total = 0;
                for($i=$start_year;$i<=$tahun;$i++)
                {
                    $xbulan = $bulan_past;
                    $ybulan = $bulan;
                    
                    if(($tahun_req>$start_year))
                    {
                        if($i==$start_year)
                        {
                            $xbulan = $bulan_past;
                            $ybulan = 12;
                        }
                        else if($i<$tahun_req)
                        {
                            $xbulan = 1;
                            $ybulan = 12;
                        }
                        else
                        {   
                            $xbulan = 1;
                            $ybulan = $bulan;
                        }
                    }

                    
                    for($j=$xbulan;$j<=$ybulan;$j++)
                    {
                                $jbulan = GeneralHelper::digitFormat($j,2);
                                $revenue_per = self::total_per_month_by_type('Revenue',$i,$jbulan);
                                $cogs_per = self::total_per_month_by_type('Cost of Goods Sold',$i,$jbulan);
                                $gross_margin = $revenue_per - $cogs_per;
                                $total_expenses = self::total_per_month_by_type('Expenses',$i,$jbulan);
                                $profit_loss = $gross_margin - $total_expenses;
                                $total += $profit_loss;
                    }
                }
                
                return round($total);
    }

    
	public static function total_per_month($category_id,$year,$month,$status=true)
    {
          $total = 0;
          $categories = AccHelper::getChild($category_id);
          foreach($categories as $category)
          {
                if($status)
                {
                    $total += fin_transactions::where('category_id',$category)->whereYear('date',$year)->whereMonth('date',$month)->where('status',1)->sum('amount');
                }
                else
                {
                    $total += fin_transactions::where('category_id',$category)->whereYear('date',$year)->whereMonth('date',$month)->sum('amount');
                }
          }
		  return $total;
	}
    
    
	
	public static function total_per_month_by_type($type,$year,$month)
    {

                $fin_date_start = self::first_date_transaction();
                $fin_date_end = date('Y-m-d') .' 23:59:00';

                $total = 0;
                $fin_categories = fin_categories::where('type',$type)->get();
                foreach($fin_categories as $fin_categorie)
                {
                    $total += fin_transactions::where('category_id',$fin_categorie->id)->whereYear('date',$year)->whereMonth('date',$month)->where('date', '>=', $fin_date_start )->where('date', '<=', $fin_date_end )->where('status',1)->sum('amount');
                }
                return $total;
	}

	public static function total_per_day_by_type($type,$year,$month,$day){
            $total = 0;
            $fin_categories = fin_categories::where('type',$type)->get();
            foreach($fin_categories as $fin_category)
            {
                $total += fin_transactions::where('category_id',$fin_category->id)->whereYear('date',$year)->whereMonth('date',$month)->whereDay('date',$day)->where('status',1)->sum('amount');
            }
        return $total;
    }

    
	

}
?>