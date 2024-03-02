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

function EDIT(id)
  {
    $.fancybox.open({
          type: 'ajax',
          src: '{{ route('route_tourcms_schedule.index') }}/'+ id +'/edit',
          modal: true,
          touch: false,
          autoFocus: false
      });
    
  }

function PAID(id,transaction_id)
  {
    $.confirm({
        title: 'Warning',
        content: 'Are you sure want to set as paid on order with booking number '+ transaction_id +'?',
        type: 'green',
      icon: 'fa fa-ban',
        buttons: {   
            ok: {
                text: "OK",
                btnClass: 'btn-success',
                keys: ['enter'],
                action: function(){
                     var table = $('#dataTableBuilder').DataTable();
                     $("#btn-paid").attr("disabled", true);
                     $('#btn-paid').html('<i class="fa fa-spinner fa-spin"></i>');
                     $.ajax({
                        data: {
                          "_token": $("meta[name=csrf-token]").attr("content"),
                          "action": 'paid',
      
                        },
                        type: 'PUT',
                        url: '{{ route('route_tourcms_booking.index') }}/'+ id
                        }).done(function( data ) {
                          table.ajax.reload( null, false );
                          setTimeout(function (){
                              $.fancybox.close();
                            }, 1000);
                        });

                }
            },
            cancel: function(){
                  console.log('the user clicked cancel');
            }
        }
    });
    
  }
  
function CANCEL(id,transaction_id)
  {
    $.confirm({
        title: 'Warning',
        content: 'Are you sure want to cancel order with booking number '+ transaction_id +'?',
        type: 'red',
      icon: 'fa fa-ban',
        buttons: {   
            ok: {
                text: "OK",
                btnClass: 'btn-danger',
                keys: ['enter'],
                action: function(){
                     var table = $('#dataTableBuilder').DataTable();
                     $("#btn-del").attr("disabled", true);
                     $('#btn-del').html('<i class="fa fa-spinner fa-spin"></i>');
                     $.ajax({
                        data: {
                          "_token": $("meta[name=csrf-token]").attr("content"),
                          "action": 'cancel',
      
                        },
                        type: 'PUT',
                        url: '{{ route('route_tourcms_booking.index') }}/'+ id
                        }).done(function( data ) {
                          table.ajax.reload( null, false );
                          setTimeout(function (){
                              $.fancybox.close();
                            }, 1000);
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
                <div class="card-header">Schedule</div>
                <div class="card-body">
        		
                <div class="container ml-0 pl-0">
                
                </div>  
      
        <hr>
        
		{!! $dataTable->table(['class'=>'table table-sm table-bordered table-hover table-striped table-responsive w-100 d-block d-md-table']) !!}
		
                </div>
            </div>
        </div>
    </div>

{!! $dataTable->scripts() !!}

@endsection
