<?php

namespace budisteikul\tourcms\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use budisteikul\tourcms\Models\fin_transactions;
use budisteikul\tourcms\Models\fin_categories;
use budisteikul\tourcms\Helpers\GeneralHelper;
use budisteikul\tourcms\Models\Transfer;
use budisteikul\tourcms\Helpers\AccHelper;

class TransactionsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        //nameCategory($id,$separator)
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('date_text', function($id){
                    return GeneralHelper::dateFormat($id->date,4);
                })
            ->editColumn('name', function($id){
                    return AccHelper::nameCategory($id->category_id,"-");
            })
            ->editColumn('amount', function($id){
                    return number_format($id->amount, 0, ',', '.');
            })
             ->editColumn('status', function($id){
                    if($id->status=="1")
                    {
                        return '<span class="badge badge-success font-weight-bold">Done</span>';
                    }
                    else
                    {
                        return '<span class="badge badge-warning font-weight-bold">Pending</span>';
                    }
            })
			->addColumn('action', function ($id) {
                        //if($id->date < date('Y-m-01'))
                        //{
                            //return '';
                        //}
                        //else
                        //{
                            return '<div class="btn-toolbar justify-content-end"><div class="btn-group mr-2 mb-0" role="group"><button id="btn-edit" type="button" onClick="EDIT(\''.$id->id.'\'); return false;" class="btn btn-sm btn-success pt-0 pb-0 pl-1 pr-1"><i class="fa fa-edit"></i> Edit</button><button id="btn-del" type="button" onClick="DELETE(\''. $id->id .'\')" class="btn btn-sm btn-danger pt-0 pb-0 pl-1 pr-1"><i class="fa fa-trash-alt"></i> Delete</button></div><div class="btn-group mb-2" role="group"></div></div>';
                        //}
                        
                  
            })
			->rawColumns(['action','status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Fin/TransactionsDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(fin_transactions $model): QueryBuilder
    {
        
        $tahun = $this->tahun;
        $bulan = $this->bulan;
        return $model->with('categories')->whereYear('date',$tahun)->whereMonth('date',$bulan)->orderBy('date','DESC')->newQuery();
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

            Column::make('name')->title('Name')->orderable(false)->addClass('align-middle')->searchable(false),
            Column::make('categories.name')->visible(false),
            Column::make('date_text')->title('Date')->orderable(false)->addClass('align-middle'),
            Column::make('amount')->title('Amount')->orderable(false)->addClass('align-middle'),
            Column::make('status')->title('Status')->orderable(false)->addClass('align-middle'),
            
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(220)
                  ->addClass('text-center align-middle'),
            
        ];

    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Fin/Transactions_' . date('YmdHis');
    }
}
