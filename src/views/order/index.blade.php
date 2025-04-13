@extends('coresdk::layouts.app')
@section('content')
@push('scripts')
<script type="text/javascript">
  

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
     						   url: '{{ route('route_tourcms_orders.index') }}/'+ id
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
	
	function CREATE(app)
	{
		if(app==1)
		{
			$.fancybox.open({
        		type: 'ajax',
       	 		src: '{{ route('route_tourcms_orders.create') }}/jnft',
				modal: true,
          		touch: false,
          		autoFocus: false
   			});	
		}
		else if(app==2)
		{
			$.fancybox.open({
        		type: 'ajax',
       	 		src: '{{ route('route_tourcms_orders.create') }}/jmft',
				modal: true,
          		touch: false,
          		autoFocus: false
   			});	
		}
		else if(app==3)
		{
			$.fancybox.open({
        		type: 'ajax',
       	 		src: '{{ route('route_tourcms_orders.create') }}/tat',
				modal: true,
          		touch: false,
          		autoFocus: false
   			});	
		}
		else if(app==4)
		{
			$.fancybox.open({
        		type: 'ajax',
       	 		src: '{{ route('route_tourcms_orders.create') }}/dft',
				modal: true,
          		touch: false,
          		autoFocus: false
   			});	
		}
		
	}
	
	

  function SHOW()
  {
    $.fancybox.open({
          type: 'ajax',
          src: '{{ route('route_tourcms_orders.index') }}/structure',
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
                <div class="card-header">Orders</div>
                <div class="card-body">
        		
                <div class="container ml-0 pl-0">
                <div class="row">
                	<div class="col">

                    <button type="button" class="btn btn-primary"  onclick="CREATE(1); return false;"><b class="fa fa-plus-square"></b> Create Order JNFT</button>
                    
                    <button type="button" class="btn btn-primary"  onclick="CREATE(2); return false;"><b class="fa fa-plus-square"></b> Create Order JMFT</button>

                    <button type="button" class="btn btn-primary"  onclick="CREATE(3); return false;"><b class="fa fa-plus-square"></b> Create Order Taman Anyar Tour</button>

                    <button type="button" class="btn btn-primary"  onclick="CREATE(4); return false;"><b class="fa fa-plus-square"></b> Create Order Denpasar Tour</button>

                    </div>
                    
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
