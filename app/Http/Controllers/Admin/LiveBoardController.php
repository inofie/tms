<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Company;
use App\Shipment_Driver;
use App\Employee;
use App\Forwarder;
use App\Shipment;
use App\Transporter;
use App\Truck;
use App\Warehouse;
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;
use App\DataTables\LiveBoardDataTable;
use Config;



class LiveBoardController extends Controller
{

	public function __construct()
    {
       
    }

    public function List(Builder $builder, LiveBoardDataTable $dataTable)
    {   
        $html = $builder->columns([
            ['data' => 'shipment_no', 'name' => 'shipment_no','title' => 'Shipment No'],
          
        ]);
        
        // $data = Shipment::with('statusData')->withTrashed()->whereNull('deleted_at')
        // ->whereRaw("find_in_set('$ff->id' , all_transporter)");

        //if(request()->ajax()) {
            $trucktransfer = Shipment_Driver::where('status',11)->orWhere('status',13)->get();
            $pickup = Shipment_Driver::where('status',6)->get();
            $reachcompany = Shipment_Driver::where('status',7)->get();
            $damagemissinghold = Shipment_Driver::where('status',4)->orWhere('is_damaged',1)->orWhere('is_missing',1)->get();
            $reachport = Shipment_Driver::where('status',12)->get();
            $delivered = Shipment::where('status',2)->get();
           // return $dataTable->dataTable($data)->toJson();
       // }
       

        return view('admin.listboard',compact('trucktransfer','html','pickup','damagemissinghold','reachcompany','reachport','delivered'));
    }


}