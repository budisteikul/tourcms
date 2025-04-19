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
  Tanggal : {{ $date_name }}<br />
  Nama : {{ $guide_name }}<br />
  Total : {{$total}}<br />
  Jalan : {{$jalan}} kali
</div>

<center>



 <table id="table1" border="1" cellspacing="2" cellpadding="3" style="border-collapse: collapse; " >
  <thead>
    <tr>
      <td width="10"><strong>No</strong></td>
      <td ><strong>Tanggal</strong></td>
      <td align="right"><strong>Sub Total</strong></td>
    </tr>
  </thead>
  <tbody>
    @php
    $total = 0;
    $no = 0;
    @endphp
    @foreach($transactions as $transaction)
    @php
    $total += $transaction->amount;
    $no += 1;
    @endphp
    <tr>
      <td align="center">{{$no}}</td>
      <td>{{$GeneralHelper->dateFormat($transaction->date,4)}}</td>
      <td align="right">{{number_format($transaction->amount, 0, ',', '.')}}</td>
    </tr>
    @endforeach
    
    
    <tr>
      <td align="center" colspan="2"><strong>Total</strong></td>
      
      <td align="right">{{number_format($total, 0, ',', '.')}}</td>
    </tr>

  </tbody>
</table>  
 </center>    

</div>






</body>
</html>
