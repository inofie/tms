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
use App\Shipment_Driver;
use App\Expense;
use Hash;
use PDF;
use Mail;
use Illuminate\Support\Facades\Auth;


class ApiController extends Controller
{

    private function checkversion($version)
    {   

        $myversion = env('MYAPP_VERSION');
        
        if($myversion != $version ){
            
            return 1;

        } else {

            return 0; 

        }

    }


    public function Login (Request $Request)  {

         try { 

                //dd(env('MYAPP_VERSION'));

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }



                $data = User::where('username',$Request->username)->first();   

                $c_data = (array)$data;

                if(count($c_data) == 0 ){

                    return response()->json(['status' => 'failed','message' => 'username Not Registered.','data' => json_decode('{}'),'code' => '500'],200);

                } else if($data->status == 1){

                    return response()->json(['status' => 'failed','message' => 'Blocked This User. Please Contact Administrator.','data' => json_decode('{}'),'code' => '500'],200);

                } else { 

                        $credentials = $Request->only('username', 'password');

                            if (Auth::attempt($credentials)) {

                                if($data->role == "admin"){

                                $com = Company::where('user_id',$data->id)->first();
                            
                                $data['other_id']=$com->id; 

                                }


                                if($data->role == "employee"){

                                $emp = Employee::where('user_id',$data->id)->first();

                                $com = Company::where('user_id',$emp->company_id)->first();
                                
                                 $data['other_id']=$com->id; 
                                     

                                }

                                if($data->role == "transporter"){

                                    $detail = Transporter::where("user_id",$data->id)->first();

                                    $data['transporter_id']=$detail->id; 

                                }

                                if($data->role == "forwarder"){

                                    $detail = Forwarder::where("user_id",$data->id)->first();

                                    $data['forwarder_id']=$detail->id; 

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


     public function CompanyList (Request $Request)  {

         try { 

                 $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Company::all();


                 return response()->json(['status' => 'success','message' => 'Company List Successfully.','data' => $data,'code' => '200'],200);


         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }

    public function CompanyAdd (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = User::where('username',$Request->username)->count();
                
                if($data > 0){

                   return response()->json(['status' => 'failed','message' => 'This Username Already Registred In Our System.','data' => json_decode('{}'),'code' => '500'],200);   

                } else {


                    $user = new User();
                    $user->name = $Request->name;
                    $user->username = $Request->username;
                    $user->password = Hash::make($Request->password);
                    $user->role = "admin";
                    $user->created_by=$Request->user_id;
                    $user->save();

                }

                $comapny = new Company();
                $comapny->user_id= $user->id;
                $comapny->name = $Request->name;
                $comapny->address = $Request->address;
                $comapny->phone = $Request->phone;
                $comapny->email= $Request->email;
                $comapny->gst_no = $Request->gst;
                $comapny->created_by=$Request->user_id;
                $comapny->myid= uniqid();


                $path = public_path('/uploads');
                    
                 if($Request->hasFile('logo') && !empty($Request->file('logo'))){
                        $file_name = time()."1".$Request->logo->getClientOriginalName();
                        $Request->logo->move($path,$file_name);
                        $comapny->logo = $file_name;
                 }

                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Company added Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }


    public function CompanyDetail (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }


                $data = Company::findorfail($Request->id);


                 return response()->json(['status' => 'success','message' => 'Comapny Detail Successfully.','data' => $data,'code' => '200'],200);




         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function CompanyEdit (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }


                $comapny = Company::findorfail($Request->id);
                $comapny->name = $Request->name;
                $comapny->address = $Request->address;
                $comapny->phone = $Request->phone;
                $comapny->email= $Request->email;
                $comapny->gst_no = $Request->gst;
                $comapny->status = $Request->status;
                $comapny->updated_by = $Request->user_id;



                $user= User::withTrashed()->findorfail($comapny->user_id);
                $user->status = $Request->status; 
                $user->save();


                $path = public_path('/uploads');
                    
                 if($Request->hasFile('logo') && !empty($Request->file('logo'))){
                        $file_name = time()."1".$Request->logo->getClientOriginalName();
                        $Request->logo->move($path,$file_name);
                        $comapny->logo = $file_name;
                 }

                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Company Updated Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }



         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }




    public function CompanyDelete (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Company::findorfail($Request->id);
                $data->deleted_by = $Request->user_id;
                $data->save();
                $user=User::findorfail($data->user_id);
                $user->deleted_by = $Request->user_id;
                $user->save();
                if($user->delete() && $data->delete()) {
                    
                    return response()->json(['status' => 'success','message' => 'Company deleted Successfully.','data' => json_decode('{}'),'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

            }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }



    public function ForwarderList (Request $Request)  {

         try { 

                 $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Forwarder::all();

                 return response()->json(['status' => 'success','message' => 'Forwarder List Successfully.','data' => $data,'code' => '200'],200);


         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }

    public function ForwarderAdd (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }


                 $data = User::where('username',$Request->username)->count();
                
                if($data > 0){

                   return response()->json(['status' => 'failed','message' => 'This Username Already Registred In Our System.','data' => json_decode('{}'),'code' => '500'],200);   

                } else {


                    $user = new User();
                    $user->name = $Request->name;
                    $user->username = $Request->username;
                    $user->password = Hash::make($Request->password);
                    $user->role = "forwarder";
                    $user->created_by=$Request->user_id;

                    $user->save();

                }


               
                $comapny = new Forwarder();

                $comapny->user_id = $user->id;

                $comapny->name = $Request->name;

                $comapny->address = $Request->address;

                $comapny->phone = $Request->phone;

                $comapny->email= $Request->email;

                $comapny->gst_no = $Request->gst;

                $comapny->created_by=$Request->user_id;

                $comapny->myid= uniqid();


                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Forwarder added Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }

      public function ForwarderDetail (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }


                $data = Forwarder::findorfail($Request->id);


                 return response()->json(['status' => 'success','message' => 'Forwarder Detail Successfully.','data' => $data,'code' => '200'],200);




         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


    public function ForwarderEdit (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }


                $comapny = Forwarder::findorfail($Request->id);

                $comapny->name = $Request->name;

                $comapny->address = $Request->address;

                $comapny->phone = $Request->phone;

                $comapny->email= $Request->email;

                $comapny->gst_no = $Request->gst;

                $comapny->status = $Request->status;

                $comapny->updated_by=$Request->user_id;

                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Forwarder Updated Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


    public function ForwarderDelete (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Forwarder::findorfail($Request->id);
                $data->deleted_by = $Request->user_id;
                $data->save();
                $user=User::findorfail($data->user_id);
                $user->deleted_by = $Request->user_id;
                $user->save();
                
                if($user->delete() && $data->delete()) {
                    
                    return response()->json(['status' => 'success','message' => 'Forwarder deleted Successfully.','data' => json_decode('{}'),'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

            }catch(\Exception $e){
                        
                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

     public function TruckList (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Truck::where('status',0)->get();

                 return response()->json(['status' => 'success','message' => 'Truck Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){ 

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function TruckDetail (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Truck::findorfail($Request->id);

                 return response()->json(['status' => 'success','message' => 'Truck Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){ 

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }




    public function TransporterList (Request $Request)  {




         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Transporter::all();

                 return response()->json(['status' => 'success','message' => 'Transporter Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){ 

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


    public function TransporterDetail (Request $Request)  {



         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Transporter::findorfail($Request->id);

                 return response()->json(['status' => 'success','message' => 'Transporter Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){ 

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

     public function TransporterAdd (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = User::where('username',$Request->username)->count();
                
                if($data > 0){

                   return response()->json(['status' => 'failed','message' => 'This Username Already Registred In Our System.','data' => json_decode('{}'),'code' => '500'],200);   

                } else {


                    $user = new User();
                    $user->name = $Request->name;
                    $user->username = $Request->username;
                    $user->password = Hash::make($Request->password);
                    $user->role = "transporter";
                    $user->created_by=$Request->user_id;
                    $user->save();

                }

               
                $comapny = new Transporter();

                $comapny->user_id = $user->id;

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->truck_no= $Request->truck_no;

                $comapny->licence_no= $Request->licence_no;

                $comapny->pan = $Request->pan_no;

                $comapny->created_by=$Request->user_id;

                $comapny->myid= uniqid();

                $path = public_path('/uploads');
                    
                 if($Request->hasFile('rc_book') && !empty($Request->file('rc_book'))){
                        $file_name = time()."1".$Request->rc_book->getClientOriginalName();
                        $Request->rc_book->move($path,$file_name);
                        $comapny->rc_book = $file_name;
                 }
                    
                 if($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))){
                        $file_name = time()."2".$Request->pan_card->getClientOriginalName();
                        $Request->pan_card->move($path,$file_name);
                        $comapny->pan_card = $file_name;
                 }
                    
                 if($Request->hasFile('licence') && !empty($Request->file('licence'))){
                        $file_name = time()."3".$Request->licence->getClientOriginalName();
                        $Request->licence->move($path,$file_name);
                        $comapny->licence = $file_name;
                 }


                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Transporter added Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }


    public function TransporterEdit (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

               
                $comapny = Transporter::findorfail($Request->id);

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->truck_no= $Request->truck_no;

                $comapny->licence_no= $Request->licence_no;

                $comapny->pan = $Request->pan_no;

                $comapny->status = $Request->status;

                $comapny->updated_by=$Request->user_id;

                $path = public_path('/uploads');
                    
                 if($Request->hasFile('rc_book') && !empty($Request->file('rc_book'))){
                        $file_name = time()."1".$Request->rc_book->getClientOriginalName();
                        $Request->rc_book->move($path,$file_name);
                        $comapny->rc_book = $file_name;
                 }
                    
                 if($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))){
                        $file_name = time()."2".$Request->pan_card->getClientOriginalName();
                        $Request->pan_card->move($path,$file_name);
                        $comapny->pan_card = $file_name;
                 }
                    
                 if($Request->hasFile('licence') && !empty($Request->file('licence'))){
                        $file_name = time()."3".$Request->licence->getClientOriginalName();
                        $Request->licence->move($path,$file_name);
                        $comapny->licence = $file_name;
                 }


                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Transporter added Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


    public function TransporterDelete (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Transporter::findorfail($Request->id);
                $data->deleted_by = $Request->user_id;
                $data->save();
                $driver= Driver::where('transporter_id', $data->id)->get();

                foreach ($driver as $key => $value) {
                    
                    $drive = Driver::findorfail($value->id);
                    $drive->deleted_by = $Request->user_id;
                    $drive->save();
                    $drive->delete();

                }

                $user=User::findorfail($data->user_id);
                $user->deleted_by = $Request->user_id;
                $user->save();
                
                if($user->delete() && $data->delete()) {
                    
                    return response()->json(['status' => 'success','message' => 'Transporter deleted Successfully.','data' => json_decode('{}'),'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

            }catch(\Exception $e){
                        
                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


    public function WarehouseList (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = array();
                $data1 = Warehouse::all();

                foreach ($data1 as $key => $value) {
                    
                    $data[$key]=$value;

                    $detail = Company::findorfail($value->company_id);
                    $data[$key]['company_name']= $detail->name;
                }




                 return response()->json(['status' => 'success','message' => 'Warehouse Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){ 

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


    public function WarehouseDetail (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Warehouse::findorfail($Request->id);

                $detail = Company::findorfail($data->company_id);
                $data['company_name']= $detail->name;

                 return response()->json(['status' => 'success','message' => 'Transporter Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){ 

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }



     public function WarehouseAdd (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }
               
               
                $comapny = new Warehouse();

                $comapny->name = $Request->name;

                $comapny->address = $Request->address;

                $comapny->phone= $Request->phone;

                $comapny->gst= $Request->gst;

                $comapny->pan = $Request->pan_no;

                $comapny->created_by=$Request->user_id;

                $comapny->company_id = $Request->company_id;

                $comapny->myid= uniqid();


                $path = public_path('/uploads');
                    
                 if($Request->hasFile('address_proof') && !empty($Request->file('address_proof'))){
                        $file_name = time()."1".$Request->address_proof->getClientOriginalName();
                        $Request->address_proof->move($path,$file_name);
                        $comapny->address_proof = $file_name;
                 }
        

                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Warehouse added Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }



    public function WarehouseEdit (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

               
                $comapny = Warehouse::findorfail($Request->id);

                $comapny->name = $Request->name;

                $comapny->address = $Request->address;

                $comapny->phone= $Request->phone;

                $comapny->gst= $Request->gst;

                $comapny->pan = $Request->pan_no;

                $comapny->status = $Request->status;

                $comapny->updated_by=$Request->user_id;

                $path = public_path('/uploads');
                    
                 if($Request->hasFile('address_proof') && !empty($Request->file('address_proof'))){
                        $file_name = time()."1".$Request->address_proof->getClientOriginalName();
                        $Request->address_proof->move($path,$file_name);
                        $comapny->address_proof = $file_name;
                 }
        

                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Warehouse Upadated Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }


    public function WarehouseDelete (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Warehouse::findorfail($Request->id);
                $data->deleted_by = $Request->user_id;
                $data->save();

                if($data->delete()) {
                    
                    return response()->json(['status' => 'success','message' => 'Warehouse deleted Successfully.','data' => json_decode('{}'),'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

            }catch(\Exception $e){
                        
                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


    public function DriverList (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data =array();
                $data1 = Driver::all();

                foreach ($data1 as $key => $value) {
                    $data[$key]= $value;
                    $details = Transporter::findorfail($value->transporter_id);
                    $data[$key]['transporter_name']= $details->name;
                }




                 return response()->json(['status' => 'success','message' => 'Driver Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){ 

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }



    public function DriverDetail (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Driver::withTrashed()->findorfail($Request->id);
                $details = Transporter::withTrashed()->findorfail($data->transporter_id);
                $data['transporter_name']= $details->name;

                 return response()->json(['status' => 'success','message' => 'Driver Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){ 

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function DriverAdd (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

               
                $comapny = new Driver();

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->truck_no= $Request->truck_no;

                $comapny->licence_no= $Request->licence_no;

                $comapny->pan = $Request->pan_no;

                $comapny->transporter_id = $Request->transporter_id;

                $comapny->created_by=$Request->user_id;

                $comapny->myid= uniqid();

                $path = public_path('/uploads');
                    
                 if($Request->hasFile('rc_book') && !empty($Request->file('rc_book'))){
                        $file_name = time()."1".$Request->rc_book->getClientOriginalName();
                        $Request->rc_book->move($path,$file_name);
                        $comapny->rc_book = $file_name;
                 }

                 if($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))){
                        $file_name = time()."2".$Request->pan_card->getClientOriginalName();
                        $Request->pan_card->move($path,$file_name);
                        $comapny->pan_card = $file_name;
                 }

                 if($Request->hasFile('licence') && !empty($Request->file('licence'))){
                        $file_name = time()."3".$Request->licence->getClientOriginalName();
                        $Request->licence->move($path,$file_name);
                        $comapny->licence = $file_name;
                 }
        

                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Driver added Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }

    public function DriverEdit (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

               
                $comapny = Driver::findorfail($Request->id);

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->truck_no= $Request->truck_no;

                $comapny->licence_no= $Request->licence_no;

                $comapny->pan = $Request->pan_no;

                $comapny->transporter_id = $Request->transporter_id;

                $comapny->status = $Request->status;

                $comapny->updated_by=$Request->user_id;

                $path = public_path('/uploads');
                    
                 if($Request->hasFile('rc_book') && !empty($Request->file('rc_book'))){
                        $file_name = time()."1".$Request->rc_book->getClientOriginalName();
                        $Request->rc_book->move($path,$file_name);
                        $comapny->rc_book = $file_name;
                 }

                 if($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))){
                        $file_name = time()."2".$Request->pan_card->getClientOriginalName();
                        $Request->pan_card->move($path,$file_name);
                        $comapny->pan_card = $file_name;
                 }

                 if($Request->hasFile('licence') && !empty($Request->file('licence'))){
                        $file_name = time()."3".$Request->licence->getClientOriginalName();
                        $Request->licence->move($path,$file_name);
                        $comapny->licence = $file_name;
                 }
        

                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Driver Updated Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }

    public function DriverDelete (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Driver::findorfail($Request->id);
                $data->deleted_by = $Request->user_id;
                $data->save();

                if($data->delete()) {
                    
                    return response()->json(['status' => 'success','message' => 'Driver deleted Successfully.','data' => json_decode('{}'),'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

            }catch(\Exception $e){
                        
                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }




    public function EmployeeList (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data= array();
                $data1 = Employee::all();

                    foreach ($data1 as $key => $value) {
                        $data[$key]= $value;
                         $company = Company::withTrashed()->findorfail($value->company_id);
                        $data[$key]['comapny_name']=$company->name;
                    }




                 return response()->json(['status' => 'success','message' => 'Employee Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){ 

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


    public function EmployeeDetail (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Employee::findorfail($Request->id);

                $company = Company::withTrashed()->findorfail($data->company_id);

                $data['comapny_name']=$company->name;

                 return response()->json(['status' => 'success','message' => 'Employee Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){ 

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function EmployeeAdd (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }


                $data = User::where('username',$Request->username)->count();
                
                if($data > 0){

                   return response()->json(['status' => 'failed','message' => 'This Username Already Registred In Our System.','data' => json_decode('{}'),'code' => '500'],200);   

                } else {


                    $user = new User();
                    $user->name = $Request->name;
                    $user->username = $Request->username;
                    $user->password = Hash::make($Request->password);
                    $user->role = "employee";
                    $user->created_by=$Request->user_id;
                    $user->save();

                }

               
                $comapny = new Employee();

                $comapny->user_id = $user->id;

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->address= $Request->address;

                $comapny->email= $Request->email;

                $comapny->company_id = $Request->company_id;

                $comapny->created_by=$Request->user_id;

                $comapny->myid= uniqid();

                $path = public_path('/uploads');
                    
                 if($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))){
                        $file_name = time()."1".$Request->pan_card->getClientOriginalName();
                        $Request->pan_card->move($path,$file_name);
                        $comapny->pan_card = $file_name;
                 }

                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Employee added Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }


    public function EmployeeEdit (Request $Request)  {

         try { 

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

               
                $comapny = Employee::findorfail($Request->id);

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->address= $Request->address;

                $comapny->email= $Request->email;

                $comapny->company_id = $Request->company_id;

                $comapny->updated_by=$Request->user_id;

                $path = public_path('/uploads');
                    
                 if($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))){
                        $file_name = time()."1".$Request->pan_card->getClientOriginalName();
                        $Request->pan_card->move($path,$file_name);
                        $comapny->pan_card = $file_name;
                 }

                 if($comapny->save()){

                    return response()->json(['status' => 'success','message' => 'Driver Updated Successfully.','data' => $comapny,'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }

         }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }


    }

    public function EmployeeDelete (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Employee::findorfail($Request->id);
                $data->deleted_by = $Request->user_id;
                $data->save();
                $user=User::findorfail($data->user_id);
                $user->deleted_by = $Request->user_id;
                $user->save();
                if($user->delete() && $data->delete()) {
                    
                    return response()->json(['status' => 'success','message' => 'Employee deleted Successfully.','data' => json_decode('{}'),'code' => '200'],200); 

                 } else {

                     return response()->json(['status' => 'failed','message' => 'Something Wrong.','data' => json_decode('{}'),'code' => '500'],200);  

                 }





            }catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

     public function ShipmentForm (Request $Request)  {

         try {

             $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }



            $data= array();

            $data['company']=Company::where('status',0)->get();
            $data['transporter']=Transporter::where('status',0)->get();
            $data['forwarder']=Forwarder::where('status',0)->get();
            $data['truck']=Truck::where('status',0)->get();

             return response()->json(['status' => 'success','message' => 'Employee deleted Successfully.','data' => $data,'code' => '200'],200); 



            }  catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


     public function ShipmentFormAdd (Request $Request)  {

         try {

             $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }


                $data = new Shipment();

                $data->date = $Request->date;

                $data->company = $Request->company_id;

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

                $data->from1 = $Request->from;

                $data->to1 = $Request->to1;

                $data->to2 = $Request->to2;

                $data->trucktype = $Request->truck_type;

                $data->trucknumber = $Request->truck_no;

                if($Request->truck_no != "" && $Request->truck_no != "null" && $Request->truck_no != null){

                $data->status = 1;

                }

                $data->forwarder = $Request->forwarder_id;

                $data->consignor = $Request->consignor;

                $data->consignor_address = $Request->consignor_address;

                $data->consignee = $Request->consignee;

                $data->consignee_address = $Request->consignee_address;

                $data->package = $Request->no_package;

                $data->description= $Request->cargo_desc;

                $data->weight = $Request->total_weight;

                $data->shipper_invoice = $Request->shipper_invoice_no;

                $data->forwarder_ref_no = $Request->forwarder_ref_no;

                $data->b_e_no = $Request->be_no;

                if($Request->type2 == "fcl"){

                $data->container_type = $Request->container_type;

                $data->destuffing_date = $Request->de_stuffing_date;

                $data->container_no = $Request->container_no;

                $data->shipping_line = $Request->shipping_line;

                $data->cha = $Request->cha;

                $data->seal_no = $Request->seal_no;

                $data->pod = $Request->pod;

                }

                $data->invoice_amount = $Request->invoice_amount;

                $data->remark = $Request->remark;

                $data->transporter = $Request->transporter_id;

                $data->save();

                $company = Company::findorfail($Request->company_id);
                $name = $company->name;
                $n =substr(ucfirst($name),0,1);
                $shipment_no = $n.''.$data->id;

                $aa= Shipment::findorfail($data->id);
                $aa->shipment_no =$shipment_no;
                $aa->lr_no = $shipment_no."/".getenv('FIN_YEAR');
                $aa->save();



                if($Request->transporter_id != null && $Request->transporter_id != '' && $Request->transporter_id != 'null'){

                    
                    $transs = new Shipment_Transporter();
                    $transs->shipment_no = $shipment_no;
                    $transs->shipment_id = $data->id;
                    $transs->transporter_id = $Request->transporter_id;
                    $transs->name = $Request->transporter_name;
                    $transs->created_by = $Request->user_id;  
                    $transs->save();                
                }


                if($Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null'){
                    
                    $driver = new Shipment_Driver();
                    $driver->shipment_no = $shipment_no;
                    $driver->truck_no = $Request->truck_no;
                    $driver->mobile = $Request->transporter_mobile;
                    $driver->created_by = $Request->user_id;  
                    $driver->save();                
                }

                $data1= Shipment::findorfail($aa->id);

                 return response()->json(['status' => 'success','message' => 'Shipment Added Successfully.','data' => $data1,'code' => '200'],200); 

            }  catch(\Exception $e){
                        
                return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }



    


    public function ShipmentTransporterList (Request $Request)  {

         try {

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }




                $data1 = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->get();

                $data = array();

                foreach ($data1 as $key => $value) {

                    $data[$key]=$value;

                    $tras = Transporter::withTrashed()->findorfail($value->transporter_id);

                    $data[$key]['name']= $tras->name;

                }

                return response()->json(['status' => 'success','message' => 'Shipment Transporter List Successfully.','data' => $data,'code' => '200'],200); 


             }  catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

     public function ShipmentTransporterSave (Request $Request)  {

         try {

                $tras = Transporter::findorfail($Request->transporter_id);
                $ship =Shipment::where('shipment_no',$Request->shipment_no)->first();
                $data =new Shipment_Transporter();

                if($Request->truck_no != "" && $Request->truck_no != null && $Request->truck_no != "null"){
                $ship->trucknumber = $Request->truck_no;
                $ship->status = 1; 
                }
                $ship->transporter = $Request->transporter_id;
                $ship->save();
                
                $data->shipment_no = $Request->shipment_no;
                $data->shipment_id = $ship->id;
                $data->transporter_id = $Request->transporter_id;
                $data->name = $tras->name;
                $data->truck_no = $Request->truck_no;
                $data->created_by = $Request->user_id;
                $data->save();


              return response()->json(['status' => 'success','message' => 'Shipment Transporter Addedd Successfully.','data' => $data,'code' => '200'],200); 


             }  catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function ShipmentTransporterDelete (Request $Request)  {

         try {

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                
                $data =Shipment_Transporter::findorfail($Request->id);
                $data->deleted_by = $Request->user_id;
                $data->save();
                $data->delete();

             return response()->json(['status' => 'success','message' => 'Shipment Transporter Deleted Successfully.','data' => json_decode('{}'),'code' => '200'],200); 

             }  catch(\Exception $e){
                        
            return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function ShipmentDriverList (Request $Request)  {

         try {

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }


                $data1 = Shipment_Driver::where('shipment_no', $Request->shipment_no)->get();

                $data = array();
                $data2 = array();

                foreach ($data1 as $key => $value) {

                    $data[$key]=$value;

                    $tras = Driver::withTrashed()->findorfail($value->transporter_id);

                    $data[$key]['name']= $tras->name;

                }

                $data2 = Driver::where('transporter_id',$Request->other_id)->get();  

                $main_data['list'] =$data;
                $main_data['drivers'] =$data2; 

                return response()->json(['status' => 'success','message' => 'Shipment Driver List Successfully.','data' => $main_data,'code' => '200'],200); 


             }  catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function ShipmentDriverSave (Request $Request)  {

         try {

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $ship =Shipment::where('shipment_no',$Request->shipment_no)->first();    

                if($Request->truck_no != "" && $Request->truck_no != null && $Request->truck_no != "null"){
                $ship->trucknumber = $Request->truck_no;
                $ship->status = 1; 
                $ship->save();
                }
                
                $data =new Shipment_Driver();
                $data->mobile = $Request->mobile;
                $data->truck_no = $Request->truck_no;
                $data->shipment_no = $Request->shipment_no;
                $data->transporter_id = $Request->other_id;
                $data->created_by = $Request->user_id;
                $data->save();


              return response()->json(['status' => 'success','message' => 'Shipment Driver Addedd Successfully.','data' => $data,'code' => '200'],200); 

             }  catch(\Exception $e){
                        
                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


    public function ShipmentDriverDelete (Request $Request)  {

         try {

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                
                $data =Shipment_Driver::findorfail($Request->id);
                $data->deleted_by = $Request->user_id;
                $data->save();
                $data->delete();

             return response()->json(['status' => 'success','message' => 'Shipment Driver Deleted Successfully.','data' => json_decode('{}'),'code' => '200'],200); 

             }  catch(\Exception $e){
                        
            return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

     public function ExpenseAdd (Request $Request)  {

         try {

                $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $expense = new Expense();
                $expense->transporter_id = $Request->transporter_id;
                $expense->reason = $Request->reason;
                $expense->amount = $Request->amount;
                $expense->shipment_no = $Request->shipment_no;
                $expense->created_by = $Request->user_id;
                $expense->save();

              return response()->json(['status' => 'success','message' => 'Expense Addedd Successfully.','data' => $expense,'code' => '200'],200); 


                 }  catch(\Exception $e){
                        
                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function ShipmentpendingList (Request $Request)  {

         try {

             $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                if($Request->role == "admin"){

                    $data1= Shipment::where('status',0)->get();

                        $data= array();

                        foreach ($data1 as $key => $value) {

                            $data[$key]=$value;

                            $com = Company::withTrashed()->findorfail($value->company);
                            $data[$key]['company'] = $com->name;
                            $forw = Forwarder::withTrashed()->findorfail($value->forwarder);
                            $data[$key]['forwarder'] = $forw->name;
                            $tk = Truck::withTrashed()->findorfail($value->trucktype);
                            $data[$key]['vehicle'] = $tk->name;

                        
                        }

                }

                if($Request->role == "transporter"){

                    $data1= Shipment::where('status',0)->where('transporter',$other_id)->get();

                        $data= array();

                        foreach ($data1 as $key => $value) {

                            $data[$key]=$value;

                            $com = Company::withTrashed()->findorfail($value->company);
                            $data[$key]['company'] = $com->name;
                            $forw = Forwarder::withTrashed()->findorfail($value->forwarder);
                            $data[$key]['forwarder'] = $forw->name;
                            $tk = Truck::withTrashed()->findorfail($value->trucktype);
                            $data[$key]['vehicle'] = $tk->name;
                        }

                }


                if($Request->role == "forwarder"){

                    $data1= Shipment::where('status',0)->where('forwarder',$other_id)->get();

                        $data= array();

                        foreach ($data1 as $key => $value) {

                            $data[$key]=$value;

                            $com = Company::withTrashed()->findorfail($value->company);
                            $data[$key]['company'] = $com->name;
                            $forw = Forwarder::withTrashed()->findorfail($value->forwarder);
                            $data[$key]['forwarder'] = $forw->name;
                            $tk = Truck::withTrashed()->findorfail($value->trucktype);
                            $data[$key]['vehicle'] = $tk->name;
                        }

                }

                 return response()->json(['status' => 'success','message' => 'Shipment List Successfully.','data' => $data,'code' => '200'],200); 

                 }  catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

     public function ShipmentOnTheWayList (Request $Request)  {

         try {

             $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                if($Request->role == "admin"){

                    $data1= Shipment::where('status',1)->get();

                        $data= array();

                        foreach ($data1 as $key => $value) {

                            $data[$key]=$value;

                            $com = Company::withTrashed()->findorfail($value->company);
                            $data[$key]['company'] = $com->name;
                            $forw = Forwarder::withTrashed()->findorfail($value->forwarder);
                            $data[$key]['forwarder'] = $forw->name;
                            $tk = Truck::withTrashed()->findorfail($value->trucktype);
                            $data[$key]['vehicle'] = $tk->name;

                        
                        }

                }

                if($Request->role == "transporter"){

                    $data1= Shipment::where('status',1)->where('transporter',$other_id)->get();

                        $data= array();

                        foreach ($data1 as $key => $value) {

                            $data[$key]=$value;

                            $com = Company::withTrashed()->findorfail($value->company);
                            $data[$key]['company'] = $com->name;
                            $forw = Forwarder::withTrashed()->findorfail($value->forwarder);
                            $data[$key]['forwarder'] = $forw->name;
                            $tk = Truck::withTrashed()->findorfail($value->trucktype);
                            $data[$key]['vehicle'] = $tk->name;
                        }

                }


                 if($Request->role == "forwarder"){

                    $data1= Shipment::where('status',1)->where('forwarder',$other_id)->get();

                        $data= array();

                        foreach ($data1 as $key => $value) {

                            $data[$key]=$value;

                            $com = Company::withTrashed()->findorfail($value->company);
                            $data[$key]['company'] = $com->name;
                            $forw = Forwarder::withTrashed()->findorfail($value->forwarder);
                            $data[$key]['forwarder'] = $forw->name;
                            $tk = Truck::withTrashed()->findorfail($value->trucktype);
                            $data[$key]['vehicle'] = $tk->name;
                        }

                }

                 return response()->json(['status' => 'success','message' => 'Shipment List Successfully.','data' => $data,'code' => '200'],200); 

                 }  catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    } 



     public function ShipmentDeliveryList (Request $Request)  {

         try {

             $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                if($Request->role == "admin"){

                    $data1= Shipment::where('status',2)->get();

                        $data= array();

                        foreach ($data1 as $key => $value) {

                            $data[$key]=$value;

                            $com = Company::withTrashed()->findorfail($value->company);
                            $data[$key]['company'] = $com->name;
                            $forw = Forwarder::withTrashed()->findorfail($value->forwarder);
                            $data[$key]['forwarder'] = $forw->name;
                            $tk = Truck::withTrashed()->findorfail($value->trucktype);
                            $data[$key]['vehicle'] = $tk->name;

                        
                        }

                }

                if($Request->role == "transporter"){

                    $data1= Shipment::where('status',2)->where('transporter',$other_id)->get();

                        $data= array();

                        foreach ($data1 as $key => $value) {

                            $data[$key]=$value;

                            $com = Company::withTrashed()->findorfail($value->company);
                            $data[$key]['company'] = $com->name;
                            $forw = Forwarder::withTrashed()->findorfail($value->forwarder);
                            $data[$key]['forwarder'] = $forw->name;
                            $tk = Truck::withTrashed()->findorfail($value->trucktype);
                            $data[$key]['vehicle'] = $tk->name;
                        }

                }

                 if($Request->role == "forwarder"){

                    $data1= Shipment::where('status',3)->where('forwarder',$other_id)->get();

                        $data= array();

                        foreach ($data1 as $key => $value) {

                            $data[$key]=$value;

                            $com = Company::withTrashed()->findorfail($value->company);
                            $data[$key]['company'] = $com->name;
                            $forw = Forwarder::withTrashed()->findorfail($value->forwarder);
                            $data[$key]['forwarder'] = $forw->name;
                            $tk = Truck::withTrashed()->findorfail($value->trucktype);
                            $data[$key]['vehicle'] = $tk->name;
                        }

                }

                 return response()->json(['status' => 'success','message' => 'Shipment List Successfully.','data' => $data,'code' => '200'],200); 

                 }  catch(\Exception $e){
                        

                        return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }   

    public function ShipmentDetail (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Shipment::withTrashed()->where('shipment_no',$Request->shipment_no)->first();

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
                $tras_list =Shipment_Transporter::withTrashed()->where('shipment_no',$Request->shipment_no)->get();
                $t_list = "";
                foreach ($tras_list as $key => $value) { 
                     if($key == 0) {
                        $t_list = $t_list."".$value->name; 
                    } else {

                        $t_list = $t_list.",".$value->name; 
                     }
                }

                $data->transporters_list =  $t_list;

                 $driver_list =Shipment_Driver::withTrashed()->where('shipment_no',$Request->shipment_no)->get();
                $d_list = "";

                foreach ($driver_list as $key2 => $value2) { 
                    if($key2 == 0) {
                         $d_list = $d_list."".$value2->truck_no."(".$value2->mobile.")"; 

                    } else {
                         $d_list = $d_list.",".$value2->truck_no."(".$value2->mobile.")"; 

                    }

                }

                $data->truck_no = $d_list;

                 return response()->json(['status' => 'success','message' => 'Shipment Detail Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){
                        
                      return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function ShipmentChangeStatusAdmin (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Shipment::where('shipment_no',$Request->shipment_no)->first();
                $data->cargo_status = $Request->status;
                $data->status_reason = $Request->reason;
                $data->updated_by = $Request->user_id;
                $data->save();

                 return response()->json(['status' => 'success','message' => 'Shipment Status Changed Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){
                        
                      return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function ShipmentChangeStatusTransporter (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Shipment::where('shipment_no',$Request->shipment_no)->first();

                $data->cargo_status = $Request->status;

               $path = public_path('/uploads');

               if($Request->status == "Loaded"){

                if($Request->hasFile('image') && !empty($Request->file('image'))){
                    $file_name = time().$Request->image->getClientOriginalName();
                    $Request->image->move($path,$file_name);
                    $data->loaded_photo = $file_name;
                }

               }

               if($Request->status == "Unloaded"){

                if($Request->hasFile('image') && !empty($Request->file('image'))){
                    $file_name = time().$Request->image->getClientOriginalName();
                    $Request->image->move($path,$file_name);
                    $data->unloaded_photo = $file_name;
                }

               }

                $data->updated_by = $Request->user_id;

                $data->save();

                 return response()->json(['status' => 'success','message' => 'Shipment Status Changed Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){
                        
                      return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }




    public function ShipmentFormEdit (Request $Request)  {

         try {

             $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }


                $data = Shipment::where('shipment_no',$Request->shipment_no)->first();

                $data->date = $Request->date;

                $data->company = $Request->company_id;

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

                $data->from1 = $Request->from;

                $data->to1 = $Request->to1;

                $data->to2 = $Request->to2;

                $data->trucktype = $Request->truck_type;

                $data->trucknumber = $Request->truck_no;


             if($Request->truck_no != $data->truck_no && $Request->truck_no != "null" && $Request->truck_no != null){

                $data->status = 1;

                }

                $data->forwarder = $Request->forwarder_id;

                $data->consignor = $Request->consignor;

                $data->consignor_address = $Request->consignor_address;

                $data->consignee = $Request->consignee;

                $data->consignee_address = $Request->consignee_address;

                $data->package = $Request->no_package;

                $data->description= $Request->cargo_desc;

                $data->weight = $Request->total_weight;

                $data->shipper_invoice = $Request->shipper_invoice_no;

                $data->forwarder_ref_no = $Request->forwarder_ref_no;

                $data->b_e_no = $Request->be_no;

                if($Request->type2 == "fcl"){

                $data->container_type = $Request->container_type;

                $data->destuffing_date = $Request->de_stuffing_date;

                $data->container_no = $Request->container_no;

                $data->shipping_line = $Request->shipping_line;

                $data->cha = $Request->cha;

                $data->seal_no = $Request->seal_no;

                $data->pod = $Request->pod;

                }

                $data->invoice_amount = $Request->invoice_amount;

                $data->remark = $Request->remark;

                $data->transporter = $Request->transporter_id;

                $data->save();

                $tra= Transporter::withTrashed()->findorfail($Request->transporter_id);

                if($Request->transporter_id != $data->transporter_id && $Request->transporter_id != null && $Request->transporter_id != '' && $Request->transporter_id != 'null'){

                    $transs = new Shipment_Transporter();
                    $transs->shipment_no = $Request->shipment_no;
                    $transs->shipment_id = $data->id;
                    $transs->transporter_id = $Request->transporter_id;
                    $transs->name = $tra->transporter_name;
                    $transs->created_by = $Request->user_id;  
                    $transs->save();                
                }


                if($Request->truck_no != $data->truck_no && $Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null'){
                    
                    $driver = new Shipment_Driver();
                    $driver->shipment_no = $Request->shipment_no;
                    $driver->truck_no = $Request->truck_no;
                    $driver->mobile = $tra->transporter_mobile;
                    $driver->created_by = $Request->user_id;  
                    $driver->save();                
                }

                $data1= Shipment::findorfail($data->id);

                 return response()->json(['status' => 'success','message' => 'Shipment Edited Successfully.','data' => $data1,'code' => '200'],200); 

            }  catch(\Exception $e){
                        
                return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }


    public function ShipmentFormDelete (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Shipment::where('shipment_no',$Request->shipment_no)->first();

                $data->deleted_by= $Request->user_id;

                $data->save();

                $data->delete();

                 return response()->json(['status' => 'success','message' => 'Shipment Deleted Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){
                        
                      return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

    public function ShipmentAmountUpdate (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                $data = Shipment::where('shipment_no',$Request->shipment_no)->first();
                $data->invoice_amount = $Request->amount;
                $data->updated_by = $Request->user_id;
                $data->save();

                 return response()->json(['status' => 'success','message' => 'Invoice Amount Successfully Updated.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){
                        
                      return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }

}


/*


 public function CompanyList (Request $Request)  {

         try { 

            $check= $this->checkversion($Request->version);

                if($check == 1){

                     return response()->json(['status' => 'failed','message' => 'Please update this application.','data' => json_decode('{}'),'code' => '500'],200);  
                }

                 return response()->json(['status' => 'success','message' => 'Login Successfully.','data' => $data,'code' => '200'],200);

         }catch(\Exception $e){
                        
                      return response()->json(['status' => 'failed','message' => $e->getMessage(),'data' => json_decode('{}'),'code' => '500'],200);
                 }

    }



$path = public_path('/uploads');
                    if($Request->hasFile('image') && !empty($Request->file('image'))){
                        $file_name = time().$Request->image->getClientOriginalName();
                        $Request->image->move($path,$file_name);
                        $user->image = $file_name;
                    }

                     if($Request->hasFile('coverimage') && !empty($Request->file('coverimage'))){
                        $file_name = time().$Request->coverimage->getClientOriginalName();
                        $Request->coverimage->move($path,$file_name);
                        $user->coverimage = $file_name;
                    }
*/