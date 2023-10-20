<?php
namespace App\DataTables;
use App\Shipment;
use Yajra\DataTables\Services\DataTable;
use App\Helper\GlobalHelper;
class ShipmentDataTable extends DataTable
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
        ->addColumn('action', function ($country) {
        $id = $country->myid;
        if($country->status == 0 || $country->status == 1){
            return
            '<a href="' .route('shipmenttransporter',$id) . '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-warning btn-xs {{ $country->shipment_no }}"><i class="fa fa-plus"></i> Transporter</i></a>
        <a href="' . route('shipalldetail',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #047fb9;border-color: #047fb9;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> View</a>
        <a href="' .route('allshipmentsummarylist',['shipment_no'=>$country->shipment_no]) . '"style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #673ab7;border-color: #673ab7;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> Shipment Summary</a>
        <a href="' .route('downloadlr',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-danger btn-xs {{ $value->shipment_no }}hide "><i class="fa fa-download "></i> LR</i></a>
        <a href="' .route('shipmentedit',$id) . '" style="margin-top: 2%;width: auto; margin:1%;width:auto;"  class="btn btn-success btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-pencil"></i> Edit</a>
        <a href="' .route('addexpensebyadmin',$id) . '" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #673ab7;border-color: #673ab7;color: #fff"  class="btn expense btn-xs"><i class="fa fa-plus"></i> Expense </a>';
    }
    else{
        return
        '<a href="' . route('shipalldetail',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #047fb9;border-color: #047fb9;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> View</a>
        <a href="' .route('allshipmentsummarylist',['shipment_no'=>$country->shipment_no]) . '"style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #673ab7;border-color: #673ab7;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> Shipment Summary</a>
        <a href="' .route('downloadlr',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-danger btn-xs {{ $value->shipment_no }}hide "><i class="fa fa-download "></i> LR</i></a>
        <a href="' .route('shipmentedit',$id) . '" style="margin-top: 2%;width: auto; margin:1%;width:auto;"  class="btn btn-success btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-pencil"></i> Edit</a>
        <a href="' .route('addexpensebyadmin',$id) . '" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #673ab7;border-color: #673ab7;color: #fff"  class="btn expense btn-xs"><i class="fa fa-plus"></i> Expense </a>';
    }
    })

        ->editColumn('created_at', function($country) {
            return GlobalHelper::getFormattedDate($country->created_at);
        })
        ->addColumn('status',  function($country) {
            $status = $country->status;

            if($status==0)
            {
                $class='text-blue';
                $label='Pending';
            }
            elseif($status==1)
            {
                $class='text-orange';
                $label='Ontheway';
            }else{
                $class='text-green';
                $label='Delivered';
            }
            return  '<a class="'.$class.'">'.$label.'</a>';
        })

        ->editColumn('type',function($data){
                if($data->imports == 1){
                    $import ='Import';
                }else{
                    $import ='Export';
                }
                if($data->lcl == 1) {
                    $lcl ='LCL';
                }else{
                    $lcl ='FCL';
                }
                return $import.'/'.$lcl;
            })
        ->rawColumns(['action','type','status']);//->toJson();
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Country $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Driver $model)
    {
        return $model->newQuery()->select('id','date', 'shipment_no', 'status', 'created_at', 'updated_at');
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
        return ['id',  'date', 'shipment_no', 'status', 'created_at', 'updated_at'];
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
