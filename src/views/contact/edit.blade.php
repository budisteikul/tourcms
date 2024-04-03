@extends('coresdk::layouts.app')
@section('content')

<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$contact->name}} +{{$contact->wa_id}}</div>
                <div class="card-body">
        		    
                
       	
<hr>

<div id="message_chat" style="overflow-y: scroll; max-height: 500px;flex-direction: column-reverse;display: flex;"></div> 
	
<hr>
<div class="mb-2">
<button type="button" class="btn btn-primary mb-2" id="template1"  onclick="sendTemplate(1); return false;"><b class="fa fa-plus-square"></b> Notif Night Food Tour</button>
                    <button type="button" class="btn btn-primary mb-2" id="template2"  onclick="sendTemplate(2); return false;"><b class="fa fa-plus-square"></b> Notif Morning Food Tour</button>
                    <button type="button" class="btn btn-primary mb-2" id="template3"  onclick="sendTemplate(3); return false;"><b class="fa fa-plus-square"></b> Ask dietary</button>
                    <button type="button" class="btn btn-primary mb-2" id="template4"  onclick="sendTemplate(4); return false;"><b class="fa fa-plus-square"></b> Thanks for question</button>
                    <button type="button" class="btn btn-primary mb-2" id="template5"  onclick="sendTemplate(5); return false;"><b class="fa fa-plus-square"></b> Kalika Guide</button>
                    <button type="button" class="btn btn-primary mb-2" id="template6"  onclick="sendTemplate(6); return false;"><b class="fa fa-plus-square"></b> Anisa Guide</button>
</div>
<form onSubmit="sendMessage(); return false;">

<div class="form-group">
<div id="status"></div>

<div id="mulitplefileuploader"><b class="fa fa-plus"> Upload Imge </b></div>

</div>

<div class="form-group">
    <textarea class="form-control" id="message_text" name="message_text" rows="4"></textarea>
</div>

<button id="submit" type="submit" class="btn btn-primary btn-block"><i class="fas fa-paper-plane"></i> Send</button>
</form>

                </div>
            </div>
        </div>
    </div>


<script language="javascript">

fileUpload();
getMessage();

function sendTemplate(template_id)
{
    var old_text = '<b class="fa fa-plus-square"></b> '+ $("#template"+template_id).text();
    
    $("#template"+template_id).attr("disabled", true);
    $("#template"+template_id).html('<i class="fa fa-spinner fa-spin"></i>');
    $.ajax({
    data: {
      "_token": $("meta[name=csrf-token]").attr("content"),
      "id": "{{ $contact->id }}",
      "template_id": template_id
        },
    type: 'POST',
    url: '{{ route('route_tourcms_contact.index') .'/template' }}'
    }).done(function( data ) {
      getMessage();
      $("#template"+template_id).attr("disabled", false);
      $("#template"+template_id).html(old_text);
    });
    return false;
}

function getMessage()
{
  $.ajax({
    data: {
      "_token": $("meta[name=csrf-token]").attr("content"),
      "id": "{{ $contact->id }}"
        },
    type: 'POST',
    url: '{{ route('route_tourcms_contact.index') .'/message' }}'
    }).done(function( data ) {
      $("#message_chat").html(data);
    });
  return false;
}

function sendMessage()
{
  var error = false;
  $("#submit").attr("disabled", true);
  $('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
  var input = ["message_text"];
  
  $.each(input, function( index, value ) {
      $('#'+ value).removeClass('is-invalid');
      $('#span-'+ value).remove();
  });
  
  $.ajax({
    data: {
      "_token": $("meta[name=csrf-token]").attr("content"),
      "message_text": $('#message_text').val(),
      "key": "{{$file_key}}",
        },
    type: 'PUT',
    url: '{{ route('route_tourcms_contact.update',$contact->id) }}'
    }).done(function( data ) {
      
      if(data.id=="1")
      {
          setTimeout(function (){
              getMessage();
              $("#message_text").val("");
              $("#submit").attr("disabled", false);
              $('#submit').html('<i class="fa fa-save"></i> {{ __('Save') }}');
              
              $(".ajax-file-upload-container").remove();
              
              fileUpload();

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


function fileUpload()
  {
    var settings = {
    url: "{{ route('route_coresdk_filetemp.index') }}",
    multiple:false,
    dragDrop:true,
    maxFileCount:1,
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
    formData: { key: '{{$file_key}}' , _token: $("meta[name=csrf-token]").attr("content") },
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
  uploadObj = $("#mulitplefileuploader").uploadFile(settings);
  }
</script>
@endsection
