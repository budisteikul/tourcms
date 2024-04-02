@extends('coresdk::layouts.app')
@section('content')

<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$contact->name}} +{{$contact->wa_id}}</div>
                <div class="card-body">
        		    
                <div class="row w-100">
                  
                    <div class="col  text-left">
                    <div class="mb-2"><strong>Template :</strong></div>
                    <button type="button" class="btn btn-primary"  onclick="CREATE(); return false;"><b class="fa fa-plus-square"></b> Template 1</button>
                    <button type="button" class="btn btn-primary"  onclick="CREATE(); return false;"><b class="fa fa-plus-square"></b> Template 2</button>
                    <button type="button" class="btn btn-primary"  onclick="CREATE(); return false;"><b class="fa fa-plus-square"></b> Template 3</button>
                    <button type="button" class="btn btn-primary"  onclick="CREATE(); return false;"><b class="fa fa-plus-square"></b> Template 4</button>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                    
                    </div>
                  
                </div>
       	
<hr>
   
@foreach($messages as $message)
@php
  $style1 = 'card bg-light mb-2';
  $style2 = 'card-text mb-0';
  if($message->from==null)
  {
      $style2 = 'card text-white bg-success mb-2';
      $style2 = 'card-text mb-0 text-right';
  }

  $message_text = '';
  if($message->type=="text")
  {
    $message_text = json_decode($message->text)->body;
  }

  if($message->type=="image")
  {
    $image = json_decode($message->image);
    $image_text = '<img src="'.$image->storage_url.'" class="img-thumbnail" style="max-height: 100px;">';
    $message_text = $image_text;
    if(isset($image->caption)) $message_text = $image_text.'<br />'. $image->caption;
  }

  if($message->type=="template")
  {
    $message_text = json_decode($message->template)->name;
  }

@endphp
<div class="{{$style1}}" >
  <div class="card-body">
    <p class="card-text mb-0">{!! nl2br($message_text) !!}</p>
    <small>{{$message->created_at}}</small>
  </div>
</div>
@endforeach
	
<hr>

<form method="post" action="">

<div class="form-group">
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
  formData: { key: '' , _token: $("meta[name=csrf-token]").attr("content") },
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
    <textarea class="form-control" id="text" name="text" rows="1"></textarea>
</div>

<button id="submit" type="submit" class="btn btn-primary btn-block"><i class="fas fa-paper-plane"></i> Send</button>
</form>

                </div>
            </div>
        </div>
    </div>



@endsection
