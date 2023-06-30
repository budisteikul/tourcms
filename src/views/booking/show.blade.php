@inject('Content', 'budisteikul\toursdk\Helpers\ContentHelper')
@inject('Booking', 'budisteikul\toursdk\Helpers\BookingHelper')
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
            

            {!! $Content->view_invoice($shoppingcart) !!}
            {!! $Content->view_product_detail($shoppingcart) !!}
            
            @if($shoppingcart->booking_channel=="WEBSITE")
            <div class="card mb-2">
                <div class="card-header">PAYMENT 
                @if($shoppingcart->shoppingcart_payment->authorization_id != "")
                 <b>{{ $shoppingcart->shoppingcart_payment->authorization_id }}</b>
                @endif
                </div>
            <div class="card-body bg-light">
               
                <div style="max-width: 350px;margin-top: 10px;">
                {!! $Booking->get_paymentStatus($shoppingcart) !!}
                </div>
            </div>
            </div>
            @endif
            

            @if(Auth::user()->id==1)
            @if($shoppingcart->booking_status!="CANCELED")
            <div class="card mb-2">
                <div class="card-body bg-light">
                    <button id="btn-del" type="button" onClick="CANCEL('{{ $shoppingcart->id }}','{{ $shoppingcart->confirmation_code }}'); return false;" class="btn btn-block btn-danger mr-0"><i class="fa fa-ban"></i> <b>Cancel this booking</b></button>
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