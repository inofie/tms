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
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;
use Config;



class DriverController extends Controller
{

	public function __construct()
    {
       
    }



    public function List(Request $Request)
    {   
        $data= array();
        $ff= Transporter::where('user_id',Auth::user()->id)->first();
        $data1 = Driver::where('transporter_id',$ff->id)->get();

        foreach ($data1 as $key => $value) {

            $data[$key]=$value;
            $transporter = Transporter::withTrashed()->findorfail($value->transporter_id);
            $data[$key]->transporter_name=$transporter->name;
        
        }

        return view('transporter.driverlist',compact('data'));
    }


    public function ADD(Request $Request)
    {
        $transporter = Transporter::all();
         return view('transporter.driveradd',compact('transporter'));
    }


     public function Save(Request $Request)
    {

        $this->validate($Request, [
           
        'name' => 'required',
        'phone' => 'required|numeric|digits:10|unique:driver,phone',
        'licence_no' => 'required',
        'truck_no'=>'required',
        'pan'=>'required',
        'password' => 'required|min:8',
        'rc_book'=>'required|mimes:jpeg,jpg,png|max:10240',
        'pan_card'=>'required|mimes:jpeg,jpg,png|max:10240',
        'licence'=>'required|mimes:jpeg,jpg,png|max:10240',
       ],[
         'transporter.required' => "Please Select Transporter",
         'password.required' => "Please Enter Password",
         'name.required' => "Please Enter Name",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'licence_no.required' => "Please Enter Licence Number",
         'truck_no.required' => "Please Enter Truck Number",
         'pan.required' => "Please Enter PAN Number",
         'rc_book.required' => "Please Upload RC Book Document",
         'rc_book.mimes' => "Please Upload RC Book Document File extension .jpg, .png, .jpeg",
         'pan_card.required' => "Please Upload PAN Card Document",
         'pan_card.mimes' => "Please Upload PAN Card document File extension .jpg, .png, .jpeg",
         'licence.required' => "Please Upload Licence Document",
         'licence.mimes' => "Please Upload Licence Document File extension .jpg, .png, .jpeg",
         ]);
    
                $ff= Transporter::where('user_id',Auth::user()->id)->first();
                $comapny = new Driver();

                $comapny->transporter_id = $ff->id;

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->truck_no= $Request->truck_no;

                $comapny->licence_no= $Request->licence_no;

                $comapny->pan = $Request->pan;

                $comapny->created_by=Auth::user()->id;

                $comapny->myid= uniqid();

                $comapny->status= $Request->status;

                $comapny->password=Hash::make($Request->password);  

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

                     return redirect()->route('transporterdriverlist')->with('success','Driver Added successfully.');

                }



    }

     public function Edit(Request $Request)
    {

        $data = Driver::where('myid',$Request->id)->first();

        $transporter = Transporter::all();
        
        return view('transporter.driveredit',compact('data','transporter'));

    }



       public function Update(Request $Request)
    {

        $this->validate($Request, [
           
        'name' => 'required',
        'phone' => 'required|numeric|digits:10',
        'licence_no' => 'required',
        'truck_no'=>'required',
        'pan'=>'required',
       ],[
         'transporter.required' => "Please Select Transporter",
         'name.required' => "Please Enter Name",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'licence_no.required' => "Please Enter Licence Number",
         'truck_no.required' => "Please Enter Truck Number",
         'pan.required' => "Please Enter PAN Number",
         
         ]);
            $ff= Transporter::where('user_id',Auth::user()->id)->first();
            $comapny = Driver::findorfail($Request->id);

               $comapny->transporter_id = $ff->id;

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->truck_no= $Request->truck_no;

                $comapny->licence_no= $Request->licence_no;

                $comapny->pan = $Request->pan;

                $comapny->created_by=Auth::user()->id;

                $comapny->myid= uniqid();

                $comapny->status= $Request->status;

                if($Request->password != "" && $Request->password != " " && $Request->password != null ){

                    $comapny->password=Hash::make($Request->password);  
                }


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

                     return redirect()->route('transporterdriverlist')->with('success','Driver Updated successfully.');

                }
    }


     public function Delete(Request $Request)
    {
        $data = Driver::where('myid',$Request->id)->first();
        $data->deleted_by =Auth::user()->id;
        $data->save();
        $data->delete();
        return redirect()->route('transporterdriverlist')->with('success','Driver deleted successfully.');

    }



}