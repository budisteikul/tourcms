@inject('CategoryHelper', 'budisteikul\tourcms\Helpers\CategoryHelper')
@extends('coresdk::layouts.input-form',["mainTitle" => "Create Category"])
@section('content')
				
<form onSubmit="STORE(); return false;">

<div id="result"></div>

<div class="form-group">
	<label for="name">Name :</label>
	<input type="text" id="name" name="name" class="form-control" placeholder="Name" autocomplete="off">
</div> 

<div class="form-group">
	<label for="description">Desription :</label>
	<input type="text" id="description" name="description" class="form-control" placeholder="Description" autocomplete="off">
</div> 

<div class="form-group">
    <label for="parent_id">Parent</label>
    <select class="form-control" id="parent_id">
      <option value="0">No Parent</option>
      @foreach($categories as $category)
      <option value="{{ $category->id }}">{{ $CategoryHelper->nameCategory($category->id,"-") }}</option>
      @endforeach
    </select>
</div>

	
	<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
	</form>

<script language="javascript">
function STORE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = ["name","description"];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	
	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"name": $('#name').val(),
			"description": $('#description').val(),
			"parent_id": $('#parent_id').val(),
        },
		type: 'POST',
		url: '{{ route('route_tourcms_category.store') }}'
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