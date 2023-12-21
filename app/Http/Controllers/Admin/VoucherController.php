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
use Yajra\DataTables\Html\Builder;
use App\DataTables\VoucherDataTable;
use App\DataTables\ExpenseDataTable;

class VoucherController extends Controller
{

	public function List(Builder $builder, VoucherDataTable $dataTable,Request $Request)
	{

		$html = $builder->columns([
            ['data' => 'id', 'name' => 'id','title' => 'No.'],
            ['data' => 'dates', 'name' => 'dates','title' => 'Date'],
			['data' => 'invoice_no', 'name' => 'invoice.invoice_no','title' => 'Invoice no'],
            ['data' => 'from', 'name' => 'from','orderable' => false, 'searchable' => false,'title' => 'From'],
            ['data' => 'to', 'name' => 'to','orderable' => false, 'searchable' => false,'title' => 'To'],
			['data' => 'type', 'name' => 'type','orderable' => false, 'searchable' => false,'title' => 'Type'],
			['data' => 'amount', 'name' => 'amount','orderable' => false, 'searchable' => false,'title' => 'Amount'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],
         ])->parameters([
			
            "processing" => true,
            "serverSide" => true,
			"order" => ["0", "DESC"],
			"dom" => 'lfrtip',
			"lengthChange"=> true,
			'lengthMenu' => [
				[ 10, 25, 50, -1 ],
				[ '10', '25', '50', 'all' ]
			]
        ]);
		if(request()->ajax()) {
		$data1 = Account::where('v_type','!=','expense');
		
		if($Request->invoice_no){
			$invoice_no = $Request->invoice_no;
			$invoice=Invoice::where('invoice_no',$invoice_no)->first();
			$invoiveNo = $invoice['id'];
			$data1 = $data1->where('invoice_list',$invoiveNo);
		}

		return $dataTable->dataTable($data1)->toJson();
		}
		$all_invoice = Invoice::whereNull('deleted_at')->groupBy('invoice_no')->orderBy('id','desc')->get();
		if(isset($Request->invoice_no)){
            $invoice_nos = $Request->invoice_no;
        } else {
            $invoice_nos = '';
        }
		// foreach ($data1 as $key => $value) {
			
		// 	$data[$key] = $value;

		// 	if($value->from_company != "" && $value->from_company != null){

		// 		$company_data = Company::withTrashed()->findorfail($value->from_company);

		// 		$data[$key]['from']= $company_data->name;

		// 	} else if($value->from_transporter != "" && $value->from_transporter != null){

		// 		$transporter_data = Transporter::withTrashed()->findorfail($value->from_transporter);

		// 		$data[$key]['from']= $transporter_data->name;

		// 	} else if($value->from_forwarder != "" && $value->from_forwarder != null){

		// 		$forwarder_data = Forwarder::withTrashed()->findorfail($value->from_forwarder);

		// 		$data[$key]['from']= $forwarder_data->name;
		// 	}


		// 	if($value->to_company != "" && $value->to_company != null){

		// 		$company_data = Company::withTrashed()->findorfail($value->to_company);

		// 		$data[$key]['to']= $company_data->name;

		// 	} else if($value->to_transporter != "" && $value->to_transporter != null){

		// 		$transporter_data = Transporter::withTrashed()->findorfail($value->to_transporter);

		// 		$data[$key]['to']= $transporter_data->name;

		// 	} else if($value->to_forwarder != "" && $value->to_forwarder != null){

		// 		$forwarder_data = Forwarder::withTrashed()->findorfail($value->to_forwarder);

		// 		$data[$key]['to']= $forwarder_data->name;
		// 	}

		// 	if($value->credit != "" && $value->credit != null){

		// 		$data[$key]['type'] = 'Credit';

		// 	} else if($value->debit != "" && $value->debit != null){

		// 		$data[$key]['type'] = 'Debit';
		// 	} 

		// 	if($value->credit != "" && $value->credit != null){

		// 		$data[$key]['amount'] = $value->credit;

		// 	} else if($value->debit != "" && $value->debit != null){

		// 		$data[$key]['amount'] = $value->debit;
		// 	} 

		// }

//dd($data);
		
		return view('admin.voucherlist',compact('html','all_invoice','invoice_nos'));
	}

	public function List2(Builder $builder, VoucherDataTable $dataTable,Request $Request)
	{
		//dd($Request->id);
		$html = $builder->columns([
            ['data' => 'id', 'name' => 'id','title' => 'No.'],
            ['data' => 'dates', 'name' => 'dates','title' => 'Date'],
			['data' => 'invoice_no', 'name' => 'invoice_no','title' => 'Invoice no'],
            ['data' => 'from', 'name' => 'from','orderable' => false, 'searchable' => false,'title' => 'From'],
            ['data' => 'to', 'name' => 'to','orderable' => false, 'searchable' => false,'title' => 'To'],
			['data' => 'type', 'name' => 'type','orderable' => false, 'searchable' => false,'title' => 'Type'],
			['data' => 'amount', 'name' => 'amount','orderable' => false, 'searchable' => false,'title' => 'Amount'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],
         ])->parameters([
			
            "processing" => true,
            "serverSide" => true,
			"order" => ["0", "DESC"],
			"dom" => 'lfrtip',
        ]);
		if(request()->ajax()) {
		$data1 = Account::where('v_type','!=','expense');
		
		if($Request->invoice_no){
			$invoice_no = $Request->invoice_no;
			$invoice=Invoice::where('invoice_no',$invoice_no)->first();
			$invoiveNo = $invoice['id'];
			$data1 = $data1->where('invoice_list',$invoiveNo);
		}

		return $dataTable->dataTable($data1)->toJson();
		}
		$all_invoice = Invoice::whereNull('deleted_at')->groupBy('invoice_no')->orderBy('id','desc')->get();
		if(isset($Request->id)){
			$invoice=Invoice::where('id',$Request->id)->first();
            $invoice_nos = $invoice->invoice_no;
        } else {
            $invoice_nos = '';
        }

		
		return view('admin.voucherlist2',compact('html','all_invoice','invoice_nos'));
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
		if($Request->invoice2) {
			$this->validate($Request, [
			'tds_amount' => 'required',
			
			 ],[
			 'tds_amount.required' => "Please Select tds amount",
			 
			 ]);
		  }
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
		if($Request->tds_amount){
		$invoice = Invoice::Where('id',$Request->invoice)->first();
		if($invoice->remaining_amount != null){
			$data->credit = $invoice->remaining_amount - $Request->tds_amount;	
		}
		else{
			$data->credit = $invoice->grand_total - $Request->tds_amount;
		}
		
		$data->tds_amount = $Request->tds_amount;
		$data->invoice_list = $invoice->id;
		}else{
			$data->credit = $amount;
		}

		$data->description = $description;



		if($forwarder != "" && $forwarder != null) {

		if($Request->invoice != null && $Request->invoice3 == null && $Request->tds_amount == null){

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

		elseif($Request->invoice3){
		$invoice = Invoice::Where('id',$Request->invoice3)->first();
		$data->invoice_list = $invoice->id;
		if($invoice->remaining_amount != null){
			$invoice->remaining_amount = $invoice->remaining_amount - $Request->amount;	
		}
		else{
			$invoice->remaining_amount = $invoice->grand_total - $Request->amount;
		}
        // $invoice->remaining_amount = $invoice->grand_total - $amount;
        $invoice->save();
		}
		elseif($Request->tds_amount){
		$invoice = Invoice::Where('id',$Request->invoice)->first();
		$invoice->paid = 1;
		$invoice->save();
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
		//dd($data);
		if($data->invoice_list){

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

	// public function ExpenseList(Request $Request)
	// {
	// 	$data= array();

	// 	$data1 = Expense::orderby('created_at','desc')->get();

	// 	//dd($data1);
		
	// 	foreach ($data1 as $key => $value) {

	// 				$data[$key] = $value;

	// 				if($value->company_id != null){

	// 				$company= Company::withTrashed()->findorfail($value->company_id);

	// 				$data[$key]['company_name'] = $company->name;	
	// 				}

	// 				if($value->transporter_id){
						
	// 				$transporter= Transporter::withTrashed()->findorfail($value->transporter_id);
						
	// 				if($transporter ){
	// 					$data[$key]['transporter_name'] = $transporter->name;	
	// 				}
	// 				}
	// 				if($value->forwarder_id){
						
	// 					$forwarder= Forwarder::withTrashed()->findorfail($value->forwarder_id);
							
	// 					if($forwarder ){
	// 						$data[$key]['forwarder_name'] = $forwarder->name;	
	// 					}
	// 					}
	// 			}	

	// 	return view('admin.expenselist',compact('data'));

	// }
	public function ExpenseList(Builder $builder, ExpenseDataTable $dataTable,Request $Request)
	{
		$html = $builder->columns([
            ['data' => 'dates', 'name' => 'dates','title' => 'Date'],
            ['data' => 'company_id', 'name' => 'company_id','orderable' => false, 'searchable' => false,'title' => 'Company Name'],
            ['data' => 'transporter_id', 'name' => 'transporter_id','orderable' => false, 'searchable' => false,'title' => 'Transporter Name'],
			['data' => 'forwarder_id', 'name' => 'forwarder_id','orderable' => false, 'searchable' => false,'title' => 'Forwarder Name'],
			['data' => 'type', 'name' => 'type','orderable' => false, 'searchable' => false,'title' => 'Type'],
			['data' => 'shipment_no', 'name' => 'shipment_no','orderable' => false, 'searchable' => false,'title' => 'Shipment No'],
			['data' => 'reason', 'name' => 'reason','title' => 'Details'],
			['data' => 'amount', 'name' => 'amount','orderable' => false, 'searchable' => false,'title' => 'Amount'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],
         ])->parameters([

            "processing" => true,
            "serverSide" => true,
			"order" => ["0", "DESC"],
			"dom" => 'lfrtip',
        ]);
		if(request()->ajax()) {
			$data1 =Expense::orderby('id','desc')->get();
			return $dataTable->dataTable($data1)->toJson();
		}
		return view('admin.expenselist',compact('html'));
	}

	public function ExpenseAdd(Request $Request)
	{
			$company = Company::get();
			$transporter = Transporter::get();
			$forwarder = Forwarder::get();

		return view('admin.expense',compact('company','transporter','forwarder'));
	}

	public function ExpenseSave(Request $Request)
	{

		//print_r($Request);

		//dd($Request->all());
		
		$company = $Request->company_id;
		$transporter = $Request->transporter_id;
		$forwarder = $Request->forwarder_id;
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
		if($transporter != "" && $transporter != null) {

			$data->from_transporter = $transporter;
		}
		if($forwarder != "" && $forwarder != null) {

			$data->from_forwarder = $forwarder;
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
		$expense->transporter_id = $Request->transporter_id;
		$expense->forwarder_id = $Request->forwarder_id;
		$expense->account_id = $data->id;
		$expense->reason = $description;
		if($Request->company_id != null){
			$expense->type = "company";
		}
		elseif($Request->transporter_id != null){
			$expense->type = "transporter";
		}
		elseif($Request->forwarder_id != null){
			$expense->type = "forwarder";
		}
		$expense->amount = $amount;
		$expense->save();

		return redirect()->route('expenselist')->with('success','Expense successfully Added.');

	}
	public function ExpenseEdit(Request $Request)
    {
		$company = Company::withTrashed()->get();
		$transporter = Transporter::withTrashed()->get();
		$forwarder = Forwarder::withTrashed()->get();
        $data = Expense::where('id',$Request->id)->first();
		$expense = Account::where('id',$data->account_id)->first();
        

        return view('admin.expenseedit',compact('data','company','expense','transporter','forwarder'));

    }



    public function ExpenseUpdate(Request $Request)
    {
		//dd($Request->all());
		$company = $Request->company_id;
		$transporter = $Request->transporter_id;
		$forwarder = $Request->forwarder_id;
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

		
		$expense = Expense::where('id',$Request->id)->first();
		$expense->dates = date('Y-m-d',strtotime($date));
		$expense->company_id = $Request->company_id;
		$expense->transporter_id = $Request->transporter_id;
		$expense->forwarder_id = $Request->forwarder_id;
		$expense->reason = $description;
		if($Request->company_id != null){
			$expense->type = "company";
		}
		elseif($Request->transporter_id != null){
			$expense->type = "transporter";
		}
		elseif($Request->forwarder_id != null){
			$expense->type = "forwarder";
		}
		
		$expense->amount = $amount;
		$expense->save();

		$data = Account::where('id',$expense->account_id)->first();
		
		$data->from_company = $company;
		
		$data->from_transporter = $transporter;
	
		$data->from_forwarder = $forwarder;
		
		$data->type = $type;
		$data->dates = date('Y-m-d',strtotime($date));
		$data->chequenumber = isset($chequenumber) ? ($chequenumber) : NULL;
		$data->chequebankname = isset($chequebankname) ? ($chequebankname) : NULL;
		$data->rtgs_paymentby = isset($rtgs_paymentby) ? ($rtgs_paymentby) : NULL;
		$data->rtgs_transaction = isset($rtgs_transaction) ? ($rtgs_transaction) : NULL;
		$data->rtgs_account_number = isset($rtgs_account_number) ? ($rtgs_account_number) : NULL;
		$data->rtgs_bank_name = isset($rtgs_bank_name) ? ($rtgs_bank_name) : NULL;
		$data->cash_from_name = isset($cash_from_name) ? ($cash_from_name) : NULL;
		$data->cash_to_name = isset($cash_to_name) ? ($cash_to_name) : NULL;
		$data->debit = $amount;
		$data->description = $description;
        $data->v_type = "expense";
		//dd($data);
		$data->save();

		
		return redirect()->route('expenselist')->with('success','Expense updated successfully.');
                
    }
	public function ExpenseView(Request $Request)
	{
		//dd($Request,$Request->id);
		$data = Expense::findorfail($Request->id);
		//dd($data);
		if($data->company_id){
			$company_data =Company::withTrashed()->findorfail($data->company_id);
			$data['company_name']=$company_data->name;

			if($data->transporter_id != ''){
			$transporter_data = Transporter::withTrashed()->findorfail($data->transporter_id);	
			$data['transporter_name']=$transporter_data->name;
			$data['transporter'] =$transporter_data ;
			} else {
				$data['transporter_name']='';
			}
			return view('admin.expenseview',compact('data'));
		} 
		elseif($data->forwarder_id){
			$forwarder_data =Forwarder::withTrashed()->findorfail($data->forwarder_id);
			$data['forwarder_name']=$forwarder_data->name;
			return view('admin.expenseview',compact('data'));
		}
		elseif($data->transporter_id){
			$transporter_data = Transporter::withTrashed()->findorfail($data->transporter_id);	
			$data['transporter_name']=$transporter_data->name;
			return view('admin.expenseview',compact('data'));
		}
		
	
		else{
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