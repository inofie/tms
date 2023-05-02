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



class CompanyController extends Controller
{

	public function __construct()
    {
       
    }

    public function List(Request $Request)
    {	
        
        $data = Company::all();
    	return view('admin.companylist',compact('data'));
    }


    public function ADD(Request $Request)
    {
        
        return view('admin.companyadd');
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
        'logo'=>'required|mimes:jpeg,jpg,png',

         ],[
         'name.required' => "Please Enter Name",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'address.required' => "Please Enter Address",
         'email.required' => "Please Enter Email-ID",
         'email.email' => "Please Enter Valid Email-ID",
         'logo.required' => "Please Upload Logo",
         'logo.mimes' => "Please Upload Logo File extension .jpg, .png, .jpeg",
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
                
                $user->role = "admin";
                
                $user->created_by=Auth::user()->id;
                
                $user->save();

                $comapny = new Company();

                $comapny->user_id = $user->id;

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->email= $Request->email;

                $comapny->gst_no= $Request->gst;

                $comapny->address= $Request->address;

                $comapny->created_by=Auth::user()->id;

                $comapny->myid= uniqid();

                $comapny->status= $Request->status;

                 $path = public_path('/uploads');
                    
                 if($Request->hasFile('logo') && !empty($Request->file('logo'))){
                        $file_name = time()."1".$Request->logo->getClientOriginalName();
                        $Request->logo->move($path,$file_name);
                        $comapny->logo = $file_name;
                 }

                 if($comapny->save()){

                     return redirect()->route('companylist')->with('success','Company Addedd successfully.');

                }



    }

     public function Edit(Request $Request)
    {

        $data = Company::where('myid',$Request->id)->first();

        return view('admin.companyedit',compact('data'));

    }



       public function Update(Request $Request)
    {

        $this->validate($Request, [
        'name' => 'required',
        'phone' => 'required|numeric',
        'address' => 'required',
        'email'=>'required|email',
        'gst'=>'required',
        'logo'=>'required|mimes:jpeg,jpg,png',

         ],[
         'name.required' => "Please Enter Name",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'address.required' => "Please Enter Address",
         'email.required' => "Please Enter Email-ID",
         'email.email' => "Please Enter Valid Email-ID",
         'logo.required' => "Please Upload Logo",
         'logo.mimes' => "Please Upload Logo File extension .jpg, .png, .jpeg",
         ]);


                
                $comapny = Company::findorfail($Request->id);

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->email= $Request->email;

                $comapny->gst_no= $Request->gst;

                $comapny->address= $Request->address;

                $comapny->updated_by=Auth::user()->id;

                $comapny->status= $Request->status;

                 $path = public_path('/uploads');
                    
                 if($Request->hasFile('logo') && !empty($Request->file('logo'))){
                        $file_name = time()."1".$Request->logo->getClientOriginalName();
                        $Request->logo->move($path,$file_name);
                        $comapny->logo = $file_name;
                 }

                 if($comapny->save()){

                     return redirect()->route('companylist')->with('success','Company Updated successfully.');

                }



    }


     public function Delete(Request $Request)
    {

        $data = Company::where('myid',$Request->id)->first();
        $data->deleted_by = Auth::user()->id;
        $data->save();

        $user = User::findorfail($data->user_id);
        $user->deleted_by = Auth::user()->id;
        $user->save();

        $data->delete();
        $user->delete();

        return redirect()->route('companylist')->with('success','Company Deleted Successfully.');



    }


   
  	





}