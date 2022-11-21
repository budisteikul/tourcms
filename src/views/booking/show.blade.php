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
             
    <div class="card-header">Booking Detail {{ $shoppingcart->confirmation_code }}</div>
    <div class="card-body">

            {!! $Content->view_invoice($shoppingcart) !!}
            {!! $Content->view_product_detail($shoppingcart) !!}
            
            <div style="max-width: 350px;">
                {!! $Booking->get_paymentStatus($shoppingcart) !!}
            </div>

            
            <!-- a class="btn btn-primary" href="{{ env('APP_URL') }}/booking/receipt/{{$shoppingcart->session_id}}/{{$shoppingcart->confirmation_code}}" target="_blank"><b class="fa fa-eye"></b> View Receipt Page</a  -->

    </div>
    </div>
    
        
            </div>

        </div>
    </div>
</div>