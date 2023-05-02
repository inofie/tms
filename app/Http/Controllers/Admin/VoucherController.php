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



class VoucherController extends Controller
{

	public function List(Request $Request)
	{

		$data= array();

		$data1 = Account::where('v_type','credit')->orwhere('v_type','debit')->get();


		foreach ($data1 as $key => $value) {
			
			$data[$key] = $value;

			if($value->from_company != "" && $value->from_company != null){

				$company_data = Company::withTrashed()->findorfail($value->from_company);

				$data[$key]['from']= $company_data->name;

			} else if($value->from_transporter != "" && $value->from_transporter != null){

				$transporter_data = Transporter::withTrashed()->findorfail($value->from_transporter);

				$data[$key]['from']= $transporter_data->name;

			} else if($value->from_forwarder != "" && $value->from_forwarder != null){

				$forwarder_data = Forwarder::withTrashed()->findorfail($value->from_forwarder);

				$data[$key]['from']= $forwarder_data->name;
			}


			if($value->to_company != "" && $value->to_company != null){

				$company_data = Company::withTrashed()->findorfail($value->to_company);

				$data[$key]['to']= $company_data->name;

			} else if($value->to_transporter != "" && $value->to_transporter != null){

				$transporter_data = Transporter::withTrashed()->findorfail($value->to_transporter);

				$data[$key]['to']= $transporter_data->name;

			} else if($value->to_forwarder != "" && $value->to_forwarder != null){

				$forwarder_data = Forwarder::withTrashed()->findorfail($value->to_forwarder);

				$data[$key]['to']= $forwarder_data->name;
			}

			if($value->credit != "" && $value->credit != null){

				$data[$key]['type'] = 'Credit';

			} else if($value->debit != "" && $value->debit != null){

				$data[$key]['type'] = 'Debit';
			} 

			if($value->credit != "" && $value->credit != null){

				$data[$key]['amount'] = $value->credit;

			} else if($value->debit != "" && $value->debit != null){

				$data[$key]['amount'] = $value->debit;
			} 




		}

//dd($data);
		
		return view('admin.voucherlist',compact('data'));
	}



	public function Credit(Request $Request)
	{
		
		$company = Company::get();
		$transporter = Transporter::get();
		$forwarder = Forwarder::get();

		return view('admin.vouchercredit',compact('company','transporter','forwarder'));
	}



	public function CreditSave(Request $Request)
	{

		$transporter = $Request->transporter_id;
		
		$forwarder = $Request->forwarder_id;

		$fcompany = $Request->fcompany_id;
		
		$company = $Request->company_id;

		$amount = $Request->amount;

		$type = $Request->type;

		$date = $Request->date;

		$chequenumber = $Request->cheque_number;

		$chequebankname = $Request->cheque_bank_name;

		$description = $Request->description;

		$rtgs_paymentby = $Request->rtgs_paymentby;

		$rtgs_transaction = $Request->rtgs_transaction;

		$rtgs_account_number = $Request->rtgs_account_number;

		$rtgs_bank_name = $Request->rtgs_bank_name;

		$cash_from_name = $Request->cash_from_name;

		$cash_to_name = $Request->cash_to_name;



		$data = new Account();

		if($transporter != "" && $transporter != null) {

			$data->from_transporter = $transporter;
		}

		if($forwarder != "" && $forwarder != null) {

			$data->from_forwarder = $forwarder;
		}

		if($fcompany != "" && $fcompany != null) {

			$data->from_company = $fcompany;
		}

		if($company != "" && $company != null) {

			$data->to_company = $company;
		}

		$data->type = $type;
		
		$data->dates = date('Y-m-d',strtotime($date));

		$data->chequenumber = $chequenumber;

		$data->chequebankname = $chequebankname;

		$data->chequebankname = $chequebankname;

		$data->rtgs_paymentby = $rtgs_paymentby;

		$data->rtgs_transaction = $rtgs_transaction;

		$data->rtgs_account_number = $rtgs_account_number;

		$data->rtgs_bank_name = $rtgs_bank_name;

		$data->cash_from_name = $cash_from_name;

		$data->cash_to_name = $cash_to_name;

		$data->credit = $amount;

		$data->description = $description;



		if($forwarder != "" && $forwarder != null) {

			if(isset($Request->invoice)){

		$aa= "";

		 $total = count($Request->invoice)-1;

        foreach($Request->invoice as $key => $value){

           if($key == $total){

                $invoice = Invoice::findorfail($value);
                $invoice->paid = 1;
                $invoice->save();

                $aa = $aa."".$value;

            } else {
                $aa= $aa."".$value.",";
                $invoice = Invoice::findorfail($value);
                $invoice->paid = 1;
                $invoice->save();

            }


        }
         $data->invoice_list = $aa;

        }
    }

        $data->v_type = "credit";

		$data->save();


		return redirect()->route('voucherlist')->with('success','Voucher successfully Created.');

	}



	public function Debit(Request $Request)
	{
		
		$company = Company::get();
		$transporter = Transporter::get();
		$forwarder = Forwarder::get();

		return view('admin.voucherdebit',compact('company','transporter','forwarder'));
	}





	public function DebitSave(Request $Request)
	{

		$transporter = $Request->transporter_id;
		
		$forwarder = $Request->forwarder_id;

		$fcompany = $Request->fcompany_id;
		
		$company = $Request->company_id;

		$amount = $Request->amount;

		$type = $Request->type;

		$date = $Request->date;

		$chequenumber = $Request->cheque_number;

		$chequebankname = $Request->cheque_bank_name;

		$description = $Request->description;

		$rtgs_paymentby = $Request->rtgs_paymentby;

		$rtgs_transaction = $Request->rtgs_transaction;

		$rtgs_account_number = $Request->rtgs_account_number;

		$rtgs_bank_name = $Request->rtgs_bank_name;

		$cash_from_name = $Request->cash_from_name;

		$cash_to_name = $Request->cash_to_name;



		$data = new Account();

		if($transporter != "" && $transporter != null) {

			$data->to_transporter = $transporter;
		}

		if($forwarder != "" && $forwarder != null) {

			$data->to_forwarder = $forwarder;
		}

		if($fcompany != "" && $fcompany != null) {

			$data->to_company = $fcompany;
		}

		if($company != "" && $company != null) {

			$data->from_company = $company;
		}

		$data->type = $type;
		
		$data->dates = date('Y-m-d',strtotime($date));

		$data->chequenumber = $chequenumber;

		$data->chequebankname = $chequebankname;

		$data->chequebankname = $chequebankname;

		$data->rtgs_paymentby = $rtgs_paymentby;

		$data->rtgs_transaction = $rtgs_transaction;

		$data->rtgs_account_number = $rtgs_account_number;

		$data->rtgs_bank_name = $rtgs_bank_name;

		$data->cash_from_name = $cash_from_name;

		$data->cash_to_name = $cash_to_name;

		$data->debit = $amount;

		$data->description = $description;

        $data->v_type = "debit";

		$data->save();


		return redirect()->route('voucherlist')->with('success','Voucher successfully Created.');

	}


	public function InvoiceBills(Request $Request)
	{


		$data = Invoice::where('forwarder_id',$Request->forwarder)->where('company_id',$Request->company)->where('paid',0)->get();

		return view('admin.invoiceslist',compact('data'));

	}



	public function Delete(Request $Request)
	{

		$data = Account::findorfail($Request->id);

		if($data->v_type == 'credit'){

			$bills = explode(',', $data->invoice_list);

			foreach ($bills as $key => $value) {
				
				$bill = Invoice::withTrashed()->findorfail($value);

				$bill->paid = 0;

				$bill->save();
			}


		}

		$data->deleted_by = Auth::id();
		
		$data->save();

		$data->delete();

		return redirect()->route('voucherlist')->with('success','Voucher successfully Deleted.');

	}



	public function View(Request $Request)
	{

		$data = Account::withTrashed()->findorfail($Request->id);

			

		if($data->from_company != "" && $data->from_company != null){

				$company_data = Company::withTrashed()->findorfail($data->from_company);

				$data->from= $company_data->name;

			} else if($data->from_transporter != "" && $data->from_transporter != null){

				$transporter_data = Transporter::withTrashed()->findorfail($data->from_transporter);

				$data->from= $transporter_data->name;

			} else if($data->from_forwarder != "" && $data->from_forwarder != null){

				$forwarder_data = Forwarder::withTrashed()->findorfail($data->from_forwarder);

				$data->from= $forwarder_data->name;
			}


			if($data->to_company != "" && $data->to_company != null){

				$company_data = Company::withTrashed()->findorfail($data->to_company);

				$data->to= $company_data->name;

			} else if($data->to_transporter != "" && $data->to_transporter != null){

				$transporter_data = Transporter::withTrashed()->findorfail($data->to_transporter);

				$data->to= $transporter_data->name;

			} else if($data->to_forwarder != "" && $data->to_forwarder != null){

				$forwarder_data = Forwarder::withTrashed()->findorfail($data->to_forwarder);

				$data->to= $forwarder_data->name;
			}

			if($data->credit != "" && $data->credit != null){

				$data->mytype = 'Credit';

			} else if($data->debit != "" && $data->debit != null){

				$data->mytype = 'Debit';
			} 

			if($data->credit != "" && $data->credit != null){

				$data->amount = $data->credit;

			} else if($data->debit != "" && $data->debit != null){

				$data->amount = $data->debit;
			} 

			//dd($data);

			$invoice_list = array();

			if($data->invoice_list != ''){


			$data_all = explode(',',$data->invoice_list);

			$total_bills = count($data_all);

			if($total_bills > 0) {

				foreach ($data_all as $key => $value) {	

					$bill_detail = Invoice::withTrashed()->findorfail($value);

					$invoice_list[$key]['no'] = $bill_detail->invoice_no;

					$invoice_list[$key]['date'] = $bill_detail->invoice_date;

					$invoice_list[$key]['total'] = $bill_detail->grand_total;

				}

				}
			} else {

				$total_bills = 0;
			}
	


			

		return view('admin.voucherview',compact('data','total_bills','invoice_list'));
	}

	public function ExpenseList(Request $Request)
	{
		$data= array();

		$data1 = Expense::orderby('created_at','desc')->get();

		//dd($data1);
		
		foreach ($data1 as $key => $value) {

					$data[$key] = $value;

					if($value->company_id != null){

					$company= Company::findorfail($value->company_id);

					$data[$key]['company_name'] = $company->name;	
					}

					if($value->transporter_id){
						
					$transporter= Transporter::withTrashed()->findorfail($value->transporter_id);
						
					if($transporter ){
						$data[$key]['transporter_name'] = $transporter->name;	
					}
					}
				}	

		return view('admin.expenselist',compact('data'));

	}

	public function ExpenseAdd(Request $Request)
	{
			$company = Company::get();


		return view('admin.expense',compact('company'));
	}

	public function ExpenseSave(Request $Request)
	{

		//print_r($Request);

		//dd($Request);
		
		$company = $Request->company_id;

		$amount = $Request->amount;

		$type = $Request->type;

		$date = $Request->date;

		$chequenumber = $Request->cheque_number;

		$chequebankname = $Request->cheque_bank_name;

		$description = $Request->description;

		$rtgs_paymentby = $Request->rtgs_paymentby;

		$rtgs_transaction = $Request->rtgs_transaction;

		$rtgs_account_number = $Request->rtgs_account_number;

		$rtgs_bank_name = $Request->rtgs_bank_name;

		$cash_from_name = $Request->cash_from_name;

		$cash_to_name = $Request->cash_to_name;



		$data = new Account();

		if($company != "" && $company != null) {

			$data->from_company = $company;
		}

		$data->type = $type;
		
		$data->dates = date('Y-m-d',strtotime($date));

		$data->chequenumber = $chequenumber;

		$data->chequebankname = $chequebankname;

		$data->chequebankname = $chequebankname;

		$data->rtgs_paymentby = $rtgs_paymentby;

		$data->rtgs_transaction = $rtgs_transaction;

		$data->rtgs_account_number = $rtgs_account_number;

		$data->rtgs_bank_name = $rtgs_bank_name;

		$data->cash_from_name = $cash_from_name;

		$data->cash_to_name = $cash_to_name;

		$data->debit = $amount;

		$data->description = $description;

        $data->v_type = "expense";


      

		$data->save();


		$expense = new Expense();
		$expense->dates = date('Y-m-d',strtotime($date));
		$expense->company_id = $Request->company_id;
		$expense->account_id = $data->id;
		$expense->reason = $description;
		$expense->type = "company";
		$expense->amount = $amount;
		$expense->save();

		return redirect()->route('expenselist')->with('success','Expense successfully Added.');

	}

	public function ExpenseView(Request $Request)
	{
		//dd($Request,$Request->id);
		$data = Expense::findorfail($Request->id);
		//dd($data);
		if($data->company_id){
			$company_data =Company::findorfail($data->company_id);
			$data['company_name']=$company_data->name;

			if($data->transporter_id != ''){
			$transporter_data = Transporter::findorfail($data->transporter_id);	
			$data['transporter_name']=$transporter_data->name;
			$data['transporter'] =$transporter_data ;
			} else {
				$data['transporter_name']='';
			}

			//dd($data);

			return view('admin.expenseview',compact('data'));
		} else{
			return redirect()->route('expenselist')->with('error','Company Name Not found.');
		}
	}

	public function ExpenseDelete(Request $Request)
	{
		$data = Expense::findorfail($Request->id);
		if($data){
			$ac_id = $data->account_id;
			$data->deleted_by = Auth::id();
			$data->save();
			$data->delete();
			$data1 = Account::findorfail($ac_id);
			$data1->deleted_by = Auth::id();
			$data1->save();
			$data1->delete();
		}

		return redirect()->route('expenselist')->with('success','Expense successfully Deleted.');

	}
	
	



}