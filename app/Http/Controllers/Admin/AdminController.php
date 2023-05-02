<?php 

namespace App\Http\Controllers\Admin;

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
use App\Invoice;
use App\Account;
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;
use Config;


class AdminController extends Controller
{

	public function __construct()
    {
       
    }



    public function Dashboard(Request $Request)
    {	
    	if(isset($Request->company_id)) {


                    $from = date('2020-01-01');
                    
                    $to = date('Y-m-d');

                    $total_credit1 = Account::where('to_company',$Request->company_id)->whereBetween('dates', [$from, $to])->sum('credit');

                    $total_credit2 = Account::where('to_company',$Request->company_id)->whereBetween('dates', [$from, $to])->sum('debit');

                    $total_credit = $total_credit1 + $total_credit2;

                    $data['pl_report'] = $total_credit;

    		

            $data['pending'] = Shipment::where('status',0)->where('company',$Request->company_id)->count();

            $data['ontheway'] = Shipment::where('status',1)->where('company',$Request->company_id)->count();

            $data['bill_status'] = Invoice::where('paid',0)->where('company_id',$Request->company_id)->count();

            $company_id = $Request->company_id;

    	} else {

            $from = date('Y-04-01');
                    
            $to = date('Y-m-d');

            $total_credit1 = Account::whereBetween('dates', [$from, $to])->sum('credit');

            $total_credit2 = Account::whereBetween('dates', [$from, $to])->sum('debit');

            $total_credit = $total_credit1 + $total_credit2;

            $data['pl_report'] = $total_credit;

            $data['pending'] = Shipment::where('status',0)->count();

            $data['ontheway'] = Shipment::where('status',1)->count();

            $data['bill_status'] = Invoice::where('paid',0)->count();

            $company_id = 0;

    	}

       // dd($data);

    	$company =Company::all();
	
    	return view('admin.dashboard',compact('data','company','company_id'));
    }



}