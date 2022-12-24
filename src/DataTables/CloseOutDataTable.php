<?php

namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\CloseOut;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use budisteikul\toursdk\Helpers\GeneralHelper;
use budisteikul\toursdk\Models\Product;

class CloseOutDataTable extends DataTable
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
                ->editColumn('date', function($id){
                    return GeneralHelper::dateFormat($id->date,4);
                })
                ->editColumn('bokun_id', function($id){
                    $product = Product::where('bokun_id', $id->bokun_id)->first();
                    return $product->name;
                })
                ->addColumn('action', function ($id) {
                return '
                <div class="btn-toolbar justify-content-end">
                    <div class="btn-group mr-2" role="group">
                        
                        <button id="btn-edit" type="button" onClick="EDIT(\''.$id->id.'\'); return false;" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> Edit</button>
                        <button id="btn-del" type="button" onClick="DELETE(\''. $id->id .'\')" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i> Delete</button>
                        
                    </div>
                </div>';
                })
                ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CloseOutDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CloseOut $model)
    {
        CloseOut::where('date','<',date('Y-m-d'))->delete();
        return $model->where('date','>=',date('Y-m-d'))->orderBy('date', 'ASC')->newQuery();
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
            ["name" => "date", "title" => "Date", "data" => "date", "orderable" => false, "class" => "align-middle"],
            ["name" => "bokun_id", "title" => "Product", "data" => "bokun_id", "orderable" => false, "class" => "align-middle"],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CloseOutDataTable_' . date('YmdHis');
    }
}
