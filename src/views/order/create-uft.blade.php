

 
<div class="h-100" style="width:99%">		
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
             
	<div class="card-header pr-0">
                <div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        Create Order for Ubud Food Tour
                        </div>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                        <div class="btn-toolbar justify-content-end">
                            <button class="btn btn-sm btn-danger mr-0" type="button" onClick="$.fancybox.close();"><i class="fa fa-window-close"></i> Close</button>
                        </div>
                    </div>
                </div>
                </div>
	<div class="card-body">


				
<form onSubmit="STORE(); return false;">

<div id="result"></div>

<div class="form-group">   
				 <label for="datetimepicker1">Date :</label>           
                <div class='input-group' id='datetimepicker1'>
                    <input type="text" id="date" name="date" value="<?= date('Y-m-d') ?>" class="form-control bg-white" readonly>
                    <div class="input-group-append input-group-addon text-muted">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
 		<script type="text/javascript">
            $(function () {
                $('#date').datetimepicker({
					format: 'YYYY-MM-DD',
					showTodayButton: true,
					showClose: true,
					ignoreReadonly: true,
					icons: {
                    	time: "fa fa-clock"
                	},
					widgetPositioning: {
            			horizontal: 'left',
            			vertical: 'bottom'
        			},
				});
            });
        </script>    
</div>

<div class="form-group">
	<label for="rate">Rate :</label>
    <select class="form-control" id="rate" data-live-search="true">
       	<option value="1">Shared</option>
       	<option value="2">Private</option>
	</select>
</div>
<div class="form-group">
	<label for="pax">Pax :</label>
	<input type="number" id="pax" name="pax" class="form-control" placeholder="Pax" autocomplete="off" value="1">
</div> 
<div class="form-group">
	<label for="additional">Additional :</label>
	<input type="number" id="additional" name="additional" class="form-control" placeholder="Additional" autocomplete="off" value="0">
</div> 

	<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
</form>



	</div>
</div>       
		
        
        		
        </div>
    </div>

</div>

<script language="javascript">
function STORE()
{
	//if(app==1)
	//{
		var error = false;
		$("#submit").attr("disabled", true);
		$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
		var input = ["pax","date","additional","rate"];
	
		$.each(input, function( index, value ) {
  			$('#'+ value).removeClass('is-invalid');
  			$('#span-'+ value).remove();
		});
	
	

		$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"pax": $('#pax').val(),
			"date": $('#date').val(),
			"additional": $('#additional').val(),
			"rate": $('#rate').val(),
			"app": 5
			
        },
		type: 'POST',
		url: '{{ route('route_tourcms_orders.store') }}'
		}).done(function( data ) {
			
			if(data.id=="1")
			{
				
       				$('#dataTableBuilder').DataTable().ajax.reload( null, false );
					$("#result").empty().append('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Success!</b></div>').hide().fadeIn();
       				setTimeout(function (){
  						$.fancybox.close();
  						get_saldo();
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
	//}
	
}
</script>
