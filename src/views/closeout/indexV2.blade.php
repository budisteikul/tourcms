@extends('coresdk::layouts.app')
@section('content')
@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<style type="text/css">
	div.ui-datepicker{
 font-size:16px;
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
                                console.log(selectedDate);
                                $('#dataTableBuilder').DataTable().ajax.url("?date="+ selectedDate).load();

                            }
                });
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
