@inject('AccHelper', 'budisteikul\tourcms\Helpers\AccHelper')
@extends('coresdk::layouts.input-form',["mainTitle" => "Edit Category"])
@section('content')

				
<form onSubmit="UPDATE(); return false;">
<div id="result"></div>

<div class="form-group">
	<label for="name">Name :</label>
	<input type="text" id="name" name="name" class="form-control" placeholder="name" value="{{ $category->name }}">
</div>

<div class="form-group">
    <label for="parent_id">Parent</label>
    <select class="form-control" id="parent_id">
      <option value="0">No Parent</option>
      @foreach($categories as $parent_category)
      <option value="{{ $parent_category->id }}" {{  ($parent_category->id == $category->parent_id) ? "selected" : "" }}>{{ $parent_category->name }} ({{ $parent_category->type }})</option>
      @endforeach
    </select>
  </div>

<div class="form-group" id="form-type">
	<label for="type">Type :</label>
    <select class="form-control" id="type">
      <option value="Expenses" {{ ($category->type=='Expenses') ? 'selected' : '' }}>Expenses</option>
      <option value="Revenue" {{ ($category->type=='Revenue') ? 'selected' : '' }}>Revenue</option>
      <option value="Cost of Goods Sold" {{ ($category->type=='Cost of Goods Sold') ? 'selected' : '' }}>Cost of Goods Sold</option>
      <option value="Capital" {{ ($category->type=='Capital') ? 'selected' : '' }}>Capital</option>
      <option value="Debt" {{ ($category->type=='Debt') ? 'selected' : '' }}>Debt</option>
      <option value="Receivable" {{ ($category->type=='Receivable') ? 'selected' : '' }}>Receivable</option>
	</select>
</div>
     
<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
</form>

<script language="javascript">
$(function() {
	if($('#parent_id').val()>0)
 	{
 		$('#form-type').hide();
 	}
 	else
 	{
 		$('#form-type').show();
 	}
});

$('#parent_id').on('change', function() {
 if(this.value>0)
 {
 	$('#form-type').hide();
 }
 else
 {
 	$('#form-type').show();
 }
  
});

function UPDATE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = ["name","type"];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	
	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"name": $('#name').val(),
			"type": $('#type').val(),
			"parent_id": $('#parent_id').val(),
        },
		type: 'PUT',
		url: '{{ route('route_fin_categories.update',$category->id) }}'
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