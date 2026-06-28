@extends('coresdk::layouts.input-form',["mainTitle" => "Create Voucher"])
@section('content')
				
<form onSubmit="STORE(); return false;">

<div id="result"></div>

<div class="form-group">
	<label for="code">Code :</label>
	<input type="text" id="code" name="code" class="form-control" placeholder="Code" autocomplete="off">
</div> 

<div class="form-group">
	<label for="amount">Amount :</label>
	<input type="number" id="amount" name="amount" class="form-control" placeholder="Amount" autocomplete="off">
</div> 

<div class="form-group">
    <label for="is_percentage">Percentage :</label>
    <select class="form-control" id="is_percentage">
      <option value="1">Yes</option>
      <option value="0">No</option>
    </select>
</div>

<div class="form-group">
    <label for="by_qty">Product :</label>
    @foreach($products as $product)
	<div class="form-check">
    	<input type="checkbox" class="form-check-input" name="products[]" value="{{ $product->id }}" id="product_{{ $product->id }}">
    	<label class="form-check-label" for="product_{{ $product->id }}">{{ $product->name }}</label>
  	</div>
  	@endforeach
</div>

	<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
	</form>

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
	
	var products = $('input[name="products\\[\\]"]:checked').map(function(i, elem) { return $(this).val(); }).get();

	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"code": $('#code').val(),
			"amount": $('#amount').val(),
			"is_percentage": $('#is_percentage').val(),
			"products": products,
        },
		type: 'POST',
		url: '{{ route('route_tourcms_voucher.store') }}'
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