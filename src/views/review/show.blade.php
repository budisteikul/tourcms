@inject('GeneralHelper', 'budisteikul\toursdk\Helpers\GeneralHelper')
@inject('ReviewHelper', 'budisteikul\toursdk\Helpers\ReviewHelper')
<div class="h-100" style="width:99%">       
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
             
    <div class="card-header pr-0">
                <div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        Review Detail
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

        <div class="col-sm-12 justify-content-left">

            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    USER
                </div>
                <div class="col-md-auto">
                    {{$review->user}}
                </div>
            </div>

            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    CHANNEL
                </div>
                <div class="col-md-auto">
                    {{$review->channel->name}}
                </div>
            </div>

            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    DATE
                </div>
                <div class="col-md-auto">
                    {{$GeneralHelper->dateFormat($review->date,4)}}
                </div>
            </div>

            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    PRODUCT
                </div>
                <div class="col-md-auto">
                    {{$review->product->name}}
                </div>
            </div>

            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    RATE
                </div>
                <div class="col-md-auto">
                    {!!$ReviewHelper->star($review->rating)!!}
                </div>
            </div>
            
            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    TEXT
                </div>
                <div class="col-md-auto">
                    @if($review->title!="")
                    {{$review->title}}
                    <br />
                    @endif
                    {{$review->text}}
                </div>
            </div>

            <div class="row border-bottom">
                <div class="col-lg-2 font-weight-bold">
                    LINK
                </div>
                <div class="col-md-auto">
                    {{$review->link}}
                </div>
            </div>
            

        </div>
            
    </div>
    </div>
    
            </div>

        </div>
    </div>
</div>