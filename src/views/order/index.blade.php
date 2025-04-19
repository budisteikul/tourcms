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
							         get_saldo();
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
		else if(app==5)
		{
			$.fancybox.open({
        		type: 'ajax',
       	 		src: '{{ route('route_tourcms_orders.create') }}/uft',
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

$(function() {
    get_saldo();
});

function get_saldo()
{
	$.get("{{ route('route_tourcms_pettycash.index') }}/saldo", function(data, status){
    	$('#saldo').html(data.pettycash_saldo);
    	$('#button').html(data.button);
  	});
}

function SET_DONE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	
	

	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content")
        },
		type: 'POST',
		url: '{{ route('route_tourcms_pettycash.store') }}'
		}).done(function( data ) {
			
			if(data.id=="1")
			{
						get_saldo();
       					$('#dataTableBuilder').DataTable().ajax.reload( null, false );
  						$("#submit").attr("disabled", true);
						$('#submit').html('{{ __('Top up') }}');
						
			}
			else
			{
				$.each( data, function( index, value ) {
					$('#'+ index).addClass('is-invalid');
						if(value!="")
						{
							$('#'+ index).after('<span id="span-'+ index  +'" class="invalid-feedback" role="alert"><strong>'+ value +'</strong></span>');
						}
					});
				$("#submit").attr("disabled", false);
				$('#submit').html('<i class="fa fa-save"></i> {{ __('Save') }}');
			}
		});
	
	
	return false;
}
	</script>
@endpush
<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Orders</div>
                <div class="card-body">

<div class="row w-100">
                	<div class="col text-left">
<div class="col-md-6">
                   		<div class="form-group">

    <select class="form-control" id="app" data-live-search="true">
       	<option value="1">Jogja Night Food Tour</option>
       	<option value="2">Jogja Morning Food Tour</option>
       	<option value="3">Taman Anyar Tour</option>
       	<option value="4">Denpasar Food Tour</option>
       	<option value="5">Ubud Food Tour</option>
	</select>
	
</div>
<button   onclick="CREATE($('#app').val()); return false;" id="create" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Create</button>
</div>

                    </div>
                    <div class="col-auto text-right mr-0 pr-0"  style="font-size: 25px">
                    	Saldo : <span id="saldo"></span>
                    	<br />
                    	<span id="button"></span>
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
