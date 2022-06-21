@inject('CategoryHelper', 'budisteikul\toursdk\Helpers\CategoryHelper')
<script language="javascript">
function STORE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = ["name","bokun_id"];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	
	

	$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"name": $('#name').val(),
			"bokun_id": $('#bokun_id').val(),
			"category_id": $('#category_id').val(),
			"deposit_percentage": $('#deposit_percentage').is(':checked'),
			"deposit_amount": $('#deposit_amount').val(),
			"key": '{{ $file_key }}'
			
        },
		type: 'POST',
		url: '{{ route('route_tourcms_product.store') }}'
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
             
	<div class="card-header">Create Product</div>
	<div class="card-body">
				
<form onSubmit="STORE(); return false;">

<div id="result"></div>

<div class="form-group">
	<label for="name">Name :</label>
	<input type="text" id="name" name="name" class="form-control" placeholder="Name" autocomplete="off">
</div>

<div class="form-group">
	<label for="bokun_id">Bokun ID :</label>
	<input type="text" id="bokun_id" name="bokun_id" class="form-control" placeholder="Bokun ID" autocomplete="off">
</div>  

<div class="form-group">
    <label for="category_id">Category</label>
    <select class="form-control" id="category_id">
      <option value="0">No Category</option>
      @foreach($categories as $category)
      <option value="{{ $category->id }}">{{ $CategoryHelper->nameCategory($category->id,"-") }}</option>
      @endforeach
    </select>
</div>

<div class="form-group">
<label>Image :</label>
<div id="status"></div>
<div id="mulitplefileuploader"><b class="fa fa-plus"> Upload Imge </b></div>
<script>
$(document).ready(function()
{
var settings = {
    url: "{{ route('route_coresdk_filetemp.index') }}",
    multiple:true,
	dragDrop:true,
	maxFileCount:-1,
    fileName: "myfile",
    allowedTypes:"jpg,jpeg",	
    returnType:"json",
	acceptFiles:"image/*",
	uploadStr:"<i class=\"fa fa-folder-open\"></i> Browse",
	onSuccess:function(files,data,xhr)
    {
		$.each( data, function( index, value ) {
		});	
    },
    showDelete:true,
	formData: { key: '{{ $file_key }}' , _token: $("meta[name=csrf-token]").attr("content") },
    deleteCallback: function(data,pd)
	{
		
    for(var i=0;i<data.length;i++)
    {
						
						$.ajax({
							beforeSend: function(request) {
    							request.setRequestHeader("X-CSRF-TOKEN", $("meta[name=csrf-token]").attr("content"));
  						},
     						type: 'DELETE',
     						url: '{{ route('route_coresdk_filetemp.index') }}/'+ data[i]
						}).done(function( msg ) {
							
						});	
     }
           
    pd.statusbar.hide();
	}
}
var uploadObj = $("#mulitplefileuploader").uploadFile(settings);
});
</script>
</div>

<div class="form-group">
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="deposit_percentage">
  <label class="form-check-label" for="deposit_percentage">
    Deposit Percentage
  </label>
</div>
</div>

<div class="form-group">
	<label for="deposit_amount">Deposit amount :</label>
	<input type="number" id="deposit_amount" name="deposit_amount" class="form-control" placeholder="Deposit amount" autocomplete="off" value="0">
</div> 


	<button  class="btn btn-danger" type="button" onClick="$.fancybox.close();"><i class="fa fa-window-close"></i> Cancel</button>
	<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
	</form>
	</div>
</div>       
		
        
        		
        </div>
    </div>

</div>