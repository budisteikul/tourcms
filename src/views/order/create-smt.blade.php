<div class="h-100 w-100 pl-2 pr-2 pt-0" style="overflow-x:hidden;"> 		

    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
                <div class="card-header pr-0">
                <div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        Create Purchase Orders for General Tour
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
    <label for="tour">Tour</label>
    <select class="form-control" id="tour">
      <option value="1">SEMARANG FOOD TOUR</option>
      <option value="2">BALI FOOD TOUR</option>
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
	<label for="total">Total Supplier :</label>
	<input type="number" id="total" name="total" class="form-control" placeholder="" autocomplete="off" value="0">
</div> 

<div class="form-group">
	<label for="additional">Total Additional :</label>
	<input type="number" id="additional" name="additional" class="form-control" placeholder="" autocomplete="off" value="0">
</div> 

	<button id="submit2" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
</form>



	</div>
</div>       
		
        
        		
        </div>
    </div>

</div>

<script language="javascript">
function STORE()
{
	//if(app==1)
	//{
		var error = false;
		$("#submit2").attr("disabled", true);
		$('#submit2').html('<i class="fa fa-spinner fa-spin"></i>');
		var input = ["total","additional"];
	
		$.each(input, function( index, value ) {
  			$('#'+ value).removeClass('is-invalid');
  			$('#span-'+ value).remove();
		});
	
		var guests = $('input[name="guests\\[\\]"]:checked').map(function(i, elem) { return $(this).val(); }).get();

		$.ajax({
		data: {
        	"_token": $("meta[name=csrf-token]").attr("content"),
			"guests": guests,
			"date": $('#date').val(),
			"tour": $('#tour').val(),
			"total": $('#total').val(),
			"additional": $('#additional').val(),
			"app": 999
			
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
	//}
	
}
</script>
