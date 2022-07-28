<?php
namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\ShoppingcartProduct;
use budisteikul\toursdk\Helpers\GeneralHelper;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PastDataTable extends DataTable
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
                ->editColumn('title', function($id){
                    $shoppingcart_id = $id->shoppingcart->id;
                    $title = '<a href="#" onClick="SHOW(\''.$shoppingcart_id.'\'); return false;">'. $id->title .'</a>';
                    return $title;
                })
                ->addColumn('booking_channel', function($id){
                    return $id->shoppingcart->booking_channel;
                })
                ->addColumn('amount', function($id){
                    return $id->shoppingcart->shoppingcart_payment->amount;
                })
                ->addColumn('date_text', function($id){
                    return GeneralHelper::dateFormat($id->date,10);
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
                ->rawColumns(['title']);
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
                return $query->where('booking_status','CONFIRMED')->where('booking_channel','WEBSITE');
        })->where('date', '<=', date('Y-m-d'))->whereNotNull('date')->orderBy('date', 'DESC')->newQuery();
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
            ["name" => "date", "title" => "Date", "data" => "date", 'orderable' => true, "visible" => false],
            ["name" => "DT_RowIndex", "title" => "No", "data" => "DT_RowIndex", "orderable" => false, "render" => null,'searchable' => false, 'width' => '30px'],
            ["name" => "date_text", "title" => "Date", "data" => "date_text", 'orderable' => false],
            ["name" => "people", "title" => "People", "data" => "people", 'orderable' => false],
            ["name" => "amount", "title" => "Amount", "data" => "amount", 'orderable' => false],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Past_' . date('YmdHis');
    }
}
