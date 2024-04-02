

   
@foreach($messages as $message)
@php
  $style1 = 'card bg-light mb-2';
  $style2 = 'card-text mb-0';
  if($message->from==null)
  {
      $style2 = 'card text-white bg-success mb-2';
      $style2 = 'card-text mb-0 text-right';
  }

  $message_text = '';
  if($message->type=="text")
  {
    $message_text = json_decode($message->text)->body;
  }

  if($message->type=="image")
  {
    $image = json_decode($message->image);
    $image_link = '';
    if(isset($image->link)) $image_link = $image->link;
    $image_text = '<img src="'.$image_link.'" class="img-thumbnail mb-2" style="max-height: 100px;">';
    $message_text = $image_text;
    if(isset($image->caption)) $message_text = $image_text.'<br />'. $image->caption;
  }

  if($message->type=="template")
  {
    $message_text = json_decode($message->template)->name;
  }

@endphp
<div class="{{$style1}}" >
  <div class="card-body">
    <p class="card-text mb-0">{!! nl2br($message_text) !!}</p>
    <small>{{$message->created_at}}</small>
  </div>
</div>
@endforeach
	

