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



class EmployeeController extends Controller
{

	public function __construct()
    {
       
    }

    public function List(Request $Request)
    {	
        $data= array();
        $data1 = Employee::all();

        foreach ($data1 as $key => $value) {

            $data[$key]=$value;
            $company = Company::findorfail($value->company_id);
            $data[$key]->company_name=$company->name;

            # code...
        }

    	return view('admin.employeelist',compact('data'));
    }


    public function ADD(Request $Request)
    {
        $company = Company::all();
        return view('admin.employeeadd',compact('company'));
    }


     public function Save(Request $Request)
    {

        $this->validate($Request, [
        'name' => 'required',
        'phone' => 'required|numeric',
        'email'=>'required|email',
        'address' => 'required',
        'username'=>'required',
        'password'=>'required|min:8',
        'company'=>'required',
        'pan_card'=>'required|mimes:jpeg,jpg,png',

        ],[
         'name.required' => "Please Enter Name",
         'address.required' => "Please Enter Address",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'email.required' => "Please Enter Email-ID",
         'email.email' => "Please Enter Valid Email-ID",
         'company.required' => "Please Enter Company Name.",
         'pan_card.required' => "Please Upload PAN Card Document",
         'pan_card.mimes' => "Please Upload PAN Card document File extension .jpg, .png, .jpeg",
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
                
                $user->role = "employee";
                
                $user->created_by=Auth::user()->id;
                
                $user->save();

                $comapny = new Employee();

                $comapny->user_id = $user->id;

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->email= $Request->email;

                $comapny->company_id= $Request->company;

                $comapny->address= $Request->address;

                $comapny->created_by=Auth::user()->id;

                $comapny->myid= uniqid();

                $comapny->status= $Request->status;

                 $path = public_path('/uploads');
                    
                 if($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))){
                        $file_name = time()."1".$Request->pan_card->getClientOriginalName();
                        $Request->pan_card->move($path,$file_name);
                        $comapny->pan_card = $file_name;
                 }

                 if($comapny->save()){

                     return redirect()->route('employeelist')->with('success','Employee Addedd successfully.');

                }



    }

     public function Edit(Request $Request)
    {

        $data = Employee::where('myid',$Request->id)->first();
        $company = Company::all();

        return view('admin.employeeedit',compact('data','company'));

    }



       public function Update(Request $Request)
    {

        $this->validate($Request, [
        'name' => 'required',
        'phone' => 'required|numeric',
        'email'=>'required|email',
        'address' => 'required',
        'company'=>'required',
        'pan_card'=>'mimes:jpeg,jpg,png',

        ],[
         'name.required' => "Please Enter Name",
         'address.required' => "Please Enter Address",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'email.required' => "Please Enter Email-ID",
         'email.email' => "Please Enter Valid Email-ID",
         'company.required' => "Please Enter Company Name.",
         'pan_card.mimes' => "Please Upload PAN Card document File extension .jpg, .png, .jpeg",
        ]);

                
                $comapny = Employee::findorfail($Request->id);

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->email= $Request->email;

                $comapny->company_id= $Request->company;

                $comapny->address= $Request->address;

                $comapny->updated_by=Auth::user()->id;

                $comapny->status= $Request->status;

                 $path = public_path('/uploads');
                    
                 if($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))){
                        $file_name = time()."1".$Request->pan_card->getClientOriginalName();
                        $Request->pan_card->move($path,$file_name);
                        $comapny->pan_card = $file_name;
                 }
                 if($comapny->save()){

                     return redirect()->route('employeelist')->with('success','Employee Updated successfully.');

                }



    }


     public function Delete(Request $Request)
    {

        $data = Employee::where('myid',$Request->id)->first();
        $data->deleted_by = Auth::user()->id;
        $data->save();

        $user = User::findorfail($data->user_id);
        $user->deleted_by = Auth::user()->id;
        $user->save();

        $data->delete();
        $user->delete();

        return redirect()->route('employeelist')->with('success','Employee Deleted Successfully.');



    }


   
  	





}