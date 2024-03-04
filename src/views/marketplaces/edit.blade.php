

<div class="h-100" style="width:99%">		

    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
                <div class="card-header pr-0">
                <div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        Edit Marketplace
                        </div>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                        <div class="btn-toolbar justify-content-end">
                            <button class="btn btn-sm btn-danger mr-0" type="button" onClick="CLOSE(); return false;"><i class="fa fa-window-close"></i> Close</button>
                        </div>
                    </div>
                </div>
                </div>
                <div class="card-body">
				
<form onSubmit="UPDATE(); return false;">
<div id="result"></div>

<div class="form-group">
    <label for="product_id">Product</label>
    <select class="form-control" id="product_id">
      @foreach($products as $product)
      <option value="{{ $product->id }}" {{ $product->id == $marketplace->product_id ? "selected" : "" }}>{{ $product->name }}</option>
      @endforeach
    </select>
</div>

<div class="form-group">
    <label for="name">Name</label>
    <select class="form-control" id="name">
      <option value="tripadvisor" {{ $marketplace->name == "tripadvisor" ? "selected" : "" }}>TripAdvisor</option>
      <option value="airbnb" {{ $marketplace->name == "airbnb" ? "selected" : "" }}>Airbnb</option>
      <option value="getyourguide" {{ $marketplace->name == "getyourguide" ? "selected" : "" }}>GetYourGuide</option>
    </select>
</div>

<div class="form-group">
	<label for="description">Link :</label>
    <input type="text" id="link" name="link" class="form-control" value="{{ $marketplace->link }}" placeholder="Link" autocomplete="off">
</div>

<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
</form>
</div>
</div>       




				
        </div>
    </div>

</div>

<script>


function CLOSE()
{
	$.fancybox.close();
}
</script>
<script language="javascript">
function UPDATE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = ["name","link","product_id"];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	

	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"name": $('#name').val(),
			"link": $('#link').val(),
			"product_id": $('#product_id').val()
        },
		type: 'PUT',
		url: '{{ route('route_tourcms_marketplaces.update',$marketplace->id) }}'
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