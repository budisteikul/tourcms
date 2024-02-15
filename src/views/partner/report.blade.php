@extends('coresdk::layouts.app')
@section('content')
<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Report Partner</div>
                <div class="card-body">
                
                @if(Auth::user()->id==1)
                <div class="row w-100">
                    <div class="col  text-left">
                    <a class="btn btn-primary"  href="{{ route('route_tourcms_partner.index') }}"><b class="fa fa-plus-square"></b> Partners</a>
                    </div>
                    
                </div>
                <hr>
                @endif
               
        {!! $dataTable->table(['class'=>'table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table']) !!}
        
                </div>
            </div>
        </div>
    </div>

{!! $dataTable->scripts() !!}

<script type="text/javascript">
  function SHOW(id)
  {
    $.fancybox.open({
          type: 'ajax',
          src: '{{ route('route_tourcms_booking.index') }}/'+ id,
          modal: true,
          touch: false,
          autoFocus: false
      }); 
  }
</script>
@endsection
