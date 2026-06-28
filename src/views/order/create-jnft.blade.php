
@extends('coresdk::layouts.input-form',["mainTitle" => "Create PO Internal"])
@section('content')

				
<form onSubmit="STORE(); return false;">

<div id="result"></div>

<div class="form-group">   
				 <label for="datetimepicker1">Date :</label>           
                <div class='input-group' id='datetimepicker1'>
                    <input type="text" id="date" name="date" value="<?= date('Y-m-d') ?>" class="form-control bg-white" readonly>
                    <div class="input-group-append input-group-addon text-muted">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
 		<script type="text/javascript">
            $(function () {
                $('#date').datetimepicker({
					format: 'YYYY-MM-DD',
					enabledDates: [{!! $moment !!}],
					showTodayButton: true,
					showClose: true,
					ignoreReadonly: true,
					icons: {
                    	time: "fa fa-clock"
                	},
					widgetPositioning: {
            			horizontal: 'left',
            			vertical: 'bottom'
        			},
				});
            });
        </script>    
</div>

<div class="form-group">
    <label for="app">Tour</label>
    <select class="form-control" id="app">
      <option value="1">Jogja Night Food Tour</option>
      <option value="3">Jogja Short Food Tour</option>
      <option value="2">Jogja Morning Food Tour</option>
    </select>
</div>

<div class="form-group">
	<label for="guide">Guide :</label>
    <select class="form-control" id="guide" data-live-search="true">
    	@foreach($guides as $guide)
       	<option value="{{$guide->id}}">{{$guide->name}}</option>
       	@endforeach
	</select>
</div>

<div class="form-group">
    <label for="by_qty">Guests :</label>
    @foreach($guests as $guest)
    @php
    				$name = "";
    				foreach($guest->shoppingcart->shoppingcart_questions as $question)
                    {
                        $name .= $question->answer .' ';
                    }
                    $name = rtrim($name);
                    
                    $people = 0;
                    foreach($guest->shoppingcart_product_details as $shoppingcart_product_detail)
                    {
                        $people += $shoppingcart_product_detail->people;
                    }
    @endphp
	<div class="form-check">
    	<input type="checkbox" class="form-check-input" name="guests[]" value="{{ $name }}|{{ $guest->shoppingcart->booking_channel }}|{{ $people }}|{{$guest->shoppingcart_id}}" id="guest_{{ $guest->id }}">
    	<label class="form-check-label" for="guest_{{ $guest->id }}">{{ $name }} - {{ $guest->shoppingcart->booking_channel }} {{ $people }} - {{ $guest->title }}</label>
  	</div>
  	@endforeach
</div>

<div class="form-group">
	<label for="additional">Additional :</label>
	<input type="number" id="additional" name="additional" class="form-control" placeholder="Additional" autocomplete="off" value="0">
</div>



	<button id="submit2" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
</form>



<script language="javascript">
function STORE()
{
	
		
		var error = false;
		$("#submit2").attr("disabled", true);
		$('#submit2').html('<i class="fa fa-spinner fa-spin"></i>');
		var input = ["guide","date","additional"];
	
		$.each(input, function( index, value ) {
  			$('#'+ value).removeClass('is-invalid');
  			$('#span-'+ value).remove();
		});
	
		var guests = $('input[name="guests\\[\\]"]:checked').map(function(i, elem) { return $(this).val(); }).get();

		$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
        	"app": $('#app').val(),
			"guide": $('#guide').val(),
			"date": $('#date').val(),
			"additional": $('#additional').val(),
			"guests": guests,
			
			
        },
		type: 'POST',
		url: '{{ route('route_tourcms_orders.store') }}'
		}).done(function( data ) {
			
			if(data.id=="1")
			{
				
       				$('#dataTableBuilder').DataTable().ajax.reload( null, false );
					$("#result").empty().append('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Success!</b></div>').hide().fadeIn();
       				setTimeout(function (){
  						$.fancybox.close();
  						get_saldo();
  						count_guest();
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
				$("#submit2").attr("disabled", false);
				$('#submit2').html('<i class="fa fa-save"></i> {{ __('Save') }}');
			}
		});
	
	
		return false;
	
	
}
</script>
 @endsection
