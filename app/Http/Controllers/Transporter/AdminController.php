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

        $ff= Transporter::where('user_id',Auth::user()->id)->first();
        $data2 = Shipment_Transporter::withTrashed()
		->where('transporter_id', $ff->id)
		->whereNull('deleted_at')->where('is_trucktransfer', '0')
		->orderby('id','desc')->groupBy('shipment_no')->get();
        
        $data = array();

        $pending1 = Shipment_Transporter::withTrashed()
		->where('transporter_id', $ff->id)
		->whereNull('deleted_at')->where('is_trucktransfer', '0')
		->orderby('id','desc')->where('status','1')->groupBy('shipment_no')->get();

        $data['pending'] = count($pending1);

		$get_shipment_trans_datas = Shipment_Transporter::withTrashed()
					->where('transporter_id', $ff->id)
					->whereNull('deleted_at')->where('is_trucktransfer', '0')
					->orderby('id','desc')->whereIn('status',['1'])->pluck('shipment_no')->toArray();
					
					$ontheway1 = Shipment_Transporter::whereNotIn('shipment_no',$get_shipment_trans_datas)
					->where('status','2')
					->where('transporter_id', $ff->id)->groupBy('shipment_no')->orderby('id','desc')->get();
			
		
        $data['ontheway'] = count($ontheway1);

		$get_shipment_trans_datas1 = Shipment_Transporter::withTrashed()
		->where('transporter_id', $ff->id)
		->whereNull('deleted_at')->where('is_trucktransfer', '0')
		->orderby('id','desc')->whereIn('status',['1','2'])->pluck('shipment_no')->toArray();
	

		$delivery1 = Shipment_Transporter::whereNotIn('shipment_no',$get_shipment_trans_datas1)
		->where('transporter_id', $ff->id)->groupBy('shipment_no')->orderby('id','desc')->get();
		
       
        $data['delivery'] = count($delivery1);

        
        $data['total'] = count($data2);
    return view('transporter.dashboard',compact('data'));
    }

}