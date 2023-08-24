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
use App\DataTables\WarehouseDataTable;


class WarehouseController extends Controller
{

	public function __construct()
    {

    }

    // public function List(Request $Request)
    // {
    //     $data= array();
    //     $data1 = Warehouse::all();

    //     foreach ($data1 as $key => $value) {

    //         $data[$key]=$value;
    //         $company = Company::withTrashed()->findorfail($value->company_id);
    //         $data[$key]->company_name=$company->name;
    //         $username = User::withTrashed()->findorfail($value->user_id);
    //         $data[$key]->user_name=$username->username;

    //         # code...
    //     }

    // 	return view('admin.warehouselist',compact('data'));
    // }

    public function List(Builder $builder, DriverDataTable $dataTable)
    {
        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name','title' => 'Full Name'],
            ['data' => 'phone', 'name' => 'phone','title' => 'User Name'],
            ['data' => 'licence_no', 'name' => 'licence_no','title' => 'Company'],
            ['data' => 'truck_no', 'name' => 'truck_no','title' => 'Address'],
            ['data' => 'pan', 'name' => 'pan','title' => 'Add Proof'],
            ['data' => 'rc_book', 'name' => 'rc_book','title' => 'Phone Number'],
            ['data' => 'pan_card', 'name' => 'pan_card','title' => 'GST Number'],
            ['data' => 'licence', 'name' => 'licence','title' => 'Pan Number'],
            ['data' => 'status', 'name' => 'status','title' => 'Status'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],
        ])
        ->parameters([
            "scrollX" => true,
            "order"=> [[ 0, "desc" ]],
            "processing"=> false,
          ]);
        if(request()->ajax()) {
            $result = Warehouse::all();
            return $dataTable->dataTable($result)->toJson();
        }
        return view('admin.warehouselist', compact('html'));
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
        'address_proof'=>'required|mimes:jpeg,jpg,png|max:5120',
        'password'=>'required|min:8',
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
                $data = User::where('username',$Request->username)->count();

                if($data > 0){

                    return redirect()->back()->withInput()->with('error','This Username Allready Registred in Our System.');
                }


                $user = new User();

                $user->name = $Request->name;

                $user->username = $Request->username;

                $user->password = Hash::make($Request->password);

                $user->role = "warehouse";

                $user->created_by=Auth::user()->id;

                $user->save();

                $comapny = new Warehouse();

                $comapny->name = $Request->name;
                $comapny->user_id = $user->id;
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

                     return redirect()->route('warehouselist')->with('success','Warehouse Added successfully.');

                }

    }

     public function Edit(Request $Request)
    {

        $data = Warehouse::where('myid',$Request->id)->first();
        $company = Company::all();
        $user = User::where('id',$data->user_id)->first();
        return view('admin.warehouseedit',compact('data','company','user'));

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
        'address_proof'=>'mimes:jpeg,jpg,png|max:5120',
       ],[
         'name.required' => "Please Enter Name",
         'address.required' => "Please Enter Address",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'company.required' => "Please Enter Company Name.",
         'gst.required' => "Please Enter GST Number",
         'pan.required' => "Please Enter PAN Number",
         'address_proof.mimes' => "Please Upload Address Proof document File extension .jpg, .png, .jpeg",
          ]);
            if($Request->username != $Request->oldusername){

                $data = User::where('username',$Request->username)->where('id','!=',$Request->user_id)->count();

            if($data > 0){

                return redirect()->back()->withInput()->with('error','This Username Allready Registred in Our System.');
            }


                    $user = User::findorfail($Request->user_id);

                    $user->username = $Request->username;

                    $user->save();

            }
                if($Request->password != '' && $Request->password != null){


                    $user = User::findorfail($Request->user_id);

                    $user->password = Hash::make($Request->password);

                    $user->save();

            }
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