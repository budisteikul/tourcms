@inject('fin', 'budisteikul\tourcms\Classes\FinClass')
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">   
<title></title>
<style type="text/css">
  
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
.container {
  display: table;
  width: 100%;
  height: 100%;
}

.content {
  display: table-cell;
  text-align: center;
  vertical-align: middle;
}
table{
  width: 100%;
}
</style>
</head>
<body>
 

<div>
<div style="margin-top: 30px; margin-bottom: 40px; font-weight: bold; text-align: center; font-size: 22px;">
  Balance Sheet {{env('APP_NAME')}} For Year {{$tahun}}
</div>

<center>





  <table id="table1" border="0" cellspacing="2" cellpadding="3" style="border-collapse: collapse; " >
  <tbody>
    <tr>
      <td valign="top"><strong>ASSETS</strong></td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    @if($cash>0)
    <tr>
      <td valign="top">Cash and Cash Equivalents</td>
      <td valign="top" align="right">{{number_format($cash, 0, ',', '.')}}</td>
      <td valign="top">&nbsp;</td>
    </tr>
    @endif
    @if($accounts_receivable>0)
    <tr>
      <td valign="top">Accounts Receivable</td>
      <td valign="top" align="right">{{number_format($accounts_receivable, 0, ',', '.')}}</td>
      <td valign="top">&nbsp;</td>
    </tr>
    @endif
    <tr>
      <td align="right"></td>
      <td align="right"><hr  class="s1" /></td>
      <td align="right"></td>
    </tr>
    <tr>
      <td valign="top"><strong>TOTAL ASSETS</strong></td>
      <td valign="top">&nbsp;</td>
      <td valign="top" align="right">{{number_format($total_asset, 0, ',', '.')}}<hr  class="s9" /></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top"><strong>LIABILITIES</strong></td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">Debt</td>
      <td valign="top" align="right">{{number_format($debt, 0, ',', '.')}}</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top"><strong>EQUITY</strong></td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    @if($capital>0)
    <tr>
      <td valign="top">Capital</td>
      <td valign="top" align="right">{{number_format($capital, 0, ',', '.')}}</td>
      <td valign="top">&nbsp;</td>
    </tr>
    @endif
    @if($retained_earnings>0)
    <tr>
      <td valign="top">Retained Earnings</td>
      <td valign="top" align="right">{{number_format($retained_earnings, 0, ',', '.')}}</td>
      <td valign="top">&nbsp;</td>
    </tr>
    @endif
    @if($earning>0)
    <tr>
      <td valign="top">Earnings</td>
      <td valign="top" align="right">{{number_format($earning, 0, ',', '.')}}</td>
      <td valign="top">&nbsp;</td>
    </tr>
    @endif
    <tr>
      <td align="right"></td>
      <td align="right"><hr  class="s1" /></td>
      <td align="right"></td>
    </tr>
    <tr>
      <td valign="top"><strong>TOTAL LIABILITIES AND EQUITY</strong></td>
      <td valign="top">&nbsp;</td>
      <td valign="top" align="right">{{number_format($total_liabilities_and_equity, 0, ',', '.')}}<hr class="s9" /></td>
    </tr>

  </tbody>
  
</table>
               
<style type="text/css">
    table
    {
      font-size: 16px;
    }

   .s9 {
    height:1px;
    border-top:1px solid ;
    border-bottom:1px solid ;
    background-color:white;
    margin:0 0 45px 0;
    max-width:600px;
  }
  .s1 {
    height:1px;
    background-color:white;
    margin:0 0 45px 0;
    max-width:600px;
    border-width:0;
    border-bottom:1px solid ;
  }
</style>     

 </center>    

</div>






</body>
</html>
