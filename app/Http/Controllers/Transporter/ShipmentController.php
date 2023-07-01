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
use App\Account;
use Hash;
use PDF;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WebNotificationController;
use Config;
use App\Helper\GlobalHelper;
use App\Notification;


class ShipmentController extends Controller
{

	public function __construct()
    {
      
    }


    public function check()
    {
         if(Auth::user()->role == "forwarder") {

            Auth::logout();
            
           return redirect()->route('login')->with('error',"You have no permission for that.");
        }
    }



    public function List(Request $Request)
    {  
         $this->check();

        $ff= Transporter::where('user_id',Auth::user()->id)->first();
        //$cargostatus = Shipment_Driver::where('shipment_no',$data->shipment_no)->latest()->take(1)->first();
        //$data = Shipment::where("status","!=",3)->whereRaw("find_in_set('$ff->id' , all_transporter)")->whereRaw('DATEDIFF(CURDATE(),date) <= 6')->where('paid',0)->get();
        
        $data2 = Shipment_Transporter::where('transporter_id', $ff->id)->whereNull('deleted_at')->groupBy('shipment_no')->get();
        
        $data = array();
        
        foreach ($data2 as $key => $value) {
            $data1 = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();
            $data[$key] = $data1;
            $data3 = Shipment_Driver::withTrashed()->where('shipment_no', $value->shipment_no)->
            where('transporter_id', $ff->id)->orderBy('id','desc')->first();
            if($data3){
             $data[$key]['status'] = $data3->status;
            }
        }
       // dd($data);
         //dd($data[$key]->status);
        $warehouse = Warehouse::get();
        
    	return view('transporter.shipmentlist',compact('data','warehouse'));

   	}


   	public function Add(Request $Request)
    {  

        $this->check();

    	$company = Company::orderby('name','asc')->get();

    	$truck_type = Truck::orderby('sorting','asc')->get();

    	$forwarder = Forwarder::orderby('name','asc')->get();

    	$transporter = Transporter::orderby('name','asc')->get();

    	return view('admin.shipmentadd',compact('company','truck_type','forwarder','transporter'));

   	}



      public function Save(Request $Request)
    { 

       //dd($Request);

         //$this->check();

      if($Request->type2 == 'fcl') {


        $this->validate($Request, [
        //'shipment_no'=>'required|unique:shipment,shipment_no',
        'date' => 'required|date_format:d-m-Y',
        'company' => 'required',
        'from1' => 'required',
        'to1' => 'required',
        'forwarder' => 'required',
        'package' => 'required|numeric',
        'weight'=>'required|numeric',
        'container_no'=>'required',
        'seal_no'=>'required',

         ],[
        // 'shipment_no.required'=>'Please Enter Shipment Number',
         //'shipment_no.unique'=>'This Number Already in System, Please Enter Another Shipment Number',
         'date.required' => "Please Select Date",
         'company.required' => "Please Select Company",
         'from1.required' => "Please Enter From",
         'to1.required' => "Please Enter To",
         'forwarder.required' => "Please Select Forwarder",
         'package.required' => "Please Enter No. of Package",
         'weight.required' => "Please Enter Weight",
         'container_no.required' => "Please Enter Container No",
         'seal_no.required' => "Please Enter Seal No",
         ]);

      } else {

       $this->validate($Request, [
       // 'shipment_no'=>'required|unique:shipment,shipment_no',
        'date' => 'required|date_format:d-m-Y',
        'company' => 'required',
        'from1' => 'required',
        'to1' => 'required',
        'forwarder' => 'required',
        'package' => 'required|numeric',
        'weight'=>'required|numeric',
        ],[
         //'shipment_no.required'=>'Please Enter Shipment Number',
         //'shipment_no.unique'=>'This Number Already in System, Please Enter Another Shipment Number',
         'date.required' => "Please Select Date",
         'company.required' => "Please Select Company",
         'from1.required' => "Please Enter From",
         'to1.required' => "Please Enter To",
         'forwarder.required' => "Please Select Forwarder",
         'package.required' => "Please Enter No. of Package",
         'weight.required' => "Please Enter Weight",
         ]);

    }

                $data = new Shipment();

                $data->date = date('Y-m-d',strtotime($Request->date));

                $data->company = $Request->company;

                if( $Request->type1 == "import"){

                    $data->imports = 1;

                    $data->exports = 0;

                } else {

                    $data->exports = 1;

                    $data->imports = 0;
                }

                if($Request->type2 == "lcl"){

                    $data->lcl = 1;

                    $data->fcl = 0;

                } else {

                    $data->fcl = 1;

                    $data->lcl = 0;

                }

                $data->from1 = $Request->from1;

                $data->to1 = $Request->to1;

                $data->to2 = $Request->to2;

                $data->trucktype = $Request->truck_type;

                if($Request->truck_no != "" && $Request->truck_no != "null" && $Request->truck_no != null){

                $data->status = 1;

                }

                $data->forwarder = $Request->forwarder;

                $data->show_detail = $Request->show_detail;

                $data->consignor = $Request->consignor;

                $data->consignor_address = $Request->consignor_add;

                $data->consignee = $Request->consignee;

                $data->consignee_address = $Request->consignee_add;

                $data->package = $Request->package;

                $data->description= $Request->cargo_description;

                $data->weight = $Request->weight;

                $data->shipper_invoice = $Request->shipper_no;

                $data->forwarder_ref_no = $Request->for_ref_no;

                $data->b_e_no = $Request->be_no;

                if($Request->type2 == "fcl"){

                $data->container_type = $Request->container_type;

                $data->destuffing_date = date('Y-m-d',strtotime($Request->destuffing));

                $data->container_no = $Request->container_no;

                $data->shipping_line = $Request->shipping_line;

                $data->cha = $Request->cha;

                $data->seal_no = $Request->seal_no;

                $data->pod = $Request->pod;

                

                }

                $data->invoice_amount = $Request->invoice_amount;

                $data->remark = $Request->remark;
                 
                if($Request->transporter != null && $Request->transporter != '' && $Request->transporter != 'null'){

                    $data->all_transporter = $Request->transporter;

                }	             

            	$data->licence_no = $Request->licence_no;

            	$data->driver_name = $Request->driver_name;


                $shipment_no =$Request->shipment_no;

                $data->shipment_no = $shipment_no;


                $data->save();

                $company = Company::findorfail($Request->company);
                $company->last_no = (int) filter_var($shipment_no, FILTER_SANITIZE_NUMBER_INT)+1;
                $company->save();

                $aa= Shipment::findorfail($data->id);
                $aa->shipment_no =$shipment_no;
                $aa->lr_no = $shipment_no."/".getenv('FIN_YEAR');
                $aa->myid = uniqid();
                $aa->save();

                $summary = new Shipment_Summary();
                $summary->shipment_no = $shipment_no;
                $summary->flag = "create";
                $summary->description = "Create Shipment";
                $summary->created_by = Auth::id();
                $summary->save();

                if($Request->transporter != null && $Request->transporter != '' && $Request->transporter != 'null') {
                    $tt=Transporter::findorfail($Request->transporter);  

                    if ($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') {

                        $driver_id = Driver::findorfail($Request->driver_id);

                        } else {

                        $driver_id = Driver::where('transporter_id', $Request->transporter)->where('self', 1)->first();

                        } 
                        
                    $transs = new Shipment_Transporter();
                    $transs->shipment_no = $shipment_no;
                    $transs->shipment_id = $data->id;
                    $transs->transporter_id = $Request->transporter;
                    $transs->driver_id =  $driver_id->id;
                    $transs->name = $tt->name;
                    $transs->created_by = Auth::id();
                    $transs->myid = uniqid();
                    $transs->save(); 

                    $summary = new Shipment_Summary();
                    $summary->shipment_no = $shipment_no;
                    $summary->flag = "create";
                    $summary->transporter_id = $Request->transporter;
                    $summary->description = "Add Transporter";
                    $summary->save();


                }

               /* if($Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null') {

                    $tt=Transporter::findorfail($Request->transporter); 
                    $driver = new Shipment_Driver();
                    $driver->shipment_no = $shipment_no;
                    $driver->transporter_id = $Request->transporter;
                    $driver->truck_no = $Request->truck_no;
                    $driver->driver_id =  $driver_id->id;
                    $driver->mobile = $tt->phone;
                    $driver->created_by = Auth::id();
                    $driver->myid = uniqid(); 
                    $driver->save(); 


                    $summary = new Shipment_Summary();
                    $summary->shipment_no = $shipment_no;
                    $summary->flag = "create";
                    $summary->transporter_id = $Request->transporter;
                    $summary->description = "Add Truck. \n".$Request->truck_no."(".$tt->phone.").";
                    $summary->save();               
                }
*/


                if (($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') || ($Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null')) {

                    if ($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') {
                        $mydriverdetails = Driver::findorfail($Request->driver_id);
                    } else {
                        $mydriverdetails = Driver::where('transporter_id', $Request->transporter)->where('self', 1)->first();
                    }


                    if ($Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null') {
                        $mytruckno = $Request->truck_no;
                    } else {
                        $mytruckno = $mydriverdetails->truck_no;
                    }

                        $tt = Transporter::findorfail($Request->transporter);
                        $driver = new Shipment_Driver();
                        $driver->shipment_no = $shipment_no;
                        $driver->transporter_id = $Request->transporter;
                        $driver->driver_id = $mydriverdetails->id;
                        $driver->truck_no = $mytruckno;
                        $driver->mobile = $tt->phone;
                        $driver->created_by = Auth::id();
                        $driver->myid = uniqid();
                        $driver->save();

                        $summary = new Shipment_Summary();
                        $summary->shipment_no = $shipment_no;
                        $summary->flag = "Add Driver";
                        $summary->transporter_id = $Request->transporter;
                        $summary->description = "Add Driver. \n" . $mytruckno . "(Co.No." . $tt->phone . ").";
                        $summary->save();
                
            }




                // Code For Notification start 

                // For ALL Company Notification

                $token =array();

                $all_company = Company::get(); 

                foreach ($all_company as $key => $value) {

                    $cuser = User::findorfail($value->user_id);

                    if($cuser->device_token != ""){

                     array_push($token,$cuser->device_token);
                    }

                    
                }


                    $title = "New Shipment Generated";

                     $message= $Request->shipment_no." shipment generated.";

                     $aa = new WebNotificationController();

                     $aa->index($token,$title,$message,$Request->shipment_no);



                  /// For Transporter

                $token =array();

                if($Request->transporter != null && $Request->transporter != '' && $Request->transporter != 'null') {

                    $tras=Transporter::findorfail($Request->transporter);  

                    $tuser = User::findorfail($tras->user_id);

                    if($tuser->device_token != ""){

                        array_push($token,$tuser->device_token);

                     $title = "New Shipment Assigned.";

                     $message= "We would like to inform, the shipment number ".$Request->shipment_no." is assigned to you.";

                     $aa = new WebNotificationController();

                     $aa->index($token,$title,$message,$Request->shipment_no);
                    }
                }



                 /// For Forwarder

                $token =array();

                if($Request->forwarder != null && $Request->forwarder != '' && $Request->forwarder != 'null') {

                    $tt=Forwarder::findorfail($Request->forwarder);

                    $tuser = User::findorfail($tt->user_id);

                    if($tuser->device_token != ""){

                        array_push($token,$tuser->device_token);

                     $title = "Your shipment order is generated.";

                     $message= "We would like to inform you, Your order is placed & its shipment number is ".$Request->shipment_no;

                     $aa = new WebNotificationController();

                     $aa->index($token,$title,$message,$Request->shipment_no);
                    }

                }

                // Send LR Mail - Start //

                if ($Request->forwarder != "" && $Request->forwarder != null && $Request->forwarder != 'null')
                {

                    $ship_data = Shipment::where('shipment_no', $Request->shipment_no)->first();

                    $comp = Company::withTrashed()->findorfail($ship_data->company);

                    $ship_data->company_name = $comp->name;

                    $ship_data->gst = $comp->gst_no;

                    $for = Forwarder::findorfail($ship_data->forwarder);

                    $ship_data->forwarder_name = $for->name;
                    
                    if ($ship_data->transporter != "" && $ship_data->transporter != null && $ship_data->transporter != 'null') {
                        $tra = Transporter::findorfail($ship_data->transporter);
                        $ship_data->transporter_name = $tra->name;
                    } else {
                        $ship_data->transporter_name = "";
                    }

                    if ($ship_data->trucktype != "" && $ship_data->trucktype != null && $ship_data->trucktype != 'null') {
                        $truck = Truck::findorfail($ship_data->trucktype);
                        $ship_data->trucktype_name = $truck->name;
                    } else {
                        $ship_data->trucktype_name = "";

                    }

                    $tras_list = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->get();
                    $t_list = "";
                    
                    foreach ($tras_list as $key => $value) {
                        $tt = Transporter::findorfail($value->transporter_id);
                        if ($key == 0) {
                            $t_list = $t_list . "" . $tt->name;
                        } else {
                            $t_list = $t_list . ", " . $tt->name;
                        }
                    }

                        $ship_data->transporters_list = $t_list;
                        $driver_list = Shipment_Driver::where('shipment_no', $Request->shipment_no)->get();
                        $d_list = "";

                        foreach ($driver_list as $key2 => $value2) {
                            if ($key2 == 0) {
                                $d_list = $d_list . "" . $value2->truck_no;
                            } else {
                                $d_list = $d_list . ", " . $value2->truck_no;
                            }
                        }

                        $ship_data->truck_no = $d_list; 
                        $trucks = Shipment_Driver::where('shipment_no', $ship_data->shipment_no)->get();


                 if($Request->transporter != null && $Request->transporter != '' && $Request->transporter != 'null') {

                    if ($comp->lr == "yoginilr") {

                        $pdf = PDF::loadView('lr.yoginilr', compact('data', 'trucks'));

                        file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

                        $shipment = $Request->shipment_no;
                        // $myemail = 'keyurdomadiya602@gmail.com';
						$myemail = $for->email;

                        $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);

                        $yogini_username = env('YOGINI_MAIL_USERNAME');

                        $yogini_password = env('YOGINI_MAIL_PASSWORD');

                       Config::set('mail.username', $yogini_username);

                        Config::set('mail.password', $yogini_password);
                            
                        $mail_service = env('MAIL_SERVICE');

                        if($mail_service == 'on'){   

                         Mail::send('yoginimail', $data2, function($message) use ($data2) {
                            $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                            $message->from('noreplay@yoginitransport.com','Yogini Transport');
                            $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                        });


                     }
                                
                    } elseif ($comp->lr == "ssilr") {

                        $pdf = PDF::loadView('lr.ssilr', compact('data', 'trucks'));

                        file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

                        
                        $shipment = $Request->shipment_no;

                        $myemail =  $for->email;

                        $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);

                        $ssi_username = env('SSI_MAIL_USERNAME');

                        $ssi_password = env('SSI_MAIL_PASSWORD'); 

                        Config::set('mail.username', $ssi_username);
                        Config::set('mail.password', $ssi_password);
                        $mail_service = env('MAIL_SERVICE');
                             if($mail_service == 'on'){   
                
                         Mail::send('ssimail', $data2, function($message) use ($data2) {
                            $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                            $message->from('noreplay@ssitransway.com','SSI Transway');
                            $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                        }); 

                     }
                            
                        
                    } elseif ($comp->lr == "hanshlr") {

                        $pdf = PDF::loadView('lr.hanshlr', compact('data', 'trucks'));

                        file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

                        $shipment = $Request->shipment_no;

                        $myemail =  $for->email;

                        $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);

                        $hansh_username = env('HANS_MAIL_USERNAME');
                        $hansh_password = env('HANS_MAIL_PASSWORD');
                        Config::set('mail.username', $hansh_username);
                        Config::set('mail.password', $hansh_password);
                            $mail_service = env('MAIL_SERVICE');
                if($mail_service == 'on'){   
                         Mail::send('hanshmail', $data2, function($message) use ($data2) {
                            $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                            $message->from('noreplay@hanstransport.com','Hansh Transport');
                            $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                        }); 
                        }    
                        
                    } elseif ($comp->lr == "bmflr") {

                        $pdf = PDF::loadView('lr.bmflr', compact('data', 'trucks'));

                        file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

                        $shipment = $Request->shipment_no;
                        $myemail =  $for->email;

                        $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);
                            
                            $mail_service = env('MAIL_SERVICE');
                if($mail_service == 'on'){   
                         Mail::send('bmfmail', $data2, function($message) use ($data2) {
                            $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                            $message->from('noreplay@bmfreight.com','BMF Freight');
                            $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                        });                 
                    
                    } 

                }

              }

            }

                // Send LR Mail - End //

                return redirect()->route('shipmentlisttransporter')->with('success','Shipment successfully Created.');


    }

     public function TruckList(Request $Request)
    
    { 
         $this->check();

        $ship = Shipment::where('myid',$Request->id)->first();
            
        $shipment_no = $ship->shipment_no;

        $data1 = Shipment_Driver::where('shipment_no',$ship->shipment_no)->get();
        
        $data = array();

        foreach ($data1 as $key => $value) {
            $data[$key]=$value;
            $d= Cargostatus::findorfail($value->status);
            $data[$key]->status_name = $d->name;
            $data[$key]->imports = $ship->imports;
            $data[$key]->exports = $ship->exports ;
            $data[$key]->fcl = $ship->fcl;
            $data[$key]->lcl = $ship->lcl;
        }
        //$status = Cargostatus::get();
        if($data){
        if($data[$key]->imports == "1" && $data[$key]->fcl == "1" ){
            if($data[$key]->status_name == "Pending"){
                $status = Cargostatus::where('id','6')->get();
            }
            if($data[$key]->status_name == "Pickup Confirmed"){
                $status = Cargostatus::where('id','12')->get();
            }
            if($data[$key]->status_name == "Reach at Port"){
                $status = Cargostatus::where('id','14')->get();
            }
            if($data[$key]->status_name == "Load Container"){
                $status = Cargostatus::where('id','9')->get();
            }
            if($data[$key]->status_name == "Document Received"){
                $status = Cargostatus::where('id','7')->get();
            }
            if($data[$key]->status_name == "Reach at Company"){
                $status = Cargostatus::where('id','18')->get();
            }
            if($data[$key]->status_name == "Unload Cargo"){
                $status = Cargostatus::where('id','17')->get();
            }
            if($data[$key]->status_name == "Unload container"){
                $status = Cargostatus::where('admin', 0)->get();
            }
        }

        if($data[$key]->imports == "1" && $data[$key]->lcl == "1"){
            if($data[$key]->status_name == "Pending"){
                $status = Cargostatus::where('id','6')->get();
            }
            if($data[$key]->status_name == "Pickup Confirmed"){
                $status = Cargostatus::where('id','12')->get();
            }
            if($data[$key]->status_name == "Reach at Port"){
                $status = Cargostatus::where('id','2')->get();
            }
            if($data[$key]->status_name == "Load (Damage, Missing)"){
                $status = Cargostatus::where('id','9')->get();
            }
            if($data[$key]->status_name == "Document Received"){
                $status = Cargostatus::where('id','13')->get();
            }
            if($data[$key]->status_name == "Truck Transfer and Reach at company"){
                $status = Cargostatus::where('id','3')->get();
            }
            if($data[$key]->status_name == "Unloaded"){
                $status = Cargostatus::where('admin', 0)->get();
            }
        }
        
        if($data[$key]->exports == "1" && $data[$key]->lcl == "1"){
            if($data[$key]->status_name == "Pending"){
                $status = Cargostatus::where('id','6')->get();
            }
            if($data[$key]->status_name == "Pickup Confirmed"){
                $status = Cargostatus::where('id','7')->get();
            }
            if($data[$key]->status_name == "Reach at Company"){
                $status = Cargostatus::where('id','2')->get();
            }
            if($data[$key]->status_name == "Load (Damage, Missing)"){
                $status = Cargostatus::where('id','9')->get();
            }
            if($data[$key]->status_name == "Document Received"){
                $status = Cargostatus::where('id','11')->get();
            }
            if($data[$key]->status_name == "Truck Transfer and Reach at port"){
                $status = Cargostatus::where('id','3')->get();
            }
            if($data[$key]->status_name == "Unloaded"){
                $status = Cargostatus::where('admin', 0)->get();
            }

        }
        if($data[$key]->exports == "1" && $data[$key]->fcl == "1"){
            if($data[$key]->status_name == "Pending"){
                $status = Cargostatus::where('id','6')->get();
            }
            if($data[$key]->status_name == "Pickup Confirmed"){
                $status = Cargostatus::where('id','12')->get();
            }
            if($data[$key]->status_name == "Reach at Port"){
                $status = Cargostatus::where('id','14')->get();
            }
            if($data[$key]->status_name == "Load Container"){
                $status = Cargostatus::where('id','7')->get();
            }
            if($data[$key]->status_name == "Reach at Company"){
                $status = Cargostatus::where('id','15')->get();
            }
            if($data[$key]->status_name == "Load Cargo"){
                $status = Cargostatus::where('id','9')->get();
            }
            if($data[$key]->status_name == "Document Received"){
                $status = Cargostatus::where('id','17')->get();
            }
            if($data[$key]->status_name == "Unload container"){
                $status = Cargostatus::where('admin', 0)->get();
            }
        }
    }else{
        $status = Cargostatus::get();
    }

        return view('transporter.shipmenttrucklist',compact('data','status','shipment_no'));

    }


    public function ChangeTruckStatus(Request $Request)
    
    {   
         $this->check();


                $data = Shipment_Driver::findorfail($Request->truck_id);

                $data->status = $Request->status;

                if($Request->reason !="" && $Request->reason != "null" && $Request->reason == null){
                    $data->reason = $Request->reason;
                }

                $data->updated_by = Auth::id();

                $data->save();
                $ship =Shipment::where('shipment_no',$data->shipment_no)->first();
                if($Request->status == "1"){

                    $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                    $ss->status = 0;
                    // $ss->cargo_status = 0;
                    $ss->save();

                    $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->first();
                    $transp->status = 1;
                    $transp->save();
    
                }

                if($Request->status == "2"){

                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->first();
                $transp->status = 2;
                $transp->save();

                }


                if($Request->status == "3") {
                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                
                // $cargostatus = Shipment_Driver::where('shipment_no',$data->shipment_no)->latest()->take(1)->first();
                // if($data->id == $cargostatus->id)
                // {
                //     $ss->cargo_status = 2;
                // }
                // else{
                //     $ss->cargo_status = 1;
                // }
                $ss->save();

                $get_all_shipment = Shipment_Driver::where('shipment_no',$data->shipment_no)->where('status',1)->orwhere('status',2)->where('deleted_at','')->count();
                
                if($get_all_shipment == 0) {

                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->first();
                $transp->status = 2;
                $transp->save();

                }
                

               }
               if($Request->status == "17") {
                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;

                // $cargostatus = Shipment_Driver::where('shipment_no',$data->shipment_no)->latest()->take(1)->first();
                // if($data->id == $cargostatus->id)
                // {
                //     $ss->cargo_status = 2;
                // }
                // else{
                //     $ss->cargo_status = 1;
                // }
                
                $ss->save();

                $get_all_shipment = Shipment_Driver::where('shipment_no',$data->shipment_no)->where('status',1)->orwhere('status',2)->where('deleted_at','')->count();
                
                if($get_all_shipment == 0) {

                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->first();
                $transp->status = 2;
                $transp->save();

                }

               }
               if($Request->status == "4" || $Request->status == "5" || $Request->status == "11"  || $Request->status == "12"  || $Request->status == "13"  || $Request->status == "14"
               || $Request->status == "15"  || $Request->status == "18"){

                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->first();
                $transp->status = 2;
                $transp->save();

                }
                
               if($Request->status == "6"){

                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->first();
                $transp->status = 2;
                $transp->save();

                }

                if($Request->status == "7"){

                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->first();
                $transp->status = 2;
                $transp->save();

                }

                if($Request->status == "8"){

                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->first();
                $transp->status = 2;
                $transp->save();

                }

                if($Request->status == "9"){

                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->first();
                $transp->status = 2;
                $transp->save();

                }


                if($Request->status == "10"){

                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->first();
                $transp->status = 2;
                $transp->save();

                }


                $cargo = Cargostatus::findorfail($Request->status);             
                $summary = new Shipment_Summary();            
                $summary->shipment_no =  $data->shipment_no;            
                $summary->flag = $data->truck_no." is ".$cargo->name;             
                $summary->transporter_id = $data->transporter_id;        
                $summary->description = "Change Truck Shipment Status By Transporter(In Web).\n".$data->truck_no." is ".$cargo->name;
                $summary->created_by = Auth::id();      
                $summary->save(); 

                //transportor
                $transporter=Transporter::where('id',$data->transporter_id)->first();
                $from_user = User::find(Auth::id());
                $to_user = User::find($transporter['user_id']);
                $user=User::where('id',Auth::id())->first();
                $getStatus=Cargostatus::where('id',$data->status)->first();
                if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                    $notification = new Notification();
                    $notification->notification_from = $from_user->id;
                    $notification->notification_to = $to_user->id;
                    $notification->shipment_id = $ship->id;
                    $id = $data->shipment_no;
                    $title= "Status changed";
                    // "New Shipment" .' '. $driver->shipment_no .' '. "Added";
                    $message= $data["shipment_no"].' '."is".' '.$getStatus['name'].' ' ."by".' '.$user['username'];
                    $notification->title = $title;
                    $notification->message = $message;
                    $notification->notification_type = '2';
                    $notification->save();
                    $notification_id = $notification->id;
                    if($to_user->device_type == 'ios'){
                        GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                    }else{
                        GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                        }
                }

                //admin
			$from_user1 = User::find(Auth::id());
            $to_user1 = User::find(1);
			$user1=User::where('id',Auth::id())->first();
			$getStatus1=Cargostatus::where('id',$data->status)->first();
            if($from_user1['id'] != $to_user1['id'] && $from_user1 && $to_user1) {
                $notification = new Notification();
                $notification->notification_from = $from_user1->id;
                $notification->notification_to = $to_user1->id;
                $notification->shipment_id = $ship->id;
                $id = $data->shipment_no;
                $title= "Status changed";
                $message= $data["shipment_no"].' '."is".' '.$getStatus1['name'].' ' ."by".' '.$user1['username'];
				$notification->title = $title;
                $notification->message = $message;
                $notification->notification_type = '2';
                $notification->save();
                $notification_id = $notification->id;
				if($to_user->device_type == 'ios'){
                    GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                }else{
                    GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                    }
            }

                return redirect()->back()->with('success', ' Truck status change successfully');



    }


    public function DeleteTruckStatus(Request $Request)
    
    {   
         $this->check();

        //dd($Request);

        $data =Shipment_Driver::findorfail($Request->id);
        $data->deleted_by = Auth::id();
        $data->save();
        $data->delete();

        $summary = new Shipment_Summary();
        $summary->shipment_no =  $data->shipment_no;
        $summary->flag = "Delete Truck";
        $summary->company_id = $data->other_id;
        $summary->description = " Admin Delete Truck No. ".$data->truck_no;
        $summary->created_by = Auth::id();
        $summary->save();



    return redirect()->back()->with('success', $data->truck_no.' Truck is Deleted ');

    }

    public function AddExpense(Request $Request)
    
    {   
         $this->check();

        $ship = Shipment::where('myid',$Request->id)->first();
       
        $data1 = Shipment_Transporter::where('shipment_no', $ship->shipment_no)->get();

                $data = array();

                foreach ($data1 as $key => $value) {

                    $data[$key]=$value;

                    $tras = Transporter::withTrashed()->findorfail($value->transporter_id);

                    $data[$key]->name= $tras->name;

                }

        

        return view('admin.addexpense2',compact('data','ship'));

    

    }

    public function SaveExpense(Request $Request)
    
    {   
        
         
        $this->check();

              $this->validate($Request, [
        
        'transporter_id' => 'required',
        'amount' => 'required|numeric',
        
         ],[
         'transporter_id.required' => "Please Select Transporter",
         'amount.required' => "Please Enter Amount",
         
         ]);

              $shipment_data = Shipment::where('shipment_no', $Request->shipment_no)->first();


                $account = new Account();
                $account->to_transporter = $Request->transporter_id;
                $account->from_company = $shipment_data->company;
                $account->description =  $Request->shipment_no. $shipment_data->date." Expense.". $Request->reason;
                $account->dates = date('Y-m-d');
                $account->v_type = "debit";
                $account->debit = $Request->amount;
                $account->save();

                $expense = new Expense();
                $expense->dates = date('Y-m-d');
                $expense->account_id = $account->id;
                $expense->transporter_id = $Request->transporter_id;
                $expense->company_id = $shipment_data->company;
                $expense->reason = $Request->reason;
                $expense->amount = $Request->amount;
                $expense->shipment_no = $Request->shipment_no;
                $expense->created_by = Auth::id();
                $expense->save();

                $summary = new Shipment_Summary();
                $summary->shipment_no =  $Request->shipment_no;
                $summary->flag = "Add Expense";
                $summary->transporter_id = $Request->transporter_id;
                $summary->description = "Add Expense. ".$Request->reason;
                $summary->created_by = Auth::id();
                $summary->save();                 

               
 
                return redirect()->route('shipmentlisttransporter')->with('success', "Expense Successfully Add.");


    }

     public function AddTransporter(Request $Request)
    
    {

        $ship = Shipment::where('myid',$Request->id)->first();   

        $data = Transporter::get();


        $data1 = Shipment_Transporter::where('shipment_no', $ship->shipment_no)->get();

                $shiptransporter = array();

                foreach ($data1 as $key => $value) {

                    $shiptransporter[$key]=$value;

                    $tras = Transporter::withTrashed()->findorfail($value->transporter_id);

                    $shiptransporter[$key]->name= $tras->name;

                    $driver = Driver::withTrashed()->findorfail($value->driver_id);

                    $shiptransporter[$key]->driver_name= $driver->name;

                }




        

        return view('admin.addtransporter',compact('data','ship','shiptransporter'));

    }


     public function SaveTransporter(Request $Request)
    
    {
         $this->check();
       // dd($Request);
                $tras = Transporter::findorfail($Request->transporter_id);

                $ship =Shipment::where('shipment_no',$Request->shipment_no)->first();

                $data =new Shipment_Transporter();

                if($Request->truck_no != "" && $Request->truck_no != null && $Request->truck_no != "null"){
                $ship->status = 1; 
                }
                if($ship->all_transporter != "" && $ship->all_transporter != "null" && $ship->all_transporter != null){
                     $ship->all_transporter =$ship->all_transporter.", ".$Request->transporter_id;
                } else {
                     $ship->all_transporter =$Request->transporter_id;
                }
               
                $ship->save();         

                $data->shipment_no = $Request->shipment_no;

                $data->shipment_id = $ship->id;

                $data->transporter_id = $tras->id;

                $data->driver_id = $Request->driver_id;

                $data->name = $tras->name;

                $data->created_by = Auth::id();

                $data->save();

                $summary = new Shipment_Summary();

                $summary->shipment_no = $Request->shipment_no;

                $summary->flag = "Add Transporter";

                $summary->transporter_id = $Request->transporter_id;

                $summary->description = "Add Transporter. - ".$tras->name;

                $summary->save(); 



                  if (($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') || ($Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null')) {

                    if ($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') {

                        $mydriverdetails = Driver::findorfail($Request->driver_id);

                        $token = array();

                        $tuser = User::findorfail($tras->user_id);

                        if($mydriverdetails->device_token != "") {

                            array_push($token,$mydriverdetails->device_token);

                         $title = "New Shipment Assigned.";

                         $message= "We would like to inform, the shipment number ".$Request->shipment_no." is assigned to you.";

                         $aa = new WebNotificationController();

                         $aa->index($token,$title,$message,$Request->shipment_no);

                        }



                    } else {

                        $mydriverdetails = Driver::where('transporter_id', $Request->transporter_id)->where('self', 1)->first();

                        $token = array();

                        $tuser = User::findorfail($tras->user_id);

                        if($mydriverdetails->device_token != "") {

                            array_push($token,$mydriverdetails->device_token);

                         $title = "New Shipment Assigned.";

                         $message= "We would like to inform, the shipment number ".$Request->shipment_no." is assigned to you.";

                         $aa = new WebNotificationController();

                         $aa->index($token,$title,$message,$Request->shipment_no);

                        } 
                    }


                    if ($Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null') {
                        $mytruckno = $Request->truck_no;
                    } else {
                        $mytruckno = $mydriverdetails->truck_no;
                    }

                        $tt = Transporter::findorfail($Request->transporter_id);
                        $driver = new Shipment_Driver();
                        $driver->shipment_no = $Request->shipment_no;
                        $driver->transporter_id = $Request->transporter_id;
                        $driver->driver_id = $mydriverdetails->id;
                        $driver->truck_no = $mytruckno;
                        $driver->mobile = $tt->phone;
                        $driver->created_by = Auth::id();
                        $driver->myid = uniqid();
                        $driver->save();
 
                        $summary = new Shipment_Summary();
                        $summary->shipment_no = $Request->shipment_no;
                        $summary->flag = "Add Driver";
                        $summary->transporter_id = $Request->transporter_id;
                        $summary->description = "Add Driver. \n" . $mytruckno . "(Co.No." . $tt->phone . ").";
                        $summary->save();

                        $summary1 = new Shipment_Summary();
                        $summary1->shipment_no =  $Request->shipment_no;
                        $summary1->flag = "Add Truck";
                        $summary1->transporter_id = $Request->transporter_id;
                        $summary1->description = "Add Driver & Truck No. ".$mytruckno;
                        $summary1->save(); 

                
            }

                /// For Transporter

                $token =array();

                if($tras->user_id != null && $tras->user_id != '' && $tras->user_id != 'null') {

                    $tuser = User::findorfail($tras->user_id);

                    if($tuser->device_token != ""){

                        array_push($token,$tuser->device_token);

                     $title = "New Shipment Assigned.";

                     $message= "We would like to inform, the shipment number ".$Request->shipment_no." is assigned to you.";

                     $aa = new WebNotificationController();

                     $aa->index($token,$title,$message,$Request->shipment_no);
                    }


                }

            return redirect()->back()->with('success', "Transporter Successfully Add.");

    }

    public function DeleteTransporter(Request $Request)
    {
             $this->check();

                $data =Shipment_Transporter::findorfail($Request->id);
               // dd($data);
                $dd = Shipment_Transporter::where('id',$data->id)->delete();
                // $data->deleted_by = Auth::id();
                // $data->save();

                $dd2 = Shipment_Driver::where('shipment_no',$data->shipment_no)->where('driver_id',$data->driver_id)->delete();



                $check =Shipment_Driver::where('shipment_no',$data->shipment_no)->count();
                
                if($check == 0) {
                    
                    $ship = Shipment::where('shipment_no',$data->shipment_no)->first();
                    $ship->status = 0;
                    $ship->save();

                } 


                
                $summary = new Shipment_Summary();
                $summary->shipment_no = $data->shipment_no;
                $summary->flag = "delete";
                $summary->transporter_id = $data->transporter_id;
                $summary->description = "Delete Transporter. ";
                $summary->created_by=Auth::id();
                $summary->save(); 


                $data->delete();

                return redirect()->back()->with('success', "Transporter Successfully Deleted.");
    }


    public function DownloadLR(Request $Request)
    {

         $this->check();

        $data2 = Shipment::where('myid',$Request->id)->first();
        
        $data = Shipment::where('shipment_no',$data2->shipment_no)->first();
        
        $comp = Company::findorfail($data->company);

        $data->company_name = $comp->name;

        $data->gst = $comp->gst_no;
		
		$data->qr_code = '';
		$account_qr_id = ['1','3','4','5'];
		if(in_array($comp->id, $account_qr_id)){
			$data->qr_code = $comp->id.'_id.jpeg';
		}

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

       //dd($data, $comp);          
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

            //return view('lr.bmffcl',compact('data','trucks'));

        } else {
        
        $pdf = PDF::loadView('lr.bmflr',compact('data','trucks'));

        return $pdf->download($data->lr_no.'.pdf');


        }

      }      

        

       
    }

    
    public function ShipmentDetails(Request $Request)
    {
         $this->check();

        $data2 = Shipment::where('myid',$Request->id)->first();
        
        $data = Shipment::where('shipment_no',$data2->shipment_no)->first();


       
        
        $comp = Company::findorfail($data->company);

        $data->company_name = $comp->name;

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
                $ff= Transporter::where('user_id',Auth::user()->id)->first();
                $check = Shipment_Summary::where('shipment_no',$data2->shipment_no)->where('flag','View Shipment')->first();
                if(!$check){
                $summary = new Shipment_Summary();
                $summary->shipment_no = $data2->shipment_no;
                $summary->flag = "View Shipment";
                $summary->transporter_id = $ff->id;
                $tra = Transporter::findorfail($ff->id);
                $summary->description = "View Shipment List By. - ".$tra->name;
                $summary->save();
                
                }
				//dd($data['exports']);

                //dd($trucks);

        return view('admin.shipmentdetail',compact('data','trucks'));

    }

    public function ShipmentAmount(Request $Request)
    {
         $this->check();

        $this->validate($Request,
            ['invoice_amount' => 'required|numeric',],
            ['amount.required' => "Please Enter Amount", ]
        );

        $data = Shipment::where('shipment_no',$Request->shipment_no)->first();
        $data->invoice_amount = $Request->invoice_amount;
        $data->updated_by = Auth::id();
        $data->save();

        $summary = new Shipment_Summary();
        $summary->shipment_no = $data->shipment_no;
        $summary->flag = "Edit Invoice Amount";
        $summary->description = "Change Invoice Amount By Admin. ";
        $summary->created_by=Auth::id();
        $summary->save(); 

        return redirect()->back()->with('success','Shipment Invoice Amount Successfully Updated.');


    }


    public function ShipmentEdit(Request $Request)
    {   
         $this->check();

        $company = Company::all();

        $truck_type = Truck::all();

        $forwarder = Forwarder::all();

        $transporter = Transporter::all();

        $data = Shipment::where('myid',$Request->id)->first();

        //dd($data);

        return view('transporter.shipmentedit',compact('data','company','truck_type','forwarder','transporter'));



    }

    public function ShipmentUpdate(Request $Request)
    { 
         $this->check();

      if($Request->type2 == 'fcl') {


        $this->validate($Request, [
        'date' => 'required|date_format:d-m-Y',
        'company' => 'required',
        'from1' => 'required',
        'to1' => 'required',
        'forwarder' => 'required',
        'package' => 'required',
        'weight'=>'required',
        'container_no'=>'required',
        'seal_no'=>'required',
         ],[
         'date.required' => "Please Select Date",
         'company.required' => "Please Select Company",
         'from1.required' => "Please Enter From",
         'to1.required' => "Please Enter To",
         'forwarder.required' => "Please Select Forwarder",
         'package.required' => "Please Enter No. of Package",
         'weight.required' => "Please Enter Weight",
         'container_no.required' => "Please Enter Container No",
         'seal_no.required' => "Please Enter Seal No",
         ]);

      } else {

       $this->validate($Request, [
        'date' => 'required|date_format:d-m-Y',
        'company' => 'required',
        'from1' => 'required',
        'to1' => 'required',
        'forwarder' => 'required',
        'package' => 'required',
        'weight'=>'required',
        ],[
         'date.required' => "Please Select Date",
         'company.required' => "Please Select Company",
         'from1.required' => "Please Enter From",
         'to1.required' => "Please Enter To",
         'forwarder.required' => "Please Select Forwarder",
         'package.required' => "Please Enter No. of Package",
         'weight.required' => "Please Enter Weight",
         ]);

    }

                $data = Shipment::where('shipment_no',$Request->shipment_no)->first();

                $data->date = date('Y-m-d',strtotime($Request->date));

                $data->company = $Request->company;

                if( $Request->type1 == "import"){

                    $data->imports = 1;

                    $data->exports = 0;

                } else {

                    $data->exports = 1;

                    $data->imports = 0;
                }

                if($Request->type2 == "lcl"){

                    $data->lcl = 1;

                    $data->fcl = 0;

                } else {

                    $data->fcl = 1;

                    $data->lcl = 0;

                }

                $data->from1 = $Request->from1;

                $data->to1 = $Request->to1;

                $data->to2 = $Request->to2;

                $data->trucktype = $Request->truck_type;

               
                $data->forwarder = $Request->forwarder;

                $data->show_detail = $Request->show_detail;

                $data->consignor = $Request->consignor;

                $data->consignor_address = $Request->consignor_add;

                $data->consignee = $Request->consignee;

                $data->consignee_address = $Request->consignee_add;

                $data->package = $Request->package;

                $data->description= $Request->cargo_description;

                $data->weight = $Request->weight;

                $data->shipper_invoice = $Request->shipper_no;

                $data->forwarder_ref_no = $Request->for_ref_no;

                $data->b_e_no = $Request->be_no;

                if($Request->type2 == "fcl"){

                $data->container_type = $Request->container_type;

                $data->destuffing_date = date('Y-m-d',strtotime($Request->destuffing));

                $data->container_no = $Request->container_no;

                $data->shipping_line = $Request->shipping_line;

                $data->cha = $Request->cha;

                $data->seal_no = $Request->seal_no;

                $data->pod = $Request->pod;

                }



                $data->licence_no = $Request->licence_no;

            	$data->driver_name = $Request->driver_name;

                $data->invoice_amount = $Request->invoice_amount;
                $data->remark = $Request->remark;    
                $data->updated_by = Auth::id();
                $data->save();

                $summary = new Shipment_Summary();
                $summary->shipment_no = $Request->shipment_no;
                $summary->flag = "Shipment Edit";
                $summary->description = "Shipment Edited By Admin";
                $summary->created_by = Auth::id();
                $summary->save();


                return redirect()->route('shipmentlisttransporter')->with('success','Shipment successfully Updated.');


    }



    public function ShipmentDelete(Request $Request)
    { 
         $this->check();

        //dd($Request);
        $ship = Shipment::where('shipment_no',$Request->id)->first();   
        $ship->deleted_by = Auth::id();
        $ship->save();
        $ship->delete();
        
        $ship_driver = Shipment_Driver::where('shipment_no',$Request->id)->get();
        
        foreach ($ship_driver as $key => $value) {
            
            $drive = Shipment_Driver::findorfail($value->id);
            
            $drive->deleted_by = Auth::id();
            
            $drive->save();
            
            $drive->delete();

        }

        $ship_transporter = Shipment_Transporter::where('shipment_no',$Request->id)->get();
        
        foreach ($ship_transporter as $key => $value) {
            
            $transporter = Shipment_Transporter::findorfail($value->id);
            
            $transporter->deleted_by = Auth::id();
            
            $transporter->save();
            
            $transporter->delete();

        }

        $summary = new Shipment_Summary();
        $summary->shipment_no = $Request->id;
        $summary->flag = "Shipment Delete";
        $summary->description = "Shipment Deleted By Admin";
        $summary->created_by = Auth::id();
        $summary->save();

        return redirect()->route('shipmentlisttransporter')->with('success','Shipment successfully Deleted.');

    }


     public function WarehouseAdd(Request $Request)
    { 
         $this->check();

        $data = Shipment::where('shipment_no',$Request->shipment_no)->first();
        $data->status = 3;
        $data->warehouse_id = $Request->warehouse_id;
        $data->reason = $Request->reason;
        $data->updated_by = Auth::id();
        $data->save();

        $warehouse = Warehouse::findorfail($Request->warehouse_id);

        $summary = new Shipment_Summary();
        $summary->shipment_no = $Request->shipment_no;
        $summary->flag = "Add in Warehouse";
        $summary->description = "Shipment shift in". $warehouse->name. " Warehouse.";
        $summary->created_by = Auth::id();
        $summary->save();



        return response()->json(['code'=>'200']);
    }

     public function ShipmentDelivered(Request $Request)
     
     { 
         $this->check();
        $data = Shipment::where('shipment_no',$Request->shipment_no)->first();
        $data->status = 2;
        $data->updated_by = Auth::id();
        $data->save();

        $summary = new Shipment_Summary();
        $summary->shipment_no = $Request->shipment_no;
        $summary->flag = "Shiment Deleivered";
        $summary->description = "Shipment Delivered by Admin.";
        $summary->created_by = Auth::id();
        $summary->save();


        $all_trucks = Shipment_Transporter::where('shipment_no',$Request->shipment_no)->get();

        foreach ($all_trucks as $key => $value) {
                $data = Shipment_Transporter::withTrashed()->findorfail($value->id);
                $data->status = 3;
                $data->save();
        }

        $all_driver = Shipment_Driver::where('shipment_no',$Request->shipment_no)->get();

        foreach ($all_driver as $key => $value1) {
                $data1 = Shipment_Driver::withTrashed()->findorfail($value1->id);
                $data1->status = 3;
                $data1->save();
        }


        return response()->json(['code'=>'200']);
    }


    
    public function WarehouseShipmentList(Request $Request)
    {  
         $this->check();
        $ff= Transporter::where('user_id',Auth::user()->id)->first();
        $data = Shipment::select("shipment.*","warehouse.name as wname")
                            ->join("warehouse","warehouse.id","=","shipment.warehouse_id")
                            ->where("shipment.status",3)
                            ->whereRaw("find_in_set('$ff->id' , all_transporter)")
                            ->get();

               
        
        return view('admin.shipmentwarehouselist',compact('data'));

    }


    public function AddWareTransporter(Request $Request)
    
    {
         $this->check();

        $ship = Shipment::where('myid',$Request->id)->first();   

        $data = Transporter::get();


        $data1 = Shipment_Transporter::where('shipment_no', $ship->shipment_no)->get();

                $shiptransporter = array();

                foreach ($data1 as $key => $value) {

                    $shiptransporter[$key]=$value;

                    $tras = Transporter::withTrashed()->findorfail($value->transporter_id);

                    $shiptransporter[$key]->name= $tras->name;

                }

        

        return view('admin.addwartransporter',compact('data','ship','shiptransporter'));

    }

    public function SaveWareTransporter(Request $Request)
    
    {   
         $this->check();


        //dd($Request);
        $tras = Transporter::findorfail($Request->transporter_id);

                $ship =Shipment::where('shipment_no',$Request->shipment_no)->first();

                $data =new Shipment_Transporter();

                if($Request->truck_no != "" && $Request->truck_no != null && $Request->truck_no != "null"){
                $ship->status = 1; 
                }
                if($ship->all_transporter != "" && $ship->all_transporter != "null" && $ship->all_transporter != null){
                     $ship->all_transporter =$ship->all_transporter.", ".$Request->transporter_id;
                } else {
                     $ship->all_transporter =$Request->transporter_id;
                }
               
                $ship->save();         

                $data->shipment_no = $Request->shipment_no;

                $data->shipment_id = $ship->id;

                $data->transporter_id = $tras->id;

                $data->name = $tras->name;

                $data->created_by = Auth::id();

                $data->save();

                $summary = new Shipment_Summary();

                $summary->shipment_no = $Request->shipment_no;

                $summary->flag = "Add Transporter";

                $summary->transporter_id = $Request->transporter_id;

                $summary->description = "Add Transporter. - ".$tras->name;

                $summary->save(); 


                if($Request->truck_no != "" && $Request->truck_no != null && $Request->truck_no != "null"){
                
                $data3 =new Shipment_Driver();
                
                $data3->mobile = $tras->phone;
                
                $data3->truck_no = $Request->truck_no;
                
                $data3->shipment_no = $Request->shipment_no;
                
                $data3->transporter_id = $tras->id;
                
                $data3->created_by = Auth::id();
                
                $data3->save();


                $summary1 = new Shipment_Summary();
                
                $summary1->shipment_no =  $Request->shipment_no;
                
                $summary1->flag = "Add Truck";
                
                $summary1->transporter_id = $Request->other_id;

                $summary1->description = "Add Driver & Truck No. ".$Request->truck_no;
                
                $summary1->save(); 
                }



            return redirect()->back()->with('success', "Transporter Successfully Add.");

    }

     public function ShipmentWareEdit(Request $Request)
    {   
         $this->check();

        $company = Company::all();

        $truck_type = Truck::all();

        $forwarder = Forwarder::all();

        $transporter = Transporter::all();

        $data = Shipment::where('myid',$Request->id)->first();

        //dd($data);

        return view('warehouse.shipmentwareedit',compact('data','company','truck_type','forwarder','transporter'));



    }

    public function ShipmentWareUpdate(Request $Request)
    { 
         $this->check();


      if($Request->type2 == 'fcl') {


        $this->validate($Request, [
        'date' => 'required|date_format:d-m-Y',
        'company' => 'required',
        'from1' => 'required',
        'to1' => 'required',
        'truck_type' => 'required',
        'forwarder' => 'required',
        'package' => 'required',
        'weight'=>'required',
        'container_no'=>'required',
        'seal_no'=>'required',
         ],[
         'date.required' => "Please Select Date",
         'company.required' => "Please Select Company",
         'from1.required' => "Please Enter From",
         'to1.required' => "Please Enter To",
         'truck_type.required' => "Please Select Truck Type",
         'forwarder.required' => "Please Select Forwarder",
         'package.required' => "Please Enter No. of Package",
         'weight.required' => "Please Enter Weight",
         'container_no.required' => "Please Enter Container No",
         'seal_no.required' => "Please Enter Seal No",
         ]);

      } else {

       $this->validate($Request, [
        'date' => 'required|date_format:d-m-Y',
        'company' => 'required',
        'from1' => 'required',
        'to1' => 'required',
        'truck_type' => 'required',
        'forwarder' => 'required',
        'package' => 'required',
        'weight'=>'required',
        ],[
         'date.required' => "Please Select Date",
         'company.required' => "Please Select Company",
         'from1.required' => "Please Enter From",
         'to1.required' => "Please Enter To",
         'truck_type.required' => "Please Select Truck Type",
         'forwarder.required' => "Please Select Forwarder",
         'package.required' => "Please Enter No. of Package",
         'weight.required' => "Please Enter Weight",
         ]);

    }

                $data = Shipment::where('shipment_no',$Request->shipment_no)->first();

                $data->date = date('Y-m-d',strtotime($Request->date));

                $data->company = $Request->company;

                if( $Request->type1 == "import"){

                    $data->imports = 1;

                    $data->exports = 0;

                } else {

                    $data->exports = 1;

                    $data->imports = 0;
                }

                if($Request->type2 == "lcl"){

                    $data->lcl = 1;

                    $data->fcl = 0;

                } else {

                    $data->fcl = 1;

                    $data->lcl = 0;

                }

                $data->from1 = $Request->from1;

                $data->to1 = $Request->to1;

                $data->to2 = $Request->to2;

                $data->trucktype = $Request->truck_type;

               
                $data->forwarder = $Request->forwarder;

                $data->show_detail = $Request->show_detail;

                $data->consignor = $Request->consignor;

                $data->consignor_address = $Request->consignor_add;

                $data->consignee = $Request->consignee;

                $data->consignee_address = $Request->consignee_add;

                $data->package = $Request->package;

                $data->description= $Request->cargo_description;

                $data->weight = $Request->weight;

                $data->shipper_invoice = $Request->shipper_no;

                $data->forwarder_ref_no = $Request->for_ref_no;

                $data->b_e_no = $Request->be_no;

                if($Request->type2 == "fcl"){

                $data->container_type = $Request->container_type;

                $data->destuffing_date = date('Y-m-d',strtotime($Request->destuffing));

                $data->container_no = $Request->container_no;

                $data->shipping_line = $Request->shipping_line;

                $data->cha = $Request->cha;

                $data->seal_no = $Request->seal_no;

                $data->pod = $Request->pod;

                

                }

                $data->invoice_amount = $Request->invoice_amount;

                $data->remark = $Request->remark; 

                $data->updated_by = Auth::id();

                $data->licence_no = $Request->licence_no;

            	$data->driver_name = $Request->driver_name;

                $data->save();

                $summary = new Shipment_Summary();
                $summary->shipment_no = $Request->shipment_no;
                $summary->flag = "Shipment Edit";
                $summary->description = "Shipment Edited By Admin";
                $summary->created_by = Auth::id();
                $summary->save();


                return redirect()->route('warehouseshiplisttransporter')->with('success','Shipment successfully Updated.');


    }


    public function ShipmentWareDetails(Request $Request)
    {   
         $this->check();


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



                //dd($trucks);

        return view('admin.shipmentwaredetail',compact('data','trucks'));

    }



    public function ShipmentOntheway(Request $Request)
     
     { 

         $this->check();


        $data = Shipment::where('shipment_no',$Request->shipment_no)->first();
        $data->status = 1;
        $data->updated_by = Auth::id();
        $data->save();

        $summary = new Shipment_Summary();
        $summary->shipment_no = $Request->shipment_no;
        $summary->flag = "Shiment Ontheway";
        $summary->description = "Shipment OnTheWay by Admin.";
        $summary->created_by = Auth::id();
        $summary->save();

        return response()->json(['code'=>'200']);
    }


    public function ShipmentNewID(Request $Request)
     
     { 
         $this->check();


        $data = Shipment::where('shipment_no',$Request->shipment_no)->first();
        
        $company = Company::withTrashed()->findorfail($data->company);

        return response()->json(['code'=>'200','newno'=>$company->code."".$company->last_no]);
    }




    public function NewShipment(Request $Request)
     
     { 
         $this->check();


        $olddata = Shipment::where('shipment_no',$Request->old_id)->first();
        $olddata->status = 2;
        $olddata->updated_by = Auth::id();
        $olddata->save();

        $summary = new Shipment_Summary();
        $summary->shipment_no = $Request->old_id;
        $summary->flag = "Shiment Delivered";
        $summary->description = "Shipment replace with New Shipment ID by Admin.";
        $summary->created_by = Auth::id();
        $summary->save();

         ///////////////////////
         
         $data = new Shipment();

         $data->date = date('Y-m-d');

        $data->company = $olddata->company;

        if( $olddata->imports == 1){

                    $data->imports = 1;

                    $data->exports = 0;

                } else {

                    $data->exports = 1;

                    $data->imports = 0;
                }

        if($olddata->lcl == "lcl"){

                    $data->lcl = 1;

                    $data->fcl = 0;

                } else {

                    $data->fcl = 1;

                    $data->lcl = 0;

                }

        $data->from1 = $Request->from1;

        $data->to1 = $Request->to1;

        $data->to2 = "";

        $data->trucktype = $olddata->trucktype;
        
        $data->forwarder = $olddata->forwarder;

        $data->show_detail = $olddata->show_detail;

        $data->consignor = $olddata->consignor;

        $data->consignor_address = $olddata->consignor_address;

        $data->consignee = $olddata->consignee;

        $data->consignee_address = $olddata->consignee_address;

        $data->package = $olddata->package;

        $data->description= $olddata->description;

        $data->weight = $olddata->weight;

        $data->shipper_invoice = $olddata->shipper_invoice;

        $data->forwarder_ref_no = $olddata->forwarder_ref_no;

        $data->b_e_no = $olddata->b_e_no;

        if($olddata->fcl == 1){

        $data->container_type = $olddata->container_type;

        $data->destuffing_date = $olddata->destuffing_date;

        $data->container_no = $olddata->container_no;

        $data->shipping_line = $olddata->shipping_line;

        $data->cha = $olddata->cha;

        $data->seal_no = $olddata->seal_no;

        $data->pod = $olddata->pod;

        }

        $data->invoice_amount = $olddata->invoice_amount;

        $data->remark = $olddata->remark;             
    
        $shipment_no =$Request->new_id;

        $data->shipment_no = $shipment_no;
        
        $data->save();

        $company = Company::findorfail($olddata->company);

        $company->last_no = (int) filter_var($shipment_no, FILTER_SANITIZE_NUMBER_INT)+1;

        $company->save();

        $aa= Shipment::findorfail($data->id);

        $aa->shipment_no =$shipment_no;

        $aa->lr_no = $shipment_no."/".getenv('FIN_YEAR');

        $aa->myid = uniqid();

        $aa->save();

        $summary = new Shipment_Summary();

        $summary->shipment_no = $shipment_no;

        $summary->flag = "New Shipment";

        $summary->description = "Shipment replace with new shipment";

        $summary->save();


        // Code For Notification start 

        // For ALL Company Notification

                $token =array();

                $all_company = Company::get(); 

                foreach ($all_company as $key => $value) {

                    $cuser = User::findorfail($value->user_id);

                    if($cuser->device_token != ""){

                     array_push($token,$cuser->device_token);
                    }

                    
                }


                    $title = "New Shipment Generated";

                     $message= $shipment_no." shipment generated.";

                     $aa = new WebNotificationController();

                     $aa->index($token,$title,$message,$shipment_no);


        /// For Forwarder

                $token =array();

                if($Request->forwarder_id != null && $Request->forwarder_id != '' && $Request->forwarder_id != 'null') {

                    $tt=Forwarder::findorfail($Request->forwarder_id);
                    $tuser = User::findorfail($tt->user_id);
                    if($tuser->device_token != ""){

                        array_push($token,$tuser->device_token);

                     $title = "Your shipment order is generated.";

                     $message= "We would like to inform you, Your order is placed & its shipment number is ".$Request->shipment_no;

                     $aa = new WebNotificationController();

                     $aa->index($token,$title,$message,$data1->shipment_no);
                    }

                }





        return response()->json(['code'=>'200','msg'=>'New Shipment successfully Generated.']);


    }


    public function ShipmentAllList(Request $Request)
    {
         $this->check();

         $ff= Transporter::where('user_id',Auth::user()->id)->first();
         //$cargostatus = Shipment_Driver::where('shipment_no',$data->shipment_no)->latest()->take(1)->first();
         //$data = Shipment::where("status","!=",3)->whereRaw("find_in_set('$ff->id' , all_transporter)")->whereRaw('DATEDIFF(CURDATE(),date) <= 6')->where('paid',0)->get();
         
         $data2 = Shipment_Transporter::where('transporter_id', $ff->id)->whereNull('deleted_at')->groupBy('shipment_no')->get();
        
        $data = array();
        
        foreach ($data2 as $key => $value) {
            $data1 = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();
            $data[$key] = $data1;
            $data3 = Shipment_Driver::withTrashed()->where('shipment_no', $value->shipment_no)->
            where('transporter_id', $ff->id)->orderBy('id','desc')->first();
            if($data3){
             $data[$key]['status'] = $data3->status;
            }
        }
        //dd($data);
        
        $warehouse = Warehouse::get();
       
        return view('transporter.shipmentalllist',compact('data','warehouse'));

    }

    public function ShipmentSummaryList(Request $Request)
    {
        $this->check();

        $data = Shipment_Summary::where('shipment_no', $Request->shipment_no)->get();
        $datas = Shipment_Summary::where('shipment_no', $Request->shipment_no)->first();
        $shipment_no = $datas->shipment_no;
        $count = $data->count();
        $i=0;
        //dd($data[1]['created_at']);
       
        $diff = $data[1]['created_at']->getTimestamp() - $data[0]['created_at']->getTimestamp();
        $totalHour = (int)($diff / 60);
        $totalMinutes = (int)($diff % 60);
        $finalMinutes = $totalMinutes.''.'m';
        $days = (int)($totalHour /24);
        $otherHour = (int)($totalHour  % 24);

        $finalDays = '';
        $finalHours = '';

        if ($days > 0) {
        $finalDays = $days.''.'d';
        }
      
        if($otherHour > 0) {
        $finalHours = $otherHour.''.'h';
        }
        $finaldiff = $finalDays.' '.$finalHours.' '.$finalMinutes;
        foreach ($data as $key => $value) {

            $data[$key]=$value;
            $data[$key]['timedifference']=$finaldiff;
        
        }
        

        return view('admin.shipmentsummarylist',compact('data','shipment_no','finaldiff'));

    }
    public function ShipmentAllDetails(Request $Request)
    {   

         $this->check();
        

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



                //dd($trucks);

        return view('admin.shipmentalldetail',compact('data','trucks'));

    }
	public function MyFilter(Request $Request)
	{
    	$data = array();
        $tt = Shipment::get();
        if(isset($Request->shipment)){
            $ttt = $Request->shipment;
        } else {
            $ttt = '';
        }
        if(isset($Request->status)){
            $tts = $Request->status;
        } else {
            $tts = '';
        }
        if(isset($Request->search)){
            $search = $Request->search;
        } else {
            $search = '';
        }
        if(isset($Request->transporter)){
            $transporter = $Request->transporter;
        } else {
            $transporter = '';
        }
        if(isset($Request->forwarder)){
            $forwarder = $Request->forwarder;
        } else {
            $forwarder = '';
        }
        if(isset($Request->year)){
            $year = $Request->year;
        } else {
            $year = '';
        }
        if(isset($Request->month)){
            $month = $Request->month;
        } else {
            $month = '';
        }
        if(isset($Request->date)){
            $date = $Request->date;
        } else {
            $date = '';
        }

        $all_transporter =  Transporter::get();

        $all_forwarder = Forwarder::get();

        
        $ff= Transporter::where('user_id',Auth::user()->id)->first();
       
        // $datas = Shipment_Transporter::withTrashed()->whereNull('deleted_at')->where('transporter_id', $ff->id);
        $data2 = Shipment_Driver::withTrashed()->where('transporter_id', $ff->id)->whereNull('deleted_at')
				->whereRaw('id IN (select MAX(id) FROM shipment_driver GROUP BY shipment_no)')->orderby('id','desc')->get();
       
        $ids = array();
        foreach ($data2 as $key => $value){
            if($value->status == "1" ){
                array_push($ids,$value->id);
            }
        }
        $data2 = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
        //dd($data2);
		$data = array();
		
        foreach ($data2 as $key => $value) {
            $data1 = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();
            $data[$key] = $data1;
            $data3 = Shipment_Driver::withTrashed()->where('shipment_no', $value->shipment_no)->
            where('transporter_id', $ff->id)->orderBy('id','desc')->first();
            if($data3){
                $data[$key]['status'] = $data3->status;
               }
        }
            // dd($data[$key]);
        $data = $data[$key]->whereYear('created_at', $Request->year)->whereMonth('created_at', $Request->month)->orderby('shipment_no','desc')->get();
        
        // if($Request->status == "" && $Request->month == null && $Request->year == null){
        //     if($Request->status == 'Pending'){
		// 	$data = $data[$key]->where('status',1)->orderby('shipment_no','desc')->get();
        //     }
        //     if($Request->status == 'Ontheway'){
        //         $data = $data[$key]->where('status',2)->orderby('shipment_no','desc')->get();
        //     }
        //     if($Request->status == 'Delivered'){
        //         $data = $data[$key]->where('status',3)->orderby('shipment_no','desc')->get();
        //     }
           
		// }
       
		
		return view('transporter.shipmentfilter', compact('tt','ttt','tts','data','all_transporter','all_forwarder','search','transporter','forwarder','year','month','date'));
	}

     public function Driverlist(Request $Request)
    {  

        $this->check();
        $old_driver = $Request->old_driver;
        $shipmentdriver = Shipment_Driver::where('transporter_id',$Request->transporter_id)->where('shipment_no',$Request->shipment_no)->pluck('driver_id')->toArray();
       // dd($shipmentdriver);
        $drivers = Driver::where('transporter_id',$Request->transporter_id)->whereNotIn('id',$shipmentdriver)->whereNull('deleted_at')->orderby('name','asc')->get();

        return view('admin.shipmentdriverlist',compact('drivers','old_driver'));

    }

    public function Detail (Request $Request)
    {
        // code...
    }



}