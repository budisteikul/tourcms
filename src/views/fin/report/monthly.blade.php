@inject('fin', 'budisteikul\tourcms\Classes\FinClass')
@inject('report', 'budisteikul\tourcms\Classes\ReportClass')
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
      
<canvas id="barChart" ></canvas>

    </div>
    
</div>


<div class="row mt-4">
    
    <div class="col-md-6">
     
<div class="card text-white bg-light mb-3 w-100">
  <div class="card-header bg-light text-dark">TRAVELLER</div>
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
</div>









  <div class="row mt-4">
    <div class="col-sm-auto">
      
<div class="card text-white bg-primary mb-3">
  <div class="card-header">{{ $fin->nameCategory(12,'-') }}</div>
  <div class="card-body">
    <h5 class="card-title">Total : IDR {{ number_format($fin->total_per_month(12,$tahun,$bulan,false), 0, ',', '.') }}</h5>
    <h5 class="card-title">Jalan : {{ number_format($fin->count_per_month(12,$tahun,$bulan,false), 0, ',', '.') }} kali</h5>
  </div>
</div>

    </div>
    <div class="col-sm-auto">
     
<div class="card text-white bg-success mb-3">
  <div class="card-header bg-success">{{ $fin->nameCategory(13,'-') }}</div>
  <div class="card-body">
    <h5 class="card-title">Total : IDR {{ number_format($fin->total_per_month(13,$tahun,$bulan,false), 0, ',', '.') }}</h5>
    <h5 class="card-title">Jalan : {{ number_format($fin->count_per_month(13,$tahun,$bulan,false), 0, ',', '.') }} kali</h5>
  </div>
</div>



    </div>
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
