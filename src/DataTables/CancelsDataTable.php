<?php

namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\ShoppingcartCancellation as Cancel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use budisteikul\toursdk\Helpers\GeneralHelper;

class CancelsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables($query)
            ->addIndexColumn()
            ->editColumn('shoppingcart.confirmation_code', function($id){
                    return '<a href="#" onClick="SHOW(\''.$id->shoppingcart_id.'\'); return false;"><b>'. $id->shoppingcart->confirmation_code .'</b></a>';
                })
            ->addColumn('amount_text', function($id){
                    return GeneralHelper::numberFormat($id->amount);
                })
            ->addColumn('refund_text', function($id){
                    return GeneralHelper::numberFormat($id->refund);
                })
            ->addColumn('action', function ($id) {
                return '
                <div class="btn-toolbar justify-content-end">
                    <div class="btn-group mr-2" role="group">
                        
                        <button id="btn-edit" type="button" onClick="EDIT(\''.$id->id.'\'); return false;" class="btn btn-sm btn-success pt-0 pb-0 pl-1 pr-1"><i class="fa fa-edit"></i> Edit</button>
                        <button id="btn-del" type="button" onClick="DELETE(\''. $id->id .'\')" class="btn btn-sm btn-danger pt-0 pb-0 pl-1 pr-1"><i class="fa fa-trash-alt"></i> Delete</button>
                        
                    </div>
                </div>';
            })
            ->rawColumns(['action','shoppingcart.confirmation_code']);
    }

    public function query(Cancel $model): QueryBuilder
    {
        return $model->with('shoppingcart')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
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
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('created_at')
                  ->visible(false)
                  ->searchable(false),
            Column::computed('DT_RowIndex')
                  ->width(30)
                  ->title('No')
                  ->orderable(false)
                  ->searchable(false)
                  ->addClass('text-center align-middle'),

            Column::make('shoppingcart.confirmation_code')->title('Transaction ID')->orderable(false)->addClass('align-middle'),
            Column::make('currency')->title('Currency')->orderable(false)->addClass('align-middle'),
            Column::make('amount_text')->title('Amount')->orderable(false)->addClass('align-middle'),
            Column::make('refund_text')->title('Refund')->orderable(false)->addClass('align-middle'),
            
            /*
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(300)
                  ->addClass('text-center'),
            */
        ];

    }

    
    protected function filename(): string
    {
        return 'Cancels_' . date('YmdHis');
    }
}
