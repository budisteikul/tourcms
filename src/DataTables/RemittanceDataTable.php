<?php
namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\ShoppingcartProduct;
use budisteikul\toursdk\Helpers\BookingHelper;
use budisteikul\toursdk\Helpers\GeneralHelper;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RemittanceDataTable extends DataTable
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
                ->addColumn('booking_channel', function($id){
                    return $id->shoppingcart->booking_channel;
                })
                ->addColumn('authorization_id', function($id){
                    return $id->shoppingcart->shoppingcart_payment->authorization_id;
                })
                ->addColumn('payment_provider', function($id){
                    if(isset($id->shoppingcart->shoppingcart_payment->payment_provider))
                    {
                        return strtoupper($id->shoppingcart->shoppingcart_payment->payment_provider);
                    }
                    else
                    {
                        return '';
                    }
                    
                })
                ->addColumn('amount', function($id){
                    
                    
                    if(isset($id->shoppingcart->shoppingcart_payment->amount) && isset($id->shoppingcart->shoppingcart_payment->currency))
                    {
                        $amount = $id->due_now;
                        $amount = $amount / $id->shoppingcart->shoppingcart_payment->rate;
                        //number_format((float)$shoppingcart->due_now / $amount, 2, '.', '');
                        $amount = number_format((float)$amount, 2, '.', '');
                        $amount = number_format($amount,2);
                        return $amount .' '. $id->shoppingcart->shoppingcart_payment->currency;
                        //return $id->shoppingcart->shoppingcart_payment->amount .' '. $id->shoppingcart->shoppingcart_payment->currency;
                    }
                    else
                    {
                        return '';
                    }
                    
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
                ->rawColumns(['name']);
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
        })->whereNotNull('date')->orderBy('date', 'DESC')->newQuery();
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

            Column::make('name')->title('Name')->orderable(false)->addClass('align-middle'),
            Column::make('date_text')->title('Date')->orderable(false)->addClass('align-middle'),
            Column::make('people')->title('People')->orderable(false)->addClass('align-middle'),
            Column::make('payment_provider')->title('Payment Provider')->orderable(false)->addClass('align-middle'),
            Column::make('authorization_id')->title('Authorization ID')->orderable(false)->addClass('align-middle'),
            Column::make('amount')->title('Amount')->orderable(false)->addClass('align-middle'),
            
            
            
        ];

        
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Remittance_' . date('YmdHis');
    }
}
