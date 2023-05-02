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



class ForwarderController extends Controller
{

	public function __construct()
    {
       
    }


    public function List(Request $Request)
    {	

        $data = Forwarder::all();
    	
    	return view('admin.forwarderlist',compact('data'));
    }


    public function ADD(Request $Request)
    {
        
        return view('admin.forwarderadd');

    }


     public function Save(Request $Request)
    {

        $this->validate($Request, [
        'name' => 'required',
        'phone' => 'required|numeric',
        'address' => 'required',
        'email'=>'required|email',
        'gst'=>'required',
        'username'=>'required',
        'password'=>'required',       
         ],[
         'name.required' => "Please Enter Name",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'address.required' => "Please Enter Address",
         'email.required' => "Please Enter Email-ID",
         'email.email' => "Please Enter Valid Email-ID",
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
                
                $user->role = "forwarder";
                
                $user->created_by=Auth::user()->id;
                
                $user->save();

                $comapny = new Forwarder();

                $comapny->user_id = $user->id;

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->email= $Request->email;

                $comapny->gst_no= $Request->gst;

                $comapny->address= $Request->address;

                $comapny->created_by=Auth::user()->id;

                $comapny->myid= uniqid();

                $comapny->status= $Request->status;

                 if($comapny->save()){

                     return redirect()->route('forwarderlist')->with('success','Transporter Addedd successfully.');

                }

    }

     public function Edit(Request $Request)
    {

        $data = Forwarder::where('myid',$Request->id)->first();

        return view('admin.forwarderedit',compact('data'));

    }



       public function Update(Request $Request)
    {

        $this->validate($Request, [
        'name' => 'required',
        'phone' => 'required|numeric',
        'address' => 'required',
        'email'=>'required|email',
        'gst'=>'required',
        ],[
         'name.required' => "Please Enter Name",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'address.required' => "Please Enter Address",
         'email.required' => "Please Enter Email-ID",
         'email.email' => "Please Enter Valid Email-ID",
        ]);


                
                $comapny = Forwarder::findorfail($Request->id);

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->email= $Request->email;

                $comapny->gst_no= $Request->gst;

                $comapny->address= $Request->address;

                $comapny->updated_by=Auth::user()->id;

                $comapny->status= $Request->status;

                if($comapny->save()){

                     return redirect()->route('forwarderlist')->with('success','Forwarder Updated successfully.');
                }
    }


     public function Delete(Request $Request)
    {

        $data = Forwarder::where('myid',$Request->id)->first();
        $data->deleted_by = Auth::user()->id;
        $data->save();

        $user = User::findorfail($data->user_id);
        $user->deleted_by = Auth::user()->id;
        $user->save();

        $user->delete();
        $data->delete();

        return redirect()->route('forwarderlist')->with('success','Forwarder Deleted Successfully.');



    }


   
  	





}