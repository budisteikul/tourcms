<?php

namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\Product;
use budisteikul\toursdk\Models\Category;
use budisteikul\toursdk\Helpers\CategoryHelper;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use budisteikul\toursdk\Helpers\GeneralHelper;
use budisteikul\toursdk\Helpers\ProductHelper;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
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
                ->addColumn('deposit', function($id){
                    if($id->deposit_percentage)
                    {
                        return $id->deposit_amount ."%";
                    }
                    else
                    {
                        return GeneralHelper::numberFormat($id->deposit_amount);
                    }
                })
				->editColumn('category_id', function($id){
                    return CategoryHelper::nameCategory($id->category_id,"-");
                })
				->addColumn('action', function ($id) {

                
                $button_refresh = '<button id="refresh-'.$id->id.'" type="button" onClick="SYNC(\''.$id->id.'\'); return false;" class="btn btn-sm btn-primary"><i class="fas fa-sync-alt"></i></i> Sync</button>';
                $button_delete = '<button id="btn-del" type="button" onClick="DELETE(\''. $id->id .'\')" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i> Delete</button>';
                if(ProductHelper::have_review($id)) $button_delete = '';

                  
                return '
                <div class="btn-toolbar justify-content-end">
                    <div class="btn-group mr-2 mb-2" role="group">
                        
                        '. $button_refresh .'

                        <button id="btn-edit" type="button" onClick="EDIT(\''.$id->id.'\'); return false;" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> Edit</button>
                        
                        '. $button_delete .'
                        
                    </div>
                </div>';
                })
                ->rawColumns(['action','category_id']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\ProductDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
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
            ["name" => "name", "title" => "Name", "data" => "name"],
			["name" => "category_id", "title" => "Category", "data" => "category_id", "orderable" => false],
            ["name" => "deposit", "title" => "Deposit", "data" => "deposit", "orderable" => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Product_' . date('YmdHis');
    }
}
