@inject('fin', 'budisteikul\tourcms\Classes\FinClass')
@extends('coresdk::layouts.app')
@section('content')
@push('scripts')
<script type="text/javascript">
$(function() {
    $('#table1').appendTo('#table2');
});
</script>
@endpush

@php
    $saldo_awal = $fin::calculate_saldo($tahun,$bulan);
    $saldo = $saldo_awal;
@endphp 
<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Bank</div>
                <div class="card-body">
                
                    <div class="row w-100">
                    <div class="col text-left">
                    {!! $fin::select_yearmonth_form($tahun,$bulan)  !!}
                    </div>
                    
                    </div>
<table id="table1" border="0" cellspacing="1" cellpadding="2" class="table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table mt-4" >
  <thead>
    <tr>
      <td width="20%"><strong>Date</strong></td>
      <td align="right"><strong>Debit</strong></td>
      <td align="right"><strong>Credit</strong></td>
      <td align="right"><strong>Balance</strong></td>
    </tr>
  </thead>
  <tbody>
    
    @php
    

    $total_debit = 0;
    $total_credit = 0;
    $total_tax = 0;

    @endphp
    @for($i=1;$i <= date("t",strtotime($tahun."-".$bulan."-01"));$i++)
    @php

      $date1 = \Carbon\Carbon::parse($tahun."-".$bulan."-".$i);
      if($date1->isPast())
      {

      $total_revenue_per_day = $fin::total_per_day_by_type('Revenue',$tahun,$bulan,$i);
      $total_cogs_per_day = $fin::total_per_day_by_type('Cost of Goods Sold',$tahun,$bulan,$i);
      $total_expenses_per_day = $fin::total_per_day_by_type('Expenses',$tahun,$bulan,$i);
      
      $saldo += $total_revenue_per_day;
      $saldo -= $total_cogs_per_day;
      $saldo -= $total_expenses_per_day;

      $debit = $total_revenue_per_day;
      $total_debit += $debit;

      $credit = $total_cogs_per_day + $total_expenses_per_day;
      $total_credit += $credit;

    @endphp
    <tr>
      <td>{{$i}} {{ date('F', mktime(0, 0, 0, $bulan, 10)); }} {{$tahun}}</td>
      <td align="right">{{number_format($debit, 0, ',', '.')}}</td>
      <td align="right">{{number_format($credit, 0, ',', '.')}}</td>
      <td align="right">{{number_format($saldo, 0, ',', '.')}}</td>
    </tr>
    @php
      }
    @endphp
    @endfor
    
  </tbody>
</table>

<table id="table2" border="0" cellspacing="1" cellpadding="2" class="table table-sm table-borderless table-responsive w-100 d-block d-md-table" >
  <tbody>
    <tr>
      <td><hr /></td>
    </tr>
    
    <tr>
      <td><b>Beginning balance :</b> {{number_format($saldo_awal, 0, ',', '.')}}</td>
    </tr>
    <tr>
      <td><b>Debit :</b> {{number_format($total_debit, 0, ',', '.')}}</td>
    </tr>
    <tr>
      <td><b>Credit :</b> {{number_format($total_credit, 0, ',', '.')}}</td>
    </tr>
    
    
    <tr>
      <td><b>Ending balance :</b> {{number_format($saldo, 0, ',', '.')}}</td>
    </tr>
    <tr>
      <td><hr /></td>
    </tr>
    
    
    
  </tbody>
</table>

 </div>
            </div>
        </div>
    </div>
@endsection
