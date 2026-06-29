@extends('coresdk::layouts.page',['mainTitle'=>'Finished'])
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


</script>
@endpush


      
        <hr>
        
		{!! $dataTable->table(['class'=>'table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table']) !!}
		

{!! $dataTable->scripts() !!}


@endsection
