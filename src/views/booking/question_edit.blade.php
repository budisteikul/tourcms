@inject('ProductHelper', 'budisteikul\toursdk\Helpers\ProductHelper')
@extends('coresdk::layouts.app')
@section('content')
<div class="h-100" style="width:99%">		

    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
                <div class="card-header pr-0">
                <div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        Edit Question
                        </div>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                        <div class="btn-toolbar justify-content-end">
                            <a href="{{ route('route_tourcms_schedule.index') }}" class="btn btn-sm btn-danger mr-0" type="button"><i class="fa fa-window-close"></i> Close</a>
                        </div>
                    </div>
                </div>
                </div>
                <div class="card-body">
				
<form onSubmit="UPDATE(); return false;">
<div id="result"></div>
<h2>Main Contact</h2>
@foreach($mainContactDetails as $mainContactDetail)
@php
$label = $mainContactDetail->label;
if($label=="") $label = $mainContactDetail->question_id;
@endphp
<div class="form-group">
	<label for="{{$mainContactDetail->question_id}}">{{$label}} :</label>
	<input type="text" id="{{$mainContactDetail->question_id}}" name="{{$mainContactDetail->question_id}}" class="form-control" autocomplete="off" value="{{$mainContactDetail->answer}}">
</div>
@endforeach

<hr />
<h2>Product Question</h2>
@foreach($activityBookings as $activityBooking)
<h5>{{$ProductHelper->product_name_by_booking_id($activityBooking->booking_id)}}</h5>
@php
$label = $activityBooking->label;
if($label=="") $label = $activityBooking->question_id;
@endphp
	@if($activityBooking->data_type=="SHORT_TEXT")
	<div class="form-group">
		<label for="{{$activityBooking->question_id}}">{{$label}} :</label>
		<input type="text" id="{{$activityBooking->question_id}}" name="{{$activityBooking->question_id}}" class="form-control" autocomplete="off" value="{{$activityBooking->answer}}">
	</div>
	@elseif($activityBooking->data_type=="OPTIONS")
	<div class="form-group">
		<label for="{{$activityBooking->question_id}}">{{$label}} :</label>
		<select id="{{$activityBooking->question_id}}" name="{{$activityBooking->question_id}}" class="form-control" >
			@foreach($activityBooking->shoppingcart_question_options as $shoppingcart_question_option)
				<option value="{{$shoppingcart_question_option->id}}" {{ $shoppingcart_question_option->answer === 1 ? "selected" : "" }}>{{$shoppingcart_question_option->label}}</option>
			@endforeach

		</select>
	</div>
	@else
	<div class="form-group">
		<label for="{{$activityBooking->question_id}}">{{$label}} :</label>
		<textarea rows="6" id="{{$activityBooking->question_id}}" name="{{$activityBooking->question_id}}" class="form-control">{{$activityBooking->answer}}</textarea>
	</div>
	@endif
@endforeach     
<button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
</form>
</div>
</div>       




				
        </div>
    </div>

</div>


<script language="javascript">
function UPDATE()
{
	var error = false;
	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	var input = [];
	
	$.each(input, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});
	

	$.ajax({
		data: {
			@foreach($activityBookings as $activityBooking)
        	"{{$activityBooking->question_id}}": $('#{{$activityBooking->question_id}}').val(),
        	@endforeach
        	@foreach($mainContactDetails as $mainContactDetail)
        	"{{$mainContactDetail->question_id}}": $('#{{$mainContactDetail->question_id}}').val(),
        	@endforeach
        	"_token": $("meta[name=csrf-token]").attr("content"),
        },
		type: 'POST',
		url: '{{ route('route_tourcms_booking.index') }}/question/{{$id}}'
		}).done(function( data ) {
			
			if(data.id=="1")
			{
       				$('#dataTableBuilder').DataTable().ajax.reload( null, false );
					$("#result").empty().append('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Success!</b></div>').hide().fadeIn();
       				setTimeout(function (){
  						window.location='{{ route('route_tourcms_schedule.index') }}';
					}, 1000);
			}
			else
			{
				$.each( data, function( index, value ) {
					$('#'+ index).addClass('is-invalid');
						if(value!="")
						{
							$('#'+ index).after('<span id="span-'+ index  +'" class="invalid-feedback" role="alert"><strong>'+ value +'</strong></span>');
						}
					});
				$("#submit").attr("disabled", false);
				$('#submit').html('<i class="fa fa-save"></i> {{ __('Save') }}');
			}
		});
	
	
	return false;
}
</script>
@endsection