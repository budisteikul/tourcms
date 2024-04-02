@extends('coresdk::layouts.app')
@section('content')

<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$contact->name}} +{{$contact->wa_id}}</div>
                <div class="card-body">
        		    
                <div class="row w-100">
                  
                    <div class="col  text-left">
                    <button type="button" class="btn btn-primary"  onclick="CREATE(); return false;"><b class="fa fa-plus-square"></b> Create Booking</button>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                    <a class="btn btn-secondary" href="{{route('route_tourcms_booking.index')}}/checkout"><i class="fas fa-shopping-cart"></i> Shopping Cart</a>
                    </div>
                  
                </div>
       	
<hr>
   
@foreach($messages as $message)
@php
  $style = 'card bg-light mb-2';
  if($message->from==null) $style = 'card text-white bg-success mb-2';

  $message_text = '';
  if($message->type=="text")
  {
    $message_text = json_decode($message->text)->body;
  }

  if($message->type=="image")
  {
    $image = json_decode($message->image);
    $image_text = '<img src="'.$image->storage_url.'" class="img-thumbnail" style="max-height: 100px;">';
    $message_text = $image_text;
    if(isset($image->caption)) $message_text = $image_text.'<br />'. $image->caption;
  }

  if($message->type=="template")
  {
    $message_text = json_decode($message->template)->name;
  }

@endphp
<div class="{{$style}}" >
  <div class="card-body">
    <p class="card-text mb-0">{!! nl2br($message_text) !!}</p>
    <small>{{$message->created_at}}</small>
  </div>
</div>
@endforeach
	
<hr>

<form method="post" action="">

<div class="form-group">
  <label for="description">Description :</label>
    <textarea class="form-control" id="body" name="body" rows="1"></textarea>
</div>
</form>

                </div>
            </div>
        </div>
    </div>



@endsection
