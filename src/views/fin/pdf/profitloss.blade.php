@inject('fin', 'budisteikul\tourcms\Helpers\AccHelper')
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">   
<title></title>
<style type="text/css">
  html { margin:20px}
  .font-weight-bolder
  {
    font-weight: bold;
  }
  body {
  margin: 0 auto; 
  color: #555555;
  background: #FFFFFF; 
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13px; 
}
hr
{
  border: 1px thin black;
}
</style>
</head>
<body>
 

<div>
<div style="margin-top: 30px; margin-bottom: 40px; font-weight: bold; text-align: center; font-size:22px">
  Income Statement {{env('APP_NAME')}} For Year {{$tahun}}
</div>
<div>
<center>
 <table border="0" cellspacing="5" cellpadding="2" class="table table-borderless table-responsive w-100 d-block d-md-table" >
  <thead>
    <tr class="table-active">
      <th colspan="3" class="font-weight-bolder">{{ $tahun }}</th>
      @for($i=1; $i<=12; $i++)
      <td align="center" class="font-weight-bolder">{{ Carbon\Carbon::createFromFormat('m', $i)->formatLocalized('%b') }}</td>
      @endfor
      <td align="center" class="font-weight-bolder"><i>Total</i></td>
      
    </tr>
  </thead>
  <tbody>
    
    
    <tr>
      <td colspan="16" class="font-weight-bolder">
      <hr>
      Income
      <hr>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="font-weight-bolder">Revenue</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      
      <!-- #### -->
    </tr>
    @php
      $total_sales_arr = [];
      for($i=1; $i<=12; $i++)
      {
            $total_sales_arr[$i] = 0;
      }
    
     
    @endphp
    @foreach($fin_categories_revenues as $fin_categories_revenue)
    <tr id="rev-{{ $fin_categories_revenue->id }}">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>{{ $fin_categories_revenue->name }}</td>
      @php
          $fin_categories_revenue_subtotal = 0;
      @endphp
      @for($i=1; $i<=12; $i++)
        @php
            $fin_categories_revenue_per = $fin::total_per_month($fin_categories_revenue->id,$tahun,$i);
            $fin_categories_revenue_subtotal += $fin_categories_revenue_per;

            $total_sales_arr[$i] += $fin_categories_revenue_per;
        @endphp
        <td align="right" style="background-color:#FEFEEF">{{  number_format($fin_categories_revenue_per, 0, ',', '.') }}</td>
      @endfor
      <td align="right" class="font-weight-bolder"><i>{{ number_format($fin_categories_revenue_subtotal, 0, ',', '.') }}</i></td>
      
    </tr>
      <script>
        @if ($fin_categories_revenue_subtotal==0)
          $('#rev-{{ $fin_categories_revenue->id }}').remove();
        @endif
      </script>
      <!-- #### -->
    @endforeach
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="16"><hr></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="font-weight-bolder">Total sales</td>
      @php
        $revenue_subtotal = 0;
      @endphp
      @for($i=1; $i<=12; $i++)
        @php
            $revenue_per = $total_sales_arr[$i];
            $revenue_subtotal += $revenue_per;
        @endphp
        <td align="right" style="background-color:#FEFEEF">{{ number_format($revenue_per, 0, ',', '.') }}</td>
      @endfor
      <td align="right" class="font-weight-bolder"><i>{{ number_format($revenue_subtotal, 0, ',', '.') }}</i></td>
      <!-- #### -->
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="font-weight-bolder">Cost of sales</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <!-- #### -->
    </tr>
    @php
      $total_cogs_arr = [];
      for($i=1; $i<=12; $i++)
      {
            $total_cogs_arr[$i] = 0;
      }
    @endphp
    @foreach($fin_categories_cogs as $fin_categories_cog)
    <tr id="cogs-{{ $fin_categories_cog->id }}">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>{{ $fin_categories_cog->name }}</td>
      @php
          $fin_categories_cog_subtotal = 0;
      @endphp
      @for($i=1; $i<=12; $i++)
        @php
            $fin_categories_cog_per = $fin::total_per_month($fin_categories_cog->id,$tahun,$i);
            $fin_categories_cog_subtotal += $fin_categories_cog_per;

            $total_cogs_arr[$i] += $fin_categories_cog_per;
        @endphp
        <td align="right" style="background-color:#FEFEEF">{{ number_format($fin_categories_cog_per, 0, ',', '.') }}</td>
      @endfor
      <td align="right" class="font-weight-bolder"><i>{{ number_format($fin_categories_cog_subtotal, 0, ',', '.') }}</i></td>
      
    </tr>
      <script>
        @if ($fin_categories_cog_subtotal==0)
          $('#cogs-{{ $fin_categories_cog->id }}').remove();
        @endif
      </script>
      <!-- #### -->
    @endforeach
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="16"><hr></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="font-weight-bolder">Total cost of sales</td>
      @php
        $cogs_subtotal = 0;
      @endphp
      @for($i=1; $i<=12; $i++)
        @php
            $cogs_per = $total_cogs_arr[$i];
            $cogs_subtotal += $cogs_per;
        @endphp
        <td align="right" style="background-color:#FEFEEF">{{ number_format($cogs_per, 0, ',', '.') }}</td>
      @endfor
      <td align="right" class="font-weight-bolder"><i>{{ number_format($cogs_subtotal, 0, ',', '.') }}</i></td>
      <!-- #### -->
    </tr>
    <tr>
      <td colspan="16">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="font-weight-bolder">Gross margin</td>
      <td>&nbsp;</td>
      @php
      $gross_margin_total = 0;
      $total_gross_arr = [];
      for($i=1; $i<=12; $i++)
      {
            $total_gross_arr[$i] = 0;
      }
      @endphp
      @for($i=1; $i<=12; $i++)
      @php
      
        $revenue_per = $total_sales_arr[$i];
        $cogs_per = $total_cogs_arr[$i];
        $total_gross_arr[$i] += $revenue_per - $cogs_per;

        $gross_margin_total += $total_gross_arr[$i];
        
        $gross_margin_print = number_format($total_gross_arr[$i], 0, ',', '.');
        if($total_gross_arr[$i]<0) $gross_margin_print = '('. number_format($total_gross_arr[$i]*-1, 0, ',', '.') .')';
        
      @endphp
      <td align="right" class="font-weight-bolder" style="background-color:#FEFEEF">{{ $gross_margin_print }}</td>
      @endfor
      @php
        $gross_margin_total_print = number_format($gross_margin_total, 0, ',', '.');
        if($gross_margin_total<0) $gross_margin_total_print = '('. number_format($gross_margin_total*-1, 0, ',', '.') .')';
      @endphp
      <td align="right" class="font-weight-bolder"><i>{{ $gross_margin_total_print }}</i></td>
      <!-- #### -->
    </tr>
    <tr>
      <td colspan="16">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="16" class="font-weight-bolder">
      <hr>
      Expenses
      <hr>
      </td>
    </tr>
      @php
      $total_expenses_arr = [];
      for($i=1; $i<=12; $i++)
      {
            $total_expenses_arr[$i] = 0;
      }
      @endphp
     @foreach($fin_categories_expenses as $fin_categories_expense)
    <tr id="exp-{{ $fin_categories_expense->id }}">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>{{ $fin_categories_expense->name }}</td>
      @php
          $fin_categories_expense_subtotal = 0;
      @endphp
      @for($i=1; $i<=12; $i++)
        @php
            $fin_categories_expense_per = $fin::total_per_month($fin_categories_expense->id,$tahun,$i);
            $fin_categories_expense_subtotal += $fin_categories_expense_per;

            $total_expenses_arr[$i] += $fin_categories_expense_per;
        @endphp
        <td align="right" style="background-color:#FEFEEF">{{ number_format($fin_categories_expense_per, 0, ',', '.') }}</td>
      @endfor
      <td align="right" class="font-weight-bolder"><i>{{ number_format($fin_categories_expense_subtotal, 0, ',', '.') }}</i></td>
      
    </tr>
      <script>
        @if ($fin_categories_expense_subtotal==0)
          $('#exp-{{ $fin_categories_expense->id }}').remove();
        @endif
      </script>
      <!-- #### -->
    @endforeach



    
   


    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="16"><hr></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="font-weight-bolder">Total expenses</td>
      @php
        $expenses_subtotal = 0;
      @endphp
      @for($i=1; $i<=12; $i++)
        @php
            $expenses_per = $total_expenses_arr[$i];
            $expenses_subtotal += $expenses_per;
        @endphp
        <td align="right" class="font-weight-bolder" style="background-color:#FEFEEF">{{ number_format($expenses_per, 0, ',', '.') }}</td>
      @endfor
      <td align="right" class="font-weight-bolder"><i>{{ number_format($expenses_subtotal, 0, ',', '.') }}</i></td>
      <!-- #### -->
    </tr>
    <tr>
      <td colspan="16">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="font-weight-bolder">Total profit (loss)</td>
      @php
        $profit_loss_total = 0;
      @endphp
      @for($i=1; $i<=12; $i++)
      @php
        
        
        
        $profit_loss = $total_gross_arr[$i] - $total_expenses_arr[$i];
        $profit_loss_total += $profit_loss;
        
        $profit_loss_print = number_format($profit_loss, 0, ',', '.');
        if($profit_loss<0) $profit_loss_print = '('. number_format($profit_loss*-1, 0, ',', '.') .')';
      @endphp
      <td align="right" class="font-weight-bolder" style="background-color:#FEFEEF">{{ $profit_loss_print }}</td>
      @endfor
      @php
        $profit_loss_total_print = number_format($profit_loss_total, 0, ',', '.');
        if($profit_loss_total<0) $profit_loss_total_print = '('. number_format($profit_loss_total*-1, 0, ',', '.') .')';
      @endphp
      <td align="right" class="font-weight-bolder"><i>{{ $profit_loss_total_print }}</i></td>
      <!-- #### -->
    </tr>
    <tr>
      <td colspan="16">&nbsp;</td>
    </tr>
  </tbody>
</table>
 </center>    
</div>
</div>






</body>
</html>
