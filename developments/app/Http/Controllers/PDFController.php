<?php

namespace App\Http\Controllers;

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
use App\Shipment_Transporter;
use App\Shipment_Driver;
use App\Expense;
use Hash;
use PDF;
use Mail;
use Illuminate\Support\Facades\Auth;
  
class PDFController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        $data["email"] = "tanmay.technoscatter@gmail.com";
        $data["title"] = "From ssiwebsql.com";
        $data["body"] = "This is Demo";
  
        $pdf = PDF::loadView('myTestMail', $data);

        return $pdf->download('invoice.pdf');
  
        Mail::send('myTestMail', $data, function($message)use($data, $pdf) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), "text.pdf");
        });
  
        dd('Mail sent successfully');

        $pdf->download('invoice.pdf');
       
        $path = public_path('/uploads');
        
        $pdf->save($path.''.$data->lr_no.'.pdf'); 
        //$pdf->save(storage_path().'_filename.pdf');
    }


     public function pdf(Request $Request)
    {   

        $data = Shipment::withTrashed()->where('shipment_no',$Request->id)->first();

                $comp = Company::withTrashed()->findorfail($data->company);

                $data->company_name = $comp->name;

                $for = Forwarder::withTrashed()->findorfail($data->forwarder);

                $data->forwarder_name = $for->name;

                $tra = Transporter::withTrashed()->findorfail($data->transporter);

                $data->transporter_name = $tra->name;

                if($data->trucktype != "" && $data->trucktype != null && $data->trucktype != 'null'){
                $truck = Truck::withTrashed()->findorfail($data->trucktype);
                $data->trucktype_name = $truck->name;
                } else {
                    $data->trucktype_name ="";

                } 
                $tras_list =Shipment_Transporter::withTrashed()->where('shipment_no',$Request->id)->get();
                $t_list = "";
                foreach ($tras_list as $key => $value) { 
                     if($key == 0) {
                        $t_list = $t_list."".$value->name; 
                    } else {

                        $t_list = $t_list.",".$value->name; 
                     }
                }

                $data->transporters_list =  $t_list;

                 $driver_list =Shipment_Driver::withTrashed()->where('shipment_no',$Request->id)->get();
                $d_list = "";

                foreach ($driver_list as $key2 => $value2) { 
                    if($key2 == 0) {
                         $d_list = $d_list."".$value2->truck_no."(".$value2->mobile.")"; 

                    } else {
                         $d_list = $d_list.",".$value2->truck_no."(".$value2->mobile.")"; 

                    }

                }

                $data->truck_no = $d_list;

              // dd($data);

                return view('yoginilr',compact('data'));
    }
}