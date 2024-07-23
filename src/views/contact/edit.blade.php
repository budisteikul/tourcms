@extends('coresdk::layouts.app')
@section('content')

<div class="row justify-content-center" style="min-height: 500px;">
        <div class="col-md-12">
            <div class="card mb-2">
                <div class="card-header text-white"><a href="https://wa.me/{{ $contact->wa_id }}" class="btn btn-sm btn-primary mb-0" target="_blank"><i class="fab fa-whatsapp"></i> {{$contact->name}} +{{$contact->wa_id}}</a> </div>
                <div class="card-body">
        		    
                

<hr>

<div id="message_chat" style="overflow-y: scroll; max-height: 500px;flex-direction: column-reverse;display: flex;"></div> 
	
<hr>

<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Message</a>
    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Jogja Night Food Tour</a>
    <a class="nav-item nav-link" id="nav-menu3-tab" data-toggle="tab" href="#nav-menu3" role="tab" aria-controls="nav-menu3" aria-selected="false">Jogja Morning Food Tour</a>
    <a class="nav-item nav-link" id="nav-menu4-tab" data-toggle="tab" href="#nav-menu4" role="tab" aria-controls="nav-menu4" aria-selected="false">General</a>
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

<button id="submit" type="submit" class="btn btn-primary btn-block"><i class="fas fa-paper-plane"></i> Send</button>
</form>

</div>
<div class="tab-pane fade pt-4" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

                    <button type="button" class="btn btn-danger mb-2" id="template101"  onclick="sendTemplate(101); return false;"><i class="fas fa-paper-plane"></i> Jogja Night Food Tour - Ask Dietary</button>

                    <button type="button" class="btn btn-danger mb-2" id="template102"  onclick="sendTemplate(102); return false;"><i class="fas fa-paper-plane"></i> Jogja Night Food Tour - Kalika Picture</button>

                    <button type="button" class="btn btn-danger mb-2" id="template103"  onclick="sendTemplate(103); return false;"><i class="fas fa-paper-plane"></i> Jogja Night Food Tour - Anisa Picture</button>

                    

                    

</div>
<div class="tab-pane fade pt-4" id="nav-menu3" role="tabpanel" aria-labelledby="nav-menu3-tab">
                    <button type="button" class="btn btn-danger mb-2" id="template101"  onclick="sendTemplate(201); return false;"><i class="fas fa-paper-plane"></i> Jogja Morning Food Tour - Ask Dietary</button>

                    <button type="button" class="btn btn-danger mb-2" id="template102"  onclick="sendTemplate(202); return false;"><i class="fas fa-paper-plane"></i> Jogja Morning Food Tour - Kalika Picture</button>

                    <button type="button" class="btn btn-danger mb-2" id="template103"  onclick="sendTemplate(203); return false;"><i class="fas fa-paper-plane"></i> Jogja Morning Food Tour - Anisa Picture</button>
</div>
<div class="tab-pane fade pt-4" id="nav-menu4" role="tabpanel" aria-labelledby="nav-menu4-tab">
                    <button type="button" class="btn btn-primary mb-2" id="template121"  onclick="sendTemplate(121); return false;"><i class="fas fa-paper-plane"></i> Thanks for answering</button>

                    <button type="button" class="btn btn-primary mb-2" id="template122"  onclick="sendTemplate(121); return false;"><i class="fas fa-paper-plane"></i> Kalika Picture</button>

                    <button type="button" class="btn btn-primary mb-2" id="template123"  onclick="sendTemplate(121); return false;"><i class="fas fa-paper-plane"></i> Anisa Picture</button>

                    <button type="button" class="btn btn-danger mb-2" id="template102"  onclick="sendTemplate(111); return false;"><i class="fas fa-paper-plane"></i> Kalika Picture</button>

                    <button type="button" class="btn btn-danger mb-2" id="template103"  onclick="sendTemplate(112); return false;"><i class="fas fa-paper-plane"></i> Anisa Picture</button>
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
                    var enable_text = '<i class="fa fa-plus-square"></i> '+ $("#template"+template_id).text();
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
