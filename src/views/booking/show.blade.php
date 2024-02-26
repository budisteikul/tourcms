@inject('Content', 'budisteikul\toursdk\Helpers\ContentHelper')
@inject('Booking', 'budisteikul\toursdk\Helpers\BookingHelper')
@inject('Payment', 'budisteikul\toursdk\Helpers\PaymentHelper')
@inject('General', 'budisteikul\toursdk\Helpers\GeneralHelper')

<div class="h-100" style="width:99%">       
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
    <div class="card-header pr-0">
                <div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        Booking Detail&nbsp;{{ $shoppingcart->confirmation_code }}
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
            <div class="card mb-2">
                <div class="card-header">CUSTOMER</div>
                  <ul class="list-group list-group-flush ml-0 mr-0 pl-0 pr-0">
                    <li class="list-group-item"><b>Invoice :</b> <a href="/api/pdf/invoice/{{ $shoppingcart->session_id }}/Invoice-{{ $shoppingcart->confirmation_code }}.pdf">Invoide-{{ $shoppingcart->confirmation_code }}.pdf</a></li>
                    <li class="list-group-item"><b>Name :</b> {{ $contact->firstName }} {{ $contact->lastName }}<input type="hidden" id="full_name" value="{{ $contact->firstName }} {{ $contact->lastName }}"> <button onclick="copyToClipboard('#full_name')" title="Copied" data-toggle="tooltip" data-placement="right" data-trigger="click" class="btn btn-light btn-sm invoice-hilang"><i class="far fa-copy"></i></button></li>
                    <li class="list-group-item"><b>Phone :</b> {{ $contact->phoneNumber }}</li>
                    <li class="list-group-item"><b>Email :</b> {{ $contact->email }}</li>
                    <li class="list-group-item"><b>Status :</b> {{ strtoupper($shoppingcart->booking_status) }}</li>
                  </ul>
            </div>

            @else
            <div class="card mb-2">
                <div class="card-header">CUSTOMER</div>
                  <ul class="list-group list-group-flush ml-0 mr-0 pl-0 pr-0">
                    <li class="list-group-item"><b>Name :</b> {{ $contact->firstName }} {{ $contact->lastName }}<input type="hidden" id="full_name" value="{{ $contact->firstName }} {{ $contact->lastName }}"> <button onclick="copyToClipboard('#full_name')" title="Copied" data-toggle="tooltip" data-placement="right" data-trigger="click" class="btn btn-light btn-sm invoice-hilang"><i class="far fa-copy"></i></button></li>
                    <li class="list-group-item"><b>Status :</b> {{ strtoupper($shoppingcart->booking_status) }}</li>
                  </ul>
            </div>    


            @endif
            
            
            <div class="card-header">PRODUCT</div>
            {!! $Content->view_product_detail($shoppingcart) !!}
            
            @if(Auth::user()->id==1)
            @if($shoppingcart->booking_channel=="WEBSITE")
            <div class="card mb-2">
                <div class="card-header">PAYMENT</div>
            
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <b>Auth ID :</b> {{ $shoppingcart->shoppingcart_payment->authorization_id }}
                        <input type="hidden" id="auth_id" value="{{ $shoppingcart->shoppingcart_payment->authorization_id }}"> <button onclick="copyToClipboard('#auth_id')" title="Copied" data-toggle="tooltip" data-placement="right" data-trigger="click" class="btn btn-light btn-sm invoice-hilang"><i class="far fa-copy"></i></button>
                    </li>
                    <li class="list-group-item"><b>Payment Provider :</b> {{ strtoupper($shoppingcart->shoppingcart_payment->payment_provider) }}</li>
                    <li class="list-group-item"><b>Total :</b> {{ strtoupper($shoppingcart->shoppingcart_payment->currency) }} {{ $General->numberFormat($shoppingcart->shoppingcart_payment->amount) }}</li>
                    @if($shoppingcart->shoppingcart_payment->payment_type=="bank_redirect")
                    <li class="list-group-item">
                        <b>Link :</b> {{ $shoppingcart->shoppingcart_payment->redirect }}
                        <input type="hidden" id="redirect" value="{{ $shoppingcart->shoppingcart_payment->redirect }}"> <button onclick="copyToClipboard('#redirect')" title="Copied" data-toggle="tooltip" data-placement="right" data-trigger="click" class="btn btn-light btn-sm invoice-hilang"><i class="far fa-copy"></i></button>
                    </li>
                    @endif
                  </ul>
                
            </div>
            @endif
            @endif

            @if(Auth::user()->id==1)
            @if($shoppingcart->booking_status!="CANCELED")
                @if($shoppingcart->shoppingcart_payment->payment_provider!="none")
                    @if($shoppingcart->shoppingcart_payment->payment_status==4)
            <div class="card mb-2">
                <div class="card-body bg-light">
                    <button id="btn-del" type="button" onClick="CANCEL('{{ $shoppingcart->id }}','{{ $shoppingcart->confirmation_code }}'); return false;" class="btn btn-block btn-danger mr-0"><i class="fa fa-ban"></i> <b>Cancel this booking</b></button>
                </div>
            </div>
                    @else
            <div class="card mb-2">
                <div class="card-body bg-light">
                    <button id="btn-ref" type="button" onClick="REFUND('{{ $shoppingcart->id }}','{{ $shoppingcart->session_id }}','{{ $shoppingcart->confirmation_code }}'); return false;" class="btn btn-block btn-danger mr-0"><i class="fa fa-ban"></i> <b>Cancel and refund this booking</b></button>
                </div>
            </div>
                    @endif
                @else
            <div class="card mb-2">
                <div class="card-body bg-light">
                    <button id="btn-del" type="button" onClick="CANCEL('{{ $shoppingcart->id }}','{{ $shoppingcart->confirmation_code }}'); return false;" class="btn btn-block btn-danger mr-0"><i class="fa fa-ban"></i> <b>Cancel this booking</b></button>
                </div>
            </div>
                @endif
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
</script>
<style type="text/css">
    h1{
        font-size: 14px;
    }
    h5{
        font-size: 14px;
    }
</style>