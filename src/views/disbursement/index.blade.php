@extends('coresdk::layouts.app')
@section('content')
@push('scripts')

<script type="text/javascript">
  	
  	function TRANSFER(id)
  	{
  		$("#btn-trans-"+ id).attr("disabled", true);
    	$("#btn-del-"+ id).attr("disabled", true);
    	$("#btn-trans-"+ id).html('<i class="fa fa-spinner fa-spin"></i>');
    	var table = $('#dataTableBuilder').DataTable();
    	$.ajax({
    		data: {
          	"_token": $("meta[name=csrf-token]").attr("content")
        	},
    		type: 'PUT',
    		url: "{{ route('route_tourcms_disbursement.index') }}/"+ id
    	}).done(function( data ) {
      		if(data.id=="1")
      		{
        		$("#btn-trans-"+ id).attr("disabled", false);
    			$("#btn-del-"+ id).attr("disabled", false);
        		$("#btn-trans-"+ id).html('<i class="far fa-money-bill-alt"></i> Transfer');
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
     						   url: '{{ route('route_tourcms_disbursement.index') }}/'+ id
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
       	 	src: '{{ route('route_tourcms_disbursement.create') }}',
			touch: false,
			modal: true,
   		});	

	}
	
	function SHOW(id)
  	{
    $.fancybox.open({
          type: 'ajax',
          src: '{{ route('route_tourcms_disbursement.index') }}/'+ id,
      modal: false,
      });
    
  	}
	</script>
@endpush
<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Disbursement</div>
                <div class="card-body">
        		
                <div class="container ml-0 pl-0">
                <div class="row">
                	<div class="col">
                    <button type="button" class="btn btn-secondary"  onclick="CREATE(); return false;"><b class="fa fa-plus-square"></b> Create Disbursement</button>
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
