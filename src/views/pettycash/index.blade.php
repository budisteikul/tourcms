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
     						   url: '{{ route('route_tourcms_pettycash.index') }}/'+ id
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

function get_button()
{

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
                <div class="card-header">Petty Cash</div>
                <div class="card-body">
 
<div class="row w-100">
                	<div class="col  text-left">
                   		<div style="font-size: 20px">Saldo : <span id="saldo"></span></div>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                    	<span id="button"></span>
                    </div>

</div>


                <div class="container ml-0 pl-0">
                <div class="row">
                	<div class="col">



                   

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
