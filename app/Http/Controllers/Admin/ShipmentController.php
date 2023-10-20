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
use App\Invoice;
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
use Mail,DateTime;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WebNotificationController;
use Config;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Helper\GlobalHelper;
use App\Notification;
use File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Html\Builder;
use App\DataTables\ShipmentDataTable;
use App\Jobs\LrMail_Yogini_Job;
use App\Jobs\LrMail_Ssi_Job;
use App\Jobs\LrMail_Hansh_Job;
use App\Jobs\LrMail_Bmf_Job;

class ShipmentController extends Controller
{
	public function __construct()
    {
        // $this->middleware('permission:shipment-list', ['only' => ['List','check','Add','save','']]);
    }
    public function check()
    {
         if(Auth::user()->role == "transporter") {
            Auth::logout();
           return redirect()->route('login')->with('error',"You have no permission for that.");
        } elseif(Auth::user()->role == "forwarder") {
            Auth::logout();
           return redirect()->route('login')->with('error',"You have no permission for that.");
        }
    }
    public function List(Request $Request)
    {
         $this->check();
        if(Auth::user()->role == "company") {
        $ff= Company::where('user_id',Auth::user()->id)->first();
        $data = Shipment::where("status","!=",3)->where('company',$ff->id)->get();
        }
        elseif(Auth::user()->role == "employee") {
            $ff2= Employee::where('user_id',Auth::user()->id)->first();
            $ff1= Company::where('id',$ff2->company_id)->first();
            $data = Shipment::where("status","!=",3)->where('company',$ff1->id)->get();
            }
        else{
        $data = Shipment::where("status","!=",3)->whereRaw('DATEDIFF(CURDATE(),date) <= 6')->where('paid',0)->get();
        }
        $warehouse = Warehouse::get();
    	return view('admin.shipmentlist',compact('data','warehouse'));
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
         $this->check();
      if($Request->type2 == 'fcl') {
        $this->validate($Request, [
        'shipment_no'=>'unique:shipment,shipment_no',
        'date' => 'required',
        'company' => 'required',
        'from1' => 'required',
        'to1' => 'required',
        'forwarder' => 'required',
        'package' => 'required|numeric',
        'weight'=>'required|numeric',
        'container_no'=>'required',
        'seal_no'=>'required',
         ],[
         //'shipment_no.required'=>'Please Enter Shipment Number',
         'shipment_no.unique'=>'This shipment number is already exist, Please enter different shipment number and try again.',
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
        'shipment_no'=>'unique:shipment,shipment_no',
        'date' => 'required',
        'company' => 'required',
        'from1' => 'required',
        'to1' => 'required',
        'forwarder' => 'required',
        'package' => 'required|numeric',
        'weight'=>'required|numeric',
        ],[
         //'shipment_no.required'=>'Please Enter Shipment Number',
         'shipment_no.unique'=>'This shipment number is already exist, Please enter different shipment number and try again.',
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
    //dd(1);
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
                $data->destuffing_date = $Request->destuffing;
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
                $shipment_no = $Request->shipment_no;
                $data->shipment_no = $shipment_no;
                $data->save();

                if($data['qrcode'] == NULL){

                    $png = QrCode::format('png')->size(1000)->generate('tms_'.$data['shipment_no']);
                    $png = base64_encode($png);
                    $path = base_path() . '/public/uploads/qrcode/';
                    $fileName = 'tms_'.$data['shipment_no'] . '.png';
                    \File::put($path. $fileName, base64_decode($png));
                    $data->qrcode =$fileName;
                    $data->save();
                }

                

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
                    // $summary = new Shipment_Summary();
                    // $summary->shipment_no = $shipment_no;
                    // $summary->flag = "create";
                    // $summary->transporter_id = $Request->transporter;
                    // $summary->description = "Add Transporter";
                    // $summary->save();
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
                        $summary->driver_id = $Request->driver_id;
                        $summary->description = "Add Driver. \n" . $mytruckno . "(Co.No." . $tt->phone . ").";
                        $summary->save();
                    $notification_user=User::where('id',Auth::id())->first();
                    if($notification_user['role']=='admin'){
                        $from_user = User::find(1);
                    }else{
                        $from_user = User::where('id',Auth::id())->first();
                    }
                     //company
                     $company_user = Company::where('id',$Request->company)->first();
                     $to_user=User::find($company_user['user_id']);
                     if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                         $notification = new Notification();
                         $notification->notification_from = $from_user->id;
                         $notification->notification_to = $to_user->id;
                         $notification->role = 'company';
                         $notification->shipment_id = $data->id;
                         $id = $data->shipment_no;
                         $title= "New Shipment" .' '. $driver->shipment_no .' '. "Added";
                         $message= "New Shipment" .' '. $driver->shipment_no .' '. "Added";
                         $notification->title = $title;
                         $notification->message = $message;
                         $notification->notification_type = '1';
                         $notification->user_name_from = $from_user['username'];
                         $notification_id = $notification->id;
                         $notification->save();
                         if($to_user->device_token != null){
                             if($to_user->device_type == 'ios'){
                                 GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                             }else{
                                 GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                             }
                         }
                     }
                 //admin
                    //  $to_user =User::find(1);
                    //  if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                    //      $notification = new Notification();
                    //      $notification->notification_from = $from_user->id;
                    //      $notification->notification_to = $to_user->id;
                    //      $notification->shipment_id = $data->id;
                    //      $id = $data->shipment_no;
                    //      $title= "New Shipment" .' '. $driver->shipment_no .' '. "Added";
                    //      $message= "New Shipment" .' '. $driver->shipment_no .' '. "Added";
                    //      $notification->title = $title;
                    //      $notification->message = $message;
                    //      $notification->notification_type = '1';
                    //      $notification_id = $notification->id;
                    //      $notification->save();
                    //      // if($to_user->notification_status=='1'){
                    //          if($to_user->device_type == 'ios'){
                    //              GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                    //          }else{
                    //              GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                    //          }
                    //      // }
                    //  }
                         //driver
                    if(isset($Request['driver_id']) && $Request['driver_id'] != ''){
                        if(!empty($driver->driver_id)){
                        $to_user = Driver::find($driver->driver_id);
                        if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                            $notification = new Notification();
                            $notification->notification_from = $from_user->id;
                            $notification->notification_to = $to_user->id;
                            $notification->role = 'driver';
                            $notification->shipment_id = $data->id;
                            $id = $data->shipment_no;
                            $title= "New Shipment" .' '. $driver->shipment_no .' '. "Added";
                            $message= "New Shipment" .' '. $driver->shipment_no .' '. "Added";
                            $notification->title = $title;
                            $notification->message = $message;
                            $notification->notification_type = '1';
                            $notification->user_name_from = $from_user['username'];
                            $notification_id = $notification->id;
                            $notification->save();
                            if($to_user->device_token != null){
                                if($to_user->device_type == 'ios'){
                                    GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                }else{
                                    GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                }
                            }
                        }
                    }
                }
                        //transporter
                        if($transs->transporter_id){
                            $transporter=Transporter::where('id',$transs->transporter_id)->first();
                            $to_user = User::find($transporter['user_id']);
                            if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                                $notification = new Notification();
                                $notification->notification_from = $from_user->id;
                                $notification->notification_to = $to_user->id;
                                $notification->role = 'transporter';
                                $notification->shipment_id = $data->id;
                                $id = $data->shipment_no;
                                $title= "New Shipment" .' '. $transs->shipment_no .' '. "Added";
                                $message= "New Shipment" .' '. $transs->shipment_no .' '. "Added";
                                $notification->title = $title;
                                $notification->message = $message;
                                $notification->notification_type = '1';
                                $notification->user_name_from = $from_user['username'];
                                $notification->save();
                                $notification_id = $notification->id;
                                if($to_user->device_token != null){
                                    if($to_user->device_type == 'ios'){
                                        GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                    }else{
                                        GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                    }
                                }
                            }
                        }
            }
                // Code For Notification start
                // For ALL Company Notification
                // $token =array();
                // $all_company = Company::get();
                // foreach ($all_company as $key => $value) {
                //     $cuser = User::findorfail($value->user_id);
                //     if($cuser->device_token != ""){
                //      array_push($token,$cuser->device_token);
                //     }
                // }
                //     $title = "New Shipment Generated";
                //      $message= $Request->shipment_no." shipment generated.";
                //      $aa = new WebNotificationController();
                //      $aa->index($token,$title,$message,$Request->shipment_no);
                //   /// For Transporter
                // $token =array();
                // if($Request->transporter != null && $Request->transporter != '' && $Request->transporter != 'null') {
                //     $tras=Transporter::findorfail($Request->transporter);
                //     $tuser = User::findorfail($tras->user_id);
                //     if($tuser->device_token != ""){
                //         array_push($token,$tuser->device_token);
                //      $title = "New Shipment Assigned.";
                //      $message= "We would like to inform, the shipment number ".$Request->shipment_no." is assigned to you.";
                //      $aa = new WebNotificationController();
                //      $aa->index($token,$title,$message,$Request->shipment_no);
                //     }
                // }
                //  /// For Forwarder
                // $token =array();
                // if($Request->forwarder != null && $Request->forwarder != '' && $Request->forwarder != 'null') {
                //     $tt=Forwarder::findorfail($Request->forwarder);
                //     $tuser = User::findorfail($tt->user_id);
                //     if($tuser->device_token != ""){
                //         array_push($token,$tuser->device_token);
                //      $title = "Your shipment order is generated.";
                //      $message= "We would like to inform you, Your order is placed & its shipment number is ".$Request->shipment_no;
                //      $aa = new WebNotificationController();
                //      $aa->index($token,$title,$message,$Request->shipment_no);
                //     }
                // }
                // Send LR Mail - Start //
                if ($Request->forwarder != "" && $Request->forwarder != null && $Request->forwarder != 'null')
                {
                    $ship_data = Shipment::where('shipment_no', $data->shipment_no)->first();
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
                    $tras_list = Shipment_Transporter::where('shipment_no', $data->shipment_no)->get();
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
                        $driver_list = Shipment_Driver::where('shipment_no', $data->shipment_no)->get();
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
                                file_put_contents("public/pdf/" . $data->shipment_no . ".pdf", $pdf->output());
                                $path = env('APP_URL') . "public/pdf/" . $data->shipment_no . ".pdf";
                                $shipment = $data->shipment_no;
                                // $myemail = 'keyurdomadiya602@gmail.com';
                                $myemail = $for->email;
                                $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);
                                $yogini_username = env('YOGINI_MAIL_USERNAME');
                                $yogini_password = env('YOGINI_MAIL_PASSWORD');
                               Config::set('mail.username', $yogini_username);
                                Config::set('mail.password', $yogini_password);
                                $mail_service = env('MAIL_SERVICE');
                                if($mail_service == 'on'){
                                //  Mail::send('yoginimail', $data2, function($message) use ($data2) {
                                //     $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                                //     $message->from('noreplay@yoginitransport.com','Yogini Transport');
                                //     $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                                // });
                                dispatch(new LrMail_Yogini_Job($data2));
                             }
                            } elseif ($comp->lr == "ssilr") {
                                $pdf = PDF::loadView('lr.ssilr', compact('data', 'trucks'));
                                file_put_contents("public/pdf/" . $data->shipment_no . ".pdf", $pdf->output());
                                $path = env('APP_URL') . "public/pdf/" . $data->shipment_no . ".pdf";
                                $shipment = $data->shipment_no;
                                $myemail =  $for->email;
                                $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);
                                $ssi_username = env('SSI_MAIL_USERNAME');
                                $ssi_password = env('SSI_MAIL_PASSWORD');
                                Config::set('mail.username', $ssi_username);
                                Config::set('mail.password', $ssi_password);
                                $mail_service = env('MAIL_SERVICE');
                                     if($mail_service == 'on'){
                                //  Mail::send('ssimail', $data2, function($message) use ($data2) {
                                //     $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                                //     $message->from('noreplay@ssitransway.com','SSI Transway');
                                //     $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                                // });
                                dispatch(new LrMail_Ssi_Job($data2));
                             }
                            } elseif ($comp->lr == "hanshlr") {
                                $pdf = PDF::loadView('lr.hanshlr', compact('data', 'trucks'));
                                file_put_contents("public/pdf/" . $data->shipment_no . ".pdf", $pdf->output());
                                $path = env('APP_URL') . "public/pdf/" . $data->shipment_no . ".pdf";
                                $shipment = $data->shipment_no;
                                $myemail =  $for->email;
                                $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);
                                $hansh_username = env('HANS_MAIL_USERNAME');
                                $hansh_password = env('HANS_MAIL_PASSWORD');
                                Config::set('mail.username', $hansh_username);
                                Config::set('mail.password', $hansh_password);
                                    $mail_service = env('MAIL_SERVICE');
                        if($mail_service == 'on'){
                                //  Mail::send('hanshmail', $data2, function($message) use ($data2) {
                                //     $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                                //     $message->from('noreplay@hanstransport.com','Hansh Transport');
                                //     $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                                // });
                                dispatch(new LrMail_Hansh_Job($data2));
                                }
                            } elseif ($comp->lr == "bmflr") {
                                $pdf = PDF::loadView('lr.bmflr', compact('data', 'trucks'));
                                file_put_contents("public/pdf/" . $data->shipment_no . ".pdf", $pdf->output());
                                $path = env('APP_URL') . "public/pdf/" . $data->shipment_no . ".pdf";
                                $shipment = $data->shipment_no;
                                $myemail =  $for->email;
                                $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);
                                    $mail_service = env('MAIL_SERVICE');
                        if($mail_service == 'on'){
                                //  Mail::send('bmfmail', $data2, function($message) use ($data2) {
                                //     $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                                //     $message->from('noreplay@bmfreight.com','BMF Freight');
                                //     $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                                // });
                                dispatch(new LrMail_Bmf_Job($data2));
                            }
                        }
                      }
                    }
                // Send LR Mail - End //
                return redirect()->route('shipmentlist')->with('success','Shipment created successfully.');
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
        }
        $status = Cargostatus::where('admin',1)->whereNotIn('id',['11'])->get();
        /*echo "<pre>";
        print_r($status);
        exit();*/
        return view('admin.shipmenttrucklist',compact('data','status','shipment_no'));
    }
    public function ChangeTruckStatus(Request $Request)
    {
         $this->check();
                $data = Shipment_Driver::findorfail($Request->truck_id);
                $data->status = $Request->status;
                $data->last_status_update_time=date('Y-m-d H:i:s');
                if($data['last_notification_time_difference'] != NULL)
                {
                    $data->last_notification_time_difference=null;
                }
                if($data['last_notification_time'] != NULL)
                {
                    $data->last_notification_time=null;
                }
                if($Request->reason !="" && $Request->reason != "null" && $Request->reason == null){
                    $data->reason = $Request->reason;
                }
                $data->updated_by = Auth::id();
                $data->save();
                $ship = Shipment::where('shipment_no',$data->shipment_no)->first();
                if($Request->status == "1"){
                    $ss = Shipment::where('shipment_no',$data->shipment_no)->first();
                    $ss->status = 1;
                    // $ss->cargo_status = 0;
                    $ss->save();
                    $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)
                    ->where('driver_id',$data->driver_id)->first();
                    $transp->status = 1;
                    $transp->save();
                }
                if($Request->status == "2"){
                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();
                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)
                ->where('driver_id',$data->driver_id)->first();
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
                
                $transp = Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)
                ->where('driver_id',$data->driver_id)->first();
                $transp->status = 3;
                $transp->save();
                
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
               
                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)
                ->where('driver_id',$data->driver_id)->first();
                $transp->status = 3;
                $transp->save();
                
               }
               if($Request->status == "4" || $Request->status == "5" || $Request->status == "11"  || $Request->status == "12"  || $Request->status == "13"  || $Request->status == "14"
               || $Request->status == "15"  || $Request->status == "18"){
                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();
                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)
                ->where('driver_id',$data->driver_id)->first();
                $transp->status = 2;
                $transp->save();
                }
               if($Request->status == "6"){
                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();
                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)
                ->where('driver_id',$data->driver_id)->first();
                $transp->status = 2;
                $transp->save();
                }
                if($Request->status == "7"){
                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();
                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)
                ->where('driver_id',$data->driver_id)->first();
                $transp->status = 2;
                $transp->save();
                }
                if($Request->status == "8"){
                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();
                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)
                ->where('driver_id',$data->driver_id)->first();
                $transp->status = 2;
                $transp->save();
                }
                if($Request->status == "9"){
                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();
                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)
                ->where('driver_id',$data->driver_id)->first();
                $transp->status = 2;
                $transp->save();
                }
                if($Request->status == "10"){
                $ss =Shipment::where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();
                $transp =Shipment_Transporter::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)
                ->where('driver_id',$data->driver_id)->first();
                $transp->status = 2;
                $transp->save();
                }
                $cargo = Cargostatus::findorfail($Request->status);
                $summary = new Shipment_Summary();
                $summary->shipment_no =  $data->shipment_no;
                $summary->flag = $data->truck_no." is ".$cargo->name;
                $summary->transporter_id = $data->transporter_id;
                $role = User::where('id',Auth::id())->first();
                $summary->description ="Change Truck Shipment Status By ".$role->name.".\n" . $data->truck_no . " is " . $cargo->name;
                $summary->change_status_by = $role->name;
                $summary->created_by = Auth::id();
                $summary->save();
                $notification_user=User::where('id',Auth::id())->first();
                if($notification_user['role']=='admin' || $notification_user['role']=='company')
                {
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
                        $notification->role = 'transporter';
                        $notification->shipment_id = $ship->id;
                        $id = $data->shipment_no;
                        $title= "Status changed";
                        // "New Shipment" .' '. $driver->shipment_no .' '. "Added";
                        $message= $data["shipment_no"].' '."is".' '.$getStatus['name'].' ' ."by".' '.$user['username'];
                        $notification->title = $title;
                        $notification->message = $message;
                        $notification->notification_type = '2';
                        $notification->user_name_from = $user['username'];
                        $notification->save();
                        $notification_id = $notification->id;
                        if($to_user->device_token != null){
                        if($to_user->device_type == 'ios'){
                            GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                        }else{
                            GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                            }
                        }
                    }
                    //driver
                    $from_user = User::find(Auth::id());
                    $to_user = Driver::find($data->driver_id);
                    $user=User::where('id',Auth::id())->first();
                    $getStatus=Cargostatus::where('id',$data->status)->first();
                    if($from_user['id'] != $to_user['id'] && $from_user && $to_user)
                    {
                        $notification = new Notification();
                        $notification->notification_from = $from_user->id;
                        $notification->notification_to = $to_user->id;
                        $notification->role = 'driver';
                        $notification->shipment_id = $ss->id;
                        $id = $data->shipment_no;
                        $title= "Status changed";
                        // "New Shipment" .' '. $driver->shipment_no .' '. "Added";
                        $message= $data["shipment_no"].' '."is".' '.$getStatus['name'].' ' ."by".' '.$user['username'];
                        $notification->title = $title;
                        $notification->message = $message;
                        $notification->notification_type = '2';
                        $notification->user_name_from = $user['username'];
                        $notification->save();
                        $notification_id = $notification->id;
                        if($to_user->device_token != null){
                        if($to_user->device_type == 'ios'){
                            GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                        }else{
                            GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                            }
                        }
                    }
                }
                return redirect()->back()->with('success', ' Truck status change successfully');
    }
    public function DeleteTruckStatus(Request $Request)
    {
         $this->check();
        $data =Shipment_Driver::findorfail($Request->id);
        $tra=Shipment_Transporter::whereNull('driver_id')->where('transporter_id',$data->transporter_id)->where('shipment_no',$data->shipment_no)->first();
        if($tra){
        $check = Shipment_Transporter::whereNull('driver_id')->where('transporter_id',$data->transporter_id)->where('shipment_no',$data->shipment_no)->delete();
        }
        else{
        $transporter = Shipment_Transporter::where('driver_id',$data->driver_id)->where('shipment_no',$data->shipment_no)->delete();
        }
        $data->deleted_by = Auth::id();
        $data->save();
        $data->delete();
        $check = Shipment_Driver::whereNull('deleted_at')->where('shipment_no',$data->shipment_no)->count();
        if($check == 0) {
        $ship = Shipment::where('shipment_no',$data->shipment_no)->first();
        $ship->status = 0;
        $ship->save();
        }
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
                $account->shipment_no = $Request->shipment_no;
                $account->from_company = $shipment_data->company;
                $account->description =  $Request->shipment_no.' '.$shipment_data->date.''.' Expense.'. $Request->reason;
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
                return redirect()->route('shipmentlist')->with('success', "Expense Successfully Add.");
    }
     public function AddTransporter(Request $Request)
    {
        $ship = Shipment::where('myid',$Request->id)->first();
        $data = Transporter::get();
        $data1 = Shipment_Transporter::withTrashed()->where('shipment_no', $ship->shipment_no)->whereNull('deleted_at')->get();
                $shiptransporter = array();
                foreach ($data1 as $key => $value) {
                    $shiptransporter[$key]=$value;
                    $tras = Transporter::withTrashed()->findorfail($value->transporter_id);
                    $shiptransporter[$key]->name= $tras->name;
                    if($value->driver_id){
                    $driver = Driver::withTrashed()->findorfail($value->driver_id);
                    $shiptransporter[$key]->driver_name= $driver->name;
                    }
                }
        return view('admin.addtransporter',compact('data','ship','shiptransporter'));
    }
     public function SaveTransporter(Request $Request)
    {
         $this->check();
         $this->validate($Request, [
        
            'transporter_id' => 'required',
         
             ],[
             'transporter_id.required' => "Please select the transporter",
             
             ]);
       // dd($Request);
                $tras = Transporter::findorfail($Request->transporter_id);
                $ship =Shipment::where('shipment_no',$Request->shipment_no)->first();
               
                $ship_check = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->transporter_id)
                ->where('driver_id',$Request->driver_id)->count();
                if ($ship_check > 0) {
                    return redirect()->back()->with('error', "This transporter is already added. Please select another transporter.");

                }

                $data =new Shipment_Transporter();
                if($Request->truck_no != "" && $Request->truck_no != null && $Request->truck_no != "null"){
                $ship->status = 1;
                }
                if($ship->all_transporter != "" && $ship->all_transporter != "null" && $ship->all_transporter != null){
                     $ship->all_transporter =$ship->all_transporter.", ".$Request->transporter_id;
                } else {
                     $ship->all_transporter =$Request->transporter_id;
                }
                if($Request->driver_id == null){
                $mydriverdetails1 = Driver::where('transporter_id', $Request->transporter_id)->where('self', 1)->first();
                $mydriverdetails = $mydriverdetails1->id;
                }else{
                    $mydriverdetails = $Request->driver_id;
                }

                $ship->save();
                $data->shipment_no = $Request->shipment_no;
                $data->shipment_id = $ship->id;
                $data->transporter_id = $tras->id;
                $data->driver_id = $mydriverdetails;
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
                        //  $title = "New Shipment Assigned.";
                        //  $message= "We would like to inform, the shipment number ".$Request->shipment_no." is assigned to you.";
                        //  $aa = new WebNotificationController();
                        //  $aa->index($token,$title,$message,$Request->shipment_no);
                        }
                    } else {
                        $mydriverdetails = Driver::where('transporter_id', $Request->transporter_id)->where('self', 1)->first();
                        $token = array();
                        $tuser = User::findorfail($tras->user_id);
                        if($mydriverdetails->device_token != "") {
                            array_push($token,$mydriverdetails->device_token);
                        //  $title = "New Shipment Assigned.";
                        //  $message= "We would like to inform, the shipment number ".$Request->shipment_no." is assigned to you.";
                        //  $aa = new WebNotificationController();
                        //  $aa->index($token,$title,$message,$Request->shipment_no);
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
                        $summary->driver_id = $Request->driver_id;
                        $summary->description = "Add Driver. \n" . $mytruckno . "(Co.No." . $tt->phone . ").";
                        $summary->save();
                        // $summary1 = new Shipment_Summary();
                        // $summary1->shipment_no =  $Request->shipment_no;
                        // $summary1->flag = "Add Truck";
                        // $summary1->transporter_id = $Request->transporter_id;
                        // $summary1->description = "Add Driver & Truck No. ".$mytruckno;
                        // $summary1->save();
                    $notification_user=User::where('id',Auth::id())->first();
                    if($notification_user['role']=='transporter' || $notification_user['role']=='admin' || $notification_user['role']=='company'){
                        //driver
                        if($driver->driver_id){
                            $from_user = User::find(Auth::id());
                            $to_user = Driver::find($driver->driver_id);
                            if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                                $notification = new Notification();
                                $notification->notification_from = $from_user->id;
                                $notification->notification_to = $to_user->id;
                                $notification->role = 'driver';
                                $notification->shipment_id = $data->shipment_id;
                                $id = $data->shipment_no;
                                $title= "New Shipment assign to" .' '. $to_user['name'] .' - '. $driver->shipment_no;
                                $message= "Tap here to see more details.";
                                $notification->title = $title;
                                $notification->message = $message;
                                $notification->notification_type = '3';
                                $notification->user_name_from = $from_user['username'];
                                $notification->save();
                                $notification_id = $notification->id;
                                if($to_user->device_token != null){
                                    if($to_user->device_type == 'ios'){
                                        GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                    }else{
                                        GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                    }
                                }
                            }
                        }
                    }
                        if($notification_user['role']=='admin' || $notification_user['role']=='company')
                        {
                            //transporter
                            if($data->transporter_id){
                                $transporter=Transporter::where('id',$data->transporter_id)->first();
                                $from_user = User::find(Auth::id());
                                $to_user = User::find($transporter['user_id']);
                                if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                                    $notification = new Notification();
                                    $notification->notification_from = $from_user->id;
                                    $notification->notification_to = $to_user->id;
                                    $notification->role = 'transporter';
                                    $notification->shipment_id = $data->shipment_id;
                                    $id = $data->shipment_no;
                                    $title= "New Shipment assign to you" .' - '. $data->shipment_no;
                                    $message= "Tap here to see more details.";
                                    $notification->title = $title;
                                    $notification->message = $message;
                                    $notification->notification_type = '3';
                                    $notification->user_name_from = $from_user['username'];
                                    $notification->save();
                                    $notification_id = $notification->id;
                                    if($to_user->device_token != null){
                                        if($to_user->device_type == 'ios'){
                                            GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                        }else{
                                            GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                        }
                                    }
                                }
                            }
                       
                        }
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
            return redirect()->back()->with('success', "Transporter Add Successfully.");
    }
    public function DeleteTransporter(Request $Request)
    {
             $this->check();
             $data =Shipment_Transporter::findorfail($Request->id);
             $ship_driver = Shipment_Driver::where('shipment_no',$data->shipment_no)
                ->where('transporter_id',$data->transporter_id)->get();
                foreach ($ship_driver as $key => $value) {
				$drive = Shipment_Driver::findorfail($value->id);
				$drive->deleted_by = Auth::id();
				$drive->save();
				$drive->delete();
			}
                $ship_transporter = Shipment_Transporter::where('shipment_no',$data->shipment_no)
                ->where('transporter_id',$data->transporter_id)->get();
                foreach ($ship_transporter as $key => $value) {
				$transporter = Shipment_Transporter::findorfail($value->id);
				$transporter->deleted_by = Auth::id();
				$transporter->save();
				$transporter->delete();
			}
            // $dd = Shipment_Transporter::where('id',$data->id)->delete();
             // $data->deleted_by = Auth::id();
             // $data->save();
             //$dd2 = Shipment_Driver::where('shipment_no',$data->shipment_no)->where('transporter_id',$data->transporter_id)->delete();
                $check = Shipment_Driver::where('shipment_no',$data->shipment_no)->count();
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
        $data2 = Shipment::withTrashed()->whereNull('deleted_at')->where('myid',$Request->id)->first();
        $data = Shipment::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data2->shipment_no)->first();
        $comp = Company::withTrashed()->whereNull('deleted_at')->findorfail($data->company);
        $data->company_name = $comp->name;
        if($data->forwarder != "" && $data->forwarder != null && $data->forwarder != 'null'){
                $for = Forwarder::withTrashed()->whereNull('deleted_at')->findorfail($data->forwarder);
                $data->forwarder_name = $for->name;
                }else {
                    $data->forwarder_name ="";
                }
                 if($data->transporter != "" && $data->transporter != null && $data->transporter != 'null'){
                $tra = Transporter::withTrashed()->whereNull('deleted_at')->findorfail($data->transporter);
                $data->transporter_name = $tra->name;
                } else {
                    $data->transporter_name ="";
                }
                if($data->trucktype != "" && $data->trucktype != null && $data->trucktype != 'null'){
                $truck = Truck::withTrashed()->whereNull('deleted_at')->findorfail($data->trucktype);
                $data->trucktype_name = $truck->name;
                } else {
                    $data->trucktype_name ="";
                }
                $tras_list =Shipment_Transporter::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data2->shipment_no)->get();
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
                $driver_list = Shipment_Driver::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data2->shipment_no)->get();
                $d_list = "";
                foreach ($driver_list as $key => $value) {
                    $tt = Driver::withTrashed()->findorfail($value->driver_id);
                     if($key == 0) {
                        $d_list = $d_list."".$tt->name;
                    } else {
                        $d_list = $d_list.", ".$tt->name;
                     }
                }
                $data->drivers_list =  $d_list;
                //dd($data);
                if($Request->role== "transporter"){
                     $driver_list =Shipment_Driver::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data2->shipment_no)->where('transporter_id',$data2->transporter_id)->get();
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
                    $driver_list =Shipment_Driver::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data2->shipment_no)->get();
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
                $trucks = Shipment_Driver::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data->shipment_no)->get();
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
        return view('admin.shipmentedit',compact('data','company','truck_type','forwarder','transporter'));
    }
    public function ShipmentUpdate(Request $Request)
    {
         $this->check();
      if($Request->type2 == 'fcl') {
        $this->validate($Request, [
        //'shipment_no' => 'unique:shipment,shipment_no,' . $Request->id,
        'date' => 'required',
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
        //'shipment_no' => 'unique:shipment,shipment_no,' . $Request->id,
        'date' => 'required',
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
                $data = Shipment::where('id',$Request->id)->first();
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
              //  $data->shipment_no = $Request->shipment_no;
              //  $data->lr_no = $Request->shipment_no."/".getenv('FIN_YEAR');
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
                $data->destuffing_date =$Request->destuffing;
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
               // dd($data);
                $summary = new Shipment_Summary();
                $summary->shipment_no = $data->shipment_no;
                $summary->flag = "Shipment Edit";
                $summary->description = "Shipment Edited By Admin";
                $summary->created_by = Auth::id();
                $summary->save();
                return redirect()->route('shipmentlist')->with('success','Shipment updated successfully.');
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
        return redirect()->route('shipmentlist')->with('success','Shipment successfully Deleted.');
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
        $role = Auth::user()->role;
        $data = Shipment::where('shipment_no',$Request->shipment_no)->first();
        $data->status = 2;
        $data->updated_by = Auth::id();
        $data->save();
        $summary = new Shipment_Summary();
        $summary->shipment_no = $Request->shipment_no;
        $summary->flag = "Shipment Delivered";
        $summary->description = "Shipment Delivered by"." ".$role;
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
        if(Auth::user()->role == "company") {
        $ff= Company::where('user_id',Auth::user()->id)->first();
        $data = Shipment::select("shipment.*","warehouse.name as wname")
        ->join("warehouse","warehouse.id","=","shipment.warehouse_id")
        ->where("shipment.status",3)->where('company',$ff->id)
        ->get();
        }
        else{
            $data = Shipment::select("shipment.*","warehouse.name as wname")
            ->join("warehouse","warehouse.id","=","shipment.warehouse_id")
            ->where("shipment.status",3)
            ->get();
        }
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

                $ship_check = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->transporter_id)
                ->count();
                if ($ship_check > 0) {
                    return redirect()->back()->with('error', "This transporter is already added. Please select another transporter.");

                }

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
                // $summary1 = new Shipment_Summary();
                // $summary1->shipment_no =  $Request->shipment_no;
                // $summary1->flag = "Add Truck";
                // $summary1->transporter_id = $Request->other_id;
                // $summary1->description = "Add Driver & Truck No. ".$Request->truck_no;
                // $summary1->save();
                if($data->transporter_id){
                    $transporter=Transporter::where('id',$data->transporter_id)->first();
                    $from_user = User::find(Auth::id());
                    $to_user = User::find($transporter['user_id']);
                    if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                        $notification = new Notification();
                        $notification->notification_from = $from_user->id;
                        $notification->notification_to = $to_user->id;
                        $notification->role = 'transporter';
                        $notification->shipment_id = $data->shipment_id;
                        $id = $data->shipment_no;
                        $title= "New Shipment assign to you" .' - '. $data->shipment_no;
                        $message= "Tap here to see more details.";
                        $notification->title = $title;
                        $notification->message = $message;
                        $notification->notification_type = '3';
                        $notification->user_name_from = $from_user['username'];
                        $notification->save();
                        $notification_id = $notification->id;
                        if($to_user->device_token != null){
                            if($to_user->device_type == 'ios'){
                                GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                            }else{
                                GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                            }
                        }
                    }
                }
                }
               

            return redirect()->back()->with('success', "Transporter Add Successfully.");
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
        return view('admin.shipmentwareedit',compact('data','company','truck_type','forwarder','transporter'));
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
                $data->destuffing_date = $Request->destuffing;
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
                return redirect()->route('warehouseshiplist')->with('success','Shipment successfully Updated.');
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
        $summary->flag = "Shipment Ontheway";
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
        $summary->flag = "Shipment Delivered";
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
    public function ShipmentAllList(Builder $builder, ShipmentDataTable $dataTable,Request $Request)
    {
         $this->check();
         $html = $builder->columns([
            ['data' => 'shipment_no', 'name' => 'shipment_no','title' => 'Ship.No'],
            ['data' => 'date', 'name' => 'date','title' => 'Date'],
            ['data' => 'type', 'name' => 'type','title' => 'Type','orderable' => false, 'searchable' => false],
            ['data' => 'consignor', 'name' => 'consignor','title' => 'Consignor'],
            ['data' => 'consignee', 'name' => 'consignee','title' => 'Consignee'],
            ['data' => 'from1', 'name' => 'from1','title' => 'From'],
            ['data' => 'to1', 'name' => 'to1','title' => 'To'],
            ['data' => 'status', 'name' => 'status','title' => 'Status'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],
         ])->parameters([
           // "scrollX" => true,
            "processing" => true,
            "serverSide" => true,
            "dom" => 'lfrtip',
            "order" => ["1", "DESC"],
        ]);

                if(request()->ajax()) {
                if(Auth::user()->role == "company") {
                    $ff= Company::where('user_id',Auth::user()->id)->first();
                    $data = Shipment::where('company',$ff->id)->whereRaw('DATEDIFF(CURDATE(),date) >= 6');
                    }
                    else{
                    $data = Shipment::whereRaw('DATEDIFF(CURDATE(),date) >= 6');
                    }
                return $dataTable->dataTable($data)->toJson();
            }
            $warehouse = Warehouse::get();
            return view('admin.shipmentalllist',compact('warehouse','html'));
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
    public function ShipmentAllDetails(Request $Request)
    {
        $this->check();
        $data2 = Shipment::withTrashed()->whereNull('deleted_at')->where('myid',$Request->id)->first();
        $data = Shipment::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data2->shipment_no)->first();
        $comp = Company::withTrashed()->whereNull('deleted_at')->findorfail($data->company);
        $data->company_name = $comp->name;
        if($data->forwarder != "" && $data->forwarder != null && $data->forwarder != 'null'){
                $for = Forwarder::withTrashed()->whereNull('deleted_at')->findorfail($data->forwarder);
                $data->forwarder_name = $for->name;
                }else {
                    $data->forwarder_name ="";
                }
                 if($data->transporter != "" && $data->transporter != null && $data->transporter != 'null'){
                $tra = Transporter::withTrashed()->whereNull('deleted_at')->findorfail($data->transporter);
                $data->transporter_name = $tra->name;
                } else {
                    $data->transporter_name ="";
                }
                if($data->trucktype != "" && $data->trucktype != null && $data->trucktype != 'null'){
                $truck = Truck::withTrashed()->whereNull('deleted_at')->findorfail($data->trucktype);
                $data->trucktype_name = $truck->name;
                } else {
                    $data->trucktype_name ="";
                }
                $tras_list =Shipment_Transporter::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data2->shipment_no)->get();
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
                $driver_list = Shipment_Driver::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data2->shipment_no)->get();
                $d_list = "";
                foreach ($driver_list as $key => $value) {
                    $tt = Driver::withTrashed()->findorfail($value->driver_id);
                     if($key == 0) {
                        $d_list = $d_list."".$tt->name;
                    } else {
                        $d_list = $d_list.", ".$tt->name;
                     }
                }
                $data->drivers_list =  $d_list;
                //dd($data);
                if($Request->role== "transporter"){
                     $driver_list =Shipment_Driver::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data2->shipment_no)->where('transporter_id',$data2->transporter_id)->get();
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
                    $driver_list =Shipment_Driver::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data2->shipment_no)->get();
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
                $trucks = Shipment_Driver::withTrashed()->whereNull('deleted_at')->where('shipment_no',$data->shipment_no)->get();
				//dd($data['exports']);
                //dd($trucks);
        return view('admin.shipmentdetail',compact('data','trucks'));
    }
	public function MyFilter(Request $Request)
	{
    	$data = array();
        if(Auth::user()->role == "company") {
            $ff= Company::where('user_id',Auth::user()->id)->first();
            $tt = Shipment::where('company',$ff->id)->orderBy('created_at','desc')->get();
            }
            else{
            $tt = Shipment::orderBy('created_at','desc')->get();
            }
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
        if(isset($Request->company)){
            $company = $Request->company;
        } else {
            $company = '';
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
        $all_company = Company::where('status',0)->get();
        if(Auth::user()->role == "company") {
        $ff= Company::where('user_id',Auth::user()->id)->first();
        $datas = Shipment::where('company',$ff->id);
        }
        elseif(Auth::user()->role == "employee"){
            $ff2= Employee::where('user_id',Auth::user()->id)->first();
            $ff1= Company::where('id',$ff2->company_id)->first();
            $datas = Shipment::where("status","!=",3)->where('company',$ff1->id);
        }
        else{
		$datas = Shipment::query();
        }
		if($Request->shipment){
			$datas = $datas->where('id',$Request->shipment);
		}
        if($Request->status){
            if($Request->status == 'Pending'){
			$datas = $datas->where('status',0);
            }
            if($Request->status == 'Ontheway'){
                $datas = $datas->where('status',1);
            }
            if($Request->status == 'Delivered'){
                $datas = $datas->where('status',2);
            }
		}
		if($Request->transporter){
            $check1 = Shipment_Transporter::withTrashed()->whereNull('deleted_at')
            ->where('transporter_id',$Request->transporter)->groupBy('shipment_no')->pluck('shipment_no')->toArray();
            $datas = $datas->whereIn('shipment_no',$check1);
        }
		if($Request->forwarder){
			$datas = $datas->where('forwarder', $Request->forwarder);
		}
        if($Request->company){
			$datas = $datas->where('company', $Request->company);
		}
		if($Request->date){
			$datas = $datas->whereDay('date',$Request->date);
		}
		if($Request->year){
			$datas = $datas->whereYear('created_at', $Request->year);
		}
		if($Request->month){
			$datas = $datas->whereMonth('created_at', $Request->month);
		}
		if($Request->search){
			$datas = $datas->where('shipment_no','like','%'.$Request->search.'%')->orwhere('from1','like','%'.$Request->search.'%')
			->orwhere('to1','like','%'.$Request->search.'%')->orwhere('to2','like','%'.$Request->search.'%')
			->orwhere('consignor','like','%'.$Request->search.'%')->orwhere('consignee','like','%'.$Request->search.'%')
			->orwhere('shipper_invoice','like','%'.$Request->search.'%')->orwhere('forwarder_ref_no','like','%'.$Request->search.'%')
			->orwhere('b_e_no','like','%'.$Request->search.'%');
		}
		//$datas->dd();
		if($Request->search || $Request->shipment || $Request->transporter || $Request->month || $Request->year || $Request->forwarder || $Request->company || $Request->date || $Request->status){
			$data = $datas->orderby('shipment_no','desc')->get();
            foreach ($data as $key => $value) {
                $data1 = Shipment_Driver::where('shipment_no',$value->shipment_no)->get();
                if($data1){
                 $d_list = "";
                            foreach ($data1 as $key2 => $value2) {
                                if($key2 == 0) {
                                    $d_list = $d_list."".$value2->truck_no;
                                } else {
                                    $d_list = $d_list.", ".$value2->truck_no;
                                }
                            }
                            $data[$key]['truck_no'] = $d_list;
                }
                else {
                    $data[$key]['truck_no'] = '-';
                }
                $tras_list = Shipment_Transporter::where('shipment_no', $value->shipment_no)->get();
                if($tras_list){
                            $t_list = "";
                            foreach ($tras_list as $key3 => $value3) {
                                $ttv = Transporter::withTrashed()->findorfail($value3->transporter_id);
                                if($ttv){
                                if ($key3 == 0) {
                                    $t_list = $t_list . "" . $ttv->name;
                                } else {
                                    $t_list = $t_list . ", " . $ttv->name;
                                }
                            }
                            else{
                                $t_list = '-';
                            }
                            }
                            $data[$key]['transporter_name'] = $t_list;
                        }
                        else {
                            $data[$key]['transporter_name'] = '-';
                        }
                        $data1v = Invoice::where('invoice_no',$value->lr_no)->first();
                        if($data1v){
                        $invoice_cost = $data1v->grand_total;
                        $data[$key]['invoice_cost'] = $invoice_cost;
                        }
                        else {
                            $data[$key]['invoice_cost'] = '-';
                        }
                        $data1c = Expense::where('shipment_no',$value->shipment_no)->sum('amount');
                        if($data1c){
                        $transporter_cost = $data1c;
                        $data[$key]['transporter_cost'] = $transporter_cost;
                        }
                        else {
                            $data[$key]['transporter_cost'] = '-';
                        }
            }
		}
        $warehouse = Warehouse::get();
        $currentYear = date('Y');
        $startYear = 2020;
        $yearRange = range($startYear, $currentYear);
       // $data = $datas->orderby('shipment_no','desc')->get();
         //dd($data);
		return view('admin.shipmentfilter', compact('tt','ttt','tts','data','all_transporter','all_forwarder','company','all_company','search','transporter','forwarder','year','month','date','warehouse','yearRange'));
	}
     public function Driverlist(Request $Request)
    {
        $this->check();
        $old_driver = $Request->old_driver;
        $shipmentdriver = Shipment_Driver::where('transporter_id',$Request->transporter_id)->where('shipment_no',$Request->shipment_no)->pluck('driver_id')->toArray();
        $drivers = Driver::where('transporter_id',$Request->transporter_id)->whereNotIn('id',$shipmentdriver)->whereNull('deleted_at')->orderby('name','asc')->get();
        return view('admin.shipmentdriverlist',compact('drivers','old_driver'));
    }
    public function Detail (Request $Request)
    {
        // code...
    }
}