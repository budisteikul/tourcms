
<script language="javascript">
function UPDATE()
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
		type: 'PUT',
		url: '{{ route('route_tourcms_voucher.update',$voucher->id) }}'
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
                <div class="card-header">Edit voucher</div>
                <div class="card-body">
				
<form onSubmit="UPDATE(); return false;">
<div id="result"></div>

<div class="form-group">
	<label for="code">Code :</label>
	<input type="text" id="code" name="code" class="form-control" placeholder="Code" autocomplete="off" value="{{ $voucher->code }}">
</div> 

<div class="form-group">
	<label for="amount">Amount :</label>
	<input type="number" id="amount" name="amount" class="form-control" placeholder="Amount" autocomplete="off" value="{{ $voucher->amount }}">
</div> 

<div class="form-group">
    <label for="is_percentage">Percentage :</label>
    <select class="form-control" id="is_percentage">
      <option value="1" {{ $voucher->is_percentage == 1 ? "selected" : "" }}>Yes</option>
      <option value="0" {{ $voucher->is_percentage == 0 ? "selected" : "" }}>No</option>
    </select>
</div>

<div class="form-group">
    <label for="by_qty">Product :</label>
    @foreach($products as $product)
	<div class="form-check">
    	<input type="checkbox" class="form-check-input" name="products[]" value="{{ $product->id }}" id="product_{{ $product->id }}" {{ ($product->vouchers->contains($voucher->id)) ? 'checked' : '' }}>
    	<label class="form-check-label" for="product_{{ $product->id }}">{{ $product->name }}</label>
  	</div>
  	@endforeach
</div>

<button  class="btn btn-danger" type="button" onClick="$.fancybox.close();"><i class="fa fa-window-close"></i> Cancel</button>
<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
</form>
</div>
</div>       




				
        </div>
    </div>

</div>