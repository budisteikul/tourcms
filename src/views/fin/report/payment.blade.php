@inject('fin', 'budisteikul\tourcms\Classes\FinClass')
@extends('coresdk::layouts.app')
@section('content')
@push('scripts')

@endpush

<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Payment Report</div>
                <div class="card-body">
                
                    <div class="row w-100">
                    <div class="col text-left">
                    {!! $fin::select_yearmonth_form($tahun,$bulan)  !!}
                    </div>
                    </div>



<div class="card mb-4">
<div class="card-header bg-info">STRIPE</div>
<div class="card-body p-0">
<table id="table1" border="0" cellspacing="1" cellpadding="2" class="m-0 table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table" >
  <thead>
    <tr>
      <td><strong>Transaction ID</strong></td>
      <td><strong>Amount</strong></td>
      <td><strong>Fee</strong></td>
      <td><strong>Net</strong></td>
    </tr>
  </thead>
  <tbody>
    @php
        $total_amount = 0;
        $total_fee = 0;
        $total_net = 0;
    @endphp
    @foreach($payments as $payment)
    @if($payment->payment_provider=="stripe")
    @php
        $amount = $payment->amount;
        $net = $payment->net;

        //if($net==0)
        //{
            //$contribution = $amount * 1 / 100;
            //$fee = ($amount * 4.4 / 100) + 0.30;
            //$net = $amount - $fee - $contribution;
        //}
        //else
        //{
            $fee = $amount - $net;
        //}

        if($payment->payment_status==5)
        {
            $amount = 0;
            $net = $amount - $fee;
        }

        
        $total_amount += $amount;
        $total_fee += $fee;
        $total_net += $net;
       
    @endphp
    <tr>
      <td>{{$payment->authorization_id}}</td>
      <td>{{ number_format($amount, 2, '.', '.') }}</td>
      <td>{{ number_format($fee, 2, '.', '.') }}</td>
      <td>{{ number_format($net, 2, '.', '.') }}</td>
    </tr>
    @endif
    @endforeach
    
    <tr>
     
      
      <td><b>TOTAL</b></td>
      <td><b>{{ number_format($total_amount, 2, '.', '.') }}</b></td>
      <td><b>{{ number_format($total_fee, 2, '.', '.') }}</b></td>
      <td><b>{{ number_format($total_net, 2, '.', '.') }}</b></td>
      
    </tr>
    
  </tbody>
</table>
</div>
</div>






<div class="card mb-4">
<div class="card-header bg-info">PAYPAL</div>
<div class="card-body p-0">
<table id="table1" border="0" cellspacing="1" cellpadding="2" class="m-0 table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table" >
  <thead>
    <tr>
      <td><strong>Transaction ID</strong></td>
      <td><strong>Amount</strong></td>
      <td><strong>Fee</strong></td>
      <td><strong>Net</strong></td>
    </tr>
  </thead>
  <tbody>
    @php
        $total_amount = 0;
        $total_fee = 0;
        $total_net = 0;
    @endphp
    @foreach($payments as $payment)
    @if($payment->payment_provider=="paypal")
    @php
        $amount = $payment->amount;
        $net = $payment->net;

        //if($net==0)
        //{
            //$fee = ($amount * 4.4 / 100) + 0.30;
            //$net = $amount - $fee;
        //}
        //else
        //{
            $fee = $amount - $net;
        //}

        

        $total_amount += $amount;
        $total_fee += $fee;
        $total_net += $net;
       
    @endphp
    <tr>
      <td>{{$payment->authorization_id}}</td>
      <td>{{ number_format($amount, 2, '.', '.') }}</td>
      <td>{{ number_format($fee, 2, '.', '.') }}</td>
      <td>{{ number_format($net, 2, '.', '.') }}</td>
      
    </tr>
    @endif
    @endforeach
    
    <tr>
     
      
      <td><b>TOTAL</b></td>
      <td><b>{{ number_format($total_amount, 2, '.', '.') }}</b></td>
      <td><b>{{ number_format($total_fee, 2, '.', '.') }}</b></td>
      <td><b>{{ number_format($total_net, 2, '.', '.') }}</b></td>
      
    </tr>
    
  </tbody>
</table>
</div>
</div>







<!-- div class="card mb-4">
<div class="card-header bg-info">XENDIT</div>
<div class="card-body p-0">
<table id="table1" border="0" cellspacing="1" cellpadding="2" class="m-0 table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table" >
  <thead>
    <tr>
      <td><strong>Transaction ID</strong></td>
      <td><strong>Amount</strong></td>
      <td><strong>Fee</strong></td>
      <td><strong>Net</strong></td>
    </tr>
  </thead>
  <tbody>
    @php
        $total_amount = 0;
        $total_fee = 0;
        $total_net = 0;
    @endphp
    @foreach($payments as $payment)
    @if($payment->payment_provider=="xendit")
    @php
        $amount = $payment->amount;
        $net = $payment->net;

        

        //if($net==0)
        //{
            //$fee_xendit = ($amount * 2.9 / 100) + 2000;
            //$vat = floor($fee_xendit * 11 / 100);
            
            //$fee = $fee_xendit + $vat;
            //$net = $amount - $fee;
        //}
        //else
        //{
            $fee = $amount - $net;
        //}

        if($payment->payment_status==5)
        {
            $amount = 0;
            $fee = 0;
            $net = 0;
        }

        $total_amount += $amount;
        $total_fee += $fee;
        $total_net += $net;
    @endphp
    <tr>
      <td>{{$payment->shoppingcart->confirmation_code}}</td>
      <td>{{ number_format($amount, 0, ',', '.') }}</td>
      <td>{{ number_format($fee, 0, ',', '.') }}</td>
      <td>{{ number_format($net, 0, ',', '.') }}</td>
    </tr>
    @endif
    @endforeach
    
    <tr>
     
      
      <td><b>TOTAL</b></td>
      <td><b>{{ number_format($total_amount, 0, ',', '.') }}</b></td>
      <td><b>{{ number_format($total_fee, 0, ',', '.') }}</b></td>
      <td><b>{{ number_format($total_net, 0, ',', '.') }}</b></td>
    </tr>
    
  </tbody>
</table>
</div>
</div -->



















                </div>
            </div>
        </div>
</div>


@endsection
