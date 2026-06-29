@extends('coresdk::layouts.input-form',["mainTitle" => "Create Revenue"])
@section('content')
				
<form onSubmit="STORE(); return false;">

<div id="result"></div>

<div class="form-group">   
				 <label for="datetimepicker1">Date :</label>           
                <div class='input-group' id='datetimepicker1'>
                    <input type="text" id="date" name="date" value="<?= $date ?>" class="form-control bg-white" readonly>
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
    <label for="app">Revenue</label>
    <select class="form-control" id="app">
      <option value="1">MARKETPLACE</option>
      <option value="2">WEBSITE</option>
      <option value="3">OFFLINE</option>
    </select>
</div>

<div class="form-group">
	<label for="amount">Amount :</label>
	<input type="number" step="0.01" id="amount" name="amount" class="form-control" placeholder="amount">
</div>





	
	<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
	</form>


<script language="javascript">
function STORE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = ["app","amount","date"];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	
	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"app": $('#app').val(),
			"amount": $('#amount').val(),
			"date": $('#date').val()
        },
		type: 'POST',
		url: '{{ route('route_tourcms_revenue.store') }}'
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
@endsection