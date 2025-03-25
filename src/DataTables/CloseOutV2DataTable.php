<?php

namespace budisteikul\tourcms\DataTables;

use budisteikul\tourcms\Models\CloseOut;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use budisteikul\tourcms\Helpers\GeneralHelper;
use budisteikul\tourcms\Helpers\BokunHelper;
use budisteikul\tourcms\Models\Product;
use budisteikul\tourcms\Models\ShoppingcartProduct;
use budisteikul\tourcms\Models\ShoppingcartProductDetail;
use Illuminate\Http\Request;

class CloseOutV2DataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable(QueryBuilder $query,Request $request): EloquentDataTable
    {
        $date = $request->input('date');
        if($date=="") $date = date('Y-m-d');
        return datatables($query)
                ->addIndexColumn()
                ->addColumn('bookings', function ($id) use ($date) {
                    $total = 0;
                    $products = ShoppingcartProduct::whereHas('shoppingcart', function ($query) {
                    return $query->where('booking_status','CONFIRMED');
                 })->where('product_id',$id->bokun_id)->whereDate('date',$date)->get();
                    foreach($products as $product)
                    {
                        $product_details = ShoppingcartProductDetail::where('shoppingcart_product_id',$product->id)->get();
                        foreach($product_details as $product_detail)
                        {
                            $total += $product_detail->people;
                        }
                    }
                    return $total;
                })
                ->addColumn('action', function ($id) use ($date) {
                    $closeout = CloseOut::where('bokun_id',$id->bokun_id)->where('date',$date)->first();
                    $status = 'open';
                    if($closeout) $status = 'closed';
                    if($status=="open")
                    {
                        
                        $contents = BokunHelper::get_calendar_admin($id->bokun_id,substr($date,0,4),substr($date,5,2));
                        foreach($contents->weeks as $week)
                        {
                            foreach($week->days as $day)
                            {
                                if($day->fullDate==$date)
                                {

                                    if($day->fullDate < date('Y-m-d'))
                                    {
                                        return '<button type="button" class="btn btn-sm btn-secondary pt-0 pb-0 pl-1 pr-1" disabled>Closed</button>';
                                    }

                                    if($day->empty==1)
                                    {
                                        return '<button onClick="UPDATE(\''. $id->bokun_id .'\',\''. $date .'\',\'3\')" type="button" class="btn btn-sm btn-secondary pt-0 pb-0 pl-1 pr-1">Closed by Server</button>';
                                    } 
                                }
                            }
                        }

                        return '<button onClick="UPDATE(\''. $id->bokun_id .'\',\''. $date .'\',\'0\')" type="button" class="btn btn-sm btn-success pt-0 pb-0 pl-1 pr-1">Open</button>';
                        
                    }
                    else
                    {
                        return '<button onClick="UPDATE(\''. $id->bokun_id .'\',\''. $date .'\',\'1\')" type="button" class="btn btn-sm btn-danger pt-0 pb-0 pl-1 pr-1">Closed</button>';
                    }
                    //return '<div id="closeout_'. $id->bokun_id .'">'. $date .' - '. $status .'</div>';
                })
                ->rawColumns(['bookings','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CloseOutDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model): QueryBuilder
    {
        CloseOut::where('date','<',date('Y-m-d'))->delete();
        $products = Product::query();
        return $products;
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
                    ->dom('rt')
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
            

            Column::make('name')->title('Name')->orderable(false)->addClass('align-middle'),
            Column::make('bookings')->title('Bookings')->orderable(false)->addClass('align-middle'),
            
            Column::computed('action')
                  ->title('Status')
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
        return 'CloseOutDataTable_' . date('YmdHis');
    }
}
