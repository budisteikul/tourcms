@extends('coresdk::layouts.app')
@section('content')
@push('scripts')
<script src="{{ asset('js/jquery-ui.js') }}"></script>
  <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
<style type="text/css">

div.ui-datepicker{
    font-size: 20px;
}

@media screen and (max-width: 767px) {
    div.ui-datepicker{
    font-size:4.5vw;
}
  
}

</style>
<script type="text/javascript">
function UPDATE(bokun_id,date,status)
  {
    var table = $('#dataTableBuilder').DataTable();
    $.ajax({
    data: {
          "_token": $("meta[name=csrf-token]").attr("content"),
          "date":date,
          "status":status,
          "bokun_id": bokun_id
        },
    type: 'POST',
    url: "{{ route('route_tourcms_closeout_v2.store') }}"
    }).done(function( data ) {
      if(data.id=="1")
      {
        $('#dataTableBuilder').DataTable().ajax.url("?date="+ date).load();
      }
    });
  }	

function schedule(month,year)
{
      
      $.ajax({
      beforeSend: function(xhr) {
          xhr.setRequestHeader("Authorization", "Bearer {{$token_api}}");
          xhr.setRequestHeader('Content-Type', 'application/json');
          xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
      },
      data: JSON.stringify({
          "month": month,
          "year": year
      }),
      type: 'POST',
      url: '{{env("APP_API_URL")}}/schedule'
      }).done(function( data ) {
        
        $.each(data.data, function( index, value ) {
            //alert( index + ": " + value );
            if(value.total>0)
            {
                $('*[data-date="'+value.date.replace(/^0+/, "") +'"]').addClass('bg-success bg-opacity-25');
                console.log(value.date +'  '+ value.total);
            }
            
        });

       //console.log(data.data);
        
      });

    return false;
}
</script>
@endpush
<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Close Out</div>
                <div class="card-body">
        		
                <div class="container ml-0 pl-0">
                <div class="row">
                	<div class="col">
                		<div class="form-group">   
				   
                <div class='input-group' id='datetimepicker1'>
                    <div id="date"></div>
                </div>
 		<script type="text/javascript">
 			//$('#date').on('dp.change', function(e){ console.log(e.date); })

            $(function () {
                $('#date').datepicker({
                	dateFormat: "yy-mm-dd",
                	onSelect: function(selectedDate) {
                                //schedule({{date('m')}},{{date('Y')}});
                                schedule(selectedDate.substr(5,2),selectedDate.substr(0,4));
                                $('#dataTableBuilder').DataTable().ajax.url("?date="+ selectedDate).load();

                            },
                    onChangeMonthYear: function (year,month) {
                        schedule(month,year);
                        //console.log(year);
                    }
                });
                schedule({{date('m')}},{{date('Y')}});
                //$('*[data-date="22"]').addClass('bg-success')
            });
        </script>    
</div> 
                    </div>
                    
                </div>
                </div>  
       
      	
        <hr>
        
		{!! $dataTable->table(['class'=>'table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table']) !!}
		
                </div>
            </div>
        </div>
    </div>

{!! $dataTable->scripts() !!}

@endsection
