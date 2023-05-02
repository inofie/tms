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
        'pan_card'=>'required|mimes:jpeg,jpg,png',
        'licence'=>'required|mimes:jpeg,jpg,png',
        'rcbook'=>'required|mimes:jpeg,jpg,png',
        'username'=>'required',
        'password'=>'required',       
         ],[
         'name.required' => "Please Enter Name",
         'phone.required' => "Please Enter Phone Number",
         'phone.numeric' => "Please Enter Valid Phone Number",
         'licence_no.required' => "Please Enter Licence Number",
         'truck_no.required' => "Please Enter Truck Number",
         'pan.required' => "Please Enter PAN Number",
         'rcbook.required' => "Please Upload RC Book Document",
         'rcbook.mimes' => "Please Upload RC Book Document File extension .jpg, .png, .jpeg",
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


                if($Request->hasFile('rcbook') && !empty($Request->file('rcbook'))){
                        $file_name1 = time()."1".$Request->rcbook->getClientOriginalName();
                        $Request->rcbook->move($path,$file_name1);
                        $comapny->rc_book = $file_name1;
                 }
                    
                 if($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))){
                        $file_name2 = time()."2".$Request->pan_card->getClientOriginalName();
                        $Request->pan_card->move($path,$file_name2);
                        $comapny->pan_card = $file_name2;
                 }
                    
                 if($Request->hasFile('licence') && !empty($Request->file('licence'))){
                        $file_name3 = time()."3".$Request->licence->getClientOriginalName();
                        $Request->licence->move($path,$file_name3);
                        $comapny->licence = $file_name3;
                 }

                        $comapny->save();

                        $comapny2 = new Driver();

                        $comapny2->name = $Request->name;

                        $comapny2->phone = $Request->phone;

                        $comapny2->truck_no= $Request->truck_no;

                        $comapny2->licence_no= $Request->licence_no;

                        $comapny2->pan = $Request->pan;

                        $comapny2->transporter_id = $comapny->id;

                        $comapny2->self = 1;

                        $comapny2->created_by=Auth::user()->id;

                        $comapny2->password=Hash::make($Request->password);

                        $comapny2->myid= uniqid();

                        $comapny->rc_book = $file_name1;
             
                        $comapny->pan_card = $file_name2;
                
                        $comapny->licence = $file_name3;

                        $comapny2->save();

                     return redirect()->route('transporterlist')->with('success','Transporter Addedd successfully.');

    }

     public function Edit(Request $Request)
    {

        $data = Transporter::where('myid',$Request->id)->first();

        $user = User::where('id',$data->user_id)->first();

        return view('admin.transportedit',compact('data','user'));

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
        'username'=>'required',
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
         'username.required' => "Please Enter Username",
         ]);

       // dd($Request);
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
                
                $user->delete();

                $data->delete(); 

        return redirect()->route('transporterlist')->with('success','Transporter deleted successfully.');

    }


    public function TypeList(Request $Request)
    {   

        $data = Truck::all();
        
        return view('admin.transporttypelist',compact('data'));
    }



    public function TypeADD(Request $Request)
    {   
        return view('admin.vehicletype');
    }

    public function TypeSave(Request $Request)
    { 
        $this->validate($Request, [
        'name' => 'required',
        'sorting' => 'required|numeric',
         ],[
         'name.required' => "Please Enter Name",
         'sorting.required' => "Please Enter Name",
         'sorting.numeric' => "Enter Only Number",
        ]);



          $data = new Truck();
          $data->name = $Request->name;
          $data->sorting = $Request->sorting;
          $data->save();

          return redirect()->route('transporttypelist')->with('success','Vehicle Type added successfully.');

    }



    public function TypeEdit(Request $Request)
    {   
        $data = Truck::findorfail($Request->id);
        return view('admin.vehicletypeedit',compact('data'));
    }

    public function TypeUpdate(Request $Request)
    { 
         $this->validate($Request, [
        'name' => 'required',
        'sorting' => 'required|numeric',
         ],[
         'name.required' => "Please Enter Name",
         'sorting.required' => "Please Enter Name",
         'sorting.numeric' => "Enter Only Number",
        ]);


          $data =Truck::findorfail($Request->id);
          $data->name = $Request->name;
          $data->sorting = $Request->sorting;
          $data->save();

          return redirect()->route('transporttypelist')->with('success','Vehicle Type Updated successfully.');

    }





    public function TypeDelete(Request $Request)
    {

        $data = Truck::where('id',$Request->id)->first();
        $data->deleted_by=Auth::user()->id;
        $data->save();
        $data->delete();

        return redirect()->route('transporttypelist')->with('success','Vehicle Type deleted successfully.');



    }




}