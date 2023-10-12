<?php

namespace App\DataTables;

use App\Invoice;
use Yajra\DataTables\Services\DataTable;
use App\Helper\GlobalHelper;

class InvoiceDataTable extends DataTable
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
        $id = $country->myid;
      
        return 
        '<a href="' .route('downloadinvoice',$id). '"style="margin-top: 2%;width: auto; margin:1%;background-color: #673ab7;border-color: #673ab7;color: #fff" class="btn expense "><i class="fa fa-download "></i> Download</a>
        <a target="_blank" style="margin-top: 2%;width: auto; margin:1%;" href="' .route('invoiceview',$id). '" class="btn btn-warning "><i class="fa fa-eye"></i> View</a>
        <a style="margin-top: 2%;width: auto; margin:1%;" href="' .route('invoiceedit',$id). '" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</a>
        <a style="margin-top: 2%;width: auto; margin:1%;" href="' .route('invoicecreditnote',$id). '" class="btn btn-info"><i class="fa fa-credit-card"></i> Credit Note</a>
        <a style="margin-top: 2%;width: auto; margin:1%;" href="' .route('invoicedelete',$id). '" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>';
    
    })
        
        ->editColumn('invoice_month', function($country) {
            return date('M-y',strtotime($country->invoice_date));
        })
        ->editColumn('company_name',function($data){
            $id = $data->companyData;

            if($id == null){
                return '--';
            }
            else{
            $company_name=$data->companyData->name;
            return  $company_name;
            }
        }) 
        ->editColumn('forwarder_name',function($data){
            $id = $data->forwarderData;

            if($id == null){
                return '--';
            }
            else{
            $forwarder_name=$data->forwarderData->name;
            return  $forwarder_name;
            }
        })
        ->editColumn('voucher_no',function($data){
            $id = $data->voucherData;

            if($id == null){
                return '--';
            }
            else{
            $voucher=$data->voucherData->id;
            return  $voucher;
            }
        })
        ->editColumn('shipper_name', function($country) {
            return GlobalHelper::getshippername($country->ships);
        })

        ->rawColumns(['action','company_name','forwarder_name','voucher_no','shipper_name']);//->toJson();
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Country $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Invoice $model)
    {
        return $model->newQuery()->select('id','invoice_date', 'invoice_no', 'created_at', 'updated_at');
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
        return ['id',  'invoice_date', 'invoice_no', 'created_at', 'updated_at'];
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
