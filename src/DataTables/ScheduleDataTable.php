<?php
namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\ShoppingcartProduct;
use budisteikul\toursdk\Helpers\GeneralHelper;
use budisteikul\toursdk\Helpers\BookingHelper;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class ScheduleDataTable extends DataTable
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
                
                ->addColumn('name', function($id){
                    $shoppingcart_id = $id->shoppingcart->id;
                    $question = BookingHelper::get_answer_contact($id->shoppingcart);
                    $name = $question->firstName .' '. $question->lastName;
                    $name = '<a href="#" onClick="SHOW(\''.$shoppingcart_id.'\'); return false;">'. $name .'</a>';
                    return $name;
                })
                ->addColumn('date_text', function($id){
                    return GeneralHelper::dateFormat($id->date,10);
                })
                ->addColumn('booking_channel', function($id){
                    return $id->shoppingcart->booking_channel;
                })
                ->addColumn('people', function($id){
                    $people = 0;
                    foreach($id->shoppingcart_product_details as $shoppingcart_product_detail)
                    {
                        $people += $shoppingcart_product_detail->people;
                    }
                    return $people;
                })
                ->addColumn('action', function ($id) {
                return '
                <div class="btn-toolbar justify-content-end">
                    <div class="btn-group mr-2" role="group">
                        
                        <button id="btn-edit" type="button" onClick="EDIT(\''.$id->id.'\'); return false;" class="btn btn-sm btn-success  pt-0 pb-0 pl-1 pr-1"><i class="fa fa-edit"></i> Edit Schedule</button>
                        
                    </div>
                </div>';
                })
                ->addIndexColumn()
                ->rawColumns(['name','date_text','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\ChannelDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ShoppingcartProduct $model): QueryBuilder
    {
        $model = $model->whereHas('shoppingcart', function ($query) {
                return $query->where('booking_status','CONFIRMED');
        })->where('date', '>=', date('Y-m-d'))->whereNotNull('date')->newQuery();
        return $model;
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
                        'order' => [0,'asc']
                    ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    public function getColumns(): array
    {
        if(Auth::user()->id==1)
        {
            return [
            Column::make('date')
                  ->visible(false)
                  ->searchable(false)
                  ->orderable(true),
            Column::computed('DT_RowIndex')
                  ->width(30)
                  ->title('No')
                  ->orderable(false)
                  ->searchable(false)
                  ->addClass('text-center align-middle'),

            Column::make('name')->title('Main Contact')->orderable(false)->addClass('align-middle'),
            Column::make('booking_channel')->title('Channel')->orderable(false)->addClass('align-middle'),
            Column::make('date_text')->title('Date')->orderable(false)->addClass('align-middle'),
            Column::make('people')->title('People')->orderable(false)->addClass('align-middle'),

            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(220)
                  ->addClass('text-center'),
            ];
        }
        else
        {
            return [
            Column::make('date')
                  ->visible(false)
                  ->searchable(false)
                  ->orderable(true),
            Column::computed('DT_RowIndex')
                  ->width(30)
                  ->title('No')
                  ->orderable(false)
                  ->searchable(false)
                  ->addClass('text-center align-middle'),

            Column::make('name')->title('Main Contact')->orderable(false)->addClass('align-middle'),
            Column::make('booking_channel')->title('Channel')->orderable(false)->addClass('align-middle'),
            Column::make('date_text')->title('Date')->orderable(false)->addClass('align-middle'),
            Column::make('people')->title('People')->orderable(false)->addClass('align-middle')
            ];
        }
        

        
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Schedule_' . date('YmdHis');
    }
}
