
<script language="javascript">
function STORE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = ["user","text"];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	
	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"product_id": $('#product_id').val(),
			"user": $('#user').val(),
			"title": $('#title').val(),
			"text": $('#text').val(),
			"date": $('#date').val(),
			"rating": $('#rating').val(),
			"channel_id": $('#channel_id').val(),
			"link": $('#link').val(),
        },
		type: 'POST',
		url: '{{ route('route_tourcms_review.store') }}'
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
 
<div class="h-100" style="width:99%">		
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
             
	<div class="card-header pr-0">
                <div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        Add Review
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
	<label for="product_id">Product :</label>
    <select class="form-control" id="product_id">
       @foreach($products as $product)
       	<option value="{{ $product->id }}">{{ $product->name }}</option>
       @endforeach
	</select>
</div>

<div class="form-group">   
				 <label for="datetimepicker1">Date :</label>           
                <div class='input-group' id='datetimepicker1'>
                    <input type="text" id="date" name="date" value="<?= date('Y-m-d 00:00:00') ?>" class="form-control bg-white" readonly>
                    <div class="input-group-append input-group-addon text-muted">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    
                </div>
 		<script type="text/javascript">
            $(function () {
                $('#date').datetimepicker({
					format: 'YYYY-MM-DD 00:00:00',
					showTodayButton: true,
					showClose: true,
					ignoreReadonly: true,
					icons: {
                    	time: "fa fa-clock"
                	},
					widgetPositioning: {
            			horizontal: 'left',
            			vertical: 'bottom'
        			}
				});
            });
        </script>    
</div>

<div class="form-group">
	<label for="channel_id">Channel :</label>
    <select class="form-control" id="channel_id">
       @foreach($channels as $channel)
       	<option value="{{ $channel->id }}">{{ $channel->name }}</option>
       @endforeach
	</select>
</div>


<div class="form-group">
	<label for="rating">Rating :</label>
    <select class="form-control" id="rating">
      <option value="5">5</option>
      <option value="4">4</option>
      <option value="3">3</option>
      <option value="2">2</option>
      <option value="1">1</option>
	</select>
</div>

<div class="form-group">
	<label for="user">User :</label>
	<input type="text" id="user" name="user" class="form-control" placeholder="User" autocomplete="off">
</div> 

<div class="form-group">
	<label for="title">Title :</label>
	<input type="text" id="title" name="title" class="form-control" placeholder="Title">
</div>

<div class="form-group">
	<label for="text">Text :</label>
    <textarea class="form-control" id="text" name="text" rows="5" placeholder="Text"></textarea>
</div>

<div class="form-group">
	<label for="link">Link :</label>
	<input type="text" id="link" name="link" class="form-control" placeholder="Link" value="">
</div>

	<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
	</form>
	</div>
</div>       
		
        
        		
        </div>
    </div>

</div>