@extends('coresdk::layouts.input-form',["mainTitle" => "Create Channel"])
@section('content')
				
<form onSubmit="STORE(); return false;">

<div id="result"></div>

<div class="form-group">
	<label for="name">Name :</label>
	<input type="text" id="name" name="name" class="form-control" placeholder="Name" autocomplete="off">
</div> 

<div class="form-group">
	<label for="description">Description :</label>
    <textarea class="form-control tinymce" id="description" name="description" rows="3"></textarea>
</div>


<div class="form-group">
    <label for="invoice">Invoice</label>
    <select class="form-control" id="invoice">
      
      
      <option value="1">Before the tour</option>
      <option value="2">After the tour</option>
      
    </select>
  </div>

<div class="form-group">
<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" id="can_review" checked>
  <label class="form-check-label" for="can_review">
    Can review
  </label>
</div>

<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" id="can_booking" checked>
  <label class="form-check-label" for="can_booking">
    Can booking
  </label>
</div>
</div>


	<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
	</form>
<script>
$( document ).ready(function() {
    	tinymce.init({
  		selector: 'textarea.tinymce',
 		height: 200,
  		menubar: false,
  		plugins: [
    	'advlist autolink lists link image charmap print preview anchor',
    	'searchreplace visualblocks code fullscreen',
    	'insertdatetime media table paste code help wordcount'
  		],
  		toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
  		content_css: [
    	'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    	'//www.tiny.cloud/css/codepen.min.css'
  		]
		});	
});

function CLOSE()
{
	tinymce.remove();
	$.fancybox.close();
}
</script>

<script language="javascript">
function STORE()
{
	tinymce.triggerSave();
	
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
			"invoice": $('#invoice').val(),
			"can_review": $('#can_review').is(':checked'),
			"can_booking": $('#can_booking').is(':checked')
        },
		type: 'POST',
		url: '{{ route('route_tourcms_channel.store') }}'
		}).done(function( data ) {
			
			if(data.id=="1")
			{
				
       				$('#dataTableBuilder').DataTable().ajax.reload( null, false );
					$("#result").empty().append('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Success!</b></div>').hide().fadeIn();
       				setTimeout(function (){
       					tinymce.remove();
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