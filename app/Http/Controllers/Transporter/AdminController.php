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

        $total = Shipment_Driver::withTrashed()->where('transporter_id', $ff->id)->whereNull('deleted_at')
        // ->whereRaw('id IN (select MAX(id) FROM shipment_driver GROUP BY shipment_no)')
        ->groupBy('shipment_no')->get();
        //dd($total);
        $ids = array();
        if(!$total->count()){
            $data['total'] = 0;
        }else{
            foreach ($total as $key => $value){
                if($value->status == "1" || $value->status == "2" || $value->status == "3" || $value->status == "4" || $value->status == "5"
                || $value->status == "6" || $value->status == "7" || $value->status == "8" || $value->status == "9" || $value->status == "10"
                || $value->status == "11" || $value->status == "12" || $value->status == "13" || $value->status == "14" || $value->status == "15"
                || $value->status == "17" || $value->status == "18" ){
                    array_push($ids,$value->id);
                }
            }
            $total = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
            
            foreach ($total as $key => $value) {
                $total1[$key] = Shipment::withTrashed()->whereNull('deleted_at')->where('shipment_no', $value->shipment_no)->first();	
               
            }
            $data['total'] = count($total1);
        }
    

        //dd($data['total']);

        $pending = Shipment_Driver::withTrashed()->where('transporter_id', $ff->id)->whereNull('deleted_at')
        // ->whereRaw('id IN (select MAX(id) FROM shipment_driver GROUP BY shipment_no)')
        ->groupBy('shipment_no')->get();
        
        $ids = array();
        $pending1 = array();
        if(!$pending->count()){
            $data['pending'] = 0;
        }else{
        foreach ($pending as $key => $value){
            if($value->status == "1" ){
                array_push($ids,$value->id);
            }
        }
        $pending = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
        
        foreach ($pending as $key => $value) {
            $pending1[$key] = Shipment::withTrashed()->whereNull('deleted_at')->where('shipment_no', $value->shipment_no)->first();	
              
        }
        $data['pending'] = count($pending1);
        }

        $ontheway = Shipment_Driver::withTrashed()->where('transporter_id', $ff->id)->whereNull('deleted_at')
       
        ->groupBy('shipment_no')->get();
        $ids = array();
        $ontheway1 = array();
        if(!$ontheway->count()){
            $data['ontheway'] = 0;
        }else{
        foreach ($ontheway as $key => $value){
            if($value->status == "2" || $value->status == "4" || $value->status == "5" || $value->status == "18"
            || $value->status == "6" || $value->status == "7" || $value->status == "8" || $value->status == "9" || $value->status == "10"
            || $value->status == "11" || $value->status == "12" || $value->status == "13" || $value->status == "14" || $value->status == "15"){
            array_push($ids,$value->id);
            }
        }
        $ontheway = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
        
        foreach ($ontheway as $key => $value) {
            $ontheway1[$key] = Shipment::withTrashed()->whereNull('deleted_at')->where('shipment_no', $value->shipment_no)->first();	
              
        }
        $data['ontheway'] = count($ontheway1);
    }

        $delivery = Shipment_Driver::withTrashed()->where('transporter_id', $ff->id)->whereNull('deleted_at')
				
				->groupBy('shipment_no')->get();
				$ids = array();
                $delivery1 = array();
                if(!$delivery->count()){
                    $data['delivery'] = 0;
                }else{
				foreach ($delivery as $key => $value){
					if($value->status == "3" || $value->status == "17"){
						array_push($ids,$value->id);
					}
				}
				$delivery = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
                
			
				foreach ($delivery as $key => $value) {
                    $delivery1[$key] = Shipment::withTrashed()->whereNull('deleted_at')->where('shipment_no', $value->shipment_no)->first();	
					  
                }
        $data['delivery'] = count($delivery1);
        //dd($data['ontheway']);
    }
    
    	
    	return view('transporter.dashboard',compact('data'));
    }



}