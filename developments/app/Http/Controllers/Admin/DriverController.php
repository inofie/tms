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
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;



class DriverController extends Controller
{

	public function __construct()
    {
       
    }



    public function List(Request $Request)
    {   
        $data= array();
        $data1 = Driver::all();

        foreach ($data1 as $key => $value) {

            $data[$key]=$value;
            $transporter = Transporter::findorfail($value->transporter_id);
            $data[$key]->transporter_name=$transporter->name;
        
        }

        return view('admin.driverlist',compact('data'));
    }


    public function ADD(Request $Request)
    {
        $transporter = Transporter::all();
         return view('admin.driveradd',compact('transporter'));
    }


     public function Save(Request $Request)
    {

        $this->validate($Request, [
        'transporter' => 'required',    
        'name' => 'required',
        'phone' => 'required|numeric',
        'licence_no' => 'required',
        'truck_no'=>'required',
        'pan'=>'required',
        'rc_book'=>'required|mimes:jpeg,jpg,png',
        'pan_card'=>'required|mimes:jpeg,jpg,png',
        'licence'=>'required|mimes:jpeg,jpg,png',
       ],[
         'transporter.required' => "Please Select Transporter",
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



        

                $comapny = new Driver();

                $comapny->transporter_id = $Request->transporter;

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->truck_no= $Request->truck_no;

                $comapny->licence_no= $Request->licence_no;

                $comapny->pan = $Request->pan;

                $comapny->created_by=Auth::user()->id;

                $comapny->myid= uniqid();

                $comapny->status= $Request->status;

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

                     return redirect()->route('driverlist')->with('success','Transporter Addedd successfully.');

                }



    }

     public function Edit(Request $Request)
    {

        $data = Driver::where('myid',$Request->id)->first();

        $transporter = Transporter::all();
        
        return view('admin.driveredit',compact('data','transporter'));

    }



       public function Update(Request $Request)
    {

        $this->validate($Request, [
        'transporter' => 'required',    
        'name' => 'required',
        'phone' => 'required|numeric',
        'licence_no' => 'required',
        'truck_no'=>'required',
        'pan'=>'required',
        'rc_book'=>'required|mimes:jpeg,jpg,png',
        'pan_card'=>'required|mimes:jpeg,jpg,png',
        'licence'=>'required|mimes:jpeg,jpg,png',
       ],[
         'transporter.required' => "Please Select Transporter",
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
                
                $comapny = Driver::findorfail($Request->id);

               $comapny->transporter_id = $Request->transporter;

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->truck_no= $Request->truck_no;

                $comapny->licence_no= $Request->licence_no;

                $comapny->pan = $Request->pan;

                $comapny->created_by=Auth::user()->id;

                $comapny->myid= uniqid();

                $comapny->status= $Request->status;

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

                     return redirect()->route('driverlist')->with('success','Transporter Updated successfully.');

                }
    }


     public function Delete(Request $Request)
    {

        $data = Driver::where('myid',$Request->id)->first();
        $data->deleted_by =Auth::user()->id;
        $data->save();
        $data->delete();
        return redirect()->route('driverlist')->with('success','Transporter deleted successfully.');

    }


   
  	





}