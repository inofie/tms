<?php 

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Company;
use App\Driver;
use App\Account;
use App\Invoice;
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
         if(Auth::user()->role != "company") {

            Auth::logout();
            
           return redirect()->route('login')->with('error',"You have no permission for that.");
        } 
    }



    public function Dashboard(Request $Request)
    {

        //$this->check();
        //dd(Auth::user()->id);
        // $ff= Company::where('user_id',Auth::user()->id)->first();
        // $data['total'] = Shipment::where('company',$ff->id)->count();

        // $data['pending'] = Shipment::where('status',0)->where('company',$ff->id)->count();

        // $data['delivery'] = Shipment::where('status',2)->where('company',$ff->id)->count();

            $from = date('Y-04-01');
                        
            $to = date('Y-m-d');
            $total_credit1 = Account::whereBetween('dates', [$from, $to])->sum('credit');

            $total_credit2 = Account::whereBetween('dates', [$from, $to])->sum('debit');

            $total_credit = $total_credit1 + $total_credit2;

            $data['pl_report'] = $total_credit;

            $data['pending'] = Shipment::where('status',0)->whereNull('deleted_at')->count();

            $data['ontheway'] = Shipment::where('status',1)->whereNull('deleted_at')->count();

            $data['bill_status'] = Invoice::where('paid',0)->whereNull('deleted_at')->count();
    	

    	return view('company.dashboard',compact('data'));
    }



}