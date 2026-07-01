@inject('fin', 'budisteikul\tourcms\Helpers\AccHelper')
@extends('coresdk::layouts.page',['mainTitle'=>'Miscellaneous Expenses'])
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
                    <div class="col-auto text-right mr-0 pr-0">
                   		<a class="btn btn-primary text-white" href="{{ route('route_tourcms_expenses.index') }}"><i class="fas fa-random"></i> General expense</a>
                    </div>


</div>

               
       
      	
        <hr>
        
		{!! $dataTable->table(['class'=>'table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table']) !!}
		
              

{!! $dataTable->scripts() !!}

@endsection
