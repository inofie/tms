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
use Hash;
use PDF;
use Mail;
use App\Account;
use Config;
use Yajra\DataTables\Html\Builder;
use App\DataTables\InvoiceDataTable;



class InvoiceController extends Controller
{

	public function __construct()
    {
      
    }
	

 	public function UnpaidList(Builder $builder, InvoiceDataTable $dataTable,Request $Request)
 	{
		$html = $builder->columns([
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex','orderable' => false, 'searchable' => false,'title' => 'SR No'],
			['data' => 'id', 'name' => 'id','title' => 'ID','visible' => false],
            ['data' => 'invoice_no', 'name' => 'invoice_no','title' => 'Invoice No'],
            ['data' => 'invoice_date', 'name' => 'invoice_date','title' => 'Invoice Date'],
            ['data' => 'invoice_month', 'name' => 'invoice_month','orderable' => false, 'searchable' => false,'title' => 'Invoice Month'],
            ['data' => 'company_name', 'name' => 'company_name','orderable' => false, 'searchable' => false,'title' => 'Company Name'],
            ['data' => 'forwarder_name', 'name' => 'forwarder_name','orderable' => false, 'searchable' => false,'title' => 'Forwarder Name'],
            ['data' => 'shipper_name', 'name' => 'shipper_name','orderable' => false, 'searchable' => false,'title' => 'Shipper Name'],
            ['data' => 'grand_total', 'name' => 'grand_total','title' => 'Invoice Amount'],
			['data' => 'remaining_amount', 'name' => 'remaining_amount','title' => 'Unpaid Amount'],
			['data' => 'voucher_no', 'name' => 'voucher_no','orderable' => false, 'searchable' => false,'title' => 'Software Shipment Voucher No'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],
         ])->parameters([
			
            "processing" => true,
            "serverSide" => true,
			"language" => [
				"processing" => '<i class="fa fa-spinner fa-spin fa-3x fa-fw" style="z-index:9999;text-align: center;position:absolute;margin:0px auto;"></i>'
			],
			"order" => ["1", "DESC"],
			"dom" => 'lBfrtip',
			"lengthChange"=> true,
			'lengthMenu' => [
				[ 10, 25, 50, -1 ],
				[ '10', '25', '50', 'Show all' ]
			],
			"buttons" => [
				[
					'extend' => 'csvHtml5',
					'exportOptions'=> [
					  'columns'=> [0, 1, 2, 3, 4, 5, 6, 7,8,9]
					]
			],
				[
					'extend'=> 'excelHtml5',
					'exportOptions'=> [
					  'columns'=> [0, 1, 2, 3, 4, 5, 6, 7,8,9]
				]
				],
			   
			],
          
        ]);
		if(request()->ajax()) {
		if(Auth::user()->role == "company") {
		$ff= Company::where('user_id',Auth::user()->id)->first();
		$data1 = Invoice::whereNull('deleted_at')->where('company_id',$ff->id)->where('paid',0);
		}
		else{
		$data1 = Invoice::whereNull('deleted_at')->where('paid',0);
		}
		
		if($Request->year){
			$data1 = $data1->whereYear('invoice_date', $Request->year);
		}
		if($Request->month){
			$data1 = $data1->whereMonth('invoice_date', $Request->month);
		}
		if($Request->company){
			$data1 = $data1->where('company_id', $Request->company);
		}
		if($Request->forwarder){
			$data1 = $data1->where('forwarder_id', $Request->forwarder);
		}
		
		return $dataTable->dataTable($data1)->toJson();
		}
		if(isset($Request->year)){
            $year = $Request->year;
        } else {
    	 $year = '';
        }
        if(isset($Request->month)){
            $month = $Request->month;
        } else {
            $month = '';
        }
		if(isset($Request->forwarder)){
            $forwarder = $Request->forwarder;
        } else {
            $forwarder = '';
        }
        if(isset($Request->company)){
            $company = $Request->company;
        } else {
            $company = '';
        }
		$all_forwarder = Forwarder::get();
        $all_company = Company::where('status',0)->get();
		
		// $data1 = $data1->orderby('id','desc')->get();
		// $data =array();

		// foreach ($data1 as $key => $value) {
 				
		// 	$data[$key]= $value;
		// 	$companydata = Company::withTrashed()->findorfail($value->company_id);
		// 	$data[$key]['company_name'] = $companydata->name;
		// 	$forwarderdata = Forwarder::withTrashed()->findorfail($value->forwarder_id);
		// 	$data[$key]['forwarder_name'] = $forwarderdata->name;
		// 	$accountdata = Account::withTrashed()->where('invoice_list',$value->id)->first();
		// 	if($accountdata){
		// 	$data[$key]['voucher_no'] = $accountdata->id;
		// 	}
		// 	else{
		// 		$data[$key]['voucher_no'] = '';
		// 	}

		// 	$shipment_list = explode(',',$value->ships);
		// 	$shipdata = Shipment::wherein("shipment_no", $shipment_list)->get();
			
		// 	$d_list = "";
		// 	foreach($shipdata as $key2 => $value2){
		// 	if($value2->imports == 1){
		// 	$shippername = $value2->consignee;
		// 	}
		// 	else{
		// 	$shippername = $value2->consignor;
		// 	}
		// 	if($key2 == 0) {
		// 	$d_list = $d_list."".$shippername;	
		// 	}
		// 	else{
		// 	$d_list = $d_list.", ".$shippername;	
		// 	}
			
		// }
		// //dd($d_list);
		// $data[$key]['shipper_name'] = $d_list;
		// }
		
 		return view('admin.invoiceunpaidlist',compact('html','month','year','all_forwarder','company','all_company','forwarder'));

 	}



	 public function PaidList(Builder $builder, InvoiceDataTable $dataTable,Request $Request)
 	{
		$html = $builder->columns([
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex','orderable' => false, 'searchable' => false,'title' => 'SR No'],
			['data' => 'id', 'name' => 'id','title' => 'ID','visible' => false],
            ['data' => 'invoice_no', 'name' => 'invoice_no','title' => 'Invoice No'],
            ['data' => 'invoice_date', 'name' => 'invoice_date','title' => 'Invoice Date'],
            ['data' => 'invoice_month', 'name' => 'invoice_month','orderable' => false, 'searchable' => false,'title' => 'Invoice Month'],
            ['data' => 'company_name', 'name' => 'company_name','orderable' => false, 'searchable' => false,'title' => 'Company Name'],
            ['data' => 'forwarder_name', 'name' => 'forwarder_name','orderable' => false, 'searchable' => false,'title' => 'Forwarder Name'],
            ['data' => 'shipper_name', 'name' => 'shipper_name','orderable' => false, 'searchable' => false,'title' => 'Shipper Name'],
            ['data' => 'grand_total', 'name' => 'grand_total','title' => 'Invoice Amount'],
			['data' => 'voucher_no', 'name' => 'voucher_no','orderable' => false, 'searchable' => false,'title' => 'Software Shipment Voucher No'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],
         ])->parameters([
			
            "processing" => true,
            "serverSide" => true,
			"language" => [
				"processing" => '<i class="fa fa-spinner fa-spin fa-3x fa-fw" style="z-index:9999;text-align: center;position:absolute;margin:0px auto;"></i>'
			],
			"order" => ["1", "DESC"],
			"dom" => 'lBfrtip',
			"lengthChange"=> true,
			'lengthMenu' => [
				[ 10, 25, 50, -1 ],
				[ '10', '25', '50', 'Show all' ]
			],
			"buttons" => [
				[
					'extend' => 'csvHtml5',
					'exportOptions'=> [
					  'columns'=> [0, 1, 2, 3, 4, 5, 6, 7,8]
					]
			],
				[
					'extend'=> 'excelHtml5',
					'exportOptions'=> [
					  'columns'=> [0, 1, 2, 3, 4, 5, 6, 7,8]
				]
				],
			   
			],
          
        ]);
		if(request()->ajax()) {
		if(Auth::user()->role == "company") {
		$ff= Company::where('user_id',Auth::user()->id)->first();
		$data1 = Invoice::whereNull('deleted_at')->where('company_id',$ff->id)->where('paid',1);
		}
		else{
		$data1 = Invoice::whereNull('deleted_at')->where('paid',1);
		}
		
		if($Request->year){
			$data1 = $data1->whereYear('invoice_date', $Request->year);
		}
		if($Request->month){
			$data1 = $data1->whereMonth('invoice_date', $Request->month);
		}
		if($Request->company){
			$data1 = $data1->where('company_id', $Request->company);
		}
		if($Request->forwarder){
			$data1 = $data1->where('forwarder_id', $Request->forwarder);
		}
		
		return $dataTable->dataTable($data1)->toJson();
		}
		if(isset($Request->year)){
            $year = $Request->year;
        } else {
    	 $year = '';
        }
        if(isset($Request->month)){
            $month = $Request->month;
        } else {
            $month = '';
        }
		if(isset($Request->forwarder)){
            $forwarder = $Request->forwarder;
        } else {
            $forwarder = '';
        }
        if(isset($Request->company)){
            $company = $Request->company;
        } else {
            $company = '';
        }
		$all_forwarder = Forwarder::get();
        $all_company = Company::where('status',0)->get();
		
		// $data1 = $data1->orderby('id','desc')->get();
		// $data =array();

		// foreach ($data1 as $key => $value) {
 				
		// 	$data[$key]= $value;
		// 	$companydata = Company::withTrashed()->findorfail($value->company_id);
		// 	$data[$key]['company_name'] = $companydata->name;
		// 	$forwarderdata = Forwarder::withTrashed()->findorfail($value->forwarder_id);
		// 	$data[$key]['forwarder_name'] = $forwarderdata->name;
		// 	$accountdata = Account::withTrashed()->where('invoice_list',$value->id)->first();
		// 	if($accountdata){
		// 	$data[$key]['voucher_no'] = $accountdata->id;
		// 	}
		// 	else{
		// 		$data[$key]['voucher_no'] = '';
		// 	}

		// 	$shipment_list = explode(',',$value->ships);
		// 	$shipdata = Shipment::wherein("shipment_no", $shipment_list)->get();
			
		// 	$d_list = "";
		// 	foreach($shipdata as $key2 => $value2){
		// 	if($value2->imports == 1){
		// 	$shippername = $value2->consignor;
		// 	}
		// 	else{
		// 	$shippername = $value2->consignee;
		// 	}
		// 	if($key2 == 0) {
		// 	$d_list = $d_list."".$shippername;	
		// 	}
		// 	else{
		// 	$d_list = $d_list.", ".$shippername;	
		// 	}
			
		// }
		// //dd($d_list);
		// $data[$key]['shipper_name'] = $d_list;
		// }
		
 		return view('admin.invoicepaidlist',compact('html','month','year','all_forwarder','company','all_company','forwarder'));

 	}


 	public function InvoiceAdd(Request $Request)
 	{

 		$data = array();
 		$data['company'] = Company::orderby('name','asc')->get();
 		$data['Forwarder'] = Forwarder::orderby('name','asc')->get();

 		
 		return view('admin.invoiceadd',compact('data'));

 	}

 	public function ShipmentList(Request $Request)
 	{

 		$data = Shipment::where('company',$Request->company)->where('forwarder',$Request->forwarder)
		->where('status',2)->where('paid',0)->get();
		
 		return view('admin.myshipmentlist',compact('data'));

 	}


 	public function ShipmentGST(Request $Request)
 	{
        

 		$data = array();
 		$expense = array();
 		$trucks = array();

 		$all_ship = explode(',', $Request->shipment_nos);
        /*echo "<pre>" ;
        print_r($all_ship);*/

 		//dd(count($all_ship));

 		foreach ($all_ship as $key => $value) {

 			$ship_data  = Shipment::where('shipment_no', $value)->first();
 			$truckss = Shipment_Driver::where('shipment_no',$value)->get();
 			$expense1= Expense::where('shipment_no',$value)->get();
 			$aa = array();
            $bb  = array();

 			foreach ($expense1 as $key1 => $value1) {


 				$aa[$key1] = array(
 					'shipment_no'=>$value1->shipment_no,
 					'reason'=>$value1->reason,
 					'sub_amount'=>$value1->sub_amount,
 					'detention_amount'=>$value1->detention_amount,
 					'labour_amount'=>$value1->labour_amount,
 					'amount'=>$value1->amount,
 				);

 				
 			}

 			foreach ($truckss as $key2 => $value2) {


                if($key2 == 0){

                    $bb[$key2] = array(
                    'shipment_no'=>$value2->shipment_no,
                    'truck_no'=>$value2->truck_no,
                    'freight'=>$ship_data->invoice_amount,
                    );

                } else {

                    $bb[$key2] = array(
                    'shipment_no'=>$value2->shipment_no,
                    'truck_no'=>$value2->truck_no,
                    'freight'=>0,
                    );

                }
 				
 				


 			}

 			
 			$trucks = array_merge($trucks,$bb);
 			 $expense = array_merge($expense,$aa);



             

             
 		}

       /* echo "<pre>";
       print_r($trucks);
             echo "<br>";
             print_r($expense);

             dd();
*/
 		$gst = $Request->gst;	
 	//dd($data,$expense,$trucks, $gst);
 		return view('admin.mygstform',compact('data', 'expense','trucks','gst'));

 	}


 	public function ShipmentSave(Request $Request)
 	{


        //dd($Request);
		if(date('m') >= 4 ){
            $financial_year = date('y').'-'.(date('y') + 1);
        }else{
            $financial_year = (date('y')-1).'-'.date('y'); 
        }
		
 		$shipment_no= $Request->invoice_no."/".$financial_year;

 		if($Request->totalshipment == 1){

 			$shipdata = Shipment::where('shipment_no',$Request->shipments)->first();

 			$descriptions = $shipdata->description;

 			$shipdata->paid=1;

 			$shipdata->save();
 		} else {

 			$all_ship = explode(',', $Request->shipments);

 			$descriptions = "";

 			foreach ($all_ship as $key => $value) {

 				$shipdata = Shipment::where('shipment_no',$value)->first();
 				$shipdata->paid=1;
				$shipdata->save();
 				
 				$descriptions = $descriptions."".$value."-";

 				$ship_truck = Shipment_Driver::where('shipment_no',$value)->get();

 				$t_total = count($ship_truck) - 1;

 				foreach ($ship_truck as $key1 => $value1) {

 					if($key1 == $t_total){

 						$descriptions = $descriptions." ".$value1->truck_no."<br>";

 					} else {

 						$descriptions = $descriptions." ".$value1->truck_no.",";

 					}

 				}


 			}

 		}

 		

 		$invoice = new Invoice();
 		$invoice->invoice_date = date('Y-m-d',strtotime($Request->invoice_date));
        $invoice->ships = implode(',',array_unique(explode(',', $Request->ship)));
 		$invoice->invoice_no = $shipment_no;
 		$invoice->company_id = $Request->company;
 		$invoice->forwarder_id = $Request->forwarder;
 		$invoice->descriptions = $descriptions;
 		$invoice->total_shipment = $Request->totalshipment;
 		$invoice->gst = $Request->gst;
 		$invoice->cgst = $Request->cgst;
 		$invoice->sgst = $Request->sgst;
 		$invoice->igst = $Request->igst;
 		$invoice->utgst = $Request->utgst;
 		$invoice->totls_gst = $Request->totalgst;
 		$invoice->sub_total = $Request->ftotal;
 		$invoice->extra_amount = $Request->extotal;
 		$invoice->grand_total = $Request->grandtotal;
        $invoice->mygst=$Request->gstoption;
 		$invoice->paid = 0;
 		$invoice->myid = uniqid();
 		$invoice->created_by = Auth::id();
 		$invoice->save();



 		$all_trucks = explode(',', $Request->trucks);
 		$all_freight = explode(',', $Request->freight);
 		$all_detention = explode(',', $Request->detention);
 		$all_loading = explode(',', $Request->loading);
 		$all_other = explode(',', $Request->other);
 		$all_total = explode(',', $Request->total);
        $ship = explode(',', $Request->ship);
     

 		foreach ($all_trucks as $key2 => $value2) {

 			$invoice_truck = new Invoice_Truck();
            $invoice_truck->ship_id = $ship[$key2];
 			$invoice_truck->invoice_id = $invoice->id;
 			$invoice_truck->invoice_no = $shipment_no;
 			$invoice_truck->truck_no = $value2;
 			$invoice_truck->fright = $all_freight[$key2];
 			$invoice_truck->detention = $all_detention[$key2];
 			$invoice_truck->loading = $all_loading[$key2];
			$invoice_truck->remarks = $Request->remarks;
 			$invoice_truck->other = $all_other[$key2];
 			$invoice_truck->totals = $all_total[$key2];
 			$invoice_truck->created_by = Auth::id();
 			$invoice_truck->save();
 			
 		}


 				$company = Company::findorfail($Request->company);
                $company->bill_no = (int) filter_var($Request->invoice_no, FILTER_SANITIZE_NUMBER_INT)+1;
                $company->save();



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



        $data123 = new Account();
        
        $data123->to_forwarder = $Request->forwarder;
       
        $data123->from_company = $Request->company;
       
        $data123->type = 'invoice';
        
        $data123->dates = date('Y-m-d',strtotime($Request->invoice_date));

        $data123->debit = $Request->grandtotal;

        $data123->description = "Invoice Bill.";

        $data123->v_type = "debit";

        $data123->invoice_list = $invoice->id;

        $data123->save();


 		return 1;


 	}


	
		public function Download(Request $Request)
 	{

 		$data = Invoice::where('myid',$Request->id)->first();

        $forw_data = Forwarder::withTrashed()->findorfail($data->forwarder_id); 

        $comp_data = Company::withTrashed()->findorfail($data->company_id);

        $data->forwarder_name = $forw_data->name;
        $data->forwarder_address = $forw_data->address;
        $data->forwarder_phone = $forw_data->phone;
        $data->forwarder_email = $forw_data->email;

        if($data->gst_no != null){
			$data->forwarder_gst = $data->gst_no ;
		}else{ 
			$data->forwarder_gst = $forw_data->gst_no ;
		}
	
		$account_qr_id = ['1','3','4','5'];
		
		$data->qr_code='';	
		$data->is_download = 1;
		if(in_array($comp_data->id, $account_qr_id)){
			$data->qr_code = $comp_data->id.'_id.jpeg';
		}
			
        $all_shipment = explode(',',$data->ships);
        $data->shipment_list = explode(',',$data->ships);
        $trucklist = array();
		$containers = array();
		$seals = array();
		$shippings =array();
		foreach($data as $key => $value){
			$invoicedata = Invoice_Truck::where('invoice_id',$data->id)->first();
			$data->fright = $invoicedata->fright;
			$data->detention = $invoicedata->detention;
			$data->loading = $invoicedata->loading;
			$data->other = $invoicedata->other;
			$data->remarks = $invoicedata->remarks;
		}
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

                    $shipdata =Shipment::withTrashed()->where('shipment_no',$value)->first();

                    $mydates[$key] =date('d-m-Y',strtotime($shipdata->date));
					
					if($shipdata->container_no){
						$containers[] = $shipdata->container_no;
					}
					if($shipdata->seal_no){
						$seals[] = $shipdata->seal_no;
					}
					if($shipdata->shipping_line){
						$shippings[] = $shipdata->seal_no;
					}
					$data->weight += $shipdata->weight;
					
        }
			
		
		$data->container = implode(',',array_unique($containers));
		$data->seal = implode(',',array_unique($seals));
		$data->shipping = implode(',',array_unique($shippings));
		
		$data->trucklist = $mytrucks;

        $data->alldates = $mydates;

        $f_shipdata = Shipment::withTrashed()->where('shipment_no',$all_shipment[0])->first();

        $data->lcl = $f_shipdata->lcl;
        $data->fcl = $f_shipdata->fcl;
		//$data->weight = $f_shipdata->weight;
		$data->consignee = $f_shipdata->consignee;
		$data->consignee_address = $f_shipdata->consignee_address;
		$data->consignor = $f_shipdata->consignor;
		$data->consignor_address = $f_shipdata->consignor_address;
		$data->imports = $f_shipdata->imports;
		$data->forwarder_ref_no = $f_shipdata->forwarder_ref_no;
			
		if(sizeof($all_shipment) > 1){
			$data->consignee = '-';
			$data->consignee_address = '';
		}
      
		if(sizeof($all_shipment) > 1){
			$data->from = '';
			$data->to = '';
			$data->forwarder_ref_no = '';
			$data->weight = '';
			if($f_shipdata->imports == 1){
				$data->consignee = '';
			}else {
				$data->consignor = '';
			}
		} else {
			$data->from = $f_shipdata->from1;
			if($f_shipdata->to2 != ''){
               $data->to = $f_shipdata->to1.",".$f_shipdata->to2;
		   	} else {
				$data->to = $f_shipdata->to1;
		   	}
		}

       //dd($data);
        if($comp_data->lr =='yoginilr' ){

           
          //  return View('bill.yoginibill',compact('data','comp_data'));


            $pdf = PDF::loadView('bill.yoginibill',compact('data','comp_data'));

             return $pdf->download('Yogini Bill '.$data->invoice_no.'.pdf');

        }

         if($comp_data->lr =='ssilr' ){

            $pdf = PDF::loadView('bill.ssibill',compact('data','comp_data'));

            return $pdf->download('SSI Bill'.$data->invoice_no.'.pdf');

        }

        if($comp_data->lr =='hanshlr' ){

            $pdf = PDF::loadView('bill.hanshbill',compact('data','comp_data'));

            return $pdf->download('Hansh Bill '.$data->invoice_no.'.pdf');

        }


        if($comp_data->lr =='bmflr' ){

            $pdf = PDF::loadView('bill.bmfbill',compact('data','comp_data'));

            return $pdf->download('BMF Bill '.$data->invoice_no.'.pdf');

        }







 		//return view('bill.yoginibill',compact('data','comp_data'));
       //return view('bill.ssibill',compact('data','comp_data'));
       //return view('bill.hanshbill',compact('data','comp_data'));
       // return view('bill.bmfbill',compact('data','comp_data'));
        



 	}


	 public function Downloadcreditnote(Request $Request)
 	{
		
		$acc = Account::where('id',$Request->id)->first();
		
 		$data = Invoice::where('id',$acc->invoice_list)->first();
		
        $forw_data = Forwarder::withTrashed()->findorfail($data->forwarder_id); 

        $comp_data = Company::withTrashed()->findorfail($data->company_id);

        $data->forwarder_name = $forw_data->name;
        $data->forwarder_address = $forw_data->address;
        $data->forwarder_phone = $forw_data->phone;
        $data->forwarder_email = $forw_data->email;

        if($data->gst_no != null){
			$data->forwarder_gst = $data->gst_no ;
		}else{ 
			$data->forwarder_gst = $forw_data->gst_no ;
		}
	
		$account_qr_id = ['1','3','4','5'];
		
		$data->qr_code='';	
		$data->is_download = 1;
		if(in_array($comp_data->id, $account_qr_id)){
			$data->qr_code = $comp_data->id.'_id.jpeg';
		}
			
        $all_shipment = explode(',',$data->ships);
        $data->shipment_list = explode(',',$data->ships);
        $trucklist = array();
		$containers = array();
		$seals = array();
		$shippings =array();
		foreach($data as $key => $value){
			$invoicedata = Invoice_Truck::where('invoice_id',$data->id)->first();
			$data->fright = $invoicedata->fright;
			$data->detention = $invoicedata->detention;
			$data->loading = $invoicedata->loading;
			$data->other = $invoicedata->other;
			$data->remarks = $invoicedata->remarks;
		}
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

                    $shipdata =Shipment::withTrashed()->where('shipment_no',$value)->first();

                    $mydates[$key] =date('d-m-Y',strtotime($shipdata->date));
					
					if($shipdata->container_no){
						$containers[] = $shipdata->container_no;
					}
					if($shipdata->seal_no){
						$seals[] = $shipdata->seal_no;
					}
					if($shipdata->shipping_line){
						$shippings[] = $shipdata->seal_no;
					}
					$data->weight += $shipdata->weight;
					
        }
			
		
		$data->container = implode(',',array_unique($containers));
		$data->seal = implode(',',array_unique($seals));
		$data->shipping = implode(',',array_unique($shippings));
		
		$data->trucklist = $mytrucks;

        $data->alldates = $mydates;

        $f_shipdata = Shipment::withTrashed()->where('shipment_no',$all_shipment[0])->first();

        $data->lcl = $f_shipdata->lcl;
        $data->fcl = $f_shipdata->fcl;
		//$data->weight = $f_shipdata->weight;
		$data->consignee = $f_shipdata->consignee;
		$data->consignee_address = $f_shipdata->consignee_address;
		$data->consignor = $f_shipdata->consignor;
		$data->consignor_address = $f_shipdata->consignor_address;
		$data->imports = $f_shipdata->imports;
		$data->forwarder_ref_no = $f_shipdata->forwarder_ref_no;
			
		if(sizeof($all_shipment) > 1){
			$data->consignee = '-';
			$data->consignee_address = '';
		}
      
		if(sizeof($all_shipment) > 1){
			$data->from = '';
			$data->to = '';
			$data->forwarder_ref_no = '';
			$data->weight = '';
			if($f_shipdata->imports == 1){
				$data->consignee = '';
			}else {
				$data->consignor = '';
			}
		} else {
			$data->from = $f_shipdata->from1;
			if($f_shipdata->to2 != ''){
               $data->to = $f_shipdata->to1.",".$f_shipdata->to2;
		   	} else {
				$data->to = $f_shipdata->to1;
		   	}
		}

       //dd($data);
        if($comp_data->lr =='yoginilr' ){

           
          //  return View('bill.yoginibill',compact('data','comp_data'));


            $pdf = PDF::loadView('bill_creditnote.yoginibill',compact('data','comp_data','acc'));

             return $pdf->download('Yogini Bill '.$data->invoice_no.'.pdf');

        }

         if($comp_data->lr =='ssilr' ){

            $pdf = PDF::loadView('bill_creditnote.ssibill',compact('data','comp_data','acc'));

            return $pdf->download('SSI Bill'.$data->invoice_no.'.pdf');

        }

        if($comp_data->lr =='hanshlr' ){

            $pdf = PDF::loadView('bill_creditnote.hanshbill',compact('data','comp_data','acc'));

            return $pdf->download('Hansh Bill '.$data->invoice_no.'.pdf');

        }


        if($comp_data->lr =='bmflr' ){

            $pdf = PDF::loadView('bill_creditnote.bmfbill',compact('data','comp_data','acc'));

            return $pdf->download('BMF Bill '.$data->invoice_no.'.pdf');

        }

 		//return view('bill.yoginibill',compact('data','comp_data'));
       //return view('bill.ssibill',compact('data','comp_data'));
       //return view('bill.hanshbill',compact('data','comp_data'));
       // return view('bill.bmfbill',compact('data','comp_data'));

 	}
	 public function InvoiceCreditnote(Request $Request)
	 {
		
		$forwarder = Forwarder::get();
		$invoice =  Invoice::where('myid',$Request->id)->first();
		//dd($invoice);

		return view('admin.vouchercreditnote',compact('forwarder','invoice'));
	 }
 
 
 
	 public function InvoiceUpdatenote(Request $Request)
	 {
		
		
		$forwarder = $Request->forwarder_id;
		$amount = $Request->amount;

		$invoice =  Invoice::where('id',$Request->id)->first();
		//dd($invoice->remaining_amount);

		if($invoice->remaining_amount != null){
			$finalamount = $invoice->remaining_amount - $amount;	
		}
		else{
			$finalamount = $invoice->grand_total - $amount;
		}

		//$finalamount = $invoice->grand_total - $Request->amount;
		if($finalamount < 0){
		$invoice->remaining_amount = 0;
		}else{
		$invoice->remaining_amount = $finalamount;
		}
		
		$invoice->save();

		$data = new Account();

		if($forwarder != "" && $forwarder != null) {

			$data->from_forwarder = $forwarder;
		}
		
		$data->dates = date('Y-m-d');
		$data->credit = $amount;
		$data->to_company = $invoice->company_id;
		$data->description = $Request->description;
		$data->invoice_list = $invoice->id;

        $data->v_type = "credit";

		$data->save();


		return redirect()->route('unpaidshipmentlist')->with('success','Credit note successfully Created.');
				 
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

		if($data->gst_no != null){
			$data->forwarder_gst = $data->gst_no ;
		}else{ 
			$data->forwarder_gst = $forw_data->gst_no ;
		}

        
		$account_qr_id = ['1','3','4','5'];
		$data->is_download = 0;
		$data->qr_code='';	
		if(in_array($comp_data->id, $account_qr_id)){
			$data->qr_code = $comp_data->id.'_id.jpeg';
		}
        $all_shipment = explode(',',$data->ships);
        $data->shipment_list = explode(',',$data->ships);
        $trucklist = array();
		$containers = array();
		$seals = array();
		$shippings =array();
		$data->weight = 0;
		foreach($data as $key => $value){
			$invoicedata = Invoice_Truck::where('invoice_id',$data->id)->first();
			$data->fright = $invoicedata->fright;
			$data->detention = $invoicedata->detention;
			$data->loading = $invoicedata->loading;
			$data->other = $invoicedata->other;
			$data->remarks = $invoicedata->remarks;
		}
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

                    $shipdata =Shipment::withTrashed()->where('shipment_no',$value)->first();
			
                    $mydates[$key] =date('d-m-Y',strtotime($shipdata->date));
					
					if($shipdata->container_no){
						$containers[] = $shipdata->container_no;
					}
					if($shipdata->seal_no){
						$seals[] = $shipdata->seal_no;
					}
					if($shipdata->shipping_line){
						$shippings[] = $shipdata->seal_no;
					}
					$data->weight += $shipdata->weight;
        }
			
		
		$data->container = implode(',',array_unique($containers));
		$data->seal = implode(',',array_unique($seals));
		$data->shipping = implode(',',array_unique($shippings));

        $data->trucklist = $mytrucks;

        $data->alldates = $mydates;

        $f_shipdata = Shipment::withTrashed()->where('shipment_no',$all_shipment[0])->first();

        $data->lcl = $f_shipdata->lcl;
        $data->fcl = $f_shipdata->fcl;
		//$data->weight = $f_shipdata->weight;
		$data->consignee = $f_shipdata->consignee;
		$data->consignee_address = $f_shipdata->consignee_address;
		$data->consignor = $f_shipdata->consignor;
		$data->consignor_address = $f_shipdata->consignor_address;
		$data->imports = $f_shipdata->imports;
		$data->forwarder_ref_no = $f_shipdata->forwarder_ref_no;
		if(sizeof($all_shipment) > 1){
			$data->consignee = '-';
			$data->consignee_address = '';
		}
      
		if(sizeof($all_shipment) > 1){
			$data->from = '';
			$data->to = '';
			$data->forwarder_ref_no = '';
			$data->weight = '';
			if($f_shipdata->imports == 1){
				$data->consignee = '';
			}else {
				$data->consignor = '';
			}
		} else {
			$data->from = $f_shipdata->from1;
			if($f_shipdata->to2 != ''){
               $data->to = $f_shipdata->to1.",".$f_shipdata->to2;
		   	} else {
				$data->to = $f_shipdata->to1;
		   	}
		}
		
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
            return view('bill.bmfbillView',compact('data','comp_data'));

        }



    }
	public function getGST(Request $Request){
        $states = Forwarder::where('id', $Request->forwarder_id)->get();
		$option = '<input></input>';
		if(isset($Request->id)){
		foreach($states as $state) {
			$option .= '<input value="'.$state->gst_no.'"></input>';
		}
        //$option = $states->gst_no;
		// $option = '<input value="'.$option.'"></input>';
        echo $option;
    }
}


    public function InvoiceEdit(Request $Request)
   {

        $data = Invoice::where('myid',$Request->id)->first();
		if($data->gst_no != null){
			$data['gst_no'] = $data->gst_no ;
		}else{
			 $gst= Forwarder::where('id', $data->forwarder_id)->first();
			 $data['gst_no'] = $gst->gst_no ;
		}

		// if(isset($Request->forwarder_id)){
		// 	$ff = Forwarder::where('id', $Request->forwarder_id)->first();
		// 	$data['gst_no'] = $ff->gst_no ;
		// }

		$forwarder = Forwarder::orderby('name','asc')->get();
      
        $trucks = Invoice_Truck::where('invoice_id',$data->id)->get();
           
        return view('admin.invoiceedit',compact('data','trucks','forwarder'));


    }



    public function InvoiceUpdate(Request $Request)
    {
        //dd($Request->all());
	  

        $myid = explode(',', $Request->myid);
        $freight = explode(',', $Request->freight);
        $detention = explode(',', $Request->detention);
        $loading = explode(',', $Request->loading);
        $other = explode(',', $Request->other);
        $total = explode(',', $Request->total);
		$truck_number = explode(',', $Request->truck_number);

        

        foreach ($myid as $key => $value) {

            $data = Invoice_Truck::findorfail($value);
			$data->truck_no = $truck_number[$key];
			$data->invoice_no = $Request->invoice_no;
			$data->remarks = $Request->remarks;
            $data->fright = $freight[$key];
            $data->detention = $detention[$key];
            $data->loading = $loading[$key];
            $data->other = $other[$key];
			$data->remarks = $Request->remarks;
            $data->totals = $total[$key];
            $data->save();

        }

        $data1 = Invoice::findorfail($Request->invoiceid);
        $data1->gst = $Request->gst;
		$data1->invoice_no = $Request->invoice_no;
		$data1->forwarder_id = $Request->forwarder_id;
		$data1->gst_no = $Request->gst_no;
	//	dd($data1);
        $data1->cgst = $Request->cgst;
        $data1->sgst = $Request->sgst;
        $data1->igst = $Request->igst;
        $data1->utgst = $Request->utgst;
        $data1->totls_gst = $Request->totalgst;
        $data1->sub_total = $Request->ftotal;
        $data1->extra_amount = $Request->extotal;
        $data1->grand_total = $Request->grandtotal;
        $data1->mygst=$Request->gstoption;
        $data1->updated_by = Auth::id();
        $data1->save();


        $data123 = Account::where('invoice_list',$data1->id)->first();      
        $data123->debit = $Request->grandtotal;
        $data123->save();
		
        return 1; 
		
    }
 	

    public function InvoiceDelete(Request $Request)
    {
            
        $data = Invoice::where('myid',$Request->id)->first();
        $data->deleted_by = Auth::id();
        $data->save();


        $data1= Invoice_Truck::where('invoice_id',$data->id)->get();
         

        foreach ($data1 as $key => $value) {
            
            $shipdata = Shipment::where('shipment_no',$value->ship_id)->first();
            $shipdata->paid=0;
            $shipdata->save();      

        }

        $data->delete();


        $data2 = Account::where('invoice_list',$data->id)->where('type','invoice')->first();
		if($data2){
	        $data2->deleted_by=Auth::id();
    	    $data2->save();
        	$data2->delete();
		}
        return redirect()->route('unpaidshipmentlist')->with('success','Invoice Sucessfully Deleted.');
    }

    

}