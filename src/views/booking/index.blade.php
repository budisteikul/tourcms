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

	function DELETE(id)
	{
		$.confirm({
    		title: 'Warning',
    		content: 'Are you sure?',
    		type: 'red',
			icon: 'fa fa-trash',
    		buttons: {   
        		ok: {
            		text: "OK",
            		btnClass: 'btn-danger',
            		keys: ['enter'],
            		action: function(){
                 		 var table = $('#dataTableBuilder').DataTable();
							       $.ajax({
							       beforeSend: function(request) {
    							     request.setRequestHeader("X-CSRF-TOKEN", $("meta[name=csrf-token]").attr("content"));
  						      },
     						   type: 'DELETE',
     						   url: '{{ route('route_tourcms_booking.index') }}/'+ id
						        }).done(function( msg ) {
							         table.ajax.reload( null, false );
						        });	
            		}
        		},
        		cancel: function(){
                	console.log('the user clicked cancel');
        		}
    		}
		});
		
	}
	
	function CREATE()
	{
		$.fancybox.open({
        	type: 'ajax',
       	 	src: '{{ route('route_tourcms_booking.create') }}',
			touch: false,
			modal: false,
   		});	
	}
	
	function CANCEL(id)
  {
    $.confirm({
        title: 'Warning',
        content: 'Are you sure?',
        type: 'orange',
      icon: 'fa fa-ban',
        buttons: {   
            ok: {
                text: "OK",
                btnClass: 'btn-warning',
                keys: ['enter'],
                action: function(){
                     var table = $('#dataTableBuilder').DataTable();
                     $.ajax({
                        data: {
                          "_token": $("meta[name=csrf-token]").attr("content"),
                          "action": 'cancel',
      
                        },
                        type: 'PUT',
                        url: '{{ route('route_tourcms_booking.index') }}/'+ id
                        }).done(function( data ) {
                          table.ajax.reload( null, false );
                        });

                }
            },
            cancel: function(){
                  console.log('the user clicked cancel');
            }
        }
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
                  <div class="col  text-left">
                    <button type="button" class="btn btn-secondary"  onclick="CREATE(); return false;"><b class="fa fa-plus-square"></b> Create Booking</button>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                    <a class="btn btn-success" href="{{route('route_tourcms_booking.index')}}/checkout"><i class="fas fa-shopping-cart"></i> Shopping Cart</a>
                    </div>
                </div>
   
       
      	
        <hr>
        
		{!! $dataTable->table(['class'=>'table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table']) !!}
		
                </div>
            </div>
        </div>
    </div>

{!! $dataTable->scripts() !!}

@endsection
