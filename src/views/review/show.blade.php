@inject('GeneralHelper', 'budisteikul\toursdk\Helpers\GeneralHelper')
@inject('ReviewHelper', 'budisteikul\toursdk\Helpers\ReviewHelper')
<div class="h-100" style="width:99%">       
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
             
    <div class="card-header">Review Detail</div>
    <div class="card-body">

        <div class="col-sm-12 justify-content-left">

            <div class="row">
                <div class="col-lg-2 font-weight-bold">
                    User
                </div>
                <div class="col-md-auto">
                    {{$review->user}}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2 font-weight-bold">
                    Channel
                </div>
                <div class="col-md-auto">
                    {{$review->channel->name}}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2 font-weight-bold">
                    Date
                </div>
                <div class="col-md-auto">
                    {{$GeneralHelper->dateFormat($review->date,4)}}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2 font-weight-bold">
                    Product
                </div>
                <div class="col-md-auto">
                    {{$review->product->name}}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2 font-weight-bold">
                    Rate
                </div>
                <div class="col-md-auto">
                    {!!$ReviewHelper->star($review->rating)!!}
                </div>
            </div>

            @if($review->title!="")
            <div class="row">
                <div class="col-lg-2 font-weight-bold">
                    Title
                </div>
                <div class="col-md-auto">
                    {{$review->title}}
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-2 font-weight-bold">
                    Text
                </div>
                <div class="col-md-auto">
                    {{$review->text}}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2 font-weight-bold">
                    Link
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