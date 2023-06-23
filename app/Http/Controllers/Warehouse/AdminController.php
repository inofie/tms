<?php 

namespace App\Http\Controllers\Warehouse;

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
         if(Auth::user()->role != "warehouse") {

            Auth::logout();
            
           return redirect()->route('login')->with('error',"You have no permission for that.");
        } 
    }



    public function Dashboard(Request $Request)
    {

        //$this->check();
        //dd(Auth::user()->id);
        $ff= Warehouse::where('user_id',Auth::user()->id)->first();
        $data['total'] = Shipment::where('warehouse_id',$ff->id)->where('status',3)->whereNull('deleted_at')->count();

        $data['pending'] = Shipment::where('status',0)->whereNull('deleted_at')->where('warehouse_id',$ff->id)->count();

        $data['delivery'] = Shipment::where('status',2)->whereNull('deleted_at')->where('warehouse_id',$ff->id)->count();

        // $dataremain =Shipment::where('paid',0)->where('forwarder',$ff->id)->sum('invoice_amount');
        
        // $data['remaining'] = (int)$dataremain;

        //dd($data);
    	
    	return view('warehouse.dashboard',compact('data'));
    }



}