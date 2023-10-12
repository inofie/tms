<?php 

namespace App\Http\Controllers\Forwarder;

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
use Mail,DateTime;
use Illuminate\Support\Facades\Auth;




class ShipmentController extends Controller
{

	public function __construct()
    {
       /* if(Auth::user()->role != "transporter") {

            Auth::logout();
            
           return redirect()->route('login')->with('error',"You have no permission for that.");
        }*/
      
    }



     public function check()
    {
         if(Auth::user()->role != "forwarder") {

            Auth::logout();
            
           return redirect()->route('login')->with('error',"You have no permission for that.");
        } 
    }



    public function List(Request $Request)
    {

        $this->check();

        $ff = Forwarder::where('user_id',Auth::user()->id)->first();
            
        $data = Shipment::where('forwarder',$ff->id)->whereRaw('DATEDIFF(CURDATE(),date) <= 6')->get();
        //dd(auth()->user());
        return view('forwarder.shipmentlist',compact('data'));

    }


     public function AllList(Request $Request)
    {

        $this->check();
        $ff = Forwarder::where('user_id',Auth::user()->id)->first();  
        $data = Shipment::where('forwarder',$ff->id)->whereRaw('DATEDIFF(CURDATE(),date) >= 6')->get();

        return view('forwarder.shipmentlist',compact('data'));

    }



    public function DownloadLR(Request $Request)
    {

         $this->check();

        $data2 = Shipment::where('myid',$Request->id)->first();
        
        $data = Shipment::where('shipment_no',$data2->shipment_no)->first();
        
        $comp = Company::findorfail($data->company);

        $data->company_name = $comp->name;

        $data->gst = $comp->gst_no;

        if($data->forwarder != "" && $data->forwarder != null && $data->forwarder != 'null'){

                $for = Forwarder::findorfail($data->forwarder);

                $data->forwarder_name = $for->name;

                }else {
                    $data->forwarder_name ="";

                } 
                 if($data->transporter != "" && $data->transporter != null && $data->transporter != 'null'){
                $tra = Transporter::findorfail($data->transporter);
                $data->transporter_name = $tra->name;
                } else {
                    $data->transporter_name ="";

                } 

                if($data->trucktype != "" && $data->trucktype != null && $data->trucktype != 'null'){
                $truck = Truck::findorfail($data->trucktype);
                $data->trucktype_name = $truck->name;
                } else {
                    $data->trucktype_name ="";

                } 
                $tras_list =Shipment_Transporter::where('shipment_no',$data2->shipment_no)->get();
                $t_list = "";
                foreach ($tras_list as $key => $value) { 
                    $tt =Transporter::findorfail($value->transporter_id);
                     if($key == 0) {
                        $t_list = $t_list."".$tt->name; 
                    } else {

                        $t_list = $t_list.", ".$tt->name; 
                     }
                }

                $data->transporters_list =  $t_list;

                if($Request->role== "transporter"){

                     $driver_list =Shipment_Driver::where('shipment_no',$data2->shipment_no)->where('transporter_id',$Request->other_id)->get();
                $d_list = "";

                foreach ($driver_list as $key2 => $value2) { 
                    if($key2 == 0) {
                         $d_list = $d_list."".$value2->truck_no; 

                    } else {
                         $d_list = $d_list.", ".$value2->truck_no; 

                    }

                }

                $data->truck_no = $d_list;

                } else {

                    $driver_list =Shipment_Driver::where('shipment_no',$data2->shipment_no)->get();
                    $d_list = "";

                    foreach ($driver_list as $key2 => $value2) { 
                        if($key2 == 0) {
                             $d_list = $d_list."".$value2->truck_no; 

                        } else {
                             $d_list = $d_list.", ".$value2->truck_no; 

                        }

                    }

                    $data->truck_no = $d_list;

                }

                $trucks = Shipment_Driver::where('shipment_no',$data->shipment_no)->get();

               
      if($comp->lr == "yoginilr") { 

        if($data2->fcl == 1){

            $pdf = PDF::loadView('lr.yoginifcl',compact('data','trucks'));

            return $pdf->download($data->lr_no.'.pdf');

            //return view('yoginifcl',compact('data','trucks'));

        } else {


        $pdf = PDF::loadView('lr.yoginilr',compact('data','trucks'));

        return $pdf->download($data->lr_no.'.pdf');

    }

      
      } elseif($comp->lr == "ssilr"){

         if($data2->fcl == 1){

            $pdf = PDF::loadView('lr.ssifcl',compact('data','trucks'));

            return $pdf->download($data->lr_no.'.pdf');

            //return view('lr.ssifcl',compact('data','trucks'));

        } else {


        $pdf = PDF::loadView('lr.ssilr',compact('data','trucks'));

        return $pdf->download($data->lr_no.'.pdf');
     }   


       } elseif($comp->lr == "hanshlr"){ 

         if($data2->fcl == 1){

            $pdf = PDF::loadView('lr.hanshfcl',compact('data','trucks'));

            return $pdf->download($data->lr_no.'.pdf');

            //return view('lr.ssifcl',compact('data','trucks'));

        } else {
        
        $pdf = PDF::loadView('lr.hanshlr',compact('data','trucks'));

        return $pdf->download($data->lr_no.'.pdf');

        }


      }  elseif($comp->lr == "bmflr"){ 

         if($data2->fcl == 1){

            $pdf = PDF::loadView('lr.bmffcl',compact('data','trucks'));

            return $pdf->download($data->lr_no.'.pdf');

            //return view('lr.ssifcl',compact('data','trucks'));

        } else {
        
        $pdf = PDF::loadView('lr.bmflr',compact('data','trucks'));

        return $pdf->download($data->lr_no.'.pdf');


        }

      }      

        

       
    }

    
    public function ShipmentDetails(Request $Request)
    {

        $data2 = Shipment::where('myid',$Request->id)->first();
        
        $data = Shipment::withTrashed()->where('shipment_no',$data2->shipment_no)->first();
        
        $comp = Company::withTrashed()->findorfail($data->company);

        $data->company_name = $comp->name;

        if($data->forwarder != "" && $data->forwarder != null && $data->forwarder != 'null'){

                $for = Forwarder::withTrashed()->findorfail($data->forwarder);

                $data->forwarder_name = $for->name;

                }else {
                    $data->forwarder_name ="";

                } 
                 if($data->transporter != "" && $data->transporter != null && $data->transporter != 'null'){
                $tra = Transporter::withTrashed()->findorfail($data->transporter);
                $data->transporter_name = $tra->name;
                } else {
                    $data->transporter_name ="";

                } 

                if($data->trucktype != "" && $data->trucktype != null && $data->trucktype != 'null'){
                $truck = Truck::withTrashed()->findorfail($data->trucktype);
                $data->trucktype_name = $truck->name;
                } else {
                    $data->trucktype_name ="";

                } 
                $tras_list =Shipment_Transporter::withTrashed()->where('shipment_no',$data2->shipment_no)->get();
                $t_list = "";
                foreach ($tras_list as $key => $value) { 
                    $tt =Transporter::withTrashed()->findorfail($value->transporter_id);
                     if($key == 0) {
                        $t_list = $t_list."".$tt->name; 
                    } else {

                        $t_list = $t_list.", ".$tt->name; 
                     }
                }

                $data->transporters_list =  $t_list;

                if($Request->role== "transporter"){

                     $driver_list =Shipment_Driver::withTrashed()->where('shipment_no',$data2->shipment_no)->where('transporter_id',$Request->other_id)->get();
                $d_list = "";

                foreach ($driver_list as $key2 => $value2) { 
                    if($key2 == 0) {
                         $d_list = $d_list."".$value2->truck_no; 

                    } else {
                         $d_list = $d_list.", ".$value2->truck_no; 

                    }

                }

                $data->truck_no = $d_list;

                } else {

                    $driver_list =Shipment_Driver::withTrashed()->where('shipment_no',$data2->shipment_no)->get();
                    $d_list = "";

                    foreach ($driver_list as $key2 => $value2) { 
                        if($key2 == 0) {
                             $d_list = $d_list."".$value2->truck_no; 

                        } else {
                             $d_list = $d_list.", ".$value2->truck_no; 

                        }

                    }

                    $data->truck_no = $d_list;

                }

                $trucks = Shipment_Driver::where('shipment_no',$data->shipment_no)->get();

                $load_image = "";
                $unload_image = "";

                foreach ($trucks as $key => $t_value) {

                                                    
                    if($load_image == '' & $t_value->loaded_photo != 'noimage.png'){
                      $load_image = $t_value->loaded_photo;
                    }


                    if($t_value->unloaded_photo != 'noimage.png'){
                      $unload_image = $t_value->unloaded_photo;
                    }


                }

                if($load_image == ''){

                    $load_image = "noimage.png";
                } 


                 if($unload_image == ''){

                    $unload_image = "noimage.png";
                } 




                //dd($trucks);

        return view('forwarder.shipmentdetail',compact('data','trucks','load_image','unload_image'));

    }
    public function ShipmentSummaryList(Request $Request)
    {
        $this->check();
        $shipment_no = $Request->shipment_no;
        $data = Shipment_Summary::where('shipment_no', $Request->shipment_no)->get();
        $count = $data->count();
        $old_time = "";
        foreach($data as $key => $value) {
            if($key > 0) {
                $datetime1 = new DateTime($value->created_at);
                $datetime2 = new DateTime($old_time);
                $interval = $datetime1->diff($datetime2);
               if($interval->format('%d') > 0){
                $elapsed = $interval->format('%d')."d ".$interval->format('%h')."h ".$interval->format('%i')."m";
               }
               else{
                if( $interval->format('%h') > 0){
                    $elapsed = $interval->format('%h')."h ".$interval->format('%i')."m";
                }
                else{
                    $elapsed = $interval->format('%i')."m";
                }
               }
                $data[$key]['timedifference'] = $elapsed;
            }
            $old_time = $value->created_at;
        }
        return view('admin.shipmentsummarylist',compact('data','shipment_no'));
    }



    


}