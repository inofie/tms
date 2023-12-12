<?php
namespace App\DataTables;
use App\Shipment;
use Yajra\DataTables\Services\DataTable;
use App\Helper\GlobalHelper;
use Carbon\Carbon;
class ShipmentLatestDataTable extends DataTable
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
        ->addColumn('action', function ($shipment)
        {
            $id = $shipment->myid;
            $shipment_no = $shipment->shipment_no;
                return
                '<a href="' . route('shipmentdetails',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #047fb9;border-color: #047fb9;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> View</a>
                <a href="' . route('allshipmentsummarylist',['shipment_no'=>$shipment_no]). '" style="margin-top: 3%;width: auto;min-width: 60%;background-color: #673ab7;border-color: #673ab7;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> Shipment Summary</a>
                <a href="' . route('shipmentedit',$id). '" style="margin-top: 3%;width: auto;min-width: 80%;"  class="btn btn-success btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-pencil"></i> Edit</a>
                <a href="' .route('shipmentdeleteLatest',$shipment_no) . '"style="margin-top: 3%;width: auto;min-width: 80%;" class="btn btn-danger btn-xs"><i class="fa fa-trash"> Delete </a>';
        })
        ->addColumn('other', function ($shipment)
        {
            $id = $shipment->myid;
            $shipment_no = $shipment->shipment_no;
            if($shipment->status == 0)
            {
                $view = '<a href="' . route('shipmenttransporters',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-warning btn-xs "'.$shipment_no.'"hide"><i class="fa fa-plus"></i> Transporter</i></a>';
            }
            if($shipment->status == 1){
                $view = '<a href="' . route('shipmenttrucklists',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-primary btn-xs"><i class="fa fa-truck"></i> Trucks</a>
                <a data-id="' . $shipment_no .'" style="margin-top: 2%;width: auto; margin:1%;width:auto;background: #047fb9; color: #fff;" class="btn btn-xs delivered"><i class="fa fa-bus"></i> Delivered</i></a>
                <a href="' . route('shipmenttransporters',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-warning btn-xs "'.$shipment_no.'"hide"><i class="fa fa-plus"></i> Transporter</i></a>
                <a  data-id="'.$shipment_no.'" style="margin-top: 2%;width: auto; margin:1%;width:auto;background: #7ca00f; color: #fff;" class="btn btn-xs warehouse "'.$shipment_no.'"hide"><i class="fa fa-plus"></i> Add in Warehouse</i></a>
                <a href="' . route('downloadlr',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-danger btn-xs "'.$shipment_no.'"hide "><i class="fa fa-download "></i> LR</i></a>';
            }

            $expense = '<a href="' . route('addexpensebyadmin',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #673ab7;border-color: #673ab7;color: #fff"  class="btn expense btn-xs"><i class="fa fa-plus"></i> Expense </a>';

            if($shipment->status == 2){
                $view = '<a href="' . route('downloadlr',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-danger btn-xs "'.$shipment_no.'"hide "><i class="fa fa-download "></i> LR</i></a>';
            }
            return $view.' '.$expense;

        })
        ->editColumn('date', function($shipment) {
            return GlobalHelper::getFormattedDatefilter($shipment->date);
            
        })
        ->editColumn('created_at', function($shipment) {
            return GlobalHelper::getFormattedDate($shipment->created_at);
        })
        ->addColumn('status',  function($shipment) {
            $status = $shipment->status;
            if($status==0)
            {
                $class='text-blue';
                $label='Pending';
            }
            elseif($status==1)
            {
                $class='text-orange';
                $label='Ontheway';
            }elseif($status==2)
            {
                $class='text-green';
                $label='Delivered';
            }elseif($status==4)
            {
                $class='text-pink';
                $label='Warehouse';
            }
          return  '<a class="'.$class.'">'.$label.'</a>';
        })

        ->editColumn('type',function($shipment){
                if($shipment->imports == 1){
                    $import ='Import';
                }else{
                    $import ='Export';
                }
                if($shipment->lcl == 1) {
                    $lcl ='LCL';
                }else{
                    $lcl ='FCL';
                }
                return $import.'/'.$lcl;
            })
            ->filterColumn('date', function($query, $keyword) {
                $sql = 'DATE_FORMAT(date,"%d/%m/%Y") like ?';
                $query->whereRaw($sql, ["%{$keyword}%"]);
              })
        ->rawColumns(['action','other','type','status','date']);//->toJson();
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
