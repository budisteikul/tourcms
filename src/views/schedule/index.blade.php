@extends('coresdk::layouts.app')
@section('content')
@push('scripts')
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

function EDIT(id)
  {
    $.fancybox.open({
          type: 'ajax',
          src: '{{ route('route_tourcms_schedule.index') }}/'+ id +'/edit',
          modal: true,
          touch: false,
          autoFocus: false
      });
    
  }

function EDIT_BOOKING(id)
  {
    $.fancybox.open({
          type: 'ajax',
          src: '{{ route('route_tourcms_booking.index') }}/question/'+ id +'/edit',
          modal: true,
          touch: false,
          autoFocus: false
      });
    
  }


  

</script>
@endpush
<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Schedule</div>
                <div class="card-body">
        		
                <div class="container ml-0 pl-0">
                
                </div>  
      
        <hr>
        
		{!! $dataTable->table(['class'=>'table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table']) !!}
		
                </div>
            </div>
        </div>
    </div>

{!! $dataTable->scripts() !!}

@endsection
