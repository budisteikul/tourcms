@extends('coresdk::layouts.app')
@section('content')
@push('scripts')
<script type="text/javascript">
  function SHOW(id)
  {
    $.fancybox.open({
          type: 'ajax',
          src: '{{ route('route_tourcms_booking.index') }}/'+ id,
          modal: true,
          touch: false,
          autoFocus: false
      }); 
  }

  function REFUND(id)
  {
    $.confirm({
        title: 'Warning',
        content: 'Are you sure want to refund this transaction?',
        type: 'red',
      icon: 'fa fa-ban',
        buttons: {   
            ok: {
                text: "OK",
                btnClass: 'btn-danger',
                keys: ['enter'],
                action: function(){
                     var table = $('#dataTableBuilder').DataTable();
                     $('#btn-del-'+id).attr("disabled", true);
                     $('#btn-del-'+id).html('<i class="fa fa-spinner fa-spin"></i>');
                     $.ajax({
                        data: {
                          "_token": $("meta[name=csrf-token]").attr("content"),
                          "action": 'refund',
                        },
                        type: 'PUT',
                        url: '{{ route('route_tourcms_cancel.index') }}/'+ id
                        }).done(function( data ) {
                            if(data.id=="1")
                            {
                                table.ajax.reload( null, false );
                            }
                        });

                }
            },
            cancel: function(){
                  console.log('the user clicked cancel');
            }
        }
    });
    
  }
</script>
@endpush
<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Refund</div>
                <div class="card-body">
        		
                <div class="container ml-0 pl-0">
                <div class="row">
                	<div class="col">
                    <b>Stripe Hold : {{$stripe_amount}}</b><br />
                    <b>Paypal Hold : {{$paypal_amount}}</b><br />
                    <b>Wise Hold : {{$wise_amount}}</b><br/>
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
