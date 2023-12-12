<?php



namespace App\DataTables;



use App\User;
use App\Level;
use Yajra\DataTables\Services\DataTable;

use App\Helper\GlobalHelper;

use Auth;



class UserDataTable extends DataTable

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

        ->addColumn('action', function ($category) {

            $id = $category->id;

             

            $edit = '<a class="label label-success" href="' . route('useredit',$id) . '" title="Update"><i class="fa fa-edit"></i>&nbsp</a>';

              return $edit;

           })

           ->addColumn('status',  function($user) {
            $id = $user->id;
            $status = $user->status;
            $class='text-danger';
            $label='Deactive';
            if($status==0)
            {
                $class='text-green';
                $label='Active';
            }
            
            return  $label;
       
        })

        ->editColumn('created_at', function($category) {

            return GlobalHelper::getFormattedDate($category->created_at);

        })
        ->editColumn('level', function($employee) {
            $username= Level::where('level_name',$employee->level)->where('forwarder_id',$employee->forwarder_id)->first();
            if($username){
            $username = $username->name;
            if($username)
            {
                return $username;
            }else{
                return '';
            }
        }
        else
        { 
            return '';
        }
    })
        ->editColumn('depend_id', function($employee) {
            $username= User::where('id',$employee->depend_id)->first();
            if($username){
            $username = $username->username;
            if($username)
            {
                return $username;
            }else{
                return '';
            }
        }
        else
        { 
            return '';
        }
    })
    ->editColumn('manager_id', function($employee) {
        $username= User::where('id',$employee->manager_id)->first();
        if($username){
        $username = $username->username;
        if($username)
        {
            return $username;
        }else{
            return '';
        }
    }
    else
    { 
        return '';
    }
    })
    

        ->rawColumns(['action','depend_id','manager_id','level','status']);//->toJson();

    }



    /**

     * Get query source of dataTable.

     *

     * @param \App\Category $model

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function query(User $model)

    {

        return $model->newQuery()->select('id', 'username', 'created_at', 'updated_at');

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

        return [

            'id',

            'username',

            'created_at',

            'updated_at'

        ];

    }



    /**

     * Get filename for export.

     *

     * @return string

     */

    protected function filename()

    {

        return 'Category_' . date('YmdHis');

    }

}

