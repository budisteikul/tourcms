@inject('BookingHelper', 'budisteikul\tourcms\Helpers\BookingHelper')
@inject('Content', 'budisteikul\tourcms\Helpers\ContentHelper')
@php
  $main_contact = $BookingHelper->get_answer_contact($shoppingcart);
@endphp

Hello {{$main_contact->firstName}} 👋 
Thank you for booking our food tour 

The Food Tour will start {{ $time_description }} at {{ $time }} and our meeting point is at {{ $location }}.

Map : {{$map}}
{{$map_description}}

By the way, do you have any food allergy or dietary restrictions?