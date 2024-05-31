@extends('coresdk::layouts.app')
@section('content')
@push('scripts')
<script type="text/javascript">
  function STATUS(id, status)
  {
    
    $("#capture-"+ id).attr("disabled", true);
    $("#void-"+ id).attr("disabled", true);
    if(status=="capture")
    {
      $("#capture-"+ id).html('<i class="fa fa-spinner fa-spin"></i>');
    }
    if(status=="void")
    {
      $("#void-"+ id).html('<i class="fa fa-spinner fa-spin"></i>');
    }
    var table = $('#dataTableBuilder').DataTable();
    $.ajax({
    data: {
          "_token": $("meta[name=csrf-token]").attr("content"),
          "update":status
        },
    type: 'PUT',
    url: "{{ route('route_tourcms_booking.index') }}/"+ id
    }).done(function( data ) {
      if(data.id=="1")
      {
        $("#capture-"+ id).attr("disabled", false);
        $("#void-"+ id).attr("disabled", false);
        $("#capture-"+ id).html('<i class="far fa-money-bill-alt"></i> Capture');
        $("#void-"+ id).html('<i class="far fa-money-bill-alt"></i> Void');
        table.ajax.reload( null, false );
      }
    });
  }

	function CREATE()
	{
		$.fancybox.open({
        	type: 'ajax',
       	 	src: '{{ route('route_tourcms_booking.create') }}',
			    modal: true,
          touch: false,
          autoFocus: false
   		});	
	}

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
<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Booking</div>
                <div class="card-body">
        		    
                <div class="row w-100">
                  @if(Auth::user()->id==1)
                    <div class="col  text-left">
                    <button type="button" class="btn btn-primary"  onclick="CREATE(); return false;"><b class="fa fa-plus-square"></b> Create Booking</button>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                    <a class="btn btn-secondary" href="{{route('route_tourcms_booking.index')}}/checkout"><i class="fas fa-shopping-cart"></i> Shopping Cart</a>
                    </div>
                  @endif
                </div>
   
       
      	
        <hr>
        
		{!! $dataTable->table(['class'=>'table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table']) !!}
		
                </div>
            </div>
        </div>
    </div>

{!! $dataTable->scripts() !!}

@endsection
