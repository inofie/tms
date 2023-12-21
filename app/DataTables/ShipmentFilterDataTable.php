<?php
namespace App\DataTables;
use App\Shipment;
use App\Shipment_Transporter;
use App\Transporter;
use App\Shipment_Driver;
use App\Invoice;
use App\Expense;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use App\Helper\GlobalHelper;
class ShipmentFilterDataTable extends DataTable
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
            if($shipment->status == 0)
            {
                $view ='<a href="' .route('shipmenttransporters',$id) . '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-warning btn-xs "'.$shipment_no.'"hide"><i class="fa fa-plus"></i> Transporter</i></a>
                ';
            }
            if($shipment->status == 1)
            {
                $view = '<a href="' .route('shipmenttrucklists',$id) . '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-primary btn-xs"><i class="fa fa-truck"></i> Trucks
                </a>
                <a data-id="' . $shipment_no .'" style="margin-top: 2%;width: auto; margin:1%;width:auto;background: #047fb9; color: #fff;" class="btn btn-xs delivered"><i class="fa fa-bus"></i> Delivered</i>
                </a>
                <a href="' .route('shipmenttransporters',$id) . '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-warning btn-xs "'.$shipment_no.'"hide"><i class="fa fa-plus"></i> Transporter</i></a>
                <a  data-id="' . $shipment_no .'" style="margin-top: 2%;width: auto; margin:1%;width:auto;background: #7ca00f; color: #fff;" class="btn btn-xs warehouse "' . $shipment_no .'"hide"><i class="fa fa-plus"></i> Add in Warehouse</i></a>
                <a href="' .route('downloadlr',$id) . '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-danger btn-xs {{ $value->shipment_no }}hide "><i class="fa fa-download "></i> LR</i></a>
                ';
            }
            if($shipment->status == 2 || $shipment->status == 3)
            {
                $view ='<a href="' .route('downloadlr',$id) . '" style="margin-top: 2%;width: auto; margin:1%;width:auto;" class="btn btn-danger btn-xs {{ $value->shipment_no }}hide "><i class="fa fa-download "></i> LR</i></a>';
            }
            $expense = '<a href="' . route('addexpensebyadmin',$id). '" style="margin-top: 2%;width: auto; margin:1%;width:auto;background-color: #673ab7;border-color: #673ab7;color: #fff"  class="btn expense btn-xs"><i class="fa fa-plus"></i> Expense </a>';
            $shipmentSummary='
            <a href="' . route('allshipmentsummarylist',['shipment_no'=>$shipment_no]). '" style="margin-top: 3%;width: auto;min-width: 60%;background-color: #673ab7;border-color: #673ab7;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> Shipment Summary</a>';
            $edit='<a href="' . route('shipmentedit',$id). '" style="margin-top: 3%;width: auto;min-width: 80%;"  class="btn btn-success btn-xs {{ $value->shipment_no }}hide"><i class="fa fa-pencil"></i> Edit</a>';
            $delete='<a href="' .route('shipmentdeleteLatest',$shipment_no) . '"style="margin-top: 3%;width: auto;min-width: 80%;" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete </a>';
            $details = '<a href="' . route('shipmentdetails',$id). '" style="margin-top: 3%;width: auto; min-width: 80%;background-color: #047fb9;border-color: #047fb9;color: #fff" class="btn  btn-xs "><i class="fa fa-eye"></i> View</a>';
            return $view.' '.$expense.' '.$shipmentSummary.' '.$edit.''.$delete.''.$details;
        })

        ->addColumn('date', function($shipment) {
            $date = date_create($shipment->date);
            return date_format($date, "d/m/Y");
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
            }elseif($status==2){
                $class='text-green';
                $label='Delivered';
            }else{
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

        ->editColumn('transporter_name',function($shipment){
            $tras_list = Shipment_Transporter::where('shipment_no', $shipment->shipment_no)->get();
                if($tras_list){
                            $t_list = "";
                            foreach ($tras_list as $key3 => $value3) {
                                $ttv = Transporter::withTrashed()->findorfail($value3->transporter_id);
                                if($ttv){
                                if ($key3 == 0) {
                                    $t_list = $t_list . "" . $ttv->name;
                                } else {
                                    $t_list = $t_list . ", " . $ttv->name;
                                }
                            }
                            else{
                                $t_list = '-';
                            }
                            }
                            $transporter_name = $t_list;
                            return $transporter_name;
                        }
                        else {
                            return  $transporter_name = '-';
                        }
        })
        ->editColumn('truck_no',function($shipment){
            $data1 = Shipment_Driver::where('shipment_no',$shipment->shipment_no)->get();
                if($data1){
                 $d_list = "";
                            foreach ($data1 as $key2 => $value2) {
                                if($key2 == 0) {
                                    $d_list = $d_list."".$value2->truck_no;
                                } else {
                                    $d_list = $d_list.", ".$value2->truck_no;
                                }
                            }
                            $truck_no = $d_list;
                            return $truck_no;
                }
                else {
                    return $truck_no = '-';
                }
        })
        ->editColumn('invoice_cost',function($shipment){
            $data1v = Invoice::where('invoice_no',$shipment->lr_no)->first();
            if($data1v){
            $invoice_cost = $data1v->grand_total;
            $invoice_cost = $invoice_cost;
            return $invoice_cost;
            }
            else {
                return $invoice_cost = '-';
            }
        })
        ->editColumn('transporter_cost',function($shipment){
            $data1c = Expense::where('shipment_no',$shipment->shipment_no)->sum('amount');
                        if($data1c){
                        $transporter_cost = $data1c;
                        $transporter_cost = $transporter_cost;
                        return $transporter_cost;
                        }
                        else {
                            return $transporter_cost = '-';
                        }
        })
        ->filterColumn('date', function($query, $keyword) {
            $sql = 'DATE_FORMAT(date,"%d/%m/%Y") like ?';
            $query->whereRaw($sql, ["%{$keyword}%"]);
          })
        ->rawColumns(['action','type','status','transporter_name','truck_no','invoice_cost','transporter_cost','date']);//->toJson();
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Shipment $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Shipment $model)
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
        return [
            Column::make('id'),
            Column::make('shipment_no'),
            Column::make('date')->exportFormat('dd/mm/yyyy'),
        ];
    }
    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Shipment_' . date('YmdHis');
    }
}
