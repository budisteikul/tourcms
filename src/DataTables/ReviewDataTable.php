<?php

namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\Review;
use budisteikul\toursdk\Helpers\GeneralHelper;
use budisteikul\toursdk\Helpers\ReviewHelper;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReviewDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query): EloquentDataTable
    {
        return datatables($query)
                ->addIndexColumn()
                ->editColumn('user', function($id){
                    return '<a href="#" onClick="SHOW(\''.$id->id.'\'); return false;"><b>'. $id->user .'</b></a>';
                })
                ->editColumn('date', function($id){
                    return GeneralHelper::dateFormat($id->date,4);
                })
                ->addColumn('channel', function($id){
                    return $id->channel->name;
                })
                ->addColumn('product', function($id){
                    return $id->product->name;
                })
                ->addColumn('rate', function($id){
                    return ReviewHelper::star($id->rating);
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
                ->rawColumns(['action','rate','user']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ReviewDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Review $model): QueryBuilder
    {
        return $model->with('product')->with('channel')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
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
    protected function getColumns(): array
    {
        return [
            ["name" => "created_at", "title" => "created_at", "data" => "created_at", "orderable" => true, "visible" => false,'searchable' => false],
            ["name" => "DT_RowIndex", "title" => "No", "data" => "DT_RowIndex", "orderable" => false, "render" => null,'searchable' => false, 'width' => '30px'],
            ["name" => "user", "title" => "User", "data" => "user", "orderable" => false],
            ["name" => "date", "title" => "Date", "data" => "date", "orderable" => false],
            ["name" => "channel.name", "title" => "Channel", "data" => "channel", "orderable" => false],
            ["name" => "product.name", "title" => "Product", "data" => "product", "orderable" => false],
            ["name" => "rate", "title" => "Rate", "data" => "rate", "orderable" => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Review_' . date('YmdHis');
    }
}
