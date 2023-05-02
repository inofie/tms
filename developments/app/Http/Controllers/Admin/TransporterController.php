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



class TransporterController extends Controller
{

	public function __construct()
    {
       
    }



    public function List(Request $Request)
    {	

        $data = Transporter::all();
    	
    	return view('admin.transportlist',compact('data'));
    }


    public function ADD(Request $Request)
    {
        
        return view('admin.transportadd');

    }


     public function Save(Request $Request)
    {

        $this->validate($Request, [
        'name' => 'required',
        'phone' => 'required|numeric',
        'licence_no' => 'required',
        'truck_no'=>'required',
        'pan'=>'required',
        'rc_book'=>'required|mimes:jpeg,jpg,png',
        'pan_card'=>'required|mimes:jpeg,jpg,png',
        'licence'=>'required|mimes:jpeg,jpg,png',
        'username'=>'required',
        'password'=>'required',       
         ],[
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
         'username.required' => "Please Enter Username",
         'password.required' => "Please Enter Password",
         ]);



         $data = User::where('username',$Request->username)->count();
                
        if($data > 0){

            return redirect()->back()->withInput()->with('error','This Username Allready Registred in Our System.');
        }


                $user = new User();
                
                $user->name = $Request->name;
                
                $user->username = $Request->username;
                
                $user->password = Hash::make($Request->password);
                
                $user->role = "transporter";
                
                $user->created_by=Auth::user()->id;
                
                $user->save();

                $comapny = new Transporter();

                $comapny->user_id = $user->id;

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

                     return redirect()->route('transporterlist')->with('success','Transporter Addedd successfully.');

                }



    }

     public function Edit(Request $Request)
    {

        $data = Transporter::where('myid',$Request->id)->first();

        return view('admin.transportedit',compact('data'));

    }



       public function Update(Request $Request)
    {

        $this->validate($Request, [
        'name' => 'required',
        'phone' => 'required|numeric',
        'licence_no' => 'required',
        'truck_no'=>'required',
        'pan'=>'required',
        'rc_book'=>'mimes:jpeg,jpg,png',
        'pan_card'=>'mimes:jpeg,jpg,png',
        'licence'=>'mimes:jpeg,jpg,png',
         ],[
         'name.required' => "Please Enter Name",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'licence_no.required' => "Please Enter Licence Number",
         'truck_no.required' => "Please Enter Truck Number",
         'pan.required' => "Please Enter PAN Number",
         'rc_book.mimes' => "Please Upload RC Book Document File extension .jpg, .png, .jpeg",
         'pan_card.mimes' => "Please Upload PAN Card document File extension .jpg, .png, .jpeg",
         'licence.mimes' => "Please Upload Licence Document File extension .jpg, .png, .jpeg",
         ]);

                
                $comapny = Transporter::findorfail($Request->id);

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->truck_no= $Request->truck_no;

                $comapny->licence_no= $Request->licence_no;

                $comapny->pan = $Request->pan;

                $comapny->updated_by=Auth::user()->id;

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

                     return redirect()->route('transporterlist')->with('success','Transporter Updated successfully.');

                }



    }


     public function Delete(Request $Request)
    {

        $data = Transporter::where('myid',$Request->id)->first();
        $data->delete();

        $user = User::findorfail($data->user_id);
        $user->delete();

        return redirect()->route('transporterlist')->with('success','Transporter deleted successfully.');



    }


   
  	





}