<?php 

namespace App\Http\Controllers\Transporter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Company;
use App\Driver;
use App\Employee;
use App\Forwarder;
use App\Shipment;
use App\Transporter;
use App\Truck;
use App\Warehouse;
use App\Shipment_Transporter;
use App\Shipment_Summary;
use App\Shipment_Driver;
use App\Expense;
use App\Cargostatus;
use Hash;
use PDF;
use Mail;
use Illuminate\Support\Facades\Auth;



class AdminController extends Controller
{

	public function __construct()
    {
        
    }


     public function check()
    {
         if(Auth::user()->role != "Transporter") {

            Auth::logout();
            
           return redirect()->route('login')->with('error',"You have no permission for that.");
        } 
    }



    public function Dashboard(Request $Request)
    {

        //$this->check();
        //dd(Auth::user()->id);
        $ff= Transporter::where('user_id',Auth::user()->id)->first();

        $total = Shipment_Transporter::whereNull('deleted_at')->where('transporter_id', $ff->id)->get();
        $total1 = array();
        foreach ($total as $key => $value) {
            $total1[$key] = Shipment::where('shipment_no', $value->shipment_no)->whereNull('deleted_at')->first();
        }
        $data['total'] = count($total1);

        $pending = Shipment_Transporter::where('status', 1)->whereNull('deleted_at')->where('transporter_id', $ff->id)->get();
        $pending1 = array();
        foreach ($pending as $key => $value) {
            $pending1[$key] = Shipment::where('shipment_no', $value->shipment_no)->whereNull('deleted_at')->first();
        }
        $data['pending'] = count($pending1);

        $ontheway = Shipment_Transporter::where('status', 2)->whereNull('deleted_at')->where('transporter_id', $ff->id)->get();
        $ontheway1 = array();
        foreach ($ontheway as $key => $value) {
            $ontheway1[$key] = Shipment::where('shipment_no', $value->shipment_no)->whereNull('deleted_at')->first();
        }
        $data['ontheway'] = count($ontheway1);

        $delivery = Shipment_Transporter::where('status', 3)->whereNull('deleted_at')->where('transporter_id', $ff->id)->get();
        $delivery1 = array();
        foreach ($delivery as $key => $value) {
            $delivery1[$key] = Shipment::where('shipment_no', $value->shipment_no)->whereNull('deleted_at')->first();
        }
        $data['delivery'] = count($delivery1);

        // $data['pending'] = Shipment::where('status',0)->whereNull('deleted_at')->whereRaw("find_in_set('$ff->id' , all_transporter)")->count();

        //$data['delivery'] = Shipment::where('status',2)->whereNull('deleted_at')->whereRaw("find_in_set('$ff->id' , all_transporter)")->count();

        // $dataremain =Shipment::where('paid',0)->where('forwarder',$ff->id)->sum('invoice_amount');
        
        // $data['remaining'] = (int)$dataremain;

        //dd($data);
    	
    	return view('transporter.dashboard',compact('data'));
    }



}