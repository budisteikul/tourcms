<?php
namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\Shoppingcart;
use budisteikul\toursdk\Helpers\GeneralHelper;

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
                ->editColumn('subtotal', function($id){
                    return GeneralHelper::numberFormat($id->subtotal);
                })
                ->editColumn('discount', function($id){
                    return GeneralHelper::numberFormat($id->discount);
                })
                ->editColumn('total', function($id){
                    return GeneralHelper::numberFormat($id->total);
                })
                ->editColumn('confirmation_code', function($id){
                    return '<a href="#" onClick="SHOW(\''.$id->id.'\'); return false;"><b>'. $id->confirmation_code .'</b></a>';
                })
                ->editColumn('created_at', function($id){
                    return GeneralHelper::dateFormat($id->created_at,10);
                })
                ->addColumn('action', function ($id) {

                if(isset($id->shoppingcart_payment->payment_status))
                    {
                        if($id->shoppingcart_payment->payment_provider=="paypal")
                        {
                            if($id->shoppingcart_payment->payment_status==1)
                            {
                                return '
                <div class="btn-toolbar justify-content-end">
                    <div class="btn-group mr-2 mb-2" role="group">
                        
                        <button id="void-'.$id->id.'" type="button" onClick="STATUS(\''.$id->id.'\',\'void\'); return false;" class="btn btn-sm btn-warning payment"><i class="fa fa-ban"></i> Void</button>
                        <button id="capture-'.$id->id.'" type="button" onClick="STATUS(\''. $id->id .'\',\'capture\')" class="btn btn-sm btn-primary payment"><i class="fa fa-money-check"></i> Capture</button>
                        
                    </div>
                </div>';
                            }
                        }
                    }
                

                $button_cancel = '';
                $button_confirm = '';
                $button_delete = '';

                if($id->booking_status=="CANCELED")
                {
                    $button_confirm = '<button id="btn-edit" type="button" onClick="CONFIRM(\''.$id->id.'\'); return false;" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Confirm</button>';
                }
                if($id->booking_status=="CONFIRMED")
                {
                    $button_cancel = '<button id="btn-edit" type="button" onClick="CANCEL(\''.$id->id.'\'); return false;" class="btn btn-sm btn-warning"><i class="fa fa-ban"></i> Cancel</button>';
                }
                if($id->booking_status!="PENDING")
                {
                    $button_delete = '<button id="btn-del" type="button" onClick="DELETE(\''. $id->id .'\')" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i> Delete</button>';
                }

                return '
                <div class="btn-toolbar justify-content-end">
                    <div class="btn-group mr-2 mb-2" role="group">
                        
                        '.$button_confirm.'
                        '.$button_cancel.'
                        '.$button_delete.'

                        
                        
                    </div>
                </div>';
                })
                ->rawColumns(['action','confirmation_code']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BookingDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Shoppingcart $model)
    {
        return $model->newQuery();
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
            ["name" => "confirmation_code", "title" => "Transaction ID", "data" => "confirmation_code", "orderable" => false],
            ["name" => "booking_channel", "title" => "Channel", "data" => "booking_channel", "orderable" => false],
            ["name" => "created_at", "title" => "Created", "data" => "created_at", "orderable" => false],
            ["name" => "subtotal", "title" => "Subtotal", "data" => "subtotal", "orderable" => false],
            ["name" => "discount", "title" => "Discount", "data" => "discount", "orderable" => false],
            ["name" => "total", "title" => "Total", "data" => "total", "orderable" => false],
            ["name" => "booking_status", "title" => "Status", "data" => "booking_status", "orderable" => false],
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
