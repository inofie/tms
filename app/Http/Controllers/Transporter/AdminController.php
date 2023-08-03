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
        $data2 = Shipment::with('statusData')->withTrashed()->whereRaw("find_in_set('$ff->id' , all_transporter)")->get();
        
        $data = array();
        foreach ($data2 as $key => $value) {
            $data1 = Shipment::with('statusData')->withTrashed()->whereNull('deleted_at')->where('shipment_no', $value->shipment_no)->first();
            if($data1){
            $data2[$key] = $data1;
            if($data1->statusData == ''){
            $data2[$key]->status = $data1->statusData->status;
            }
            else{
                $data2[$key]->status = $data1['status'];
            }
        }
        }

        $ids = array();
        $pending1 = array();

        foreach ($data2 as $key => $value){
            if($value->status == "1" ){
                array_push($ids,$value->id);
            }
        }
        $pending1 = Shipment::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
        $data['pending'] = count($pending1);

        $ids = array();
        $ontheway1 = array();

        foreach ($data2 as $key => $value){
            if($value->status == "2" || $value->status == "4" || $value->status == "5" || $value->status == "18"
            || $value->status == "6" || $value->status == "7" || $value->status == "8" || $value->status == "9" || $value->status == "10"
            || $value->status == "11" || $value->status == "12" || $value->status == "13" || $value->status == "14" || $value->status == "15"){
            array_push($ids,$value->id);
            }
        }
        $ontheway1 = Shipment::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
        $data['ontheway'] = count($ontheway1);

        $ids = array();
        $delivery1 = array();

        foreach ($data2 as $key => $value){
            if($value->status == "3" || $value->status == "17"){
                array_push($ids,$value->id);
            }
        }

        $delivery1 = Shipment::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
    	$data['delivery'] = count($delivery1);
        $data['total'] = count($data2);
    return view('transporter.dashboard',compact('data'));
    }

}