<style>
     .fancybox-container {
         z-index: 10000 !important;
     }
</style>
<script type="text/javascript">
$( document ).ready(function() {
	search_vendor();
});

function search_vendor()
{
	$('#vendor_name').autocomplete({
    	serviceUrl: '{{ route('route_tourcms_disbursement.index') }}/search/vendor',
    	minChars: 2,
    	onSelect: function (suggestion) {
    		$('#vendor_name').val(suggestion.value);
    		$('#vendor_id').val(suggestion.data);
    	}
	});
	$('.autocomplete-suggestions').css('position', 'fixed');
	$('.autocomplete-suggestions').css('z-index', 10000);
}


function STORE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = ["vendor_id","amount","reference"];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	
	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"vendor_id": $('#vendor_id').val(),
			"amount": $('#amount').val(),
			"reference": $('#reference').val(),
			
        },
		type: 'POST',
		url: '{{ route('route_tourcms_disbursement.store') }}'
		}).done(function( data ) {
			
			if(data.id=="1")
			{
				
       				$('#dataTableBuilder').DataTable().ajax.reload( null, false );
					$.fancybox.close();	
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
 
<div class="h-100" style="width:99%">		
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
             
	<div class="card-header">Create Disbursement</div>
	<div class="card-body">
				
<form onSubmit="STORE(); return false;">

<div id="result"></div>

<div class="form-group">
	<label for="vendor_name">Vendor Name :</label>
	<input type="text" id="vendor_name" name="reference" class="form-control" placeholder="Vendor Name" autocomplete="off">
	<input type="hidden" id="vendor_id" name="vendor_id">
</div>

<div class="form-group">
	<label for="amount">Amount :</label>
	<input type="number" id="amount" name="amount" class="form-control" placeholder="10000" autocomplete="off">
</div>


<div class="form-group">
	<label for="reference">Reference :</label>
	<input type="text" id="reference" name="reference" value="{{ env('APP_NAME') }}" class="form-control" placeholder="Reference" autocomplete="off">
</div>


	<button  class="btn btn-danger" type="button" onClick="$.fancybox.close();"><i class="fa fa-window-close"></i> Cancel</button>
	<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
	</form>
	</div>
</div>       
		
        
        		
        </div>
    </div>

</div>