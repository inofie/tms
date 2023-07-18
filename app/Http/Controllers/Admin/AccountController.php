<?php 

namespace App\Http\Controllers\Admin;

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
use Config;


class AccountController extends Controller
{

	public function Account(Request $Request)
	{
		$company = Company::get();

		$transporter = Transporter::get();

		$forwarder = Forwarder::get();

		return view('admin.account',compact('company','transporter','forwarder'));
	}



	public function AccountData(Request $Request)
	{

		//dd($Request->from,$Request->to);
		if($Request->from != ''){

			$from = date('Y-m-d',strtotime($Request->from));

		} else {


			$from = date('2022-04-01');

		}

		if($Request->to != ''){

			$to = date('Y-m-d',strtotime($Request->to));

		} else {

			$to = date('Y-m-d');

		}

		
		
		if($Request->type == 'company'){
		 
		      $total_credit1 = Account::where('to_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			//$total_credit1->dd();
		 	  $total_credit2 = Account::where('to_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_credit = $total_credit1 + $total_credit2;

		 // echo "<br>";
		      $total_debit1 = Account::where('from_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_debit2 = Account::where('from_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			  $total_debit = $total_debit1 + $total_debit2;


		}

		if($Request->type == 'forwarder') {
		 	  $total_credit1 = Account::where('to_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
		 	  $total_credit2 = Account::where('to_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_credit = $total_credit1 + $total_credit2;

		 // echo "<br>";
		      $total_debit1 = Account::where('from_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_debit2 = Account::where('from_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			  $total_debit = $total_debit1 + $total_debit2;
		}

		if($Request->type == 'transporter') {

			$total_credit1 = Account::where('to_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
		 	  $total_credit2 = Account::where('to_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_credit = $total_credit1 + $total_credit2;

		 // echo "<br>";
		      $total_debit1 = Account::where('from_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_debit2 = Account::where('from_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			  $total_debit = $total_debit1 + $total_debit2;
		
		}

	

		$nyllist = array();

		$expense = array();

		if($Request->type == 'company'){
			//$data12 = Account::orwhere('to_company',$Request->id)->orwhere('from_company',$Request->id)->whereBetween('dates', [$from, $to])->orderby('dates','asc')->get();
			//$cc = Account::orwhere('to_company',$Request->id)->orwhere('from_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			//$dd = Account::orwhere('to_company',$Request->id)->orwhere('from_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
			
			$data12 = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_company', $Request->id)
								  ->orWhere('from_company', $Request->id);
						})
					->orderby('id','desc')->get();
					
				//dd($data12[5]);	
			$cc = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_company', $Request->id)
								  ->orWhere('from_company', $Request->id);
						})
					->sum('credit');
			$dd = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_company', $Request->id)
								  ->orWhere('from_company', $Request->id);
						})
					->sum('debit');

			foreach ($data12 as $key => $value) {
				if($value->v_type == 'credit'){
					if($value->from_company != '' && $value->from_company != null){
						$nyllist[$key]=$value;
						$com = Company::withTrashed()->findorfail($value->from_company);
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = "By: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->credit;
						$nyllist[$key]['debitst'] = '';
					}
					if($value->from_transporter != '' && $value->from_transporter != null){
						$com = Transporter::withTrashed()->findorfail($value->from_transporter);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = "By: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->credit;
						$nyllist[$key]['debitst'] = '';
					}
					if($value->from_forwarder != '' && $value->from_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->from_forwarder);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->credit;
						$nyllist[$key]['debitst'] = '';
					}
				}

				if($value->v_type == 'debit'){
					if($value->to_transporter != '' && $value->to_transporter != null){
						$com = Transporter::withTrashed()->findorfail($value->to_transporter);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = "To: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->debit;
					}

					if($value->to_forwarder != '' && $value->to_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->to_forwarder);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = "To: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->debit;
					}
				}

				if($value->v_type == 'expense'){
					$nyllist[$key]=$value;
					if($value->type == 'invoice'){
						$invoice = Invoice::findorfail($value->invoice_list);
						$nyllist[$key]['detailss'] = "To: ".$value->description." (".$invoice->invoice_no.")";
					} else {
						$nyllist[$key]['detailss'] = "To: ".$value->description;
					}
					//$nyllist[$key]['detailss'] = "To: ".$value->description;
					$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
					$nyllist[$key]['creditt'] = '';
					$nyllist[$key]['debitst'] = $value->debit;
				}
			}
		}	

		if($Request->type == 'transporter'){
			//$data12 = Account::orwhere('to_transporter',$Request->id)->orwhere('from_transporter',$Request->id)->whereBetween('dates', [$from, $to])->orderby('dates','asc')->get();
			//$cc = Account::orwhere('to_transporter',$Request->id)->orwhere('from_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			//$dd = Account::orwhere('to_transporter',$Request->id)->orwhere('from_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
			//dd($Request->id);
			//$data12['datato'] = Account::whereBetween('dates', [$from, $to])->where('to_transporter', $Request->id)->orderby('dates','asc')->get();
			//$data12['datafrom'] = Account::whereBetween('dates', [$from, $to])->where('from_transporter', $Request->id)->orderby('dates','asc')->get();
			//$data12->dd();
			//dd(array_merge($datato, $datafrom));
			//$data12 = array_merge($datato,$datafrom);	
			$data12 = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_transporter', $Request->id)
								  ->orWhere('from_transporter', $Request->id);
						})
					->orderby('id','desc')->get();
			//$data12->dd();
			$cc = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_transporter', $Request->id)
								  ->orWhere('from_transporter', $Request->id);
						})
					->sum('credit');
			$dd = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_transporter', $Request->id)
								  ->orWhere('from_transporter', $Request->id);
						})
					->sum('debit');
			foreach ($data12 as $key => $value) {
					if($value->v_type == 'credit'){
						if($value->from_company != '' && $value->from_company != null){
							$nyllist[$key]=$value;
							$com = Company::withTrashed()->findorfail($value->from_company);
							if($value->type == 'invoice'){
								$invoice = Invoice::findorfail($value->invoice_list);
								$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
							} else {
								$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
							}
							//$nyllist[$key]['detailss'] = $com->name;
							$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
							$nyllist[$key]['creditt'] = $value->credit;
							$nyllist[$key]['debitst'] = '';
						}

						if($value->from_transporter != '' && $value->from_transporter != null){
							$com = Transporter::withTrashed()->findorfail($value->from_transporter);
							$nyllist[$key]=$value;
							if($value->type == 'invoice'){
								$invoice = Invoice::findorfail($value->invoice_list);
								$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
							} else {
								$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
							}
							//$nyllist[$key]['detailss'] = $com->name;
							$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
							$nyllist[$key]['creditt'] = $value->credit;
							$nyllist[$key]['debitst'] = '';
						}

						if($value->from_forwarder != '' && $value->from_forwarder != null){
							$com = Forwarder::withTrashed()->findorfail($value->from_forwarder);
							$nyllist[$key]=$value;
							if($value->type == 'invoice'){
								$invoice = Invoice::findorfail($value->invoice_list);
								$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
							} else {
								$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
							}
							//$nyllist[$key]['detailss'] = $com->name;
							$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
							$nyllist[$key]['creditt'] = $value->credit;
							$nyllist[$key]['debitst'] = '';
						}
					}

					if($value->v_type == 'debit'){
						if($value->to_transporter != '' && $value->to_transporter != null){
							$com = Transporter::withTrashed()->findorfail($value->to_transporter);
							$nyllist[$key]=$value;
							if($value->type == 'invoice'){
								$invoice = Invoice::findorfail($value->invoice_list);
								$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
							} else {
								$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
							}
							//$nyllist[$key]['detailss'] = $com->name;
							$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
							$nyllist[$key]['creditt'] = '';
							$nyllist[$key]['debitst'] = $value->debit;
						}

						if($value->to_company != '' && $value->to_company != null){
							$com = Company::withTrashed()->findorfail($value->to_company);
							$nyllist[$key]=$value;
							if($value->type == 'invoice'){
								$invoice = Invoice::findorfail($value->invoice_list);
								$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
							} else {
								$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
							}
							//$nyllist[$key]['detailss'] = $com->name;
							$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
							$nyllist[$key]['creditt'] = '';
							$nyllist[$key]['debitst'] = $value->debit;
						}
					}
			}

		}

		if($Request->type == 'forwarder'){
			//$data12 = Account::orwhere('to_forwarder',$Request->id)->orwhere('from_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->orderby('dates','asc')->get();
			//$cc = Account::orwhere('to_forwarder',$Request->id)->orwhere('from_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
			//$dd = Account::orwhere('to_forwarder',$Request->id)->orwhere('from_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');

			$data12 = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->id)
								  ->orWhere('from_forwarder', $Request->id);
						})
					->orderby('id','desc')->get();
					
					
			$cc = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->id)
								  ->orWhere('from_forwarder', $Request->id);
						})
					->sum('credit');
			$dd = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->id)
								  ->orWhere('from_forwarder', $Request->id);
						})
					->sum('debit');
			foreach ($data12 as $key => $value) {
				if($value->v_type == 'credit'){
					if($value->to_company != '' && $value->to_company != null){
						$nyllist[$key]=$value;
						$com = Company::withTrashed()->findorfail($value->to_company);
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->credit;
					}

					if($value->to_transporter != '' && $value->to_transporter != null){
						$com = Transporter::withTrashed()->findorfail($value->to_transporter);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = "To: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->credit;
					}

					if($value->to_forwarder != '' && $value->to_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->to_forwarder);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->credit;
					}
				}

				if($value->v_type == 'debit'){
					if($value->from_forwarder != '' && $value->from_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->from_forwarder);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->debit;
						$nyllist[$key]['debitst'] = '';
					}

					if($value->from_company != '' && $value->from_company != null){
						$com = Company::withTrashed()->findorfail($value->from_company);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->debit;
						$nyllist[$key]['debitst'] = '';
					}
				}
			}
		}


		$mytype = $Request->type;
		$myfrom = $from;
		$myto = $to;
		$myid = $Request->id;

		return view('admin.accountdata',compact('total_credit','total_debit','nyllist','cc','dd','mytype','myfrom','myto','myid'));
	}
	public function AccountPDF(Request $Request)
	{
		if($Request->from != ''){
			$from = date('Y-m-d',strtotime($Request->from));
		} else {
			$from = date('2022-04-01');
		}

		if($Request->to != ''){
			$to = date('Y-m-d',strtotime($Request->to));
		} else {
			$to = date('Y-m-d');
		}
		$qr_code = '';
		$account_qr_id = ['1','3','4','5'];
		if($Request->type == 'company'){
		      $total_credit1 = Account::where('to_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
		 	  $total_credit2 = Account::where('to_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_credit = $total_credit1 + $total_credit2;

		      $total_debit1 = Account::where('from_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_debit2 = Account::where('from_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			  $total_debit = $total_debit1 + $total_debit2;
			if(in_array($Request->id, $account_qr_id)){
				$qr_code = $Request->id.'_id.jpeg';
			}
		}

		if($Request->type == 'forwarder') {
		 	  $total_credit1 = Account::where('to_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
		 	  $total_credit2 = Account::where('to_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_credit = $total_credit1 + $total_credit2;

		      $total_debit1 = Account::where('from_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_debit2 = Account::where('from_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			  $total_debit = $total_debit1 + $total_debit2;
		}

		if($Request->type == 'transporter') {
			$total_credit1 = Account::where('to_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
		 	$total_credit2 = Account::where('to_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	$total_credit = $total_credit1 + $total_credit2;

		    $total_debit1 = Account::where('from_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
		 	$total_debit2 = Account::where('from_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			$total_debit = $total_debit1 + $total_debit2;
		}

		$nyllist = array();

		if($Request->type == 'company'){
			$maindata = Company::withTrashed()->findorfail($Request->id);
			//$data12 = Account::orwhere('to_company',$Request->id)->orwhere('from_company',$Request->id)->whereBetween('dates', [$from, $to])->orderby('dates','asc')->get();
			//$cc = Account::orwhere('to_company',$Request->id)->orwhere('from_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			///$dd = Account::orwhere('to_company',$Request->id)->orwhere('from_company',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');

			$data12 = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_company', $Request->id)
								  ->orWhere('from_company', $Request->id);
						})->orderby('id','desc')->get();
					
			$cc = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_company', $Request->id)
								  ->orWhere('from_company', $Request->id);
						})->sum('credit');
			$dd = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_company', $Request->id)
								  ->orWhere('from_company', $Request->id);
						})->sum('debit');

			foreach ($data12 as $key => $value) {
				if($value->v_type == 'credit'){
					if($value->from_company != '' && $value->from_company != null){
						$nyllist[$key]=$value;
						$com = Company::withTrashed()->findorfail($value->from_company);
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = "By: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->credit;
						$nyllist[$key]['debitst'] = '';
					}
					if($value->from_transporter != '' && $value->from_transporter != null){
						$com = Transporter::withTrashed()->findorfail($value->from_transporter);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = "By: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->credit;
						$nyllist[$key]['debitst'] = '';
					}
					if($value->from_forwarder != '' && $value->from_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->from_forwarder);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->credit;
						$nyllist[$key]['debitst'] = '';
					}
				}

				if($value->v_type == 'debit'){
					if($value->to_transporter != '' && $value->to_transporter != null){
						$com = Transporter::withTrashed()->findorfail($value->to_transporter);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = "To: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->debit;
					}

					if($value->to_forwarder != '' && $value->to_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->to_forwarder);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = "To: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->debit;
					}
				}

				if($value->v_type == 'expense'){
					$nyllist[$key]=$value;
					if($value->type == 'invoice'){
						$invoice = Invoice::findorfail($value->invoice_list);
						$nyllist[$key]['detailss'] = "To: ".$value->description." (".$invoice->invoice_no.")";
					} else {
						$nyllist[$key]['detailss'] = "To: ".$value->description;
					}
					//$nyllist[$key]['detailss'] = "To: ".$value->description;
					$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
					$nyllist[$key]['creditt'] = '';
					$nyllist[$key]['debitst'] = $value->debit;
				}
			}
		}	

		if($Request->type == 'transporter'){

			$maindata = Transporter::withTrashed()->findorfail($Request->id);
			//$data12 = Account::orwhere('to_transporter',$Request->id)->orwhere('from_transporter',$Request->id)->whereBetween('dates', [$from, $to])->orderby('dates','asc')->get();
			//$cc = Account::orwhere('to_transporter',$Request->id)->orwhere('from_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');
			//$dd = Account::orwhere('to_transporter',$Request->id)->orwhere('from_transporter',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
			
			$data12 = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_transporter', $Request->id)
								  ->orWhere('from_transporter', $Request->id);
						})->orderby('id','desc')->get();
					
			$cc = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_transporter', $Request->id)
								  ->orWhere('from_transporter', $Request->id);
						})->sum('credit');
			$dd = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_transporter', $Request->id)
								  ->orWhere('from_transporter', $Request->id);
						})->sum('debit');
			foreach ($data12 as $key => $value) {
				if($value->v_type == 'credit'){
					if($value->from_company != '' && $value->from_company != null){
						$nyllist[$key]=$value;
						$com = Company::withTrashed()->findorfail($value->from_company);
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = $com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->credit;
						$nyllist[$key]['debitst'] = '';
					}

					if($value->from_transporter != '' && $value->from_transporter != null){
						$com = Transporter::withTrashed()->findorfail($value->from_transporter);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = $com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->credit;
						$nyllist[$key]['debitst'] = '';
					}

					if($value->from_forwarder != '' && $value->from_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->from_forwarder);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = $com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->credit;
						$nyllist[$key]['debitst'] = '';
					}
				}

				if($value->v_type == 'debit'){
					if($value->to_transporter != '' && $value->to_transporter != null){
						$com = Transporter::withTrashed()->findorfail($value->to_transporter);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = $com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->debit;
					}

					if($value->to_company != '' && $value->to_company != null){
						$com = Company::withTrashed()->findorfail($value->to_company);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = $com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->debit;
					}
				}
			}
		}

		if($Request->type == 'forwarder'){

			$maindata = Forwarder::withTrashed()->findorfail($Request->id);
			//$data12 = Account::orwhere('to_forwarder',$Request->id)->orwhere('from_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->orderby('dates','asc')->get();
			//$cc = Account::orwhere('to_forwarder',$Request->id)->orwhere('from_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('debit');
			//$dd = Account::orwhere('to_forwarder',$Request->id)->orwhere('from_forwarder',$Request->id)->whereBetween('dates', [$from, $to])->sum('credit');

			$data12 = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->id)
								  ->orWhere('from_forwarder', $Request->id);
						})->orderby('id','desc')->get();
					
			$cc = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->id)
								  ->orWhere('from_forwarder', $Request->id);
						})->sum('credit');
			$dd = Account::whereBetween('dates', [$from, $to])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->id)
								  ->orWhere('from_forwarder', $Request->id);
						})->sum('debit');
			foreach ($data12 as $key => $value) {
				if($value->v_type == 'credit'){
					if($value->to_company != '' && $value->to_company != null){
						$nyllist[$key]=$value;
						$com = Company::withTrashed()->findorfail($value->to_company);
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->credit;
					}

					if($value->to_transporter != '' && $value->to_transporter != null){
						$com = Transporter::withTrashed()->findorfail($value->to_transporter);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						//$nyllist[$key]['detailss'] = "To: ".$com->name;
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->credit;
					}

					if($value->to_forwarder != '' && $value->to_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->to_forwarder);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "To: ".$com->name." (".$value->description.")";
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = '';
						$nyllist[$key]['debitst'] = $value->credit;
					}
				}

				if($value->v_type == 'debit'){
					if($value->from_forwarder != '' && $value->from_forwarder != null){
						$com = Forwarder::withTrashed()->findorfail($value->from_forwarder);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->debit;
						$nyllist[$key]['debitst'] = '';
					}

					if($value->from_company != '' && $value->from_company != null){
						$com = Company::withTrashed()->findorfail($value->from_company);
						$nyllist[$key]=$value;
						if($value->type == 'invoice'){
							$invoice = Invoice::findorfail($value->invoice_list);
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$invoice->invoice_no.")";
						} else {
							$nyllist[$key]['detailss'] = "By: ".$com->name." (".$value->description.")";
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
						$nyllist[$key]['creditt'] = $value->debit;
						$nyllist[$key]['debitst'] = '';
					}
				}
			}
		}

		$mytype = $Request->type;
		$myfrom = $from;
		$myto = $to;
		$myid = $Request->id;
		
		// return view('admin.accountpdf',compact('total_credit','total_debit','nyllist','cc','dd','mytype','myfrom','myto','myid','maindata','qr_code'));

			$pdf = PDF::loadView('admin.accountpdf',compact('total_credit','total_debit','nyllist','cc','dd','mytype','myfrom','myto','myid','maindata', 'qr_code'));

           return $pdf->download($maindata->name.'.pdf');
	}

}