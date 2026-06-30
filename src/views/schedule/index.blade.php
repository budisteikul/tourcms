@extends('coresdk::layouts.page',['mainTitle'=>'Schedule'])
@section('content')

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
<style>
  font-color:#94a2b8;
</style>

       
               
        <hr>
     
		{!! $dataTable->table(['class'=>'table table-sm table-bordered table-hover table-striped table-responsive w-100 d-md-table']) !!}
  
		
               

{!! $dataTable->scripts() !!}


@endsection
