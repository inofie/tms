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
        $data['total'] = Shipment::where('all_transporter',$ff->id)->count();

        // $data['pending'] = Shipment::where('status',0)->where('forwarder',$ff->id)->count();

        // $data['delivery'] = Shipment::where('status',2)->where('forwarder',$ff->id)->count();

        // $dataremain =Shipment::where('paid',0)->where('forwarder',$ff->id)->sum('invoice_amount');
        
        // $data['remaining'] = (int)$dataremain;

        //dd($data);
    	
    	return view('transporter.dashboard',compact('data'));
    }



}