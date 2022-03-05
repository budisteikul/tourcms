@inject('Content', 'budisteikul\toursdk\Helpers\ContentHelper')
@inject('Booking', 'budisteikul\toursdk\Helpers\BookingHelper')
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
            
            <div style="max-width: 300px;">
                {!! $Booking->get_paymentStatus($shoppingcart) !!}
            </div>
            <a class="btn btn-primary" href="{{ env('APP_URL') }}/booking/receipt/{{$shoppingcart->session_id}}/{{$shoppingcart->confirmation_code}}" target="_blank"><b class="fa fa-eye"></b> View Receipt Page</a>
    </div>
    </div>
    
        
            </div>

        </div>
    </div>
</div>