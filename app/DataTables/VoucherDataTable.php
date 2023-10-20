<?php

namespace App\DataTables;

use App\Account;
use Yajra\DataTables\Services\DataTable;
use App\Helper\GlobalHelper;

class VoucherDataTable extends DataTable
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
        ->addColumn('action', function ($country) {
        $id = $country->id;
        if($country->invoice_list != null && $country->v_type == 'credit'){
            return 
            '<a href="' .route('voucherview',$id) . '"style="min-width: 20%; width: auto;" class="btn btn-primary "><i class="fa fa-eye"></i> View</a>
            <a href="' .route('downloadinvoicecreditnote',$id). '"style="margin-top: 2%;width: auto; margin:1%;background-color: #673ab7;border-color: #673ab7;color: #fff" class="btn expense "><i class="fa fa-download "></i> Download</a>
            <a href="' .route('voucherdelete',$id) . '"style="min-width: 20%; width: auto;" class="btn btn-danger "><i class="fa fa-trash-o "> Delete</a>';    
        } 
        else{
        return 
        '<a href="' .route('voucherview',$id) . '"style="min-width: 20%; width: auto;" class="btn btn-primary "><i class="fa fa-eye"></i> View</a>
        <a href="' .route('voucherdelete',$id) . '"style="min-width: 20%; width: auto;" class="btn btn-danger "><i class="fa fa-trash-o "> Delete</a>';
        }
    })
        
    ->editColumn('from', function($country) {
        return GlobalHelper::getfromname($country->id);
    })
    ->editColumn('to', function($country) {
        return GlobalHelper::gettoname($country->id);
    })
    ->editColumn('type', function($country) {
        return GlobalHelper::gettype($country->id);
    })
    ->editColumn('amount', function($country) {
        return GlobalHelper::getamount($country->id);
    })
    ->editColumn('invoice_no',function($data){
        $id = $data->invoiceData;

        if($id == null){
            return '--';
        }
        else{
        $voucher=$data->invoiceData->invoice_no;
        return  $voucher;
        }
    })
        ->rawColumns(['action','from','to','type','invoice_no']);//->toJson();
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Country $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Account $model)
    {
        return $model->newQuery()->select('id','dates', 'type', 'created_at', 'updated_at');
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
                   ->addAction(['width' => '80px'])
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return ['id',  'dates', 'type', 'created_at', 'updated_at'];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Driver_' . date('YmdHis');
    }
}
