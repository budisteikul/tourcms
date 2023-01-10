<?php

namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\Review;
use budisteikul\toursdk\Helpers\GeneralHelper;
use budisteikul\toursdk\Helpers\ReviewHelper;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
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
    public function dataTable(QueryBuilder $query): EloquentDataTable
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
                        
                        <button id="btn-edit" type="button" onClick="EDIT(\''.$id->id.'\'); return false;" class="btn btn-sm btn-success pt-0 pb-0 pl-1 pr-1"><i class="fa fa-edit"></i> Edit</button>
                        <button id="btn-del" type="button" onClick="DELETE(\''. $id->id .'\')" class="btn btn-sm btn-danger pt-0 pb-0 pl-1 pr-1"><i class="fa fa-trash-alt"></i> Delete</button>
                        
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

    /**
     * Get columns.
     *
     * @return array
     */
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

            Column::make('user')->title('User')->orderable(false)->addClass('align-middle'),
            Column::make('date')->title('Date')->orderable(false)->addClass('align-middle'),
            Column::make('channel')->title('Channel')->orderable(false)->addClass('align-middle'),
            Column::make('product')->title('Product')->orderable(false)->addClass('align-middle'),
            Column::make('rate')->title('Rate')->orderable(false)->addClass('align-middle'),
            
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(220)
                  ->addClass('text-center'),
            
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
