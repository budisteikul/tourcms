
<script language="javascript">
function STORE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = ["name"];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	
	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"name": $('#name').val(),
			"contact_person": $('#contact_person').val(),
			"phone": $('#phone').val(),
			"email": $('#email').val(),
			"bank_code": $('#bank_code').val(),
			"account_number": $('#account_number').val(),
			
        },
		type: 'POST',
		url: '{{ route('route_tourcms_vendor.store') }}'
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
             
	<div class="card-header">Create Vendor</div>
	<div class="card-body">
				
<form onSubmit="STORE(); return false;">

<div id="result"></div>

<div class="form-group">
	<label for="name">Name :</label>
	<input type="text" id="name" name="name" class="form-control" placeholder="Name" autocomplete="off">
</div>

<div class="form-group">
	<label for="contact_person">Contact person :</label>
	<input type="text" id="contact_person" name="contact_person" class="form-control" placeholder="Contact person" autocomplete="off">
</div>

<div class="form-group">
	<label for="phone">Phone :</label>
	<input type="text" id="phone" name="phone" class="form-control" placeholder="Phone" autocomplete="off">
</div>

<div class="form-group">
	<label for="email">Email :</label>
	<input type="text" id="email" name="email" class="form-control" placeholder="Email" autocomplete="off">
</div>  

<div class="form-group">
	<label for="bank_code">Bank code :</label>
	<input type="text" id="bank_code" name="bank_code" class="form-control" placeholder="Bank code" autocomplete="off">
</div> 

<div class="form-group">
	<label for="account_number">Account number :</label>
	<input type="text" id="account_number" name="account_number" class="form-control" placeholder="Account number" autocomplete="off">
</div> 



	<button  class="btn btn-danger" type="button" onClick="$.fancybox.close();"><i class="fa fa-window-close"></i> Cancel</button>
	<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
	</form>
	</div>
</div>       
		
        
        		
        </div>
    </div>

</div>