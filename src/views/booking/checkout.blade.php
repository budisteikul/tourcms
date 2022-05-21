@inject('ProductHelper', 'budisteikul\toursdk\Helpers\ProductHelper')
@inject('GeneralHelper', 'budisteikul\toursdk\Helpers\GeneralHelper')
@extends('coresdk::layouts.app')
@section('content')
<script language="javascript">
function CREATE()
    {
        $.fancybox.open({
            type: 'ajax',
            src: '{{ route('route_tourcms_booking.create') }}',
            touch: false,
            modal: false,
        }); 
    }
</script>
<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Checkout Booking</div>
                <div class="card-body">


	<div class="row">
		<div class="col-lg-12 col-md-12 mx-auto">
			<div class="row" style="padding-bottom:0px;">
				<div class="col-lg-12 text-left">
				
            	<div class="row mb-2">  
				<div class="col-lg-6 col-lg-auto mb-6 mt-4">
                
<!-- ################################################################### -->  
<script language="javascript">
function REMOVE(id)
{
	$('#remove-'+id).attr("disabled", true);
	$('#remove-'+id).html('<i class="fa fa-spinner fa-spin"></i>');
	
	$.ajax({
		data: JSON.stringify({
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"bookingId": id,
            "sessionId": '{{$shoppingcart->session_id}}',
        }),
        contentType: 'application/json',
		type: 'POST',
		url: '/api/activity/remove'
		}).done(function( data ) {
			if(data.message=="success")
			{
				window.location.href = '{{ route('route_tourcms_booking.index') }}/checkout';
			}
			else
			{
				$('#remove-'+id).attr("disabled", false);
				$('#remove-'+id).html('<i class="fa fa-trash-alt"></i>');
			}
		});
	
	
	return false;
}
</script>
                <div class="card">
                <div class="card-header bg-success">Order Summary</div>
                
                <?php
				$grand_subtotal = 0;
				$grand_discount = 0;
				$grand_total = 0;
				?>
                @foreach($shoppingcart->products as $shoppingcart_product)
                <!-- Product booking -->
                <div class="card-body">

                            <!-- Product detail booking -->
							<div class="row mb-2">
                				<div class="col-8">
                    				<b>{{ $shoppingcart_product->title }}</b>
                    			</div>
                    			<div class="col-4 text-right">
                                	<?php
									$product_subtotal = 0;
									$product_discount = 0;
									$product_total = 0;
									foreach($shoppingcart_product->product_details as $shoppingcart_product_details)
									{
										$product_subtotal += $shoppingcart_product_details->subtotal;
										$product_discount += $shoppingcart_product_details->discount;
										$product_total += $shoppingcart_product_details->total;
									}
									?>
                                    @if($product_discount>0)
                                    	<strike class="text-muted">{{ $GeneralHelper->numberFormat($product_subtotal) }}</strike><br><b>{{ $GeneralHelper->numberFormat($product_total) }}</b>
                                    @else
                    					<b>{{ $GeneralHelper->numberFormat($product_total) }}</b>
                    				@endif
                                </div>
                			 </div>
                    
                    		 <div class="row mb-2">
                             <div class="col-10 row">
                				<div class="ml-4 mb-2">
                               		@if(isset($shoppingcart_product->image))
                    				<img class="img-fluid" width="55" src="{{ $shoppingcart_product->image }}">
                                	@endif
                    			</div>
                    			<div class="col-8" style="font-size:12px; margin-left:-5px">
                                	{{ $ProductHelper->datetotext($shoppingcart_product->date) }}
                                	<br>
                                    {{ $shoppingcart_product->rate }}
                                    <br>
                                    @foreach($shoppingcart_product->product_details as $shoppingcart_product_details)
                                    	
                                        	{{ $shoppingcart_product_details->qty }} x {{ $shoppingcart_product_details->unit_price }} ({{ $GeneralHelper->numberFormat($shoppingcart_product_details->price) }})
                                    	
                                        <br>
                                    @endforeach
                                </div>
                			</div>
                            <div class="col text-right">
                            	<button id="remove-{{ $shoppingcart_product->booking_id }}" onClick="REMOVE({{ $shoppingcart_product->booking_id }});" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt fa-sm"></i></button>
                            </div>
                            </div>
                            <!-- Product detail booking -->
                            
                            
				</div>
                <!-- Product booking -->
                <?php
				$grand_subtotal += $shoppingcart_product->subtotal;
				$grand_discount += $shoppingcart_product->discount;
				$grand_total += $shoppingcart_product->total;
				?>
                
                
                @endforeach
                <div class="card-body pt-0 mt-0">
                    <hr>
                    <div class="row mb-2">
                        <div class="col-8">
                            <span style="font-size:18px">Items</span>
                        </div>
                        <div class="col-4 text-right">
                            <span style="font-size:18px">{{ $GeneralHelper->numberFormat($grand_subtotal) }}</span>
                        </div>
                    </div>
                    @if($grand_discount>0)
                    <div class="row mb-2">
                        <div class="col-8">
                            <span style="font-size:18px">Discount</span>
                        </div>
                        <div class="col-4 text-right">
                            <span style="font-size:18px">{{ $GeneralHelper->numberFormat($grand_discount) }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="row mb-2">
                        <div class="col-8">
                            <b style="font-size:18px">Total ({{ $shoppingcart->currency }})</b>
                        </div>
                        <div class="col-4 text-right">
                            <b style="font-size:18px">{{ $GeneralHelper->numberFormat($grand_total) }}</b>
                        </div>
                    </div>
                </div>
                
                @if($shoppingcart->due_on_arrival>0)
                <div class="card-body pt-0">
                    <hr class="mt-0"> 
                    <div class="row mb-2 mt-0">
                        <div class="col-8">
                            <b style="font-size:18px">Due now ({{ $shoppingcart->currency }})</b>
                        </div>
                        <div class="col-4 text-right">
                           <b style="font-size:18px">{{ $GeneralHelper->numberFormat($shoppingcart->due_now) }}</b>
                        </div>
                    </div>
                    <div class="row mb-4 mt-0">
                        <div class="col-8">
                            <span style="font-size:18px">Due on arrival ({{ $shoppingcart->currency }})</span>
                        </div>
                        <div class="col-4 text-right">
                            <span style="font-size:18px">{{ $GeneralHelper->numberFormat($shoppingcart->due_on_arrival) }}</span>
                        </div>
                    </div>
                </div>
                @endif
                <a class="btn btn-lg btn-block btn-secondary text-white" onclick="CREATE(); return false;"><b class="fa fa-plus-square"></b> <b>Add product to shopping cart</b></a>
                </div>
<!-- ################################################################### -->
@if(!isset($shoppingcart->promo_code))
<script language="javascript">
function PROMOCODE()
{
	$('#alert-promocode-success').fadeOut("slow");
	$('#alert-promocode-failed').fadeOut("slow");
	$("#apply").attr("disabled", true);
	$("#promocode").attr("disabled", true);
	$('#apply').html('<i class="fa fa-spinner fa-spin"></i>');
	
	$.ajax({
		data: JSON.stringify({
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"promocode": $('#promocode').val(),
            "sessionId": '{{$shoppingcart->session_id}}',
        }),
        contentType : 'application/json',
		type: 'POST',
		url: '/api/promocode'
		}).done(function( data ) {
			if(data.message=="success")
			{
				window.location.href = '{{route('route_tourcms_booking.index')}}/checkout';
				$('#alert-promocode').hide();
                $('#alert-promocode').html('<div id="alert-promocode-success" class="alert alert-primary text-center" role="alert"><i class="far fa-smile"></i> Promo code applied</div>');
                $('#alert-promocode').fadeIn("slow");
			}
			else
			{
				$('#promocode').val('');
                $('#alert-promocode').hide();
                $('#alert-promocode').html('<div id="alert-promocode-failed" class="alert alert-danger text-center" role="alert"><i class="far fa-frown"></i> Promo code not valid</div>');
                $('#alert-promocode').fadeIn("slow");
                $("#promocode").attr("disabled", false);
                $("#apply").attr("disabled", false);
                $('#apply').html('Apply');
			}
		});
	
	
	return false;
}
</script>
<!-- ################################################################### -->

                <div class="card mt-4">
                	<div class="card-body">
                    		<div id="alert-promocode"></div>
                    	<form onSubmit="PROMOCODE(); return false;" class="form-inline">
  							<div class="form-row align-items-center">
    							<div class="col-auto">
      								<input type="text" class="form-control" id="promocode" placeholder="Promo code" required>
    							</div>
    							<div class="col-auto">
      								<button id="apply" type="submit" class="btn btn-secondary ">Apply</button>
    							</div>
  							</div>
						</form>
                	</div>
                </div>
 <!-- ################################################################### --> 
 @else
 <script>
$( document ).ready(function() {
	$('#alert-promocode-failed').hide();
});
</script>
<script language="javascript">
function DELETE()
{
	$("#apply").attr("disabled", true);
	$('#apply').html('<i class="fa fa-spinner fa-spin"></i>');
	
	$.ajax({
		data: JSON.stringify({
        	"_token": $("meta[name=csrf-token]").attr("content"),
            "sessionId": '{{$shoppingcart->session_id}}',
        }),
        contentType: 'application/json',
		type: 'POST',
		url: '/api/promocode/remove'
		}).done(function( data ) {
			if(data.message=="success")
			{
				window.location.href = '{{route('route_tourcms_booking.index')}}/checkout';
				$('#alert-promocode').hide();
                $('#alert-promocode').html('<div id="alert-promocode-failed" class="alert alert-danger text-center" role="alert"><i class="far fa-frown"></i> Promo code removed</div>');
                $('#alert-promocode').fadeIn("slow");
			}
		});
	
	
	return false;
}
</script>
<div class="card shadow mt-4">
	<div class="card-body">
            <div id="alert-promocode"></div>
    	<div class="row mb-2">
        	<div class="col-8 my-auto">
				<strong>Promo code : {{ $shoppingcart->promo_code }}</strong>
			</div>
			<div class="col-4 my-auto text-right">
				<button id="apply" type="button" onClick="DELETE();" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i></button>
			</div>
		</div>	
	</div>
</div>
 @endif         
<!-- ################################################################### -->

                
            	</div>
                

            <div class="col-lg-6 col-lg-auto mb-6 mt-4">
            <div class="card mb-8">
            	<div class="card-header bg-success">Session ID : {{$shoppingcart->session_id}}</div>
 				 <div class="card-body" style="padding-left:10px;padding-right:10px;padding-top:10px;padding-bottom:15px;">
                 
<form onSubmit="STORE(); return false;">


<!-- ########################################### -->
<h3>Payment Type</h3>
<div class="form-group">
<label for="payment_type"><strong>Payment</strong></label>
<select style="font-size:16px;height:47px;"  class="form-control" id="payment_type" name="payment_type">
		<option value="duitku-sampoerna">DUITKU SAHABAT SAMPOERNA</option>
		<option value="rapyd-paynow">RAPYD PAYNOW</option>
		<option value="rapyd-fast">RAPYD FAST</option>
		<option value="rapyd-cimb">RAPYD CIMB NIAGA VA</option>
		<option value="rapyd-permata">RAPYD PERMATA VA</option>
		<option value="rapyd-mandiri">RAPYD MANDIRI VA</option>
		<option value="paydia-qris">PAYDIA QRIS</option>
		<option value="doku-mandiri">DOKU MANDIRI VA</option>
		<option value="doku-danamon">DOKU DANAMON VA</option>
		<option value="doku-permata">DOKU PERMATA VA</option>
		<option value="doku-bri">DOKU BRI VA</option>
		<option value="doku-mandirisyariah">DOKU BSI VA</option>
		<option value="doku-cimb">DOKU CIMB NIAGA VA</option>
		<option value="doku-doku">DOKU VA</option>
		<option value="doku-qris">DOKU QRIS</option>
		<option value="midtrans-mandiri">MIDTRANS MANDIRI BILL</option>
        <option value="midtrans-bni">MIDTRANS BNI VA</option>
        <option value="midtrans-bri">MIDTRANS BRI VA</option>
        <option value="midtrans-permata">MIDTRANS PERMATA VA</option>
        <option value="midtrans-gopay">MIDTRANS GOPAY</option>
        <option value="midtrans-shopeepay">MIDTRANS SHOPEEPAY</option>
        <option value="midtrans-qris_gopay">MIDTRANS QRIS GOPAY</option>
        <option value="midtrans-qris_shopeepay">MIDTRANS QRIS SHOPEEPAY</option>
        <option value="oyindonesia-mandiri">OY MANDIRI VA</option>
        <option value="oyindonesia-btpn">OY BTPN VA</option>
        <option value="oyindonesia-permata">OY PERMATA VA</option>
        <option value="oyindonesia-bri">OY BRI VA</option>
        <option value="oyindonesia-cimb">OY CIMB NIAGA VA</option>
        <option value="oyindonesia-bni">OY BNI VA</option>
        <option value="oyindonesia-shopeepay">OY SHOPEEPAY</option>
        <option value="oyindonesia-linkaja">OY LINKAJA</option>
        <option value="oyindonesia-qris">OY QRIS</option>
        <option value="oyindonesia-dana">OY DANA</option>
        <option value="none">NONE</option>

</select>
</div>            
<!-- ########################################### -->
<h3>Booking Channel</h3>
<div class="form-group">
<label for="bookingChannel"><strong>Channel</strong></label>
<select style="font-size:16px;height:47px;"  class="form-control" id="bookingChannel" name="bookingChannel">
        <option value="WEBSITE">WEBSITE</option>
        @foreach($channels as $channel)
        <option value="{{$channel->name}}">{{$channel->name}}</option>
        @endforeach
</select>
</div>
<!-- ########################################### -->
<h3>Main Contact</h3>
@foreach($shoppingcart->questions as $question)
	@if($question->type=="mainContactDetails")
	<div class="form-group">
		<label for="{{ $question->question_id }}"><strong>{{ $question->label }}</strong></label>
		@if($question->data_type=="EMAIL_ADDRESS")
			<input name="{{ $question->question_id }}" value="{{ $question->answer }}" type="email" class="form-control" id="{{ $question->question_id }}" style="height:47px;">
		@elseif($question->data_type=="PHONE_NUMBER")
			<input name="{{ $question->question_id }}" value="{{ $question->answer }}" type="tel" class="form-control" id="{{ $question->question_id }}" style="height:47px;">
		@else
			@if($question->select_option)
				<select style="font-size:16px;height:47px;"  class="form-control" id="{{ $question->question_id }}" name="{{ $question->question_id }}">
    			<option value=""></option>
    			@foreach($question->question_options as $question_option)
    				<option value="{{ $question_option->value }}" {{ $question->answer==$question_option->value ? "selected" : "" }}>{{ $question_option->label }}</option>
    			@endforeach
    			</select>
			@else
				<input name="{{ $question->question_id }}" value="{{ $question->answer }}" type="text" class="form-control" id="{{ $question->question_id }}" style="height:47px;">
			@endif
		@endif
	</div>
	@endif
@endforeach

@foreach($shoppingcart->products as $shoppingcart_product)
	
	@if(@count($shoppingcart->questions) > 4)
	<h2>Question for {{ $shoppingcart_product->title }}</h2>
	<h5>{{ $ProductHelper->datetotext($shoppingcart_product->date) }}</h5>
	@endif

	@foreach($shoppingcart->questions as $question)
		@if($question->booking_id == $shoppingcart_product->booking_id)
			@if($question->when_to_ask=="booking")
			<label for="{{ $question->question_id }}"><strong>{{ $question->label }}</strong></label>
			<div class="form-group">
				@if($question->select_option)
					<select style="font-size:16px;height:47px;"  class="form-control" id="{{ $question->question_id }}" name="{{ $question->question_id }}">
						<option value=""></option>
    				@foreach($question->question_options as $question_option)
    					@php
							$answer = 0;
							if(isset($question_option->answer)) $answer = $question_option->answer
						@endphp
    					<option value="{{ $question_option->value }}" {{ $answer==1 ? "selected" : "" }}>{{ $question_option->label }}</option>
    				@endforeach
				@else
					<input type="text" id="{{ $question->question_id }}" value="{{ $question->answer }}" style="height:47px;" name="{{ $question->question_id }}" class="form-control">
				@endif
			</div>
			@endif
		@endif
	@endforeach

	
	@php
	$participant_number = '';
	$participant_number_arrays = array();
	@endphp
	@foreach($shoppingcart->questions as $question)
		@if($question->booking_id == $shoppingcart_product->booking_id)
			@if($question->when_to_ask=="participant")
					@if($participant_number!=$question->participant_number)
							@php
								$participant_number_arrays[] = (object)array(
									'number' => $question->participant_number
								);
							@endphp
					@endif
					@php
						$participant_number = $question->participant_number;
					@endphp
			@endif
		@endif
	@endforeach
	@php
		//print_r($participant_number_arrays);
	@endphp	
	@foreach($participant_number_arrays as $participant_number_array)
		<div class="card mt-2 mb-2">
			<div class="card-header bg-light">
				<strong class="text-dark">Participant {{ $participant_number_array->number }}</strong>
			</div>
			<div class="card-body">
		@foreach($shoppingcart->questions as $question)
			@if($question->booking_id == $shoppingcart_product->booking_id)
				@if($question->when_to_ask=="participant")
					@if($participant_number_array->number==$question->participant_number)
						<label for="{{ $question->question_id }}"><strong>{{ $question->label }}</strong></label>
						<div class="form-group">
							@if($question->select_option)
								<select style="font-size:16px;height:47px;"  class="form-control" id="{{ $question->question_id }}" name="{{ $question->question_id }}">
									<option value=""></option>
								@foreach($question->question_options as $question_option)
									@php
										$answer = 0;
										if(isset($question_option->answer)) $answer = $question_option->answer
									@endphp
    								<option value="{{ $question_option->value }}" {{ $answer==1 ? "selected" : "" }}>{{ $question_option->label }}</option>
    							@endforeach
    							</select>
							@else
								<input type="text" id="{{ $question->question_id }}" value="{{ $question->answer }}" style="height:47px;" name="{{ $question->question_id }}" class="form-control">
							@endif
						</div>
					@endif
				@endif
			@endif
		@endforeach
			</div>
		</div>
	@endforeach
@endforeach




<button id="submit" type="submit" style="height:47px;" class="btn btn-lg btn-block btn-primary"><i class="fas fa-save"></i> Save</button>
</form>


			</div>
            </div>
            </div>
        	</div>
				<div style="height:40px;"></div>		
				</div>
			</div>
        </div>
	</div>

<script language="javascript">
function STORE()
{
	var error = false;

	var data = ['firstName','lastName','email','phoneNumber'];
	$.each(data, function( index, value ) {
  		$('#'+ value).removeClass('is-invalid');
  		$('#span-'+ value).remove();
	});

	$("#submit").attr("disabled", true);
	$('#submit').html('<i class="fa fa-spinner fa-spin"></i>');
	
	const questions = {
			@foreach($shoppingcart->questions as $question)
				@if($question->type=="mainContactDetails")
					"{{ $question->question_id }}": $('#{{ $question->question_id }}').val(),
				@endif
			@endforeach

			@foreach($shoppingcart->products as $shoppingcart_product)
				@foreach($shoppingcart->questions as $question)
					@if($question->booking_id == $shoppingcart_product->booking_id)
						"{{ $question->question_id }}": $('#{{ $question->question_id }}').val(),
					@endif
				@endforeach
			@endforeach
	}



	$.ajax({
		data: JSON.stringify({
            "bookingChannel": $("#bookingChannel").val(),
            "payment_type": $("#payment_type").val(),
            "sessionId": '{{$shoppingcart->session_id}}',
            "questions": questions,
        }),
        headers: {
        	'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr("content")
    	},
		type: 'POST',
		url: '{{ route('route_tourcms_booking.store') }}'
		}).done(function( data ) {
			
			if(data.message=="success")
			{
				window.location.href = '{{route('route_tourcms_booking.index')}}';
			}
			else
			{
				$.each( data, function( index, value ) {
					index = index.toString().replace("questions.", "");
					value = value.toString().replace("questions.","")
					$('#'+ index).addClass('is-invalid');
					if(value!="")
					{

						$('#'+ index).after('<span id="span-'+ index  +'" class="invalid-feedback" role="alert"><strong>'+ value +'</strong></span>');
					}
				});
				
				$("#submit").attr("disabled", false);
				$('#submit').html('<i class="fas fa-save"></i> Save');
				
			}
		});
	
	
	return false;
}
</script>


		
                </div>
            </div>
        </div>
 </div>
@endsection
