@inject('GeneralHelper', 'budisteikul\tourcms\Helpers\GeneralHelper')
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
<div style="margin-top: 30px; margin-bottom: 40px; font-weight: bold; text-align: left; font-size:14px">
  Date : {{ $date_name }}<br />
  Name : {{ $guide_name }}<br />
  Total Fee : {{number_format($salary->fee - $cash_advance->total, 0, ',', '.')}}<br />
  On duty : {{number_format($salary->count, 0, ',', '.')}}
</div>

<center>
<div style="text-align:left"><h3>Fee</h3></div>
<table id="table1" border="1" cellspacing="2" cellpadding="3" style="border-collapse: collapse; " >
  <thead>
    <tr>
      <td width="10"><strong>No</strong></td>
      <td ><strong>Date</strong></td>
      <td ><strong>Note</strong></td>
      <td align="right"><strong>Sub Total</strong></td>
    </tr>
  </thead>
  <tbody>
    @php
    $total = 0;
    $no = 0;
    @endphp
    @foreach($salary->query as $x)
    @php
    $total += $x->fee;
    $no += 1;
    @endphp
    <tr>
      <td align="center">{{$no}}</td>
      <td>{{$GeneralHelper->dateFormat($x->date,4)}}</td>
      <td>{{$x->note}}</td>
      <td align="right">{{number_format($x->fee, 0, ',', '.')}}</td>
    </tr>
    @endforeach
    
    
    <tr>
      <td align="center" colspan="3"><strong>Total</strong></td>
      
      <td align="right">{{number_format($total, 0, ',', '.')}}</td>
    </tr>

  </tbody>
</table>
<br />
<br />
@if($cash_advance->total>0)
<div style="text-align:left"><h3>Fee Deductions</h3></div>
<table id="table1" border="1" cellspacing="2" cellpadding="3" style="border-collapse: collapse; " >
  <thead>
    <tr>
      <td width="10"><strong>No</strong></td>
      <td ><strong>Date</strong></td>
      <td ><strong>Note</strong></td>
      <td align="right"><strong>Sub Total</strong></td>
    </tr>
  </thead>
  <tbody>
    @php
    $total = 0;
    $no = 0;
    @endphp
    @foreach($cash_advance->query as $x)
    @php
    $total += $x->total;
    $no += 1;
    @endphp
    <tr>
      <td align="center">{{$no}}</td>
      <td>{{$GeneralHelper->dateFormat($x->date,4)}}</td>
      <td>{{$x->note}}</td>
      <td align="right">{{number_format($x->total, 0, ',', '.')}}</td>
    </tr>
    @endforeach
    
    
    <tr>
      <td align="center" colspan="3"><strong>Total</strong></td>
      
      <td align="right">{{number_format($total, 0, ',', '.')}}</td>
    </tr>

  </tbody>
</table>
<br />
<br />
@endif
</center>    

</div>






</body>
</html>
