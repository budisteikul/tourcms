<?php
namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\ShoppingcartProduct;
use budisteikul\toursdk\Helpers\GeneralHelper;
use budisteikul\toursdk\Helpers\BookingHelper;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ScheduleDataTable extends DataTable
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
                
                ->addColumn('name', function($id){
                    $shoppingcart_id = $id->shoppingcart->id;
                    $question = BookingHelper::get_answer_contact($id->shoppingcart);
                    $name = $question->firstName .' '. $question->lastName;
                    $name = '<a href="#" onClick="SHOW(\''.$shoppingcart_id.'\'); return false;">'. $name .'</a>';
                    return $name;
                })
                ->addColumn('date_text', function($id){
                    $date_text = '<a href="#" onClick="EDIT(\''.$id->id.'\'); return false;">'. GeneralHelper::dateFormat($id->date,10) .'</a>';
                    return $date_text;
                })
                ->addColumn('people', function($id){
                    $people = 0;
                    foreach($id->shoppingcart_product_details as $shoppingcart_product_detail)
                    {
                        $people += $shoppingcart_product_detail->people;
                    }
                    return $people;
                })
                ->addIndexColumn()
                ->rawColumns(['name','date_text']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\ChannelDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ShoppingcartProduct $model)
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
    public function html()
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
            Column::make('date')
                  ->visible(false)
                  ->searchable(false)
                  ->orderable(true),
            Column::computed('DT_RowIndex')
                  ->width(30)
                  ->title('No')
                  ->orderable(false)
                  ->searchable(false)
                  ->render(null)
                  ->addClass('text-center align-middle'),

            Column::make('name')->title('Name')->orderable(false)->addClass('align-middle'),
            Column::make('date_text')->title('Date')->orderable(false)->addClass('align-middle'),
            Column::make('people')->title('Person')->orderable(false)->addClass('align-middle'),
            
            
            
        ];

        
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
