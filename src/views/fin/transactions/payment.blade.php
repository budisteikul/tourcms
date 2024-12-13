@inject('fin', 'budisteikul\tourcms\Helpers\AccHelper')

<div class="h-100" style="width:99%">		
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
             
	<div class="card-header pr-0"><div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        Pending Transaction
                        </div>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                        <div class="btn-toolbar justify-content-end">
                            <button class="btn btn-sm btn-danger mr-0" type="button" onClick="$.fancybox.close();"><i class="fa fa-window-close"></i> Close</button>
                        </div>
                    </div>
                </div></div>
	<div class="card-body">
				
<form onSubmit="SET_DONE(); return false;">

<div id="result"></div>

<div class="card mb-2">
  <ul class="list-group list-group-flush">
  	@foreach($fin_transactions as $fin_transaction)
    <li class="list-group-item">{{ $fin->nameCategory($fin_transaction->category_id,"-") }} : {{ number_format($fin_transaction->amount, 0, ',', '.') }}</li>
    <input type="hidden" name="trans_id[]" value="{{$fin_transaction->id}}">
    @endforeach
    <li class="list-group-item">TOTAL : <b>{{ number_format($total, 0, ',', '.') }}</b></li>
  </ul>
</div>

    @if($total>0)   
	<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Set Done</button>
	@endif
	</form>
	</div>
</div>       
		
        
        		
        </div>
    </div>

</div>


<script language="javascript">


function SET_DONE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = [];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	
	var arr = $('input[name="trans_id[]"]').map(function () {
    	return this.value; // $(this).val()
	}).get();

	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
        	"trans_id": arr
        },
		type: 'POST',
		url: '{{ route('route_fin_transactions.index') }}/payment'
		}).done(function( data ) {
			
			if(data.id=="1")
			{
       				$('#dataTableBuilder').DataTable().ajax.reload( null, false );
					$("#result").empty().append('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Success!</b></div>').hide().fadeIn();
       				setTimeout(function (){
  						$.fancybox.close();
					}, 1000);
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
