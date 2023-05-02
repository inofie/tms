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
use Config;


class WarehouseController extends Controller
{

	public function __construct()
    {
       
    }

    public function List(Request $Request)
    {	
        $data= array();
        $data1 = Warehouse::all();

        foreach ($data1 as $key => $value) {

            $data[$key]=$value;
            $company = Company::findorfail($value->company_id);
            $data[$key]->company_name=$company->name;

            # code...
        }

    	return view('admin.warehouselist',compact('data'));
    }


    public function ADD(Request $Request)
    {
        $company = Company::all();
        return view('admin.warehouseadd',compact('company'));
    }


     public function Save(Request $Request)
    {

        $this->validate($Request, [
        'name' => 'required',
        'phone' => 'required|numeric',
        'address' => 'required',
        'company'=>'required',
        'gst'=>'required',
        'pan'=>'required',
        'address_proof'=>'required|mimes:jpeg,jpg,png',
       ],[
         'name.required' => "Please Enter Name",
         'address.required' => "Please Enter Address",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'company.required' => "Please Enter Company Name.",
         'gst.required' => "Please Enter GST Number",
         'pan.required' => "Please Enter PAN Number",
         'address_proof.required' => "Please Upload Address Proof Document",
         'address_proof.mimes' => "Please Upload Address Proof document File extension .jpg, .png, .jpeg",
          ]);
        
               
                $comapny = new Warehouse();

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->address = $Request->address;

                $comapny->gst= $Request->gst;

                $comapny->pan= $Request->pan;

                $comapny->company_id= $Request->company;

                $comapny->created_by=Auth::user()->id;

                $comapny->myid= uniqid();

                $comapny->status= $Request->status;

                 $path = public_path('/uploads');
                    
                 if($Request->hasFile('address_proof') && !empty($Request->file('address_proof'))){
                        $file_name = time()."1".$Request->address_proof->getClientOriginalName();
                        $Request->address_proof->move($path,$file_name);
                        $comapny->address_proof = $file_name;
                 }

                 if($comapny->save()){

                     return redirect()->route('warehouselist')->with('success','Warehouse Addedd successfully.');

                }

    }

     public function Edit(Request $Request)
    {

        $data = Warehouse::where('myid',$Request->id)->first();
        $company = Company::all();

        return view('admin.warehouseedit',compact('data','company'));

    }



       public function Update(Request $Request)
    {

        $this->validate($Request, [
        'name' => 'required',
        'phone' => 'required|numeric',
        'address' => 'required',
        'company'=>'required',
        'gst'=>'required',
        'pan'=>'required',
        'address_proof'=>'required|mimes:jpeg,jpg,png',
       ],[
         'name.required' => "Please Enter Name",
         'address.required' => "Please Enter Address",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'company.required' => "Please Enter Company Name.",
         'gst.required' => "Please Enter GST Number",
         'pan.required' => "Please Enter PAN Number",
         'address_proof.required' => "Please Upload Address Proof Document",
         'address_proof.mimes' => "Please Upload Address Proof document File extension .jpg, .png, .jpeg",
          ]);

                
                $comapny = Warehouse::findorfail($Request->id);

                $comapny->name = $Request->name;

                $comapny->phone = $Request->phone;

                $comapny->address = $Request->address;

                $comapny->gst= $Request->gst;

                $comapny->pan= $Request->pan;

                $comapny->company_id= $Request->company;

                $comapny->created_by=Auth::user()->id;

                $comapny->myid= uniqid();

                $comapny->status= $Request->status;

                 $path = public_path('/uploads');
                    
                 if($Request->hasFile('address_proof') && !empty($Request->file('address_proof'))){
                        $file_name = time()."1".$Request->address_proof->getClientOriginalName();
                        $Request->address_proof->move($path,$file_name);
                        $comapny->address_proof = $file_name;
                 }

                 if($comapny->save()){

                     return redirect()->route('warehouselist')->with('success','Warehouse Updated successfully.');

                }



    }


     public function Delete(Request $Request)
    {

        $data = Warehouse::where('myid',$Request->id)->first();
        $data->deleted_by = Auth::user()->id;
        $data->save();
        $data->delete();
        return redirect()->route('warehouselist')->with('success','Warehouse Deleted Successfully.');

    }


   
  	





}