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
use Yajra\DataTables\Html\Builder;
use App\DataTables\DriverDataTable;
use Config;



class DriverController extends Controller
{

	public function __construct()
    {

    }

    public function List(Builder $builder, DriverDataTable $dataTable)
    {
        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name','title' => 'Full Name'],
            ['data' => 'phone', 'name' => 'phone','title' => 'Phone Number'],
            ['data' => 'licence_no', 'name' => 'licence_no','title' => 'Licence Number'],
            ['data' => 'truck_no', 'name' => 'truck_no','title' => 'Truck Number'],
            ['data' => 'pan', 'name' => 'pan','title' => 'Pan Number'],
            ['data' => 'rc_book', 'name' => 'rc_book','title' => 'R.c Book'],
            ['data' => 'pan_card', 'name' => 'pan_card','title' => 'Pan Card'],
            ['data' => 'licence', 'name' => 'licence','title' => 'Licence'],
            ['data' => 'transporter_name', 'name' => 'transporter_name','title' => 'Transport'],
            ['data' => 'status', 'name' => 'status','title' => 'Status'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],
        ]);
        $data= array();

        if(request()->ajax()) {
            $data1 = Driver::where('self',0)->get();
            return $dataTable->dataTable($data1)->toJson();
        }
        // foreach ($data1 as $key => $value) {

        //     $data[$key]=$value;
        //     $transporter = Transporter::withTrashed()->findorfail($value->transporter_id);
        //     $data[$key]->transporter_name=$transporter->name;

        // }

        return view('admin.driverlist',compact('data','html'));
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
        'phone' => 'required|numeric|digits:10|unique:driver,phone',
        'licence_no' => 'required',
        'truck_no'=>'required',
        'pan'=>'required',
        'password' => 'required|min:8',
        'rc_book'=>'required|mimes:jpeg,jpg,png|max:2560',
        'pan_card'=>'required|mimes:jpeg,jpg,png|max:2560',
        'licence'=>'required|mimes:jpeg,jpg,png|max:2560',
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

                     return redirect()->route('driverlist')->with('success','Driver Addedd successfully.');

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
        // 'phone' => 'required|numeric|digits:10|unique:driver,phone,' . $Request->id,
        'phone' => 'required|exists:driver,id',
        'licence_no' => 'required',
        'truck_no'=>'required',
        'pan'=>'required',
        'rc_book'=>'mimes:jpeg,jpg,png|max:2560',
        'pan_card'=>'mimes:jpeg,jpg,png|max:2560',
        'licence'=>'mimes:jpeg,jpg,png|max:2560',
       ],[
         'transporter.required' => "Please Select Transporter",
         'name.required' => "Please Enter Name",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'licence_no.required' => "Please Enter Licence Number",
         'truck_no.required' => "Please Enter Truck Number",
         'pan.required' => "Please Enter PAN Number",

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
                        // if (!file_exists($path)) {
                        //     File::makeDirectory($path, 0777, true);
                        //     chmod($path, 0777);
                        // }
                        $Request->pan_card->move($path,$file_name);
                        // chmod($path . $file_name, 0777);
                        $comapny->pan_card = $file_name;
                 }

                 if($Request->hasFile('licence') && !empty($Request->file('licence'))){
                        $file_name = time()."3".$Request->licence->getClientOriginalName();
                        $Request->licence->move($path,$file_name);
                        $comapny->licence = $file_name;
                 }


                 if($comapny->save()){

                     return redirect()->route('driverlist')->with('success','Driver Updated successfully.');

                }
    }


     public function Delete(Request $Request)
    {

        $data = Driver::where('myid',$Request->id)->first();
        $data->deleted_by =Auth::user()->id;
        $data->save();
        $data->delete();
        return redirect()->route('driverlist')->with('success','Driver deleted successfully.');

    }









}