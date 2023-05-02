<?php 

namespace App\Http\Controllers\Forwarder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
use App\Shipment_Summary;
use App\Shipment_Driver;
use App\Expense;
use App\Cargostatus;
use App\Invoice;
use App\Invoice_Truck;
use App\Account;
use Hash;
use PDF;
use Mail;



class AccountController extends Controller
{

	public function Account(Request $Request)
	{

		$years = ['2022','2023','2024','2025','2026','2027','2028','2029','2030'];

		$months =array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'Octomber','11'=>'November','12'=>'December');

		$select_year = date('Y');

		$select_month = date('m');

		return view('forwarder.account',compact('years','months','select_year','select_month'));
	}


	public function GetListLR(Request $Request)
	{
		$years = ['2022','2023','2024','2025','2026','2027','2028','2029','2030'];

		$months =array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'Octomber','11'=>'November','12'=>'December');

		$select_year = $Request->years;
		$select_month = $Request->month;

		return view('forwarder.account',compact('years','months','select_year','select_month'));
	}

	public function AccountData(Request $Request)
	{
		$year = $Request->years;
		$month = $Request->month;
		$date = $year.'-'.$month.'-01';
		$start_date = date("Y-m-d", strtotime($date));
		$last_date = date("Y-m-t", strtotime($date));
		$ff = Forwarder::where('user_id',Auth::user()->id)->first();
		$invoice_data = Invoice::where('forwarder_id',$ff->id)->whereBetween('invoice_date',[$start_date,$last_date])->orderby('id','asc')->get();
		//dd($invoice_data);

		$all_data =array();

		foreach ($invoice_data as $key => $value) {
		 	
		 	$all_data[$key]= $value;
		 	$company = Company::find($value->company_id);
		 	$all_data[$key]->company = $company->name; 	
		 } 

		// dd($all_data);
		return view('forwarder.invoice_list',compact('year','month','all_data','invoice_data'));
	}

	public function InvoiceDownload(Request $Request)
 	{

 		$data = Invoice::where('myid',$Request->id)->first();

        $forw_data = Forwarder::withTrashed()->findorfail($data->forwarder_id); 

        $comp_data = Company::withTrashed()->findorfail($data->company_id);

        $data->forwarder_name = $forw_data->name;
        $data->forwarder_address = $forw_data->address;
        $data->forwarder_phone = $forw_data->phone;
        $data->forwarder_email = $forw_data->email;
        $data->forwarder_gst = $forw_data->gst_no;

        $all_shipment = explode(',',$data->ships);
        $data->shipment_list = explode(',',$data->ships);
        $trucklist = array();

        foreach($all_shipment as $key => $value){

             $driver_list =Shipment_Driver::where('shipment_no',$value)->get();
            
            $d_list = "";

                    foreach ($driver_list as $key2 => $value2) { 
                        if($key2 == 0) {
                             $d_list = $d_list."".$value2->truck_no; 

                        } else {
                             $d_list = $d_list.", ".$value2->truck_no; 

                        }

                    }

                    $mytrucks[$key] = $d_list;

                    $shipdata =Shipment::where('shipment_no',$value)->first();

                    $mydates[$key] =date('d-m-Y',strtotime($shipdata->date)); 
            

        }

        $data->trucklist = $mytrucks;

        $data->alldates = $mydates;

        $f_shipdata = Shipment::where('shipment_no',$all_shipment[0])->first();

       $data->from = $f_shipdata->from1;

        $data->lcl = $f_shipdata->lcl;
        $data->fcl = $f_shipdata->fcl;


       if($f_shipdata->to2 != ''){
               $data->to = $f_shipdata->to1.",".$f_shipdata->to2;
       } else {
            $data->to = $f_shipdata->to1;
       }

        if($comp_data->lr =='yoginilr' ){

            $pdf = PDF::loadView('bill.yoginibill',compact('data','comp_data'));

            return $pdf->download('Yogini Bill '.$data->invoice_no.'.pdf');

        }

         if($comp_data->lr =='ssilr' ){

            $pdf = PDF::loadView('bill.ssibill',compact('data','comp_data'));

            return $pdf->download('SSI Bill'.$data->invoice_no.'.pdf');

        }

        if($comp_data->lr =='hanshlr' ){

            $pdf = PDF::loadView('bill.hanshlr',compact('data','comp_data'));

            return $pdf->download('Hansh Bill '.$data->invoice_no.'.pdf');

        }

        if($comp_data->lr =='bmflr' ){

            $pdf = PDF::loadView('bill.bmflr',compact('data','comp_data'));

            return $pdf->download('BMF Bill '.$data->invoice_no.'.pdf');

        }

 	}

 	    public function InvoiceView(Request $Request)
    {


        $data = Invoice::where('myid',$Request->id)->first();

        $forw_data = Forwarder::withTrashed()->findorfail($data->forwarder_id); 

        $comp_data = Company::withTrashed()->findorfail($data->company_id);

        $data->forwarder_name = $forw_data->name;
        $data->forwarder_address = $forw_data->address;
        $data->forwarder_phone = $forw_data->phone;
        $data->forwarder_email = $forw_data->email;
        $data->forwarder_gst = $forw_data->gst_no;

        $all_shipment = explode(',',$data->ships);
        $data->shipment_list = explode(',',$data->ships);
        $trucklist = array();

        foreach($all_shipment as $key => $value){

             $driver_list =Shipment_Driver::where('shipment_no',$value)->get();
            
            $d_list = "";

                    foreach ($driver_list as $key2 => $value2) { 
                        if($key2 == 0) {
                             $d_list = $d_list."".$value2->truck_no; 

                        } else {
                             $d_list = $d_list.", ".$value2->truck_no; 

                        }

                    }

                    $mytrucks[$key] = $d_list;

                    $shipdata =Shipment::where('shipment_no',$value)->first();

                    $mydates[$key] =date('d-m-Y',strtotime($shipdata->date)); 
            

        }

        $data->trucklist = $mytrucks;

        $data->alldates = $mydates;

        $f_shipdata = Shipment::where('shipment_no',$all_shipment[0])->first();

       $data->from = $f_shipdata->from1;

        $data->lcl = $f_shipdata->lcl;
        $data->fcl = $f_shipdata->fcl;


       if($f_shipdata->to2 != ''){
        
               $data->to = $f_shipdata->to1.",".$f_shipdata->to2;
       
       } else {

            $data->to = $f_shipdata->to1;
       }


       //dd($data);



        //dd($data);
        if($comp_data->lr =='yoginilr' ){
/*
            $pdf = PDF::loadView('bill.yoginibill',compact('data','comp_data'));

            return $pdf->download('Yogini Bill '.$data->invoice_no.'.pdf');*/
            return view('bill.yoginibill',compact('data','comp_data'));

        }

         if($comp_data->lr =='ssilr' ){

            /*$pdf = PDF::loadView('bill.ssibill',compact('data','comp_data'));
            return $pdf->download('SSI Bill'.$data->invoice_no.'.pdf');*/
            return view('bill.ssibill',compact('data','comp_data'));

        }

        if($comp_data->lr =='hanshlr' ){

            /*$pdf = PDF::loadView('bill.hanshlr',compact('data','comp_data'));
            return $pdf->download('Hansh Bill '.$data->invoice_no.'.pdf');*/
            return view('bill.hanshbill',compact('data','comp_data'));

        }


        if($comp_data->lr =='bmflr' ){

            /*$pdf = PDF::loadView('bill.bmflr',compact('data','comp_data'));
            return $pdf->download('BMF Bill '.$data->invoice_no.'.pdf');*/
            return view('bill.bmfbill',compact('data','comp_data'));

        }

    }



    public function LedgerAccount(Request $Request)
	{
		$years = ['2022','2023','2024','2025','2026','2027','2028','2029','2030'];

		$months =array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'Octomber','11'=>'November','12'=>'December');

		$select_year = '';

		$select_month = '';

		return view('forwarder.ledger',compact('years','months','select_year','select_month'));
	}

	public function LegerData(Request $Request)
	{

		$ff = Forwarder::where('user_id',Auth::user()->id)->first();
		$myid= $ff->id;

		if($Request->from != ''){

			$from = date('Y-m-d',strtotime($Request->from));

		} else {

			$from = date('2020-01-01');
		}

		if($Request->to != ''){

			$to = date('Y-m-d',strtotime($Request->to));

		} else {

			$to = date('Y-m-d');
		}

		if($Request->type == 'forwarder') {
		 	  $total_credit1 = Account::where('to_forwarder',$myid)->whereBetween('dates', [$from, $to])->sum('credit');
		 	  $total_credit2 = Account::where('to_forwarder',$myid)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_credit = $total_credit1 + $total_credit2;

		 // echo "<br>";
		      $total_debit1 = Account::where('from_forwarder',$myid)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_debit2 = Account::where('from_forwarder',$myid)->whereBetween('dates', [$from, $to])->sum('credit');
			  $total_debit = $total_debit1 + $total_debit2;
		}

		$nyllist = array();

		if($Request->type == 'forwarder'){

			$data12 = Account::orwhere('to_forwarder',$myid)->orwhere('from_forwarder',$myid)->whereBetween('dates', [$from, $to])->orderby('dates','asc')->get();

			$cc = Account::orwhere('to_forwarder',$myid)->orwhere('from_forwarder',$myid)->whereBetween('dates', [$from, $to])->sum('debit');

			$dd = Account::orwhere('to_forwarder',$myid)->orwhere('from_forwarder',$myid)->whereBetween('dates', [$from, $to])->sum('credit');

			foreach ($data12 as $key => $value) {


				if($value->v_type == 'credit'){

					if($value->to_company != '' && $value->to_company != null){
						$nyllist[$key]=$value;
						$com = Company::withTrashed()->findorfail($value->to_company);
						$nyllist[$key]['detailss'] = "To: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->credit;

					}

					if($value->to_forwarder != '' && $value->to_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->to_forwarder);
						$nyllist[$key]=$value;
						$nyllist[$key]['detailss'] = "To: ".$com->name;"To: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->credit;
					}

				}


				if($value->v_type == 'debit'){

					if($value->from_forwarder != '' && $value->from_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->from_forwarder);
						$nyllist[$key]=$value;
						$nyllist[$key]['detailss'] = "By: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->debit;
						$nyllist[$key]['debitst'] = '';
					}


					if($value->from_company != '' && $value->from_company != null){
						$com = Company::withTrashed()->findorfail($value->from_company);
						$nyllist[$key]=$value;
						$nyllist[$key]['detailss'] = "By: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->debit;
						$nyllist[$key]['debitst'] = '';
					}
				}
			}
		}



		$myfrom = $from;
		$myto = $to;
		

		return view('forwarder.accountdata',compact('total_credit','total_debit','nyllist','cc','dd','myfrom','myto'));
	}



}