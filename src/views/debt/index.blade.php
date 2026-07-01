@inject('fin', 'budisteikul\tourcms\Helpers\AccHelper')
@extends('coresdk::layouts.page',['mainTitle'=>'Fee and Deduction'])
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
     						   url: '{{ route('route_tourcms_debt.index') }}/'+ id
						        }).done(function( msg ) {
						        	get_fee();
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
		
			$.fancybox.open({
        		type: 'ajax',
       	 		src: '{{ route('route_tourcms_debt.create') }}',
				modal: true,
          		touch: false,
          		autoFocus: false
   			});	
		
		
	}
	
	$(function() {
    	get_fee();
	});

	function get_fee()
	{
		$.get("{{ route('route_tourcms_debt.index') }}/fee?date={{$tahun}}-{{$bulan}}", function(data, status){
    		$('#view').html(data.view);
  		});
	}

  
	</script>
@endpush



<div class="row w-100">
                	<div class="col  text-left">
                   		{!! $fin::select_yearmonth_form($tahun,$bulan)  !!}
                    </div>
                    
                	</div>
                	<hr />        		

        		<div class="row w-100">
                	<div class="col  text-left">
                   		<button   onclick="CREATE(); return false;" id="create" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Create</button>
                    </div>
                    


</div>

               
       
      	
        <hr>
        
		{!! $dataTable->table(['class'=>'table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table']) !!}
		
<hr>              

<div id="view"></div>

<hr>

{!! $dataTable->scripts() !!}

@endsection
