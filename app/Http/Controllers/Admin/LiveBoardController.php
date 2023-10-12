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
use Carbon\Carbon;



class LiveBoardController extends Controller
{

	public function __construct()
    {

    }

    public function List(Request $Request)
    {
        return view('admin.listboard');
    }


    public function getLiveBoardData(Request $Request)
    {
        $response = [];
        if($Request->status == "0" || $Request->status == "1") {
            $pickups = Shipment_Driver::leftJoin('shipment','shipment_driver.shipment_no','=','shipment.shipment_no')
            ->whereRaw('DATEDIFF(CURDATE(),shipment.date) <= 6')->where('shipment.paid',0)->where('shipment_driver.status',6)
            ->orderBy('shipment_driver.updated_at','desc')->get();
            $pickuphtml = "";
            foreach($pickups as $pickup) {
                $pickuphtml .= "<li>".$pickup->shipment_no.', '.$pickup->from1.' - '.$pickup->to1."</li>";
            }
            $response['pickup'] = $pickuphtml;
            $response['pickup_count'] = $pickups->count();
        }
        if($Request->status == "0" || $Request->status == "2") {
            $trucktransfers = Shipment_Driver::leftJoin('shipment','shipment_driver.shipment_no','=','shipment.shipment_no')
            ->whereRaw('DATEDIFF(CURDATE(),shipment.date) <= 6')->where('shipment.paid',0)
            ->where('shipment_driver.is_trucktransfer',1)
            ->orderBy('shipment_driver.updated_at','desc')->get();
            $trucktransferhtml = "";
            foreach($trucktransfers as $trucktransfer) {
                $trucktransferhtml .= "<li>".$trucktransfer->shipment_no.', '.$trucktransfer->from1.' - '.$trucktransfer->to1."</li>";
            }
            $response['trucktransfer'] = $trucktransferhtml;
            $response['trucktransfer_count'] = $trucktransfers->count();
        }
        if($Request->status == "0" || $Request->status == "3") {
            $reachcompanys = Shipment_Driver::leftJoin('shipment','shipment_driver.shipment_no','=','shipment.shipment_no')
            ->whereRaw('DATEDIFF(CURDATE(),shipment.date) <= 6')->where('shipment.paid',0)->where('shipment_driver.status',7)->orderBy('shipment_driver.updated_at','desc')->get();
            $reachcompanyhtml = "";
            foreach($reachcompanys as $reachcompany) {
                $reachcompanyhtml .= "<li>".$reachcompany->shipment_no.', '.$reachcompany->from1.' - '.$reachcompany->to1."</li>";
            }
            $response['reachcompany'] = $reachcompanyhtml;
            $response['reachcompany_count'] = $reachcompanys->count();
        }
        if($Request->status == "0" || $Request->status == "4") {
            $damagemissingholds = Shipment_Driver::leftJoin('shipment','shipment_driver.shipment_no','=','shipment.shipment_no')
            ->where(function ($query) {
                $query->Where('shipment_driver.status',4)->orWhere('shipment_driver.is_damaged','1')
                ->orWhere('shipment_driver.is_missing','1');
            }) 
            ->whereRaw('DATEDIFF(CURDATE(),shipment.date) <= 6')->where('shipment.paid',0)
            ->orderBy('shipment_driver.updated_at','desc')->get();
            $damagemissingholdhtml = "";
            foreach($damagemissingholds as $damagemissinghold) {
                $damagemissingholdhtml .= "<li>".$damagemissinghold->shipment_no.', '.$damagemissinghold->from1.' - '.$damagemissinghold->to1."</li>";
            }
            $response['damagemissinghold'] = $damagemissingholdhtml;
            $response['damagemissinghold_count'] = $damagemissingholds->count();
        }
        if($Request->status == "0" || $Request->status == "5") {
            $reachports = Shipment_Driver::leftJoin('shipment','shipment_driver.shipment_no','=','shipment.shipment_no')
            ->whereRaw('DATEDIFF(CURDATE(),shipment.date) <= 6')->where('shipment.paid',0)->where('shipment_driver.status',12)->orderBy('shipment_driver.updated_at','desc')->get();
            $reachporthtml = "";
            foreach($reachports as $reachport) {
                $reachporthtml .= "<li>".$reachport->shipment_no.', '.$reachport->from1.' - '.$reachport->to1."</li>";
            }
            $response['reachport'] = $reachporthtml;
            $response['reachport_count'] = $reachports->count();
        }
        if($Request->status == "0" || $Request->status == "6") {
            $delivereds = Shipment::where('status',2)->whereRaw('DATEDIFF(CURDATE(),date) <= 6')->where('shipment.paid',0)->orderBy('shipment.updated_at','desc')->get();
            $deliveredhtml = "";
            foreach($delivereds as $delivered) {
                $deliveredhtml .= "<li>".$delivered->shipment_no.', '.$delivered->from1.' - '.$delivered->to1."</li>";
            }
            $response['delivered'] = $deliveredhtml;
            $response['delivered_count'] = $delivereds->count();
        }
        return $response;
    }

    public function getLiveBoardDataCount(Request $Request)
    {
        $response = [];
        $pickups = Shipment_Driver::leftJoin('shipment','shipment_driver.shipment_no','=','shipment.shipment_no')
        ->whereRaw('DATEDIFF(CURDATE(),shipment.date) <= 6')->where('shipment.paid',0)->where('shipment_driver.status',6)->count();
        $response['pickup_count'] = $pickups;
        $trucktransfers = Shipment_Driver::leftJoin('shipment','shipment_driver.shipment_no','=','shipment.shipment_no')
        ->whereRaw('DATEDIFF(CURDATE(),shipment.date) <= 6')->where('shipment.paid',0)
        ->where('shipment_driver.is_trucktransfer',1)
        ->count();
        $response['trucktransfer_count'] = $trucktransfers;
        $reachcompanys = Shipment_Driver::leftJoin('shipment','shipment_driver.shipment_no','=','shipment.shipment_no')
        ->whereRaw('DATEDIFF(CURDATE(),shipment.date) <= 6')->where('shipment.paid',0)->where('shipment_driver.status',7)->count();
        $response['reachcompany_count'] = $reachcompanys;
        $damagemissingholds = Shipment_Driver::leftJoin('shipment','shipment_driver.shipment_no','=','shipment.shipment_no')
        ->where(function ($query) {
            $query->Where('shipment_driver.status',4)->orWhere('shipment_driver.is_damaged','1')
            ->orWhere('shipment_driver.is_missing','1');
        })
        ->whereRaw('DATEDIFF(CURDATE(),shipment.date) <= 6')->where('shipment.paid',0)
        ->count();
        $response['damagemissinghold_count'] = $damagemissingholds;
        $reachports = Shipment_Driver::leftJoin('shipment','shipment_driver.shipment_no','=','shipment.shipment_no')
        ->whereRaw('DATEDIFF(CURDATE(),shipment.date) <= 6')->where('shipment.paid',0)->where('shipment_driver.status',12)->count();
        $response['reachport_count'] = $reachports;
        $delivereds = Shipment::where('status',2)->whereRaw('DATEDIFF(CURDATE(),date) <= 6')->where('shipment.paid',0)->count();
        $response['delivered_count'] = $delivereds;
        return $response;
    }
}