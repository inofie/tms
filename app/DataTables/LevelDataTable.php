<?php



namespace App\DataTables;



use App\Level;

use Yajra\DataTables\Services\DataTable;

use App\Helper\GlobalHelper;

use Auth;



class LevelDataTable extends DataTable

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

             

            $edit = '<a class="label label-success" href="' . route('leveledit',$id) . '" title="Update"><i class="fa fa-edit"></i>&nbsp</a>';

              return $edit;

           })

        ->editColumn('level_name', function($category) {
              

            return 'Level'.' '.$category->level_name;
          

        })

        ->editColumn('created_at', function($category) {

            return GlobalHelper::getFormattedDate($category->created_at);

        })

        ->rawColumns(['action','level_name']);//->toJson();

    }



    /**

     * Get query source of dataTable.

     *

     * @param \App\Category $model

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function query(Level $model)

    {

        return $model->newQuery()->select('id', 'name', 'created_at', 'updated_at');

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

            'name',

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

