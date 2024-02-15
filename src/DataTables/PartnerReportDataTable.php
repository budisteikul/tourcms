<?php

namespace budisteikul\tourcms\DataTables;

use budisteikul\toursdk\Models\Partner;
use budisteikul\toursdk\Models\Shoppingcart;

use budisteikul\toursdk\Helpers\GeneralHelper;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PartnerReportDataTable extends DataTable
{
    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables($query)
            ->addIndexColumn()
            ->editColumn('confirmation_code', function($id){
                    return '<a href="#" onClick="SHOW(\''.$id->id.'\'); return false;"><b>'. $id->confirmation_code .'</b></a>';
                })
            ->editColumn('created_at', function($id){
                    return GeneralHelper::dateFormat($id->created_at,10);
                })
            ->editColumn('booking_status', function($id){
                    
                    if($id->booking_status=="PENDING") return '<span class="badge badge-info font-weight-bold">WAITING FOR PAYMENT</span>';
                    if($id->booking_status=="CANCELED") return '<span class="badge badge-danger font-weight-bold">CANCELED</span>';
                    if($id->booking_status=="CONFIRMED") return '<span class="badge badge-success font-weight-bold">CONFIRMED</span>';

                    //return $id->booking_status;
                })
            ->rawColumns(['confirmation_code','booking_status']);
    }

    
    public function query(Shoppingcart $model): QueryBuilder
    {
        $model = $model->whereNotNull('referer')->with('partners')->orderBy('created_at', 'DESC')->newQuery();
        return $model;
    }

    
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

    protected function getColumns(): array
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

            Column::make('confirmation_code')->title('Transaction ID')->orderable(false)->addClass('align-middle'),
            Column::make('partners.name')->title('Referer')->orderable(false)->addClass('align-middle'),
            Column::make('created_at')->title('Created at')->orderable(false)->addClass('align-middle'),
            Column::make('booking_status')->title('Status')->orderable(false)->addClass('align-middle'),
            
        ];

        
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'PartnerReport_' . date('YmdHis');
    }
}
