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


    public function tanmaypdf()
    {

        $pdf = PDF::loadView('tanmay');

        return $pdf->download('tanmay.pdf');
    }

     public function tanmay(Request $Request)
    {   

        return view('tanmay');

    }

     public function pdf(Request $Request)
    {   

        return view('yoginilr');

    }

    


    public function yashpdf()
    {

        $pdf = PDF::loadView('yash');

        return $pdf->download('yash.pdf');
    }

     public function yash(Request $Request)
    {   

        return view('yash');
    }





    ///yoginibill
    public function yoginibillpdf()
    {
        $pdf = PDF::loadView('yoginibill');

        return $pdf->download('yoginibill.pdf');
    }

     public function yoginibill(Request $Request)
    {   
        return view('yoginibill');

    }


    ///hanshbill
    public function hanshbillpdf()
    {
        $pdf = PDF::loadView('hanshbill');

        return $pdf->download('hanshbill.pdf');
    }

     public function hanshbill(Request $Request)
    {   
        return view('hanshbill');

    }



///ssibill
    public function ssibillpdf()
    {
        $pdf = PDF::loadView('ssibill');

        return $pdf->download('ssibill.pdf');
    }

     public function ssibill(Request $Request)
    {   
        return view('ssibill');

    }


///bmfbill
    public function bmfbillpdf()
    {
        $pdf = PDF::loadView('bmfbill');

        return $pdf->download('bmfbill.pdf');
    }

     public function bmfbill(Request $Request)
    {   
        return view('bmfbill');

    }




///yoginilr
    public function yoginilrpdf()
    {
        $pdf = PDF::loadView('yoginilr');

        return $pdf->download('yoginilr.pdf');
    }

     public function yoginilr(Request $Request)
    {   
        return view('yoginilr');

    }


    ///ssilr
    public function ssilrpdf()
    {
        $pdf = PDF::loadView('ssilr');

        return $pdf->download('ssilr.pdf');
    }

     public function ssilr(Request $Request)
    {   
        return view('ssilr');

    }

    ///hanshlr
    public function hanshlrpdf()
    {
        $pdf = PDF::loadView('hanshlr');

        return $pdf->download('hanshlr.pdf');
    }

     public function hanshlr(Request $Request)
    {   
        return view('hanshlr');

    }

    ///bmflr
    public function bmflrpdf()
    {
        $pdf = PDF::loadView('bmflr');

        return $pdf->download('bmflr.pdf');
    }

     public function bmflr(Request $Request)
    {   
        return view('bmflr');

    }


    ///yoginifcl
    public function yoginifclpdf()
    {
        $pdf = PDF::loadView('yoginifcl');

        return $pdf->download('yoginifcl.pdf');
    }

     public function yoginifcl(Request $Request)
    {   
        return view('yoginifcl');

    }


    ///ssifcl
    public function ssifclpdf()
    {
        $pdf = PDF::loadView('ssifcl');

        return $pdf->download('ssifcl.pdf');
    }

     public function ssifcl(Request $Request)
    {   
        return view('ssifcl');

    }

    ///hanshfcl
    public function hanshfclpdf()
    {
        $pdf = PDF::loadView('hanshfcl');

        return $pdf->download('hanshfcl.pdf');
    }

     public function hanshfcl(Request $Request)
    {   
        return view('hanshfcl');

    }

    ///bmffcl
    public function bmffclpdf()
    {
        $pdf = PDF::loadView('bmffcl');

        return $pdf->download('bmffcl.pdf');
    }

     public function bmffcl(Request $Request)
    {   
        return view('bmffcl');

    }




    




      public function pdf3(Request $Request)
    {   

        return view('yoginilr');



    }



     public function pdf4(Request $Request)
    { 

        $data = Shipment::withTrashed()->where('shipment_no','Y5')->first();

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
	
	public function updateExpenseDate()
	{
		
		//$data = Expense::whereNull('dates');
		$data = Expense::whereNull('dates')->get();
		//$data->dd();
		
		foreach ($data as $val) {
			$val->dates = date('Y-m-d',strtotime($val->created_at));
			$val->save();
		}

		return redirect()->route('expenselist')->with('success','Expense successfully Deleted.');
	}
}