@inject('fin', 'budisteikul\tourcms\Helpers\AccHelper')
@inject('report', 'budisteikul\tourcms\Helpers\ReportHelper')
@inject('productHelper', 'budisteikul\tourcms\Helpers\ProductHelper')
@extends('coresdk::layouts.app')
@section('content')
@push('scripts')
<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>
@endpush

<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Monthly Report</div>
                <div class="card-body">
                
                    <div class="row w-100">
                    <div class="col text-left">
                    {!! $fin::select_yearmonth_form($tahun,$bulan)  !!}
                    </div>
                    </div>

<div class="row mt-4">
    <div class="col-md-6">

<div class="card h-100 border-0">      
<canvas id="barChart" ></canvas>
</div>

    </div>


      
<div class="col-md-6">
<div class="card text-white bg-light mb-3 w-100 h-100">
<div class="card-header bg-light text-dark"><b>BOOKING</b></div>
<div class="card-body bg-light text-dark">
@php
$total_booking = 0;  
foreach($bookings as $booking)
{
    
    print(''.$booking->booking_channel.' : '. $booking->total .' <br />');
    $total_booking += $booking->total;
}
@endphp
</div>
<div class="card-footer bg-light text-dark">Total Booking : {{$total_booking}}</div>
</div>
</div>

    
    
</div>


<div class="row mt-4 ">
    
<div class="col-md-6">
<div class="card text-white bg-light mb-3 w-100 h-100">
<div class="card-header bg-light text-dark"><b>TRAVELLER BY PRODUCT</b></div>
<div class="card-body bg-light text-dark">
@php
$total_tamu = 0;    
foreach($products as $product)
{
    $tamu = $product->count;
    $total_tamu += $tamu;
    print(''.$product->title.' : '. $tamu .' persons <br />');
}
@endphp
</div>
<div class="card-footer bg-light text-dark">Total Traveller : {{$total_tamu}}</div>
</div>




    </div>


<div class="col-md-6">
<div class="card text-white bg-light mb-3 w-100 h-100">
<div class="card-header bg-light text-dark"><b>TRAVELLER BY BOOKING</b></div>
<div class="card-body bg-light text-dark">
@php
$total_tamu = 0;    
foreach($traveler_booking_per_months as $traveler_booking_per_month)
{
    
    print(''.$traveler_booking_per_month->booking_channel.' : '. $traveler_booking_per_month->total .' persons <br />');
    $total_tamu += $traveler_booking_per_month->total;
}
@endphp
</div>
<div class="card-footer bg-light text-dark">Total Traveller : {{$total_tamu}}</div>
</div>
</div>







</div>














  <div class="row mt-4">
    
@foreach($guides as $guide)
    <div class="col-sm-auto">
      
<div class="card text-white bg-primary mb-3">
  <div class="card-header">{{ $fin->nameCategory($guide->id,'-') }}</div>
  <div class="card-body">
    <h5 class="card-title">Total : IDR {{ number_format($fin->total_per_month($guide->id,$tahun,$bulan,false), 0, ',', '.') }}</h5>
    <h5 class="card-title">Order : {{ number_format($fin->count_per_month($guide->id,$tahun,$bulan,false), 0, ',', '.') }} </h5>
  </div>
  <div class="card-footer">
    <h5><a href="{{ route('route_tourcms_salary.index') }}?id={{$guide->id}}&date={{$tahun}}-{{ $bulan }}"><i class="far fa-file-pdf"></i> Download</a></h5>
  </div>
</div>

    </div>
@endforeach


    

  </div>









                </div>
            </div>
        </div>
</div>


<script language="javascript">
var ctx = document.getElementById("barChart").getContext('2d');
var barChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($tgl); ?>,
    datasets: [{
      label: 'Traveller',
      data: <?php echo json_encode($traveller); ?>,
      backgroundColor: "rgba(54, 162, 235, 0.2)",
      borderWidth: 1
    }]
  }
});
</script>
@endsection
