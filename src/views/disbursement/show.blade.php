@inject('GeneralHelper', 'budisteikul\toursdk\Helpers\GeneralHelper')
<div class="h-100" style="width:99%">       
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
             
    <div class="card-header">Disbursement Detail</div>
    <div class="card-body">

        <div class="col-sm-12 justify-content-left">

            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    VENDOR
                </div>
                <div class="col-md-auto">
                    {{$disbursement->vendor_name}}
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    ACCOUNT NUMBER
                </div>
                <div class="col-md-auto">
                    {{$disbursement->bankcode}} {{$disbursement->account_number}}
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    AMOUNT
                </div>
                <div class="col-md-auto">
                    {{$GeneralHelper->numberFormat($disbursement->amount)}}
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    REFERENCE
                </div>
                <div class="col-md-auto">
                    {{$disbursement->reference}}
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    CREATED AT
                </div>
                <div class="col-md-auto">
                    {{$GeneralHelper->dateFormat($disbursement->created_at,10)}}
                </div>
            </div>

            @if($disbursement->status==2)
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    TRANSFERED AT
                </div>
                <div class="col-md-auto">
                    {{$GeneralHelper->dateFormat($disbursement->updated_at,10)}}
                </div>
            </div>
            @endif

        </div>
            
    </div>
    </div>
            
            </div>

        </div>

    </div>
    
</div>