<?php

namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\Disbursement;
use budisteikul\toursdk\Helpers\GeneralHelper;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DisbursementDataTable extends DataTable
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
                ->editColumn('amount', function($id){
                    return GeneralHelper::numberFormat($id->amount);
                })
                ->editColumn('transaction_id', function($id){
                    return '<a href="#" onClick="SHOW(\''.$id->id.'\'); return false;"><b>'. $id->transaction_id .'</b></a>';
                })
                ->addColumn('action', function ($id) {
                
                $button_transfer = '';
                

                if($id->status==0) $button_transfer = '<button id="btn-trans-'.$id->id.'" type="button" onClick="TRANSFER(\''. $id->id .'\')" class="btn btn-sm btn-primary"><i class="far fa-money-bill-alt"></i> Transfer</button>';

                $button_delete = '<button id="btn-del-'.$id->id.'" type="button" onClick="DELETE(\''. $id->id .'\')" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i> Delete</button>';
                
                return '
                <div class="btn-toolbar justify-content-end">
                    <div class="btn-group mr-2 mb-2" role="group">
                        
                        '. $button_transfer .'
                        
                        '. $button_delete .'
                        
                    </div>
                </div>';
                })
                ->rawColumns(['action','transaction_id']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DisbursementDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Disbursement $model)
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
                    ->addAction(['title' => '','width' => '300px','class' => 'text-center'])
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
            ["name" => "DT_RowIndex", "title" => "No", "data" => "DT_RowIndex", "orderable" => false, "render" => null,'searchable' => false, 'width' => '30px'],
            ["name" => "transaction_id", "title" => "Transaction ID", "data" => "transaction_id"],
            ["name" => "vendor_name", "title" => "Vendor Name", "data" => "vendor_name"],
            ["name" => "amount", "title" => "Amount", "data" => "amount"],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Disbursement_' . date('YmdHis');
    }
}
