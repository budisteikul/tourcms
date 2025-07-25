@extends('coresdk::layouts.app')
@section('content')

<div class="row justify-content-center" style="min-height: 500px;">
        <div class="col-md-12">
            <div class="card mb-2">
                
                <div class="card-header pr-0">
                <div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        <a href="https://wa.me/{{ $contact->wa_id }}" class="btn btn-sm btn-success mb-0" target="_blank"><i class="fab fa-whatsapp"></i> {{$contact->name}} +{{$contact->wa_id}}</a>
                        
                        </div>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                        <div class="btn-toolbar justify-content-end">
                            <button class="btn btn-sm btn-danger mr-0" type="button" onClick="clear_messages();return false;"><i class="fa fa-window-close"></i> Clear messages</button>
                        </div>
                    </div>
                </div>
                </div>
                <div class="card-body">
        		    
                

<hr>

<div id="message_chat" style="overflow-y: scroll; max-height: 500px;flex-direction: column-reverse;display: flex;"></div> 
	
<hr>

<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Message</a>
    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Jogja Night Food Tour</a>
    <a class="nav-item nav-link" id="nav-menu3-tab" data-toggle="tab" href="#nav-menu3" role="tab" aria-controls="nav-menu3" aria-selected="false">Jogja Morning Food Tour</a>
    <a class="nav-item nav-link" id="nav-menu5-tab" data-toggle="tab" href="#nav-menu5" role="tab" aria-controls="nav-menu5" aria-selected="false">Bali Tour</a>
    <a class="nav-item nav-link" id="nav-menu6-tab" data-toggle="tab" href="#nav-menu6" role="tab" aria-controls="nav-menu6" aria-selected="false">Request Review</a>
    <a class="nav-item nav-link" id="nav-menu4-tab" data-toggle="tab" href="#nav-menu4" role="tab" aria-controls="nav-menu4" aria-selected="false">General</a>
    <a class="nav-item nav-link" id="nav-menu7-tab" data-toggle="tab" href="#nav-menu7" role="tab" aria-controls="nav-menu4" aria-selected="false">Testing</a>
  </div>
</nav>

<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active pt-4" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

<form onSubmit="sendMessage(); return false;">

<div class="form-group">
<div id="status"></div>

<div id="mulitplefileuploader"><b class="fa fa-plus"> Upload Imge </b></div>

</div>

<div class="form-group">

    <textarea class="form-control" id="message_text" name="message_text" rows="4"></textarea>
    
</div>



<button id="submit_openai" type="submit" class="btn btn-primary btn-block" onclick="openai();"><i class="fas fa-language"></i> Translate</button>

<button id="submit" type="submit" class="btn btn-primary btn-block"><i class="fas fa-paper-plane"></i> Send</button>

</form>

</div>
<div class="tab-pane fade pt-4" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

                    <button type="button" class="btn btn-danger mb-2" id="template101"  onclick="sendTemplate(101); return false;"><i class="fas fa-paper-plane"></i> Jogja Night Food Tour - Ask Dietary</button>

                    <button type="button" class="btn btn-danger mb-2" id="template102"  onclick="sendTemplate(102); return false;"><i class="fas fa-paper-plane"></i> Jogja Night Food Tour - Kalika Picture</button>

                    <button type="button" class="btn btn-danger mb-2" id="template103"  onclick="sendTemplate(103); return false;"><i class="fas fa-paper-plane"></i> Jogja Night Food Tour - Anisa Picture</button>

                    <button type="button" class="btn btn-danger mb-2" id="template104"  onclick="sendTemplate(104); return false;"><i class="fas fa-paper-plane"></i> Jogja Night Food Tour - Jasmine Picture</button>


                    <button type="button" class="btn btn-danger mb-2" id="template105"  onclick="sendTemplate(105); return false;"><i class="fas fa-paper-plane"></i> Jogja Night Food Tour - Dhea Picture</button>

                    

                    

</div>
<div class="tab-pane fade pt-4" id="nav-menu3" role="tabpanel" aria-labelledby="nav-menu3-tab">
                    <button type="button" class="btn btn-danger mb-2" id="template201"  onclick="sendTemplate(201); return false;"><i class="fas fa-paper-plane"></i> Jogja Morning Food Tour - Ask Dietary</button>

                    <button type="button" class="btn btn-danger mb-2" id="template202"  onclick="sendTemplate(202); return false;"><i class="fas fa-paper-plane"></i> Jogja Morning Food Tour - Kalika Picture</button>

                    <button type="button" class="btn btn-danger mb-2" id="template203"  onclick="sendTemplate(203); return false;"><i class="fas fa-paper-plane"></i> Jogja Morning Food Tour - Anisa Picture</button>

                    <button type="button" class="btn btn-danger mb-2" id="template204"  onclick="sendTemplate(204); return false;"><i class="fas fa-paper-plane"></i> Jogja Morning Food Tour - Jasmine Picture</button>

                    <button type="button" class="btn btn-danger mb-2" id="template205"  onclick="sendTemplate(205); return false;"><i class="fas fa-paper-plane"></i> Jogja Morning Food Tour - Dhea Picture</button>
</div>

<div class="tab-pane fade pt-4" id="nav-menu5" role="tabpanel" aria-labelledby="nav-menu5-tab">
                    <button type="button" class="btn btn-danger mb-2" id="template301"  onclick="sendTemplate(301); return false;"><i class="fas fa-paper-plane"></i> Taman Anyar SDN 3 Penarungan</button>
                    <button type="button" class="btn btn-danger mb-2" id="template304"  onclick="sendTemplate(304); return false;"><i class="fas fa-paper-plane"></i> Taman Anyar SDN 2 Penarungan</button>
                    
                    <button type="button" class="btn btn-danger mb-2" id="template303"  onclick="sendTemplate(303); return false;"><i class="fas fa-paper-plane"></i> Bali Night Food Tour - Dharma Picture</button>
</div>

<div class="tab-pane fade pt-4" id="nav-menu4" role="tabpanel" aria-labelledby="nav-menu4-tab">
                    <button type="button" class="btn btn-primary mb-2" id="template121"  onclick="sendTemplate(121); return false;"><i class="fas fa-paper-plane"></i> Thanks for answering</button>

                    <button type="button" class="btn btn-primary mb-2" id="template122"  onclick="sendTemplate(122); return false;"><i class="fas fa-paper-plane"></i> Kalika Picture</button>

                    <button type="button" class="btn btn-primary mb-2" id="template123"  onclick="sendTemplate(123); return false;"><i class="fas fa-paper-plane"></i> Anisa Picture</button>

                    <button type="button" class="btn btn-primary mb-2" id="template124"  onclick="sendTemplate(124); return false;"><i class="fas fa-paper-plane"></i> Jasmine Picture</button>

                    <button type="button" class="btn btn-primary mb-2" id="template125"  onclick="sendTemplate(125); return false;"><i class="fas fa-paper-plane"></i> Dhea Picture</button>

                    
</div>


<div class="tab-pane fade pt-4" id="nav-menu6" role="tabpanel" aria-labelledby="nav-menu6-tab">
                    <button type="button" class="btn btn-danger mb-2" id="template901"  onclick="sendTemplate(901); return false;"><i class="fas fa-paper-plane"></i> Denpasar Night Food Tour</button>
                    <button type="button" class="btn btn-danger mb-2" id="template902"  onclick="sendTemplate(902); return false;"><i class="fas fa-paper-plane"></i> Taman Anyar</button>
                    <button type="button" class="btn btn-danger mb-2" id="template903"  onclick="sendTemplate(903); return false;"><i class="fas fa-paper-plane"></i> Ubud Food Tour</button>
</div>


<div class="tab-pane fade pt-4" id="nav-menu7" role="tabpanel" aria-labelledby="nav-menu7-tab">
        <button type="button" class="btn btn-danger mb-2" id="template1001"  onclick="sendTemplate(1001); return false;"><i class="fas fa-paper-plane"></i> Jogja Night Food Tour</button>
        <button type="button" class="btn btn-danger mb-2" id="template1002"  onclick="sendTemplate(1002); return false;"><i class="fas fa-paper-plane"></i> Jogja Morning Food Tour</button>
</div>










</div>

                </div>
            </div>

           

        </div>
    </div>
@push('scripts')
<script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-app.js";
  import { getDatabase, ref, onValue } from 'https://www.gstatic.com/firebasejs/10.10.0/firebase-database.js'
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "{{ env("FIREBASE_API_KEY") }}",
    authDomain: "{{ env("FIREBASE_AUTH_DOMAIN") }}",
    databaseURL: "{{ env("FIREBASE_DATABASE_URL") }}",
    projectId: "{{ env("FIREBASE_PROJECT_ID") }}",
    messagingSenderId: "{{ env("FIREBASE_MESSAGING_SENDER_ID") }}",
    appId: "{{ env("FIREBASE_APP_ID") }}",
    measurementId: "{{ env("FIREBASE_MEASUREMENT_ID") }}"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const db = getDatabase();

  const starCountRef = ref(db, 'messages/{{ $contact->id }}');
  onValue(starCountRef, (snapshot) => {
    const data = snapshot.val();
    $("#message_chat").html(data);
  });
  



</script>
@endpush
<script language="javascript">



fileUpload();

function openai()
{
    
      $("#submit_openai").attr("disabled", true);
      $("#submit_openai").html('<i class="fa fa-spinner fa-spin"></i>');

      var input = ["message_text"];

      $.each(input, function( index, value ) {
        $('#'+ value).removeClass('is-invalid');
        $('#span-'+ value).remove();
      });

      $.ajax({
      beforeSend: function(xhr) {
          xhr.setRequestHeader("Authorization", "Bearer {{$token_api}}");
          xhr.setRequestHeader('Content-Type', 'application/json');
          xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
      },
      data: JSON.stringify({
          "message_text": $('#message_text').val(),
          //"text": "thank you for booking our tour",
      }),
      type: 'POST',
      url: '{{env("APP_API_URL")}}/openai'
      }).done(function( data ) {
        if(data.id=="1")
        {
            $('#message_text').val(data.message_text);
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
        }

        
        $("#submit_openai").attr("disabled", false);
        $("#submit_openai").html('<i class="fas fa-language"></i> Translate');
        //var table = $('#dataTableBuilder').DataTable();
        //table.ajax.reload( null, false );
      });

    return false;
}

function clear_messages()
{
    $.confirm({
        title: 'Warning',
        content: 'Are you sure?',
        type: 'red',
        icon: 'fa fa-trash',
        buttons: {   
            ok: {
                text: "OK",
                btnClass: 'btn-danger',
                keys: ['enter'],
                action: function(){
                     
                     $.ajax({
                        data: {
                          "_token": $("meta[name=csrf-token]").attr("content"),
                          "id": "{{ $contact->id }}"
                        },
                        type: 'POST',
                        url: '{{ route('route_tourcms_contact.index') .'/clear_messages' }}'
                    }).done(function( data ) {
      

                    });

                }
            },
            cancel: function(){
                  console.log('the user clicked cancel');
            }
        }
    });
}

function sendTemplate(template_id)
{

    $.confirm({
        title: 'Are you sure?',
        content: $("#template"+template_id).text(),
        type: 'blue',
        icon: 'fas fa-paper-plane',
        buttons: {   
            ok: {
                text: "OK",
                btnClass: 'btn-primary',
                keys: ['enter'],
                action: function(){
                    var enable_text = '<i class="fas fa-paper-plane"></i> '+ $("#template"+template_id).text();
                    var disable_text = '<i class="fa fa-spinner fa-spin"></i> '+ $("#template"+template_id).text();
    
                    $("#template"+template_id).attr("disabled", true);
                    $("#template"+template_id).html(disable_text);
                    $.ajax({
                        data: {
                          "_token": $("meta[name=csrf-token]").attr("content"),
                          "id": "{{ $contact->id }}",
                          "template_id": template_id
                        },
                        type: 'POST',
                        url: '{{ route('route_tourcms_contact.index') .'/template' }}'
                    }).done(function( data ) {
      
                        $("#template"+template_id).attr("disabled", false);
                        $("#template"+template_id).html(enable_text);

                    });
                    
                }
            },
            cancel: function(){
                  console.log('the user clicked cancel');
            }
        }
    });

    


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
          $("#message_text").val("");
          $("#submit").attr("disabled", false);
          $('#submit').html('<i class="fas fa-paper-plane"></i> {{ __('Send') }}');
          $(".ajax-file-upload-container").remove();
          fileUpload();
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
