@inject('fin', 'budisteikul\tourcms\Helpers\AccHelper')
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
                <div class="card-header">Assets</div>
                <div class="card-body">
                
                
{!! $fin::select_profitloss_form($tahun)  !!}                
                

<div class="row mt-4">
    <div class="col-md-6">
      
<canvas id="barChart" ></canvas>

    </div>
    
</div>


 </div>
            </div>
        </div>
    </div>

<script language="javascript">
var ctx = document.getElementById("barChart").getContext('2d');
var barChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($bulan); ?>,
    datasets: [{
      label: 'Asset',
      data: <?php echo json_encode($saldo); ?>,
      backgroundColor: "rgb(245,41,41,0.5)",
      borderColor: "rgb(245,41,41)"
    }]
  }
});
</script>
@endsection
