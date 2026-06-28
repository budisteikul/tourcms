@inject('CategoryHelper', 'budisteikul\tourcms\Helpers\CategoryHelper')
@extends('coresdk::layouts.input-form',["mainTitle" => "Edit Category"])
@section('content')
				
<form onSubmit="UPDATE(); return false;">
<div id="result"></div>


<div class="form-group">
	<label for="name">Name :</label>
	<input type="text" id="name" name="name" class="form-control" placeholder="Name" autocomplete="off" value="{{ $category->name }}">
</div>

<div class="form-group">
	<label for="description">Description :</label>
	<input type="text" id="description" name="description" class="form-control" placeholder="Description" autocomplete="off" value="{{ $category->description }}">
</div>

  <div class="form-group">
    <label for="parent_id">Parent</label>
    <select class="form-control" id="parent_id">
      <option value="0">No Parent</option>
      @foreach($categories as $parent_category)
      <option value="{{ $parent_category->id }}" {{  ($parent_category->id == $category->parent_id) ? "selected" : "" }}>{{ $CategoryHelper->nameCategory($parent_category->id,"-") }}</option>
      @endforeach
    </select>
  </div>
     

<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
</form>


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
	

	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"name": $('#name').val(),
			"parent_id": $('#parent_id').val(),
			"description": $('#description').val(),
        },
		type: 'PUT',
		url: '{{ route('route_tourcms_category.update',$category->id) }}'
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