@extends('coresdk::layouts.input-form',["mainTitle" => "Edit Transaction"])
@section('content')

				
<form onSubmit="UPDATE(); return false;">
<div id="result"></div>

<div class="form-group">   
				 <label for="datetimepicker1">Date :</label>           
                <div class='input-group' id='datetimepicker1'>
                    <input type="text" id="date" name="date" value="{{ $fin_transactions->date }}" class="form-control bg-white" readonly>
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
	<label for="category_id">Name :</label>
    <select class="form-control" id="category_id">
       @foreach($fin_categories as $fin_category)
       	<option value="{{ $fin_category->id }}" {{ ($fin_category->id==$fin_transactions->category_id) ? 'selected' : '' }}>{{ $fin_category->name }}</option>
       @endforeach
	</select>
</div>



<div class="form-group">
	<label for="amount">Amount :</label>
	<input type="number" step="0.01" id="amount" name="amount" class="form-control" value="{{ (float)$fin_transactions->amount }}" placeholder="amount">
</div>

<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
</form>

<script language="javascript">
function UPDATE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = ["category_id","date","amount"];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	
	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"date": $('#date').val(),
			"category_id": $('#category_id').val(),
			"amount": $('#amount').val()
        },
		type: 'PUT',
		url: '{{ route('route_fin_transactions.update',$fin_transactions->id) }}'
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