<?php

namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\Shoppingcart;
use budisteikul\toursdk\Helpers\BookingHelper;
use budisteikul\toursdk\Helpers\ContentHelper;
use budisteikul\toursdk\Helpers\ProductHelper;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;

class BookingDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
                ->addIndexColumn()
                ->addColumn('invoice', function ($id){
                    
                    $invoice = ContentHelper::view_invoice($id);
                    $product = ContentHelper::view_product_detail($id);
                    
                    return $invoice . $product ;
                })
                
                ->addColumn('payment', function ($id){
                	if(isset($id->shoppingcart_payment->payment_status))
                	{
                        if($id->shoppingcart_payment->payment_provider=="paypal")
                        {
                            if($id->shoppingcart_payment->payment_status==1)
                            {
                                return '
                <div class="btn-toolbar">
                    <div class="btn-group mr-2 mb-2" role="group">
                        
                        <button id="void-'.$id->id.'" type="button" onClick="STATUS(\''.$id->id.'\',\'void\'); return false;" class="btn btn-sm btn-warning payment"><i class="fa fa-ban"></i> Void</button>
                        <button id="capture-'.$id->id.'" type="button" onClick="STATUS(\''. $id->id .'\',\'capture\')" class="btn btn-sm btn-primary payment"><i class="fa fa-money-check"></i> Capture</button>
                        
                    </div>
                </div>';
                            }
                        }
                	}
                    return BookingHelper::get_paymentStatus($id);
                    
                })
                ->addColumn('action', function ($id) {
                if(isset($id->shoppingcart_payment->payment_status))
                {
                    if($id->shoppingcart_payment->payment_status==1)
                    {
                        return '
                <div class="btn-toolbar justify-content-end">
                    <div class="btn-group mr-2 mb-2" role="group">
                        
                        
                        <button id="btn-del" type="button" onClick="DELETE(\''. $id->id .'\')" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i> Delete</button>
                        
                    </div>
                </div>';
                    }
                }

                if($id->booking_status=='CANCELED')
                {
                    $button_cancel = '';
                }
                else
                {
                    $button_cancel = '<button id="btn-edit" type="button" onClick="CANCEL(\''.$id->id.'\'); return false;" class="btn btn-sm btn-warning"><i class="fa fa-ban"></i> Cancel</button>';
                }
                

                return '
                <div class="btn-toolbar justify-content-end">
                    <div class="btn-group mr-2 mb-2" role="group">
                        
                        '.$button_cancel.'
                        <button id="btn-del" type="button" onClick="DELETE(\''. $id->id .'\')" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i> Delete</button>
                        
                    </div>
                </div>';
                })
                ->rawColumns(['action','invoice','product','payment']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BookingDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Shoppingcart $model)
    {
        return $model->where('booking_status','CONFIRMED')->orWhere('booking_status','CANCELED')->orWhere('booking_status','PENDING')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['title' => '','class' => 'text-center'])
                    ->parameters([
                        'language' => [
                            'paginate' => [
                                'previous'=>'<i class="fa fa-step-backward"></i>',
                                'next'=>'<i class="fa fa-step-forward"></i>',
                                'first'=>'<i class="fa fa-fast-backward"></i>',
                                'last'=>'<i class="fa fa-fast-forward"></i>'
                                ]
                            ],
                        'pagingType' => 'full_numbers',
                        'responsive' => true,
                        'order' => [0,'desc']
                    ])
                    ->ajax('/'.request()->path());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            ["name" => "created_at", "title" => "created_at", "data" => "created_at", "orderable" => true, "visible" => false,'searchable' => false],
            ["name" => "invoice", "title" => "Invoice", "data" => "invoice", "orderable" => false],
            ["name" => "payment", "title" => "Payment", "data" => "payment", "orderable" => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Booking_' . date('YmdHis');
    }
}
