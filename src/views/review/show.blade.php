@inject('GeneralHelper', 'budisteikul\tourcms\Helpers\GeneralHelper')
@inject('ReviewHelper', 'budisteikul\tourcms\Helpers\ReviewHelper')
@extends('coresdk::layouts.input-form',["mainTitle" => "Review Detail"])
@section('content')

        <div class="col-sm-12 justify-content-left">

            <div class="row border-bottom p-2">
                <div class="col-lg-2 font-weight-bold">
                    USER
                </div>
                <div class="col-md-auto ">
                    {{$review->user}}
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-lg-2 font-weight-bold">
                    CHANNEL
                </div>
                <div class="col-md-auto">
                    {{$review->channel->name}}
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-lg-2 font-weight-bold">
                    DATE
                </div>
                <div class="col-md-auto">
                    {{$GeneralHelper->dateFormat($review->date,4)}}
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-lg-2 font-weight-bold">
                    PRODUCT
                </div>
                <div class="col-md-auto">
                    {{$review->product->name}}
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-lg-2 font-weight-bold">
                    RATE
                </div>
                <div class="col-md-auto">
                    {!!$ReviewHelper->star($review->rating)!!}
                </div>
            </div>
            
            <div class="row border-bottom p-2">
                <div class="col-md-auto">
                    @if($review->title!="")
                    {{$review->title}}
                    <br />
                    @endif
                    {{$review->text}}
                </div>
            </div>

            
            

        </div>
            
    </div>
    @endsection