<div class="h-100" style="width:99%">       
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
             
    <div class="card-header">Vendor Detail</div>
    <div class="card-body">

        <div class="col-sm-12 justify-content-left">

            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    NAME
                </div>
                <div class="col-md-auto">
                    {{$vendor->name}}
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    CONTACT PERSON
                </div>
                <div class="col-md-auto">
                    {{$vendor->contact_person}}
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    PHONE
                </div>
                <div class="col-md-auto">
                    {{$vendor->phone}}
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    EMAIL
                </div>
                <div class="col-md-auto">
                    {{$vendor->email}}
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    ACCOUNT HOLDER
                </div>
                <div class="col-md-auto">
                    {{$vendor->account_holder}}
                </div>
            </div>
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    ACCOUNT NUMBER
                </div>
                <div class="col-md-auto">
                    {{$vendor->bank_code}} {{$vendor->account_number}}
                </div>
            </div>

        </div>
            
    </div>
    </div>
    
            </div>

        </div>
    </div>
</div>