<?php

namespace App\DataTables;

use App\Transporter;
use App\User;
use Yajra\DataTables\Services\DataTable;
use App\Helper\GlobalHelper;

class TransporterDataTable extends DataTable
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
        ->addColumn('action', function ($transporter) {
        $id = $transporter->myid;

        return
        '<a class="label label-success" href="' . route('transporteredit',$id) . '"  title="Update"><i class="fa fa-edit"></i>&nbsp</a>
        <a class="btn btn-danger btn-xs" href="'. route('transporterdelete',$id) .'" title="Delete"><i class="fa fa-trash-o "></i></a>';
        })
        ->addColumn('status',  function($transporter) {
            $id = $transporter->myid;
            $status = $transporter->status;
            $class='text-danger';
            $label='Deactive';
            if($status==1)
            {
                $class='text-green';
                $label='Active';
            }
          return  $label;
        })
        ->editColumn('created_at', function($transporter) {
            return GlobalHelper::getFormattedDate($transporter->created_at);
        })
        ->editcolumn('rc_book', function ($transporter) {
            if ($transporter->rc_book) {
                $image = "<img src='".asset('/public/uploads') ."/".$transporter->rc_book."' style='vertical-align: middle;width: 50px;height: 50px;border-radius: 50%;'>";
            }
            else{
                $image = "<img src='".asset('/noimage.png')."' style='vertical-align: middle;width: 50px;height: 50px;border-radius: 50%;'>";
            }

            return $image;
        })
        ->editcolumn('pan_card', function ($transporter) {
            if ($transporter->pan_card) {
                $image = "<img src='".asset('/public/uploads') ."/".$transporter->pan_card."' style='vertical-align: middle;width: 50px;height: 50px;border-radius: 50%;'>";
            }
            else{
                $image = "<img src='".asset('/noimage.png')."' style='vertical-align: middle;width: 50px;height: 50px;border-radius: 50%;'>";
            }

            return $image;
        })
        ->editcolumn('licence', function ($transporter) {
            if ($transporter->licence) {
                $image = "<img src='".asset('/public/uploads') ."/".$transporter->licence."' style='vertical-align: middle;width: 50px;height: 50px;border-radius: 50%;'>";
            }
            else{
                $image = "<img src='".asset('/noimage.png')."' style='vertical-align: middle;width: 50px;height: 50px;border-radius: 50%;'>";
            }

            return $image;
        })
        ->editColumn('user_id',function($transporter){
            $username= User::withTrashed()->where('id',$transporter->user_id)->first();
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
        ->rawColumns(['action','rc_book','pan_card','licence']);//->toJson();
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
        return 'Driver_' . date('YmdHis');
    }
}
