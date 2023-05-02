<?php 

namespace App\Http\Controllers\API;

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


class ForwarderController extends Controller
{

    private function checkuser($user_id)
    {   

         $user = User::withTrashed()->findorfail($user_id);

            if($user->status == 1){
            
            return 1;

        } 

        if($user->status == 0){
            

            return 0; 

        }

    }


    public function Login (Request $Request)  {

         try { 

                $data = array();

                $data1 = User::where('username',$Request->username)->first();   

                $c_data = (array)$data1;

                if(count($c_data) == 0 ){

                    return response()->json(['status' => 'failed','message' => 'Username Not Registered.','data' => json_decode('{}'),'code' => '500'],200);

                } else if($data1->status == 1){

                    return response()->json(['status' => 'failed','message' => 'This User Blocked By Admin, Please Contact Administrator.','data' => json_decode('{}'),'code' => '500'],200);

                } else { 

                        $credentials = $Request->only('username', 'password');

                            if (Auth::attempt($credentials)) {


                                if($data1->role == "forwarder"){

                                    $detail = Forwarder::where("user_id",$data1->id)->first();

                                    $data1['other_id']=$detail->id;

                                    $data['user_id'] = $data1->id;
                                    $data['forwarder_id'] = $detail->id;
                                    $data['name'] = $detail->name;



                                }

                                return response()->json(['status' => 'success','message' => 'Login Successfully.','data' => $data,'code' => '200'],200);

                            } else {

                             return response()->json(['status' => 'failed','message' => 'Email & Password Are Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                            }

                }

          }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


     public function List (Request $Request)  {

         try { 

                 $check = $this->checkuser($Request->user_id);

                 if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'You are Blocked By Admin, Please Contact Administrator.','data' => json_decode('{}'),'code' => '500'],200);  
                }



             $data1= Shipment::where('forwarder',$Request->forwarder_id)->get();

             $data= array();

             foreach ($data1 as $key => $value) {

                    $data[$key]['shipment_no'] = $value->shipment_no;

                    $data[$key]['date'] = date('d-m-Y',strtotime($value->date));

                        if($value->exports == 1){

                            $data[$key]['consignee'] = $value->consignee;

                        } else {

                            $data[$key]['consignor'] = $value->consignor;

                        }
                     
                    $data[$key]['from'] = $value->from1;

                    $data[$key]['to'] = $value->to1;

                        if($value->status == 0){

                            $data[$key]['status'] = "Pending"; 

                        } elseif($value->status == 1){

                            $data[$key]['status'] = "OnTheWay"; 

                        }elseif($value->status == 2){

                            $data[$key]['status'] = "Delivered";

                        } 
                    
             }

              return response()->json(['status' => 'success','message' => 'Shipment List Successfully.','data' => $data,'code' => '200'],200);

            }catch(\Exception $e){

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


        public function Details(Request $Request)  {

         try {

             $check = $this->checkuser($Request->user_id);

                 if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'You are Blocked By Admin, Please Contact Administrator.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                 

                if($Request->shipper_no != "" && $Request->shipper_no != " " && $Request->shipper_no != "null"  && $Request->shipper_no != null ){

                    $details = Shipment::where('shipper_invoice',$Request->shipper_no)->first();

                } 

                if($Request->shipment_no != "" && $Request->shipment_no != " " && $Request->shipment_no != "null"  && $Request->shipment_no != null ){

                    $details = Shipment::where('shipment_no',$Request->shipment_no)->first();
                    
                }  

                if(empty($details)) {

                     return response()->json(['status' => 'failed','message' => 'Please Enter Valid Number.','data' => json_decode('{}'),'code' => '500'],200);

                }


                 if($details->show_detail == 0) {

                     return response()->json(['status' => 'failed','message' => 'You have to no permission for view shipment details. Contact to Administrator.','data' => json_decode('{}'),'code' => '500'],200);

                }


                $data = array();

                $data['shipment_no'] = $details->shipment_no;

                $data['date'] = date('d-m-Y',strtotime($details->date));

                $company = Company::withTrashed()->findorfail($details->company);

                $data['company'] = $company->name;


                    if($details->exports == 1){

                            $data['shipment_type1'] = "Export";

                            $data['consignor'] = $details->consignor;

                            $data['consignor_address'] = $details->consignor_address;

                            $data['consignee_address'] = $details->consignee_address;

                        } else {

                            $data['shipment_type1'] = "Import";

                            $data['consignor_address'] = $details->consignor_address;

                            $data['consignee'] = $details->consignee;

                            $data['consignee_address'] = $details->consignee_address;
                        }

                     if($details->lcl == 1){

                            $data['shipment_type2'] = "LCL";

                        } else {

                            $data['shipment_type2'] = "FCL";

                            $data['container_type'] = $details->container_type;

                            $data['destuffing_date'] = date('d-m-Y',strtotime($details->destuffing_date));

                            $data['container_no'] = $details->container_no;

                            $data['shipping_line'] = $details->shipping_line;

                            $data['cha'] = $details->cha;

                            $data['seal_no'] = $details->seal_no;

                            $data['pod'] = $details->pod;

                        }

                $data['from'] = $details->from1;

                $data['to'] = $details->to1;

                $truck = Truck::withTrashed()->findorfail($details->trucktype);

                $data['truck_type'] =$truck->name;

                $data['no_package'] = $details->package;

                $data['description'] = $details->description;

                $data['total_gross_weight'] = $details->weight;

                $data['shipper_invoice_no'] = $details->shipper_invoice;
               
                $data['forwarder_reference_no'] = $details->forwarder_ref_no;

                $data['b_e_no'] = $details->b_e_no;

                $trucks = Shipment_Driver::where('shipment_no',$details->shipment_no)->get();

                $alltrucks= array();

                foreach ($trucks as $key => $value) {
                        
                    if($key == 0){
                        $alltrucks=$value->truck_no;
                    } else {
                        $alltrucks= $alltrucks.", ".$value->truck_no;
                    }
                }

                $data['truck_no'] = $alltrucks;



                        return response()->json(['status' => 'success','message' => 'Shipment List Successfully.','data' => $data,'code' => '200'],200);

                



            }catch(\Exception $e){

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


}
