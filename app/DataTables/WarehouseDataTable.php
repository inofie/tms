<?php

namespace App\DataTables;

use App\Warehouse;
use App\User;
use App\Company;
use Yajra\DataTables\Services\DataTable;
use App\Helper\GlobalHelper;

class WarehouseDataTable extends DataTable
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
        ->addColumn('action', function ($warehouse) {
        $id = $warehouse->myid;

        return
        '<a class="label label-success" href="' . route('warehouseedit',$id) . '"  title="Update"><i class="fa fa-edit"></i>&nbsp</a>
        <a class="btn btn-danger btn-xs" href="'. route('warehousedelete',$id) .'" title="Delete"><i class="fa fa-trash-o "></i></a>';
        })
        ->addColumn('status',  function($warehouse) {
            $id = $warehouse->myid;
            $status = $warehouse->status;
            $class='text-danger';
            $label='Deactive';
            if($status==1)
            {
                $class='text-green';
                $label='Active';
            }
          return  $label;
        })
        ->editColumn('created_at', function($warehouse) {
            return GlobalHelper::getFormattedDate($warehouse->created_at);
        })
        ->editcolumn('address_proof', function ($warehouse) {
            if ($warehouse->address_proof) {
                $image = "<img src='".asset('/public/uploads') ."/".$warehouse->address_proof."' style='vertical-align: middle;width: 50px;height: 50px;border-radius: 50%;'>";
            }
            else{
                $image = "<img src='".asset('/noimage.png')."' style='vertical-align: middle;width: 50px;height: 50px;border-radius: 50%;'>";
            }

            return $image;
        })
        ->editColumn('user_id', function($warehouse) {
            $username= User::withTrashed()->where('id',$warehouse->user_id)->first();
            if($username){
            $username = $username->username;
            if($username)
            {
                return $username;
            }else{
                return '';
            }
        }else{ return '';}
    })
    ->editColumn('company_id', function($warehouse) {
        $username= company::withTrashed()->where('id',$warehouse->company_id)->first();
        if($username){
        $username = $username->name;
        if($username)
        {
            return $username;
        }else{
            return '';
        }
    }else{ return '';}
    })
        ->rawColumns(['action','status','address_proof']);//->toJson();
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Country $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Driver $model)
    {
        return $model->newQuery()->select('id','name', 'phone', 'status', 'created_at', 'updated_at');
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
        return ['id',  'name', 'phone', 'status', 'created_at', 'updated_at'];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Warehouse_' . date('YmdHis');
    }
}
