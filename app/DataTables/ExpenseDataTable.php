<?php

namespace App\DataTables;

use App\Expense;
use App\Company;
use App\Transporter;
use App\Forwarder;
use Yajra\DataTables\Services\DataTable;
use App\Helper\GlobalHelper;

class ExpenseDataTable extends DataTable
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
        ->addColumn('action', function ($expense) {
        $id = $expense->id;

        return
        '<a href="' .route('expenseview',$id) . '"style="min-width: 20%; width: auto;" class="btn btn-warning"><i class="fa fa-eye"></i> View</a>
        <a  href="' . route('expenseedit',$id) . '"style="min-width: 20%; width: auto;" class="btn btn-primary "><i class="fa fa-edit"></i>Edit</a>
        <a href="' .route('expensedelete',$id) . '"style="min-width: 20%; width: auto;" class="btn btn-danger "><i class="fa fa-trash-o "> Delete</a>';

    })

    ->editColumn('company_id', function($expense) {
            $company= Company::withTrashed()->where('id',$expense->company_id)->first();
            if($company){
            $company_name = $company->name;
            if($company_name)
            {
                return $company_name;
            }else{
                return '';
            }
        }else{ return '';}
    })
    ->editColumn('transporter_id', function($expense) {
        $transporter= Transporter::withTrashed()->where('id',$expense->transporter_id)->first();
        if($transporter){
            $transporter_name = $transporter->name;
            if($transporter_name)
            {
                return $transporter_name;
            }else{
                return '';
            }
        }else
        {
            return '';
        }
    })
    ->editColumn('forwarder_id', function($expense) {
        $forwarder= Forwarder::withTrashed()->where('id',$expense->forwarder_id)->first();
        if($forwarder){
            $forwarder_name = $forwarder->name;
            if($forwarder_name)
            {
                return $forwarder_name;
            }else{
                return '';
            }
        }else{
            return '';
        }
    })
    ->rawColumns(['action','company_name','transporter_name','forwarder_name']);//->toJson();
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Country $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Expense $model)
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
        return 'Expense' . date('YmdHis');
    }
}
