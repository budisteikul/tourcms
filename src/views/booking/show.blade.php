@inject('Content', 'budisteikul\toursdk\Helpers\ContentHelper')
@inject('Booking', 'budisteikul\toursdk\Helpers\BookingHelper')
@inject('Payment', 'budisteikul\toursdk\Helpers\PaymentHelper')
@inject('General', 'budisteikul\toursdk\Helpers\GeneralHelper')
@inject('Whatsapp', 'budisteikul\toursdk\Helpers\WhatsappHelper')

<div class="h-100" style="width:99%">       
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
    <div class="card-header pr-0">
                <div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        <a href="{{env("APP_URL")}}/booking/receipt/{{$shoppingcart->session_id}}/{{$shoppingcart->confirmation_code}}" target="_blank" class="text-white">Booking Detail&nbsp;{{ $shoppingcart->confirmation_code }}</a>
                        </div>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                        <div class="btn-toolbar justify-content-end">
                            <button class="btn btn-sm btn-danger mr-0" type="button" onClick="$.fancybox.close();"><i class="fa fa-window-close"></i> Close</button>
                        </div>
                    </div>
                </div>
    </div>
    

    <div class="card-body">
            
            @if(Auth::user()->id==1)
            <div class="card mb-2 ">
                <div class="card-header bg-secondary">CUSTOMER</div>
                  <ul class="list-group list-group-flush ml-0 mr-0 pl-0 pr-0">
                    <li class="list-group-item"><a href="/api/pdf/invoice/{{ $shoppingcart->session_id }}/Invoice-{{ $shoppingcart->confirmation_code }}.pdf"><i class="far fa-file-pdf"></i> Invoice-{{ $shoppingcart->confirmation_code }}.pdf</a></li>
                    <li class="list-group-item"><b>Name :</b> {{ $contact->firstName }} {{ $contact->lastName }}<input type="hidden" id="full_name" value="{{ $contact->firstName }} {{ $contact->lastName }}"> <button onclick="copyToClipboard('#full_name')" title="Copied" data-toggle="tooltip" data-placement="right" data-trigger="click" class="btn btn-light btn-sm invoice-hilang"><i class="far fa-copy"></i></button></li>
                    
                    
                    @if($contact->phoneNumber!="")
                    @php
                        $number = $contact->phoneNumber;
                        $number_array = explode(" ",$number);
                        if(isset($number_array[1]))
                        {
                            $number_array[1] = ltrim($number_array[1], '0');
                        }
                        
                        $nomor = '';
                        foreach($number_array as $no)
                        {
                            $nomor .= preg_replace("/[^0-9]/","",$no);
                        }
                        
                    @endphp
                    <li class="list-group-item"><b>Phone :</b> {{ $contact->phoneNumber }} <input type="hidden" id="Whatsapp" value="{{ $nomor }}"> <button onclick="copyToClipboard('#Whatsapp')" title="Copied" data-toggle="tooltip" data-placement="right" data-trigger="click" class="btn btn-light btn-sm invoice-hilang"><i class="far fa-copy"></i></button></li>
                    @endif
                    @if($contact->email!="")
                    <li class="list-group-item"><b>Email :</b> {{ $contact->email }}</li>
                    @endif
                    <li class="list-group-item"><b>Status :</b> {{ strtoupper($shoppingcart->booking_status) }}</li>
                    @if($contact->phoneNumber!="")
                    <li class="list-group-item">
                         <a href="/cms/contact/{{ $Whatsapp->contact($nomor, $contact->firstName) }}/edit" class="btn btn-sm btn-primary mb-2" ><i class="fab fa-whatsapp"></i> Whatsapp Business API</a>
                         <a href="https://wa.me/{{ $nomor }}" class="btn btn-sm btn-success mb-2" target="_blank"><i class="fab fa-whatsapp"></i> Whatsapp Business App</a>
                    </li>
                    @endif
                  </ul>
            </div>

            @else
            <div class="card mb-2">
                <div class="card-header bg-secondary">CUSTOMER</div>
                  <ul class="list-group list-group-flush ml-0 mr-0 pl-0 pr-0">
                    <li class="list-group-item"><b>Name :</b> {{ $contact->firstName }} {{ $contact->lastName }}<input type="hidden" id="full_name" value="{{ $contact->firstName }} {{ $contact->lastName }}"> <button onclick="copyToClipboard('#full_name')" title="Copied" data-toggle="tooltip" data-placement="right" data-trigger="click" class="btn btn-light btn-sm invoice-hilang"><i class="far fa-copy"></i></button></li>
                    <li class="list-group-item"><b>Status :</b> {{ strtoupper($shoppingcart->booking_status) }}</li>
                  </ul>
            </div>    


            @endif
            
            
            <div class="card-header bg-secondary">PRODUCT</div>
            {!! $Content->view_product_detail($shoppingcart) !!}
            
            @if(Auth::user()->id==1)
            
            @if(isset($shoppingcart->shoppingcart_payment))
            <div class="card mb-2" style="border-radius: 0px;">
                <div class="card-header bg-secondary" style="border-radius: 0px;">PAYMENT</div>
            
                  <ul class="list-group list-group-flush">
                    @if($shoppingcart->shoppingcart_payment->payment_provider!="none")
                    <li class="list-group-item">
                        <b>ID :</b> {{ $shoppingcart->shoppingcart_payment->order_id }}
                        <input type="hidden" id="order_id" value="{{ $shoppingcart->shoppingcart_payment->order_id }}"> <button onclick="copyToClipboard('#order_id')" title="Copied" data-toggle="tooltip" data-placement="right" data-trigger="click" class="btn btn-light btn-sm invoice-hilang"><i class="far fa-copy"></i></button>
                    </li>
                    <li class="list-group-item"><b>Payment Provider :</b> {{ strtoupper($shoppingcart->shoppingcart_payment->payment_provider) }}</li>
                    @endif
                    <li class="list-group-item"><b>Total :</b> {{ strtoupper($shoppingcart->shoppingcart_payment->currency) }} {{ $General->numberFormat($shoppingcart->shoppingcart_payment->amount) }}</li>
                    @if($shoppingcart->shoppingcart_payment->payment_type=="bank_redirect")
                    <li class="list-group-item">
                        <b>Link :</b> {{ $shoppingcart->shoppingcart_payment->redirect }}
                        <input type="hidden" id="redirect" value="{{ $shoppingcart->shoppingcart_payment->redirect }}"> <button onclick="copyToClipboard('#redirect')" title="Copied" data-toggle="tooltip" data-placement="right" data-trigger="click" class="btn btn-light btn-sm invoice-hilang"><i class="far fa-copy"></i></button>
                    </li>
                    @endif

                    

                    @if($shoppingcart->shoppingcart_payment->payment_status==4 && $shoppingcart->shoppingcart_payment->payment_provider=="none")
                    <li class="list-group-item"><button id="btn-paid" type="button" onClick="PAID('{{ $shoppingcart->id }}','{{ $shoppingcart->confirmation_code }}'); return false;" class="btn btn-block btn-success mr-0"><b>Set payment as Paid</b></button></li>
                    @endif
                    
                   

                  </ul>
                
            </div>
             @endif
             
            @endif

            @if(Auth::user()->id==1)
            @if($shoppingcart->booking_status!="CANCELED")
            <div class="card mb-2">
                <div class="card-body bg-light">
                    <li class="list-group-item"><button id="btn-whatsapp" type="button" onClick="RESEND_WHATSAPP('{{ $shoppingcart->id }}','{{ $shoppingcart->confirmation_code }}'); return false;" class="btn btn-block btn-success mr-0"><i class="fab fa-whatsapp"></i> <b>Resend Whatsapp</b></button></li>
                    <li class="list-group-item"><button id="btn-email" type="button" onClick="RESEND_EMAIL('{{ $shoppingcart->id }}','{{ $shoppingcart->confirmation_code }}'); return false;" class="btn btn-block btn-primary mr-0"><i class="far fa-envelope"></i> <b>Resend Email</b></button></li>

                    <li class="list-group-item"><button id="btn-del" type="button" onClick="CANCEL('{{ $shoppingcart->id }}','{{ $shoppingcart->confirmation_code }}'); return false;" class="btn btn-block btn-danger mr-0"><i class="fa fa-ban"></i> <b>Cancel this booking</b></button></li>
                </div>
            </div>
            @endif
            
            @endif
            
    </div>

    </div>
    

        
            </div>

        </div>
    </div>
</div>



<script type="text/javascript">
$( document ).ready(function() {
    $("#qris-img").css("width","150");
});


function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).val()).select();
  document.execCommand("copy");
  $temp.remove();
  
  $(element +'_button').tooltip('hide');
  $(element +'_button').tooltip('show');
  hideTooltip(element +'_button');
}

function hideTooltip(element) {
  setTimeout(function() {
    $(element).tooltip('dispose');
  }, 1000);
}


function RESEND_WHATSAPP(id,transaction_id)
  {
    $.confirm({
        title: 'Warning',
        content: 'Are you sure want to resend whatsapp with booking number '+ transaction_id +'?',
        type: 'green',
      icon: 'fa fa-ban',
        buttons: {   
            ok: {
                text: "OK",
                btnClass: 'btn-success',
                keys: ['enter'],
                action: function(){
                     var table = $('#dataTableBuilder').DataTable();
                     $("#btn-whatsapp").attr("disabled", true);
                     $('#btn-whatsapp').html('<i class="fa fa-spinner fa-spin"></i>');
                     $.ajax({
                        data: {
                          "_token": $("meta[name=csrf-token]").attr("content"),
                          "action": 'resend_whatsapp',
      
                        },
                        type: 'PUT',
                        url: '{{ route('route_tourcms_booking.index') }}/'+ id
                        }).done(function( data ) {
                          table.ajax.reload( null, false );
                          setTimeout(function (){
                              $.fancybox.close();
                            }, 1000);
                        });

                }
            },
            cancel: function(){
                  console.log('the user clicked cancel');
            }
        }
    });
    
  }

  function RESEND_EMAIL(id,transaction_id)
  {
    $.confirm({
        title: 'Warning',
        content: 'Are you sure want to resend email with booking number '+ transaction_id +'?',
        type: 'blue',
      icon: 'fa fa-ban',
        buttons: {   
            ok: {
                text: "OK",
                btnClass: 'btn-primary',
                keys: ['enter'],
                action: function(){
                     var table = $('#dataTableBuilder').DataTable();
                     $("#btn-email").attr("disabled", true);
                     $('#btn-email').html('<i class="fa fa-spinner fa-spin"></i>');
                     $.ajax({
                        data: {
                          "_token": $("meta[name=csrf-token]").attr("content"),
                          "action": 'resend_email',
      
                        },
                        type: 'PUT',
                        url: '{{ route('route_tourcms_booking.index') }}/'+ id
                        }).done(function( data ) {
                          table.ajax.reload( null, false );
                          setTimeout(function (){
                              $.fancybox.close();
                            }, 1000);
                        });

                }
            },
            cancel: function(){
                  console.log('the user clicked cancel');
            }
        }
    });
    
  }

  function PAID(id,transaction_id)
  {
    $.confirm({
        title: 'Warning',
        content: 'Are you sure want to set as paid on order with booking number '+ transaction_id +'?',
        type: 'green',
      icon: 'fa fa-ban',
        buttons: {   
            ok: {
                text: "OK",
                btnClass: 'btn-success',
                keys: ['enter'],
                action: function(){
                     var table = $('#dataTableBuilder').DataTable();
                     $("#btn-paid").attr("disabled", true);
                     $('#btn-paid').html('<i class="fa fa-spinner fa-spin"></i>');
                     $.ajax({
                        data: {
                          "_token": $("meta[name=csrf-token]").attr("content"),
                          "action": 'paid',
      
                        },
                        type: 'PUT',
                        url: '{{ route('route_tourcms_booking.index') }}/'+ id
                        }).done(function( data ) {
                          table.ajax.reload( null, false );
                          setTimeout(function (){
                              $.fancybox.close();
                            }, 1000);
                        });

                }
            },
            cancel: function(){
                  console.log('the user clicked cancel');
            }
        }
    });
    
  }

  function CANCEL(id,transaction_id)
  {
    $.confirm({
        title: 'Warning',
        content: 'Are you sure want to cancel order with booking number '+ transaction_id +'?',
        type: 'red',
      icon: 'fa fa-ban',
        buttons: {   
            ok: {
                text: "OK",
                btnClass: 'btn-danger',
                keys: ['enter'],
                action: function(){
                     var table = $('#dataTableBuilder').DataTable();
                     $("#btn-del").attr("disabled", true);
                     $('#btn-del').html('<i class="fa fa-spinner fa-spin"></i>');
                     $.ajax({
                        data: {
                          "_token": $("meta[name=csrf-token]").attr("content"),
                          "action": 'cancel',
      
                        },
                        type: 'PUT',
                        url: '{{ route('route_tourcms_booking.index') }}/'+ id
                        }).done(function( data ) {
                          table.ajax.reload( null, false );
                          setTimeout(function (){
                              $.fancybox.close();
                            }, 1000);
                        });

                }
            },
            cancel: function(){
                  console.log('the user clicked cancel');
            }
        }
    });
    
  }
</script>
<style type="text/css">
    h1{
        font-size: 14px;
    }
    h5{
        font-size: 14px;
    }
</style>