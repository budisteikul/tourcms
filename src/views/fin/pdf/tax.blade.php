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
<div style="margin-top: 30px; margin-bottom: 40px; font-weight: bold; text-align: center; font-size:22px">
  Payment List PP23 {{env('APP_NAME')}} For Year {{$tahun}}
</div>

<center>



 <table id="table1" border="1" cellspacing="2" cellpadding="3" style="border-collapse: collapse; " >
  <thead>
    <tr>
      <td width="10"><strong>NO</strong></td>
      <td ><strong>MONTH</strong></td>
      <td align="right"><strong>DPP</strong></td>
      <td align="right"><strong>PPH</strong></td>
    </tr>
  </thead>
  <tbody>
    
    @for($i=1;$i <= 12; $i++)
    <tr>
      <td align="center">{{$i}}</td>
      <td>{{$data->month_text[$i]}}</td>
      <td align="right">{{$data->revenue[$i]}}</td>
      <td align="right">{{$data->tax[$i]}}</td>
    </tr>
    @endfor
    
    <tr>
      <td align="center" colspan="2"><strong>TOTAL</strong></td>
      
      <td align="right">{{$data->dpp_total}}</td>
      <td align="right">{{$data->pph_total}}</td>
    </tr>

  </tbody>
</table>  
 </center>    

</div>






</body>
</html>
