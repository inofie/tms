<?php

namespace App\DataTables;
use App\Employee;
use App\User;
use App\Company;
use Yajra\DataTables\Services\DataTable;
use App\Helper\GlobalHelper;

class EmployeeDataTable extends DataTable
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
        ->addColumn('action', function ($employee) {
        $id = $employee->myid;

        return
        '<a  href="' . route('employeeedit',$id) . '"style="min-width: 20%; width: auto;" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
        <a href="' .route('employeedelete',$id) . '"style="min-width: 20%; width: auto;" class="btn btn-danger btn-xs"><i class="fa fa-trash-o "> </a>';

    })
    ->addColumn('status',  function($employee) {
        $id = $employee->myid;
        $status = $employee->status;
        $class='text-danger';
        $label='Deactive';
        if($status==1)
        {
            $class='text-green';
            $label='Active';
        }
      return  $label;
    })
    ->editColumn('user_id', function($employee) {
            $username= User::withTrashed()->where('id',$employee->user_id)->first();
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
    ->editColumn('company_id', function($employee) {
        $username= company::withTrashed()->where('id',$employee->company_id)->first();
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
    ->editcolumn('pan_card', function ($employee) {
        if ($employee->pan_card) {
            $image = "<img src='".asset('/public/uploads') ."/".$employee->pan_card."' style='vertical-align: middle;width: 50px;height: 50px;border-radius: 50%;'>";
        }
        else{
            $image = "<img src='".asset('/noimage.png')."' style='vertical-align: middle;width: 50px;height: 50px;border-radius: 50%;'>";
        }

        return $image;
    })
    ->rawColumns(['action','status','pan_card']);//->toJson();
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Country $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Employee $model)
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
        return 'Employee' . date('YmdHis');
    }
}
