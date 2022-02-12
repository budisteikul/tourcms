<?php
namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\Shoppingcart;
use budisteikul\toursdk\Helpers\GeneralHelper;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;

class BookingDataTable extends DataTable
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
                ->editColumn('created_at', function($id){
                    return GeneralHelper::dateFormat($id->created_at,10);
                })
                ->addColumn('action', function ($id) {
                return '
                <div class="btn-toolbar justify-content-end">
                    <div class="btn-group mr-2 mb-2" role="group">
                        
                        <button id="btn-del" type="button" onClick="DELETE(\''. $id->id .'\')" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i> Delete</button>
                        
                    </div>
                </div>';
                })
                ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BookingDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Shoppingcart $model)
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
                    ->addAction(['title' => '','class' => 'text-center'])
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
            ["name" => "confirmation_code", "title" => "Transaction ID", "data" => "confirmation_code", "orderable" => false],
            ["name" => "booking_channel", "title" => "Booking channel", "data" => "booking_channel", "orderable" => false],
            ["name" => "created_at", "title" => "Created", "data" => "created_at", "orderable" => false],
            ["name" => "subtotal", "title" => "Subtotal", "data" => "subtotal", "orderable" => false],
            ["name" => "discount", "title" => "Discount", "data" => "discount", "orderable" => false],
            ["name" => "total", "title" => "Total", "data" => "total", "orderable" => false],
            ["name" => "booking_status", "title" => "Status", "data" => "booking_status", "orderable" => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Booking_' . date('YmdHis');
    }
}
