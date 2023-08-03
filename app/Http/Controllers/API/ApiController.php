<?php

namespace App\Http\Controllers\API;

use App\Account;
use App\Cargostatus;
use App\Company;
use App\Driver;
use App\Employee;
use App\Expense;
use Carbon\Carbon;
use App\Forwarder;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WebNotificationController;
use App\Invoice;
use App\Shipment;
use App\Shipment_Driver;
use App\Shipment_Summary;
use App\Shipment_Transporter;
use App\Transporter;
use App\Truck;
use App\User;
use App\Warehouse;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use PDF;
use Config;
use Validator;
use App\Jobs\CertificateApproveJob;
use App\Http\Response\APIResponse;
use App\Helper\GlobalHelper;
use App\Notification;
use DB;

class ApiController extends Controller {
	public function __construct()
    {
        $this->APIResponse = new APIResponse();
    }

	private function checkversion($version) {

		$myversion = env('MYAPP_VERSION');

		if ($myversion != $version) {

			return 1;

		} else {

			return 0;

		}

	}

//1
	public function ApplicationCheck(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '420'], 200);
			}

			$user = User::withTrashed()->findorfail($Request->user_id);

			if ($user->status == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '420'], 200);

			}
			if ($Request->role != 'driver'){
			$user->device_token = $Request->device_token;
			$user->device_type = $Request->device_type;
			$user->save();
			}

			if ($Request->role == 'driver'){
				$driver = Driver::withTrashed()->findorfail($Request->user_id);

				if ($driver->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}
				$driver->device_token = $Request->device_token;
				$driver->device_type = $Request->device_type;
				$driver->save();
			}
			if ($Request->role == 'admin') {

				$user = User::withTrashed()->where('id', $Request->user_id)->first();

				if ($user->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

			}
			if ($Request->role == 'company') {

				$comp = Company::withTrashed()->where('user_id', $Request->user_id)->first();

				if ($comp->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

			} else if ($Request->role == 'transporter') {

				$trans = Transporter::withTrashed()->where('user_id', $Request->user_id)->first();

				if ($trans->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

			} else if ($Request->role == 'forwarder') {

				$forwa = Forwarder::withTrashed()->where('user_id', $Request->user_id)->first();

				if ($forwa->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

			} else if ($Request->role == 'employee') {

				$emp = Employee::withTrashed()->where('user_id', $Request->user_id)->first();

				if ($emp->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

				$comp = Company::withTrashed()->findorfail($emp->company_id);

				if ($comp->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

			}
			$otherData['unread_count']=Notification::where('notification_to',$Request->user_id)->where('read_status','unread')->count();
			return response()->json(['status' => 'success', 'message' => 'Successfully.', 'data' => $otherData, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}






	//2
	public function Login(Request $Request) {

		try {

			//dd(env('MYAPP_VERSION'));
			//dd($Request->all());
			$data=$Request->all();
			$rules = array(
                'username' => 'required',
				'password' => 'required'
            );

            $messages = [

            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return $this->APIResponse->respondOk(__($validator->errors()->first()));
            }
            else
			{
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$ucount = User::where('username', $Request->username)->count();

			$dcount = 0;

			if ($ucount == 0) {

				$dcount = Driver::where('phone', $Request->username)->count();

				if ($dcount == 0) {

					return response()->json(['status' => 'failed', 'message' => 'Username not registered.', 'data' => json_decode('{}'), 'code' => '500'], 200);
				}

			}

			if ($ucount > 0) {

				$data = User::where('username', $Request->username)->first();

				if ($data->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '500'], 200);

				}

				$credentials = $Request->only('username', 'password');

				if (Auth::attempt($credentials)) {

					$data->device_token = $Request->device_token;

					$data->device_type = $Request->device_type;

					$data->save();

					$tokens = [$Request->device_token];

					if ($data->role == "admin" || $data->role == "company") {

						$com = Company::where('user_id', $data->id)->first();
						if(!$com){
							$data['other_id'] = 0;
						}
						else{
						if ($com->status == 1) {

							return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '500'], 200);
						}

						$data['other_id'] = $com->id;
					}
					}

					if ($data->role == "employee") {

						$emp = Employee::where('user_id', $data->id)->first();

						$com = Company::where('user_id', $emp->company_id)->first();
						if ($emp->status == 1) {
							return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '500'], 200);
						}
						// $data['other_id']=$com->id;
						$data['other_id'] = $emp->company_id;

					}

					if ($data->role == "transporter") {

						$detail = Transporter::where("user_id", $data->id)->first();
						if ($detail->status == 1) {
							return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '500'], 200);
						}
						$data['other_id'] = $detail->id;

					}

					if ($data->role == "forwarder") {

						$detail = Forwarder::where("user_id", $data->id)->first();
						if ($detail->status == 1) {
							return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '500'], 200);
						}
						$data['other_id'] = $detail->id;

					}

					return response()->json(['status' => 'success', 'message' => 'Login Successfully.', 'data' => $data, 'code' => '200'], 200);

				} else {

					return response()->json(['status' => 'failed', 'message' => 'Email & password are wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

				}

			}

			if ($dcount > 0) {
/*
				$pass = Hash::make($Request->password);
				return response()->json(['status' => 'success', 'message' => 'Login Successfully.', 'data' => $pass, 'code' => '200'], 200);
*/



				$data2 = Driver::where('phone', $Request->username)->first();

				if ($data2->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your account is deactivated. Please contact admin to active your account.', 'data' => json_decode('{}'), 'code' => '500'], 200);

				}

				if (Hash::check($Request->password, $data2->password)) {

					$data2->device_token = $Request->device_token;

					$data2->device_type = $Request->device_type;

					$data2->save();

					//$data = $c_data2

					$mydata = array();

					$mydata['name'] = $data2->name;

					$mydata['id'] = $data2->id;

					$mydata['other_id'] = $data2->transporter_id;

					$mydata['email'] = '';

					$mydata['role'] = 'driver';

					return response()->json(['status' => 'success', 'message' => 'Login successfully.', 'data' => $mydata, 'code' => '200'], 200);

				} else {

					return response()->json(['status' => 'failed', 'message' => 'Username & password are wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

				}

			}

			return response()->json(['status' => 'failed', 'message' => 'Username & password are wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}


		// Logout

	public function Logout(Request $Request) {

		try {

				if($Request->role == "driver") {

					$driver_dataa = Driver::where('device_token', $Request->device_token)->count();

					if($driver_dataa > 0 ) {

						$driver_data = Driver::where('device_token', $Request->device_token)->first();

						$driver_data->device_token = null;

						$driver_data->save();

						return response()->json(['status' => 'success', 'message' => ' Logout Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);


					} else {

						return response()->json(['status' => 'success', 'message' => ' Logout Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);
					}


				} else {

					$user_dataa = User::where('device_token', $Request->device_token)->count();

					if($user_dataa > 0) {

						$user_data = User::where('device_token', $Request->device_token)->first();

						$user_data->device_token = null;

						$user_data->save();

						return response()->json(['status' => 'success', 'message' => 'Logout Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);


					} else {

							return response()->json(['status' => 'success', 'message' => ' Logout Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);
					}

				}


			} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//3
	public function CompanyList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			//dd($all);
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;
				if ($Request->role == 'admin'|| $Request->role == 'company') {
				$data = Company::whereNull('deleted_at');
				$data = $data->paginate($perPage);


				if (!empty($data)) {
					$message = 'Company List Successfully.';
					$dataa = $data;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}
			}
			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{
			if ($Request->role == 'admin'|| $Request->role == 'company') {
			$data = Company::whereNull('deleted_at')->get();
			return response()->json(['status' => 'success', 'message' => 'Company List Successfully.', 'data' => $data, 'code' => '200'], 200);
			}

			else{
				return response()->json(['status' => 'success', 'message' => 'This user have not permission.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

		}
	}
	catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//4
	public function CompanyAdd(Request $Request) {

		try {
			$data=$Request->all();
			$rules = array(
                'username' => 'required',
				'password' => 'required'
            );

            $messages = [

            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return $this->APIResponse->respondOk(__($validator->errors()->first()));
            }
            else
			{
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = User::where('username', $Request->username)->count();

			if ($data > 0) {

				return response()->json(['status' => 'failed', 'message' => 'This username already registred in our system.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			} else {

				$user = new User();
				$user->name = $Request->name;
				$user->username = $Request->username;
				$user->password = Hash::make($Request->password);
				$user->role = "company";
				$user->created_by = $Request->user_id;
				$user->save();

			}

			$comapny = new Company();
			$comapny->user_id = $user->id;
			$comapny->name = $Request->name;
			$comapny->address = $Request->address;
			$comapny->phone = $Request->phone;
			$comapny->email = $Request->email;
			$comapny->gst_no = $Request->gst;
			$comapny->created_by = $Request->user_id;
			$comapny->myid = uniqid();

			$path = public_path('/uploads');

			if ($Request->hasFile('logo') && !empty($Request->file('logo'))) {
				$file_name = time() . "1" . $Request->logo->getClientOriginalName();
				$Request->logo->move($path, $file_name);
				$comapny->logo = $file_name;
			}

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Company added Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//5
	public function CompanyDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Company::findorfail($Request->id);
			$user = User::withTrashed()->findorfail($data->user_id);
			$data->username = $user->username;

			return response()->json(['status' => 'success', 'message' => 'Comapny Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	// 6
	public function CompanyEdit(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			// $data = User::where('username', $Request->username)->where('id', '!=', $Request->user_id2)->count();

			// if ($data > 0) {

			// 	return response()->json(['status' => 'failed', 'message' => 'This username already registred in our system.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			// }

			$comapny = Company::findorfail($Request->id);
			$comapny->name = $Request->name;
			$comapny->address = $Request->address;
			$comapny->phone = $Request->phone;
			$comapny->email = $Request->email;
			$comapny->gst_no = $Request->gst;
			$comapny->status = $Request->status;
			$comapny->updated_by = $Request->user_id;

			$user = User::withTrashed()->findorfail($comapny->user_id);
			$user->status = $Request->status;
			$user->username = $Request->username;
			if ($Request->password != "" && $Request->password != " " && $Request->password != "null" && $Request->password != null) {
				$user->password = Hash::make($Request->password);
			}
			$user->save();

			$path = public_path('/uploads');

			if ($Request->hasFile('logo') && !empty($Request->file('logo'))) {
				$file_name = time() . "1" . $Request->logo->getClientOriginalName();
				$Request->logo->move($path, $file_name);
				$comapny->logo = $file_name;
			}

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Company Updated Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//7
	public function CompanyDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$aa = Shipment::where('status', 0)->orwhere('status', 1)->where('company', $Request->id)->count();

			if ($aa > 0) {

				return response()->json(['status' => 'failed', 'message' => 'Unable to delete.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Company::findorfail($Request->id);
			$data->deleted_by = $Request->user_id;
			$data->save();
			$user = User::findorfail($data->user_id);
			$user->deleted_by = $Request->user_id;
			$user->save();
			$empl = Employee::where('company_id', $Request->id)->get();

			foreach ($empl as $key => $value) {
				$ee = Employee::findorfail($value->id);
				$ee->deleted_by = $Request->user_id;
				$ee->save();

				$user1 = User::findorfail($ee->user_id);
				$user1->deleted_by = $Request->user_id;
				$user1->save();

				$user1->delete();
				$ee->delete();

			}

			$ware = Warehouse::where('company_id', $Request->id)->get();

			foreach ($ware as $key => $value2) {
				$ee1 = Warehouse::findorfail($value2->id);
				$ee1->deleted_by = $Request->user_id;
				$ee1->save();
				$ee1->delete();

			}

			if ($user->delete() && $data->delete()) {

				return response()->json(['status' => 'success', 'message' => 'Company deleted Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//8
	public function ForwarderList(Request $Request) {

		try {
			$all = $Request->all();
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				$data = Forwarder::whereNull('deleted_at')->orderBy('id','desc');
				$data = $data->paginate($perPage);
				foreach($data as $key => $value){
					$username = User::withTrashed()->where('id',$value->user_id)->first();
					$data[$key]['username'] = $username->username;
				}


				if (!empty($data)) {
					$message = 'Forwarder List Successfully.';
					$dataa = $data;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}else{
			$data = Forwarder::whereNull('deleted_at')->orderBy('id','desc')->get();
			foreach($data as $key => $value){
				$username = User::withTrashed()->where('id',$value->user_id)->first();
				$data[$key]['username'] = $username->username;
			}

			return response()->json(['status' => 'success', 'message' => 'Forwarder List Successfully.', 'data' => $data, 'code' => '200'], 200);
			}
		}

	catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//9
	public function ForwarderAdd(Request $Request) {

		try {
			$data=$Request->all();
			$rules = array(
                'username' => 'required',
				'password' => 'required'
            );

            $messages = [

            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return $this->APIResponse->respondOk(__($validator->errors()->first()));
            }
            else
			{
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = User::where('username', $Request->username)->count();

			if ($data > 0) {

				return response()->json(['status' => 'failed', 'message' => 'This username already registred in our system.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			} else {

				$user = new User();
				$user->name = $Request->name;
				$user->username = $Request->username;
				$user->password = Hash::make($Request->password);
				$user->role = "forwarder";
				$user->created_by = $Request->user_id;

				$user->save();

			}

			$comapny = new Forwarder();

			$comapny->user_id = $user->id;

			$comapny->name = $Request->name;

			$comapny->address = $Request->address;

			$comapny->phone = $Request->phone;

			$comapny->email = $Request->email;

			$comapny->gst_no = $Request->gst;

			$comapny->created_by = $Request->user_id;

			$comapny->myid = uniqid();

			if ($comapny->save()) {

				$user->myid = $comapny->id;

				return response()->json(['status' => 'success', 'message' => 'Forwarder added Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//10
	public function ForwarderDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Forwarder::findorfail($Request->id);
			$user = User::withTrashed()->findorfail($data->user_id);
			$data->username = $user->username;

			return response()->json(['status' => 'success', 'message' => 'Forwarder Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//11
	public function ForwarderEdit(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = Forwarder::findorfail($Request->id);

			$uu = User::withTrashed()->findorfail($comapny->user_id);

			if ($Request->username != $uu->username) {

				$data = User::where('username', $Request->username)->count();

				if ($data > 0) {

					return redirect()->back()->withInput()->with('error', 'This username already registred in our system.');
				}

				$user = User::withTrashed()->findorfail($comapny->user_id);

				$user->username = $Request->username;

				$user->save();

			}

			if ($Request->password != "" && $Request->password != " " && $Request->password != "null" && $Request->password != null) {

				$user = User::withTrashed()->findorfail($comapny->user_id);

				$user->password = Hash::make($Request->password);

				$user->save();

			}

			$comapny->name = $Request->name;

			$comapny->address = $Request->address;

			$comapny->phone = $Request->phone;

			$comapny->email = $Request->email;

			$comapny->gst_no = $Request->gst;

			$comapny->status = $Request->status;

			$comapny->updated_by = $Request->user_id;

			$user = User::withTrashed()->findorfail($comapny->user_id);

			$user->status = $Request->status;

			$user->save();

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Forwarder Updated Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//12
	public function ForwarderDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$aa = Shipment::where('status', 1)->where('forwarder', $Request->id)->count();
			//dd($aa);

			if ($aa > 0) {

				return response()->json(['status' => 'success', 'message' => 'Sorry you can not delete this forwarder, Because it is already connected in some shipments.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			else{
			$data = Forwarder::findorfail($Request->id);
			$data->deleted_by = $Request->user_id;
			$data->save();
			$user = User::findorfail($data->user_id);
			$user->deleted_by = $Request->user_id;
			$user->save();

			if ($user->delete() && $data->delete()) {

				return response()->json(['status' => 'success', 'message' => 'Forwarder deleted Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//13
	public function TruckList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				$data = Truck::where('status', 0)->whereNull('deleted_at');
				$data = $data->paginate($perPage);


				if (!empty($data)) {
					$message = 'Truck Detail Successfully.';
					$dataa = $data;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{
			$data = Truck::where('status', 0)->whereNull('deleted_at')->get();

			return response()->json(['status' => 'success', 'message' => 'Truck Detail Successfully.', 'data' => $data, 'code' => '200'], 200);
			}
		}
		catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//14
	public function TruckDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Truck::findorfail($Request->id);

			return response()->json(['status' => 'success', 'message' => 'Truck Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//15
	public function TransporterList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				$data = Transporter::whereNull('deleted_at')->orderBy('id','desc');
				$data = $data->paginate($perPage);

				foreach ($data as $key => $value) {
                    $username = User::withTrashed()
                        ->where("id", $value->user_id)
                        ->first();
                    $data[$key]["username"] = $username->username;
                }

				if (!empty($data)) {
					$message = 'Transporter Detail Successfully.';
					$dataa = $data;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
		else{
			$data = Transporter::whereNull('deleted_at')->orderBy('id','desc')->get();
			foreach ($data as $key => $value) {
				$username = User::withTrashed()
					->where("id", $value->user_id)
					->first();
				$data[$key]["username"] = $username->username;
			}

			return response()->json(['status' => 'success', 'message' => 'Transporter Detail Successfully.', 'data' => $data, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//16
	public function TransporterDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Transporter::findorfail($Request->id);
			$user = User::withTrashed()->findorfail($data->user_id);
			$data->username = $user->username;

			return response()->json(['status' => 'success', 'message' => 'Transporter Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//17
	public function TransporterAdd(Request $Request) {

		try {
			$data=$Request->all();
			$rules = array(
                'username' => 'required',
				'password' => 'required'
            );

            $messages = [

            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return $this->APIResponse->respondOk(__($validator->errors()->first()));
            }
            else
			{
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = User::where('username', $Request->username)->count();

			if ($data > 0) {

				return response()->json(['status' => 'failed', 'message' => 'This username already registred in our system.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			} else {

				$user = new User();

				$user->name = $Request->name;

				$user->username = $Request->username;

				$user->password = Hash::make($Request->password);

				$user->role = "transporter";

				$user->created_by = $Request->user_id;

				$user->save();

			}

			$comapny = new Transporter();

			$comapny->user_id = $user->id;

			$comapny->name = $Request->name;

			$comapny->phone = $Request->phone;

			$comapny->truck_no = $Request->truck_no;

			$comapny->licence_no = $Request->licence_no;

			$comapny->pan = $Request->pan_no;

			$comapny->created_by = $Request->user_id;

			$comapny->myid = uniqid();

			$path = public_path('/uploads');

			$file_name1 = null;
			$file_name2 = null;
			$file_name3 = null;

			if ($Request->hasFile('rc_book') && !empty($Request->file('rc_book'))) {
				$file_name1 = time() . "1" . $Request->rc_book->getClientOriginalName();
				$Request->rc_book->move($path, $file_name1);
				$comapny->rc_book = $file_name1;
			}

			if ($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))) {
				$file_name2 = time() . "2" . $Request->pan_card->getClientOriginalName();
				$Request->pan_card->move($path, $file_name2);
				$comapny->pan_card = $file_name2;
			}

			if ($Request->hasFile('licence') && !empty($Request->file('licence'))) {
				$file_name3 = time() . "3" . $Request->licence->getClientOriginalName();
				$Request->licence->move($path, $file_name3);
				$comapny->licence = $file_name3;
			}

			if ($comapny->save()) {

				$comapny2 = new Driver();

				$comapny2->name = $Request->name;

				$comapny2->phone = $Request->phone;

				$comapny2->truck_no = $Request->truck_no;

				$comapny2->licence_no = $Request->licence_no;

				$comapny2->pan = $Request->pan_no;

				$comapny2->transporter_id = $comapny->id;

				$comapny2->self = 1;

				$comapny2->created_by = $Request->user_id;

				$comapny2->password = Hash::make($Request->password);

				$comapny2->myid = uniqid();

				$comapny2->rc_book = $file_name1;

				$comapny2->pan_card = $file_name2;

				$comapny2->licence = $file_name3;

				$comapny2->save();

				return response()->json(['status' => 'success', 'message' => 'Transporter added Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	// 18
	public function TransporterEdit(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = Transporter::findorfail($Request->id);

			$comapny->name = $Request->name;

			$comapny->phone = $Request->phone;

			$comapny->truck_no = $Request->truck_no;

			$comapny->licence_no = $Request->licence_no;

			$comapny->pan = $Request->pan_no;

			$comapny->status = $Request->status;

			$comapny->updated_by = $Request->user_id;

			$user = User::withTrashed()->findorfail($comapny->user_id);

			$user->status = $Request->status;

			$user->username = $Request->username;

			if ($Request->password != "" && $Request->password != " " && $Request->password != "null" && $Request->password != null) {

				$user->password = Hash::make($Request->password);
			}

			$user->save();

			$path = public_path('/uploads');

			if ($Request->hasFile('rc_book') && !empty($Request->file('rc_book'))) {
				$file_name = time() . "1" . $Request->rc_book->getClientOriginalName();
				$Request->rc_book->move($path, $file_name);
				$comapny->rc_book = $file_name;
			}

			if ($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))) {
				$file_name = time() . "2" . $Request->pan_card->getClientOriginalName();
				$Request->pan_card->move($path, $file_name);
				$comapny->pan_card = $file_name;
			}

			if ($Request->hasFile('licence') && !empty($Request->file('licence'))) {
				$file_name = time() . "3" . $Request->licence->getClientOriginalName();
				$Request->licence->move($path, $file_name);
				$comapny->licence = $file_name;
			}

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Transporter added Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//19
	public function TransporterDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Transporter::findorfail($Request->id);

			$data->deleted_by = $Request->user_id;

			$data->save();

			$driver = Driver::where('transporter_id', $data->id)->get();

			foreach ($driver as $key => $value) {

				$drive = Driver::findorfail($value->id);
				$drive->deleted_by = $Request->user_id;
				$drive->save();
				$drive->delete();

			}

			$user = User::findorfail($data->user_id);

			$user->deleted_by = $Request->user_id;

			$user->save();

			if ($user->delete() && $data->delete()) {

				return response()->json(['status' => 'success', 'message' => 'Transporter deleted Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//20
	public function WarehouseList(Request $Request)
    {
        try {
            $check = $this->checkversion($Request->version);
            $all = $Request->all();
            if ($check == 1) {
                return response()->json(
                    [
                        "status" => "failed",
                        "message" => "Please update this application.",
                        "data" => json_decode("{}"),
                        "code" => "500",
                    ],
                    200
                );
            }
            if (isset($all["page"]) && $all["offset"]) {
                //pagination coding
                $page = 1;
                $perPage = 10;
                if (isset($all["page"]) && !empty($all["page"])) {
                    $page = $all["page"];
                }
                if (isset($all["offset"]) && !empty($all["offset"])) {
                    $perPage = $all["offset"];
                }
                $offset = ($page - 1) * $perPage;

                $data = Warehouse::whereNull("deleted_at")->orderBy('id','desc');

                $data = $data->paginate($perPage);
                foreach ($data as $key => $value) {
                    $data[$key] = $value;

                    $detail = Company::findorfail($value->company_id);
                    $data[$key]["company_name"] = $detail->name;
                    $username = User::withTrashed()
                        ->where("id", $value->user_id)
                        ->first();
						if($username){
                    $data[$key]["username"] = $username->username;
				}else{
					$data[$key]["username"] = '';
				}
                }


                if (!empty($data)) {
                    $message = "Warehouse Detail Successfully.";
                    $dataa = $data;
                    return $this->APIResponse->successWithPagination(
                        $message,
                        $dataa
                    );
                } else {
                    return $this->APIResponse->respondNotFound(
                        __("No Record Found")
                    );
                }
            } else {
                $data = [];
                $data1 = Warehouse::whereNull("deleted_at")->orderBy('id','desc')->get();

                foreach ($data1 as $key => $value) {
                    $data[$key] = $value;

                    $detail = Company::findorfail($value->company_id);
                    $data[$key]["company_name"] = $detail->name;
                    $username = User::withTrashed()
                        ->where("id", $value->user_id)
                        ->first();
						if($username){
                    $data[$key]["username"] = $username->username;
						}else{
							$data[$key]["username"] = '';
						}
                }

                return response()->json(
                    [
                        "status" => "success",
                        "message" => "Warehouse Detail Successfully.",
                        "data" => $data,
                        "code" => "200",
                    ],
                    200
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    "status" => "failed",
                    "message" => $e->getMessage(),
                    "data" => json_decode("{}"),
                    "code" => "500",
                ],
                200
            );
        }
    }

	//21
	public function WarehouseDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Warehouse::findorfail($Request->id);

			$detail = Company::findorfail($data->company_id);
			$data['company_name'] = $detail->name;

			return response()->json(['status' => 'success', 'message' => 'Transporter Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//22
	public function WarehouseAdd(Request $Request) {

		try {
			$data=$Request->all();
			$rules = array(
                'username' => 'required',
				'password' => 'required'
            );

            $messages = [

            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return $this->APIResponse->respondOk(__($validator->errors()->first()));
            }
            else
			{
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			$data = User::where('username', $Request->username)->count();

			if ($data > 0) {

				return response()->json(['status' => 'failed', 'message' => 'This username already registred in our system.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			} else {

				$user = new User();

				$user->name = $Request->name;

				$user->username = $Request->username;

				$user->password = Hash::make($Request->password);

				$user->role = "warehouse";

				$user->created_by = $Request->user_id;

				$user->save();

			}
			$comapny = new Warehouse();

			$comapny->name = $Request->name;
			$comapny->user_id = $user->id;
			$comapny->address = $Request->address;

			$comapny->phone = $Request->phone;

			$comapny->gst = $Request->gst;

			$comapny->pan = $Request->pan_no;

			$comapny->created_by = $Request->user_id;

			$comapny->company_id = $Request->other_id;

			$comapny->myid = uniqid();

			$path = public_path('/uploads');

			if ($Request->hasFile('address_proof') && !empty($Request->file('address_proof'))) {
				$file_name = time() . "1" . $Request->address_proof->getClientOriginalName();
				$Request->address_proof->move($path, $file_name);
				$comapny->address_proof = $file_name;
			}

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Warehouse added Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//23
	public function WarehouseEdit(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = Warehouse::findorfail($Request->id);

			$comapny->name = $Request->name;

			$comapny->address = $Request->address;

			$comapny->phone = $Request->phone;

			$comapny->gst = $Request->gst;

			$comapny->pan = $Request->pan_no;

			$comapny->status = $Request->status;

			$comapny->company_id = $Request->other_id;

			$comapny->updated_by = $Request->user_id;

			$path = public_path('/uploads');

			if ($Request->hasFile('address_proof') && !empty($Request->file('address_proof'))) {
				$file_name = time() . "1" . $Request->address_proof->getClientOriginalName();
				$Request->address_proof->move($path, $file_name);
				$comapny->address_proof = $file_name;
			}

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Warehouse Updated Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//24
	public function WarehouseDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Warehouse::findorfail($Request->id);
			$data->deleted_by = $Request->user_id;
			$data->save();

			if ($data->delete()) {

				return response()->json(['status' => 'success', 'message' => 'Warehouse deleted Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//25
	public function DriverList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;
				$data = array();
				$check = User::where('other_id',$Request->other_id)->first();

				if($check){
					$checks = $check->role ;
				}else{
					$checks = null;
				}
				if ($Request->role == 'admin' && $Request->other_id == 0 || $Request->role == 'company' && $checks == 'company') {

					$data1 = Driver::where('self',0)->whereNull('deleted_at')->orderBy('id','desc');

				}
				else{
					$data1 = Driver::where('transporter_id', $Request->other_id)->whereNull('deleted_at')->orderBy('id','desc')->where('self', 0);
				}

				if ($Request->role == 'employee') {

					$data1 = Driver::where('self',0)->whereNull('deleted_at')->orderBy('id','desc');

				}
				if ($Request->role == 'transporter') {

					$data1 = Driver::where('transporter_id', $Request->other_id)->whereNull('deleted_at')->orderBy('id','desc')->where('self', 0);

				}
				$data1 = $data1->paginate($perPage);

				foreach ($data1 as $key => $value) {
					$data1[$key] = $value;
					if($value->transporter_id){
					$details = Transporter::withTrashed()->findorfail($value->transporter_id);

					$data1[$key]['transporter_name'] = $details->name;
					}else{
						$data1[$key]['transporter_name'] = '';
					}
				}

				if (!empty($data1)) {
					$message = 'Driver List Successfully.';
					$dataa = $data1;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No record found'));
				}

			}
			else{
			$data = array();
			$check = User::where('other_id',$Request->other_id)->first();
			if($check){
				$checks = $check->role ;
			}else{
				$checks = null;
			}
			if ($Request->role == 'admin' && $Request->other_id == 0 || $Request->role == 'company' && $checks == 'company') {

				$data1 = Driver::where('self',0)->whereNull('deleted_at')->orderBy('id','desc')->get();

			}
			else{
				$data1 = Driver::where('transporter_id', $Request->other_id)->whereNull('deleted_at')->orderBy('id','desc')->where('self', 0)->get();
			}

			if ($Request->role == 'employee') {

				$data1 = Driver::where('self',0)->whereNull('deleted_at')->orderBy('id','desc')->get();

			}

			if ($Request->role == 'transporter' ) {

				$data1 = Driver::where('transporter_id', $Request->other_id)->whereNull('deleted_at')->orderBy('id','desc')->where('self', 0)->get();

			}
			foreach ($data1 as $key => $value) {
				$data[$key] = $value;
				if($value->transporter_id){
				$details = Transporter::withTrashed()->findorfail($value->transporter_id);
				$data[$key]['transporter_name'] = $details->name;
				}else{
					$data[$key]['transporter_name'] = '';
				}
			}

			return response()->json(['status' => 'success', 'message' => 'Driver List Successfully.', 'data' => $data, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//26
	public function DriverDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Driver::withTrashed()->findorfail($Request->id);
			$details = Transporter::withTrashed()->findorfail($data->transporter_id);
			$data['transporter_name'] = $details->name;

			return response()->json(['status' => 'success', 'message' => 'Driver Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//27
	public function DriverAdd(Request $Request) {

		try {

				$data=$Request->all();
				$rules = array(
					'phone' => 'required|unique:driver,phone',
					'password' => 'required'
				);

				$messages = [

				];
				$validator = Validator::make($data, $rules, $messages);
				if ($validator->fails()) {
					return $this->APIResponse->respondOk(__($validator->errors()->first()));
				}
				else
				{
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = new Driver();

			$comapny->name = $Request->name;

			$comapny->phone = $Request->phone;

			$comapny->truck_no = $Request->truck_no;

			$comapny->licence_no = $Request->licence_no;

			$comapny->pan = $Request->pan_no;

			$comapny->transporter_id = $Request->other_id;

			$comapny->created_by = $Request->user_id;

			$comapny->password = Hash::make($Request->password);

			$comapny->myid = uniqid();

			$path = public_path('/uploads');

			if ($Request->hasFile('rc_book') && !empty($Request->file('rc_book'))) {
				$file_name = time() . "1" . $Request->rc_book->getClientOriginalName();
				$Request->rc_book->move($path, $file_name);
				$comapny->rc_book = $file_name;
			}

			if ($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))) {
				$file_name = time() . "2" . $Request->pan_card->getClientOriginalName();
				$Request->pan_card->move($path, $file_name);
				$comapny->pan_card = $file_name;
			}

			if ($Request->hasFile('licence') && !empty($Request->file('licence'))) {
				$file_name = time() . "3" . $Request->licence->getClientOriginalName();
				$Request->licence->move($path, $file_name);
				$comapny->licence = $file_name;
			}

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Driver added Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//28
	public function DriverEdit(Request $Request) {

		try {

				$data=$Request->all();
				$rules = array(
					'phone' => 'unique:driver,phone,' . $Request->id,

				);

				$messages = [

				];
				$validator = Validator::make($data, $rules, $messages);
				if ($validator->fails()) {
					return $this->APIResponse->respondOk(__($validator->errors()->first()));
				}
				else
				{
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = Driver::findorfail($Request->id);

			$comapny->name = $Request->name;

			$comapny->phone = $Request->phone;

			$comapny->truck_no = $Request->truck_no;

			$comapny->licence_no = $Request->licence_no;

			$comapny->pan = $Request->pan_no;

			$comapny->transporter_id = $Request->other_id;

			$comapny->status = $Request->status;

			$comapny->updated_by = $Request->user_id;

			if ($Request->password != "" && $Request->password != " " && $Request->password != null) {

				$comapny->password = Hash::make($Request->password);
			}

			$path = public_path('/uploads');

			if ($Request->hasFile('rc_book') && !empty($Request->file('rc_book'))) {
				$file_name = time() . "1" . $Request->rc_book->getClientOriginalName();
				$Request->rc_book->move($path, $file_name);
				$comapny->rc_book = $file_name;
			}

			if ($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))) {
				$file_name = time() . "2" . $Request->pan_card->getClientOriginalName();
				$Request->pan_card->move($path, $file_name);
				$comapny->pan_card = $file_name;
			}

			if ($Request->hasFile('licence') && !empty($Request->file('licence'))) {
				$file_name = time() . "3" . $Request->licence->getClientOriginalName();
				$Request->licence->move($path, $file_name);
				$comapny->licence = $file_name;
			}

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Driver Updated Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//29
	public function DriverDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Driver::findorfail($Request->id);
			$data->deleted_by = $Request->user_id;
			$data->save();

			if ($data->delete()) {

				return response()->json(['status' => 'success', 'message' => 'Driver deleted Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//30
	public function EmployeeList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				$data = array();
				$data1 = Employee::where('company_id',$Request->other_id)->whereNull('deleted_at');


				$data1 = $data1->paginate($perPage);

				foreach ($data1 as $key => $value) {
					$data1[$key] = $value;
					$company = Company::withTrashed()->findorfail($value->company_id);
					$data1[$key]['comapny_name'] = $company->name;
					}

				if (!empty($data1)) {
					$message = 'Employee Detail Successfully.';
					$dataa = $data1;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{
			$data = array();
			$data1 = Employee::where('company_id',$Request->other_id)->whereNull('deleted_at')->get();

			foreach ($data1 as $key => $value) {
				$data[$key] = $value;
				$company = Company::withTrashed()->findorfail($value->company_id);
				$data[$key]['comapny_name'] = $company->name;
			}

			return response()->json(['status' => 'success', 'message' => 'Employee Detail Successfully.', 'data' => $data, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//31
	public function EmployeeDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Employee::findorfail($Request->id);

			$company = Company::withTrashed()->findorfail($data->company_id);

			$data['comapny_name'] = $company->name;

			$user = User::withTrashed()->findorfail($data->user_id);

			$data->username = $user->username;

			return response()->json(['status' => 'success', 'message' => 'Employee Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//32
	public function EmployeeAdd(Request $Request) {

		try {
			$data=$Request->all();
			$rules = array(
                'username' => 'required',
				'password' => 'required'
            );

            $messages = [

            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return $this->APIResponse->respondOk(__($validator->errors()->first()));
            }
            else
			{
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = User::where('username', $Request->username)->count();

			if ($data > 0) {

				return response()->json(['status' => 'failed', 'message' => 'This username already registred in our system.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			} else {

				$user = new User();
				$user->name = $Request->name;
				$user->username = $Request->username;
				$user->password = Hash::make($Request->password);
				$user->role = "employee";
				$user->created_by = $Request->user_id;
				$user->save();

			}

			$comapny = new Employee();

			$comapny->user_id = $user->id;

			$comapny->name = $Request->name;

			$comapny->phone = $Request->phone;

			$comapny->address = $Request->address;

			$comapny->email = $Request->email;

			$comapny->company_id = $Request->other_id;

			$comapny->created_by = $Request->user_id;

			$comapny->myid = uniqid();

			$path = public_path('/uploads');

			if ($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))) {
				$file_name = time() . "1" . $Request->pan_card->getClientOriginalName();
				$Request->pan_card->move($path, $file_name);
				$comapny->pan_card = $file_name;
			}

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Employee added Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//33
	public function EmployeeEdit(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = Employee::findorfail($Request->id);

			$comapny->name = $Request->name;

			$comapny->phone = $Request->phone;

			$comapny->address = $Request->address;

			$comapny->email = $Request->email;

			$comapny->company_id = $Request->other_id;

			$comapny->updated_by = $Request->user_id;

			$user = User::withTrashed()->findorfail($comapny->user_id);

			$user->status = $Request->status;

			$user->username = $Request->username;

			if ($Request->password != "" && $Request->password != " " && $Request->password != "null" && $Request->password != null) {
				$user->password = Hash::make($Request->password);
			}

			$user->save();

			$path = public_path('/uploads');

			if ($Request->hasFile('pan_card') && !empty($Request->file('pan_card'))) {
				$file_name = time() . "1" . $Request->pan_card->getClientOriginalName();
				$Request->pan_card->move($path, $file_name);
				$comapny->pan_card = $file_name;
			}

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Employee Updated Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//34
	public function EmployeeDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Employee::findorfail($Request->id);
			$data->deleted_by = $Request->user_id;
			$data->save();
			$user = User::findorfail($data->user_id);
			$user->deleted_by = $Request->user_id;
			$user->save();
			if ($user->delete() && $data->delete()) {

				return response()->json(['status' => 'success', 'message' => 'Employee deleted Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//35
	public function ShipmentForm(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			$data['company'] = Company::where('status', 0)->get();
			$data['transporter'] = Transporter::where('status', 0)->get();
			$data['forwarder'] = Forwarder::where('status', 0)->get();
			$data['truck'] = Truck::where('status', 0)->get();

			return response()->json(['status' => 'success', 'message' => 'Employee deleted Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	public function filteroptionlist(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();


			$data['transporter'] = Transporter::whereNull('deleted_at')->where('status', 0)->orderBy('name','asc')->get();
			$data['forwarder'] = Forwarder::whereNull('deleted_at')->where('status', 0)->orderBy('name','asc')->get();
			$data['shipment'] = Shipment::whereNull('deleted_at')->orderBy('id','desc')->get();

			return response()->json(['status' => 'success', 'message' => 'detail get Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//36
	public function ShipmentFormAdd(Request $Request) {

		try {
			$data=$Request->all();
			$rules = array(
				'shipment_no' => 'required',
				'date' => 'required',
				'from' => 'required',
				'to1' => 'required',
				'forwarder_id' => 'required',
				'no_package' => 'required|numeric',
				'total_weight'=>'required|numeric',

			);

			$messages = [

				'shipment_no.required' => "Please add shipment no",
				'date.required' => "Please Select Date",
				'from.required' => "Please Enter From",
				'to1.required' => "Please Enter To",
				'forwarder_id.required' => "Please Select Forwarder",
				'no_package.required' => "Please Enter No. of Package",
				'total_weight.required' => "Please Enter Weight",

			];
			$validator = Validator::make($data, $rules, $messages);
			if ($validator->fails()) {
				return $this->APIResponse->respondOk(__($validator->errors()->first()));
			}
			else
			{
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$ship_check = Shipment::where('shipment_no', $Request->shipment_no)->count();

			if ($ship_check > 0) {

				return response()->json(['status' => 'success', 'message' => 'Shipment number already registred.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = new Shipment();

			$data->date = $Request->date;

			$data->company = $Request->company_id;

			if ($Request->type1 == "import") {

				$data->imports = 1;

				$data->exports = 0;

			} else {

				$data->exports = 1;

				$data->imports = 0;
			}

			if ($Request->type2 == "lcl") {

				$data->lcl = 1;

				$data->fcl = 0;

			} else {

				$data->fcl = 1;

				$data->lcl = 0;

			}

			$data->from1 = $Request->from;

			$data->to1 = $Request->to1;

			$data->to2 = $Request->to2;

			if ($Request->trucktype != "" && $Request->trucktype != "null" && $Request->trucktype != null) {

			$data->trucktype = $Request->truck_type;

			}

			if ($Request->truck_no != "" && $Request->truck_no != "null" && $Request->truck_no != null) {
				$data->status = 1;
			}

			$data->forwarder = $Request->forwarder_id;

			$data->show_detail = $Request->show_detail;

			$data->consignor = $Request->consignor;

			$data->consignor_address = $Request->consignor_address;

			$data->consignee = $Request->consignee;

			$data->consignee_address = $Request->consignee_address;

			$data->package = $Request->no_package;

			$data->description = htmlentities($Request->cargo_desc);

			$data->weight = $Request->total_weight;

			$data->shipper_invoice = $Request->shipper_invoice_no;

			$data->forwarder_ref_no = $Request->forwarder_ref_no;

			$data->b_e_no = $Request->b_e_no;

			if ($Request->type2 == "fcl") {

				$data->container_type = $Request->container_type;

				$data->destuffing_date = $Request->de_stuffing_date;

				$data->container_no = $Request->container_no;

				$data->shipping_line = $Request->shipping_line;

				$data->cha = $Request->cha;

				$data->seal_no = $Request->seal_no;

				$data->pod = $Request->pod;

			}

			$data->invoice_amount = $Request->invoice_amount;

			$data->remark = $Request->remark;

			if ($Request->transporter_id != null && $Request->transporter_id != '' && $Request->transporter_id != 'null') {

				$data->all_transporter = $Request->transporter_id;

			}

			$shipment_no = $Request->shipment_no;

			$data->shipment_no = $shipment_no;

			$data->lr_no = $shipment_no . "/" . getenv('FIN_YEAR');

			$data->myid = uniqid();

			$data->save();

			$company = Company::findorfail($Request->company_id);
			$company->last_no = (int) filter_var($shipment_no, FILTER_SANITIZE_NUMBER_INT) + 1;
			$company->save();

			$summary = new Shipment_Summary();
			$summary->shipment_no = $shipment_no;
			$summary->flag = "create";
			$summary->description = "Create Shipment";
			$summary->created_by = $Request->user_id;
			$summary->save();

			if ($Request->transporter_id != null && $Request->transporter_id != '' && $Request->transporter_id != 'null') {

				$tt = Transporter::findorfail($Request->transporter_id);

				if ($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') {

					$driver_id = Driver::findorfail($Request->driver_id);

				} else {

					$driver_id = Driver::where('transporter_id', $Request->transporter_id)->where('self', 1)->first();
				}

				$transs = new Shipment_Transporter();
				$transs->shipment_no = $shipment_no;
				$transs->shipment_id = $data->id;
				$transs->transporter_id = $Request->transporter_id;
				$transs->driver_id = $driver_id->id;
				$transs->name = $tt->name;
				$transs->created_by = $Request->user_id;
				$transs->myid = uniqid();
				$transs->save();

				$summary = new Shipment_Summary();
				$summary->shipment_no = $shipment_no;
				$summary->flag = "create";
				$summary->transporter_id = $Request->transporter_id;
				$summary->description = "Add Transporter";
				$summary->save();

			}


			if (($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') || ($Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null')) {

					if ($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') {
						$mydriverdetails = Driver::findorfail($Request->driver_id);
					} else {
						$mydriverdetails = Driver::where('transporter_id', $Request->transporter_id)->where('self', 1)->first();
					}


					if ($Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null') {
						$mytruckno = $Request->truck_no;
					} else {
						$mytruckno = $mydriverdetails->truck_no;
					}

						$tt = Transporter::findorfail($Request->transporter_id);
						$driver = new Shipment_Driver();
						$driver->shipment_no = $shipment_no;
						$driver->transporter_id = $Request->transporter_id;
						$driver->driver_id = $mydriverdetails->id;
						$driver->truck_no = $mytruckno;
						$driver->mobile = $tt->phone;
						$driver->created_by = $Request->user_id;
						$driver->myid = uniqid();
						$driver->save();

						$summary = new Shipment_Summary();
						$summary->shipment_no = $shipment_no;
						$summary->flag = "Add Driver";
						$summary->transporter_id = $Request->transporter_id;
						$summary->driver_id = $Request->driver_id;
						$summary->description = "Add Driver. \n" . $mytruckno . "(Co.No." . $tt->phone . ").";
						$summary->save();

			}

			$data1 = Shipment::findorfail($data->id);

			// Code For Notification start
			$token = array();

			$all_company = Company::get();

			foreach ($all_company as $key => $value) {

				$cuser = User::findorfail($value->user_id);

				if ($cuser->device_token != "") {

					array_push($token, $cuser->device_token);
				}
			}

			if ($Request->transporter_id != null && $Request->transporter_id != '' && $Request->transporter_id != 'null') {

				$tt = Transporter::findorfail($Request->transporter_id);

				$tuser = User::findorfail($tt->user_id);

				if ($tuser->device_token != "") {

					array_push($token, $tuser->device_token);
				}

			}

			if ($Request->forwarder_id != null && $Request->forwarder_id != '' && $Request->forwarder_id != 'null') {

				$tt = Forwarder::findorfail($Request->forwarder_id);

				$tuser = User::findorfail($tt->user_id);

				if ($tuser->device_token != "") {

					array_push($token, $tuser->device_token);
				}

			}

			$title = $data1->shipment_no . " Shipment Geneated";

			$message = "We inform you, Shipment " . $data1->shipment_no . "is generated.";

			$aa = new WebNotificationController();

			$aa->index($token, $title, $message, $data1->shipment_no);

			// Code For Notification End

			 if ($Request->forwarder_id != "" && $Request->forwarder_id != null && $Request->forwarder_id != 'null')
                {

                    $ship_data = Shipment::where('shipment_no', $Request->shipment_no)->first();

                    $comp = Company::withTrashed()->findorfail($ship_data->company);

                    $ship_data->company_name = $comp->name;

                    $ship_data->gst = $comp->gst_no;

                    $for = Forwarder::findorfail($ship_data->forwarder);

                    $ship_data->forwarder_name = $for->name;

                    if ($ship_data->transporter != "" && $ship_data->transporter != null && $ship_data->transporter != 'null') {
                        $tra = Transporter::findorfail($ship_data->transporter);
                        $ship_data->transporter_name = $tra->name;
                    } else {
                        $ship_data->transporter_name = "";
                    }

                    if ($ship_data->trucktype != "" && $ship_data->trucktype != null && $ship_data->trucktype != 'null') {
                        $truck = Truck::findorfail($ship_data->trucktype);
                        $ship_data->trucktype_name = $truck->name;
                    } else {
                        $ship_data->trucktype_name = "";

                    }

                    $tras_list = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->get();
                    $t_list = "";

                    foreach ($tras_list as $key => $value) {
                        $tt = Transporter::findorfail($value->transporter_id);
                        if ($key == 0) {
                            $t_list = $t_list . "" . $tt->name;
                        } else {
                            $t_list = $t_list . ", " . $tt->name;
                        }
                    }

                        $ship_data->transporters_list = $t_list;
                        $driver_list = Shipment_Driver::where('shipment_no', $Request->shipment_no)->get();
                        $d_list = "";

                        foreach ($driver_list as $key2 => $value2) {
                            if ($key2 == 0) {
                                $d_list = $d_list . "" . $value2->truck_no;
                            } else {
                                $d_list = $d_list . ", " . $value2->truck_no;
                            }
                        }

                        $ship_data->truck_no = $d_list;
                        $trucks = Shipment_Driver::where('shipment_no', $ship_data->shipment_no)->get();

                    if ($comp->lr == "yoginilr") {

                        $pdf = PDF::loadView('lr.yoginilr', compact('data', 'trucks'));

                        file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

                        $shipment = $Request->shipment_no;
                        $myemail = $for->email;

                        $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);


                         $yogini_username = env('YOGINI_MAIL_USERNAME');
				     	 $yogini_password = env('YOGINI_MAIL_PASSWORD');

				      /*	Config::set('mail.username', $yogini_username);
				      	Config::set('mail.password', $yogini_password);*/

				      	$mail_service = env('MAIL_SERVICE');

				      	if($mail_service == 'on'){

				      		 Mail::send('yoginimail', $data2, function($message) use ($data2) {
                            $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                            $message->from('noreplay@yoginitransport.com','Yogini Transport');
                            $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                        	});

				      	}



                    } elseif ($comp->lr == "ssilr") {

                        $pdf = PDF::loadView('lr.ssilr', compact('data', 'trucks'));

                        file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "/pdf/" . $Request->shipment_no . ".pdf";


                        $shipment = $Request->shipment_no;
                        $myemail =  $for->email;

                        $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);

                        $ssi_username = env('SSI_MAIL_USERNAME');
				     	$ssi_password = env('SSI_MAIL_PASSWORD');
				      	/*Config::set('mail.username', $ssi_username);
				      	Config::set('mail.password', $ssi_password);*/


                		$mail_service = env('MAIL_SERVICE');
						if($mail_service == 'on'){

                         Mail::send('ssimail', $data2, function($message) use ($data2) {
                            $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                             $message->from('noreplay@ssitransway.com','SSI Transway');
                            $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                        	});
                     	}


                    } elseif ($comp->lr == "hanshlr") {

                        $pdf = PDF::loadView('lr.hanshlr', compact('data', 'trucks'));

                        file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

                        $shipment = $Request->shipment_no;
                        $myemail =  $for->email;

                        $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);

                        $hansh_username = env('HANS_MAIL_USERNAME');
				     	$hansh_password = env('HANS_MAIL_PASSWORD');

				      	/*Config::set('mail.username', $hansh_username);
				      	Config::set('mail.password', $hansh_password);*/

				      	$mail_service = env('MAIL_SERVICE');
						if($mail_service == 'on'){

                         Mail::send('hanshmail', $data2, function($message) use ($data2) {
                            $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                            $message->from('noreplay@hanstransport.com','Hansh Transport');
                            $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                        	});

                        	}

                    } elseif ($comp->lr == "bmflr") {

                        $pdf = PDF::loadView('lr.bmflr', compact('data', 'trucks'));

                        file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

                        $shipment = $Request->shipment_no;
                        $myemail =  $for->email;

                        $data2 = array('shipment_no'=>$shipment,'email'=>$myemail);


                        $mail_service = env('MAIL_SERVICE');
						if($mail_service == 'on'){

                         Mail::send('bmfmail', $data2, function($message) use ($data2) {
                            $message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
                            $message->from('noreplay@bmfreight.com','BMF Freight');
                            $message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
                        });
                        }

                    }

                }






			return response()->json(['status' => 'success', 'message' => 'Shipment Added Successfully.', 'data' => $data1, 'code' => '200'], 200);
			}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//37
	public function ShipmentTransporterList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				$data1 = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->whereNull('deleted_at');

				$data = array();


				$data1 = $data1->paginate($perPage);
				foreach ($data1 as $key => $value) {

					$data1[$key] = $value;

					$tras = Transporter::withTrashed()->findorfail($value->transporter_id);

					$data1[$key]['name'] = $tras->name;
					if($value->driver_id){
						$driver = Driver::withTrashed()->findorfail($value->driver_id);
						$data1[$key]['driver_name'] = $driver->name;
						$data1[$key]['truck_no'] = $driver->truck_no;
						}
						else{
							$data1[$key]['driver_name'] = '';
							$data1[$key]['truck_no'] = $tras->truck_no;
						}

				}

				if (!empty($data1)) {
					$message = 'Shipment Transporter List Successfully.';
					$dataa = $data1;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{
			$data1 = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->whereNull('deleted_at')->get();

			$data = array();

			foreach ($data1 as $key => $value) {

				$data[$key] = $value;

				$tras = Transporter::withTrashed()->findorfail($value->transporter_id);

				$data[$key]['name'] = $tras->name;
				if($value->driver_id){
					$driver = Driver::withTrashed()->findorfail($value->driver_id);
					$data1[$key]['driver_name'] = $driver->name;
					$data1[$key]['truck_no'] = $driver->truck_no;
					}
					else{
						$data1[$key]['driver_name'] = '';
						$data1[$key]['truck_no'] = $tras->truck_no;
					}

			}

			return response()->json(['status' => 'success', 'message' => 'Shipment Transporter List Successfully.', 'data' => $data, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//38
	public function ShipmentTransporterSave(Request $Request) {

		try {

			$tras = Transporter::findorfail($Request->other_id);

			$ship = Shipment::where('shipment_no', $Request->shipment_no)->first();
			$ship_check = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->other_id)
			->where('driver_id',$Request->driver_id)->count();

			if ($ship_check > 0) {
				return response()->json(['status' => 'success', 'message' => 'This Transporter is already added. Please select another Transporter.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = new Shipment_Transporter();

			if ($Request->truck_no != "" && $Request->truck_no != null && $Request->truck_no != "null") {
				$ship->status = 1;
			}
			if ($ship->all_transporter != "" && $ship->all_transporter != "null" && $ship->all_transporter != null) {
				$ship->all_transporter = $ship->all_transporter . ", " . $Request->other_id;
			} else {
				$ship->all_transporter = $Request->other_id;
			}

			$ship->save();

			$data->shipment_no = $Request->shipment_no;

			$data->shipment_id = $ship->id;

			$data->transporter_id = $tras->id;

			$data->driver_id = $Request->driver_id;

			$data->name = $tras->name;

			$data->created_by = $Request->user_id;

			$data->save();

			$summary = new Shipment_Summary();

			$summary->shipment_no = $Request->shipment_no;

			$summary->flag = "Add Transporter";

			$summary->transporter_id = $Request->other_id;

			$summary->description = "Add Transporter. - " . $tras->name;

			$summary->save();



			if (($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') || ($Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null')) {


				if ($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') {
                        $mydriverdetails = Driver::findorfail($Request->driver_id);
                    } else {
                        $mydriverdetails = Driver::where('transporter_id', $Request->other_id)->where('self', 1)->first();
                    }


                 if ($Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null') {
                        $mytruckno = $Request->truck_no;
                    } else {
                        $mytruckno = $mydriverdetails->truck_no;
                    }


                    	$tt = Transporter::findorfail($Request->other_id);
                        $driver = new Shipment_Driver();
                        $driver->shipment_no = $Request->shipment_no;
                        $driver->transporter_id = $Request->other_id;
                        $driver->driver_id = $mydriverdetails->id;
                        $driver->truck_no = $mytruckno;
                        $driver->mobile = $tt->phone;
                        $driver->created_by = $Request->user_id;
                        $driver->myid = uniqid();
                        $driver->save();

                        $summary = new Shipment_Summary();
                        $summary->shipment_no = $Request->shipment_no;
                        $summary->flag = "Add Driver";
                        $summary->transporter_id = $Request->other_id;
						$summary->driver_id = $Request->driver_id;
                        $summary->description = "Add Driver. \n" . $mytruckno . "(Co.No." . $tt->phone . ").";
                        $summary->save();

                        // $summary1 = new Shipment_Summary();
                        // $summary1->shipment_no =  $Request->shipment_no;
                        // $summary1->flag = "Add Truck";
                        // $summary1->transporter_id = $Request->other_id;
                        // $summary1->description = "Add Driver & Truck No. ".$mytruckno;
                        // $summary1->save();

					$notification_user=User::where('id',$Request->user_id)->first();
					if($notification_user['role']=='transporter'){
						//driver
                        if($driver->driver_id){
                            $from_user = User::where('id',$Request->user_id)->first();
                            $to_user = Driver::find($driver->driver_id);
                            if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                                $notification = new Notification();
                                $notification->notification_from = $from_user->id;
                                $notification->notification_to = $to_user->id;
                                $notification->shipment_id = $data->shipment_id;
								$id = $data->shipment_no;
								$title= "New Shipment assign to" .' '. $to_user['name'] .' - '. $driver->shipment_no;
                                $message= "New Shipment assign to" .' '. $to_user['name'] .' - '. $driver->shipment_no;
                                $notification->title = $title;
                                $notification->message = $message;
                                $notification->notification_type = '3';
								$notification->user_name_from = $from_user['username'];
                                $notification->save();
								$notification_id = $notification->id;
                                // if($to_user->notification_status=='1'){
                                    if($to_user->device_type == 'ios'){
                                        GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                    }else{
                                        GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                    }
                                // }
                            }
                        }
					}

					if($notification_user['role']=='admin' || $notification_user['role']=='company')
					{
                            //transporter
                            if($driver->transporter_id){
                                $transporter=Transporter::where('id',$driver->transporter_id)->first();
                                $from_user = User::where('id',$Request->user_id)->first();
                                $to_user = User::find($transporter['user_id']);
                                if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                                    $notification = new Notification();
                                    $notification->notification_from = $from_user->id;
                                    $notification->notification_to = $to_user->id;
                                    $notification->shipment_id = $data->shipment_id;
									$id = $data->shipment_no;
									$title= "New Shipment assign to you" .' - '. $driver->shipment_no;
                                    $message= "New Shipment assign to you" .' - '. $driver->shipment_no;
                                    $notification->title = $title;
                                    $notification->message = $message;
                                    $notification->notification_type = '3';
									$notification->user_name_from = $from_user['username'];
                                    $notification->save();
									$notification_id = $notification->id;
                                    // if($to_user->notification_status=='1'){
                                        if($to_user->device_type == 'ios'){
                                            GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                        }else{
                                            GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                                        }
                                    // }
                                }
                            }
					}


				/*$data3 = new Shipment_Driver();

				$data3->mobile = $tras->phone;

				$data3->truck_no = $Request->truck_no;

				$data3->shipment_no = $Request->shipment_no;

				$data3->driver_id = $Request->shipment_no;

				$data3->transporter_id = $tras->id;

				$data3->created_by = $Request->user_id;

				$data3->save();

				$summary1 = new Shipment_Summary();

				$summary1->shipment_no = $Request->shipment_no;

				$summary1->flag = "Add Truck";

				$summary1->transporter_id = $Request->other_id;

				$summary1->description = "Add Driver & Truck No. " . $Request->truck_no;

				$summary1->save();*/

			}



			/// For Transporter

			$token = array();

			if ($Request->other_id != null && $Request->other_id != '' && $Request->other_id != 'null') {

				$tuser = User::findorfail($tras->user_id);

				if ($tuser->device_token != "") {

					array_push($token, $tuser->device_token);

					$title = "New Shipment Assigned.";

					$message = "We would like to inform, the shipment " . $Request->shipment_no . " is assigned to you.";

					$aa = new WebNotificationController();

					$aa->index($token, $title, $message, $Request->shipment_no);
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Shipment Transporter Added Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//39
	public function ShipmentTransporterDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {
				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment_Transporter::findorfail($Request->id);
			$ship_driver = Shipment_Driver::where('shipment_no',$data->shipment_no)
                ->where('transporter_id',$data->transporter_id)->get();
                foreach ($ship_driver as $key => $value) {

				$drive = Shipment_Driver::findorfail($value->id);
				$drive->deleted_by = $Request->user_id;
				$drive->save();
				$drive->delete();
			}

			$ship_transporter = Shipment_Transporter::where('shipment_no',$data->shipment_no)
			->where('transporter_id',$data->transporter_id)->get();
			foreach ($ship_transporter as $key => $value) {
			$transporter = Shipment_Transporter::findorfail($value->id);
			$transporter->deleted_by = $Request->user_id;
			$transporter->save();
			$transporter->delete();

			}
			//$data->deleted_by = $Request->user_id;
			//$data->save();

			//$dd = Shipment_Driver::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->delete();
			$check = Shipment_Driver::where('shipment_no',$data->shipment_no)->count();
			if($check == 0) {
				$ship = Shipment::where('shipment_no',$data->shipment_no)->first();
				$ship->status = 0;
				$ship->save();
			}

			$summary = new Shipment_Summary();
			$summary->shipment_no = $data->shipment_no;
			$summary->flag = "delete";
			$summary->transporter_id = $data->transporter_id;
			$summary->description = "Delete Transporter. ";
			$summary->save();

			$data->delete();

			return response()->json(['status' => 'success', 'message' => 'Shipment Transporter Deleted Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//40
	public function ShipmentDriverList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data1 = Shipment_Driver::where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->other_id)->orderby('created_at', 'desc')->whereNull('deleted_at')->get();

			$data2 = array();

			foreach ($data1 as $key => $value) {

			$tras = Driver::withTrashed()->findorfail($value->driver_id);

			$data1[$key]['name']= $tras->name;
			}

			$shipmentdriver = Shipment_Driver::where('transporter_id',$Request->other_id)->where('shipment_no',$Request->shipment_no)->pluck('driver_id')->toArray();

			$data2 = Driver::where('transporter_id', $Request->other_id)->whereNotIn('id',$shipmentdriver)->get();

			if (count($data2) > 0) {

				$main_data['drivers'] = $data2;

			} else {

				$main_data['drivers'] = array();

			}

			$main_data['list'] = $data1;

			/* if(count($data1)>0){

				                } else {

				                $main_data['list'] =array();

			*/

			return response()->json(['status' => 'success', 'message' => 'Shipment Driver List Successfully.', 'data' => $main_data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//41
	public function ShipmentDriverSave(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$ship = Shipment::where('shipment_no', $Request->shipment_no)->first();
			$ship_trans = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->first();

			if ($Request->truck_no != "" && $Request->truck_no != null && $Request->truck_no != "null") {

				$ship->status = 1;
				$ship->save();

			}

			$data = new Shipment_Driver();
			$data->mobile = $Request->mobile;
			$data->truck_no = $Request->truck_no;
			$data->shipment_no = $Request->shipment_no;
			$data->driver_id = $Request->driver_id;
			$data->transporter_id = $Request->other_id;
			$data->created_by = $Request->user_id;
			$data->save();

			$summary = new Shipment_Summary();
			$summary->shipment_no = $Request->shipment_no;
			$summary->flag = "Add Driver";
			$summary->transporter_id = $Request->other_id;
			$summary->driver_id = $Request->driver_id;
			$summary->description = "Add Driver & Truck No. " . $Request->truck_no;
			$summary->save();

			$notification_user=User::where('id',$Request->user_id)->first();
			if($notification_user['role']=='transporter'){
				//driver
				if($data->driver_id){
					$from_user = User::where('id',$Request->user_id)->first();
					$to_user = Driver::find($data->driver_id);
					if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
						$notification = new Notification();
						$notification->notification_from = $from_user->id;
						$notification->notification_to = $to_user->id;
						$notification->shipment_id = $ship->id;
						$id = $data->shipment_no;
						$title= "New Shipment assign to" .' '. $to_user['name'] .' - '. $data->shipment_no;
						$message= "New Shipment assign to" .' '. $to_user['name'] .' - '. $data->shipment_no;
						$notification->title = $title;
						$notification->message = $message;
						$notification->notification_type = '3';
						$notification->user_name_from = $from_user['username'];
						$notification->save();
						$notification_id = $notification->id;
						// if($to_user->notification_status=='1'){
							if($to_user->device_type == 'ios'){
								GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
							}else{
								GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
							}
						// }
					}
				}
			}

		if($notification_user['role']=='admin' || $notification_user['role']=='company'){
				//transporter
				if($data->transporter_id){
					$transporter=Transporter::where('id',$data->transporter_id)->first();
					$from_user = User::where('id',$Request->user_id)->first();
					$to_user = User::find($transporter['user_id']);
					if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
						$notification = new Notification();
						$notification->notification_from = $from_user->id;
						$notification->notification_to = $to_user->id;
						$notification->shipment_id = $ship->id;
						$id = $data->shipment_no;
						$title= "New Shipment assign to you" .' - '. $data->shipment_no;
                        $message= "New Shipment assign to you" .' - '. $data->shipment_no;
						$notification->title = $title;
						$notification->message = $message;
						$notification->notification_type = '3';
						$notification->user_name_from = $from_user['username'];
						$notification->save();
						$notification_id = $notification->id;
						// if($to_user->notification_status=='1'){
							if($to_user->device_type == 'ios'){
								GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
							}else{
								GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
							}
						// }
					}
				}
		}

			/// For Transporter

			// $token = array();

			// if ($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') {

			// 	$tuser = Driver::findorfail($Request->driver_id);

			// 	if ($tuser->device_token != "") {

			// 		array_push($token, $tuser->device_token);

			// 		$title = "New Shipment Assigned.";

			// 		$message = "We would like to inform, the shipment number " . $Request->shipment_no . " is assigned to you.";

			// 		$aa = new WebNotificationController();

			// 		$aa->index($token, $title, $message, $Request->shipment_no);
			// 	}

			// }

			return response()->json(['status' => 'success', 'message' => 'Shipment Driver Added Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {
			dd($e);

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//42
	public function ShipmentDriverDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			if ($Request->role == "transporter") {

				$data = Shipment_Driver::findorfail($Request->id);

				$data->deleted_by = $Request->user_id;
				$data->save();

				$summary = new Shipment_Summary();
				$summary->shipment_no = $data->shipment_no;
				$summary->flag = "Delete Driver";
				$summary->transporter_id = $data->transporter_id;
				$summary->description = "Delete Driver & Truck No. " . $data->truck_no;
				$summary->created_by = $data->deleted_by;
				$summary->save();

				$tra=Shipment_Transporter::whereNull('driver_id')->where('transporter_id',$data->transporter_id)->where('shipment_no',$data->shipment_no)->first();
				if($tra){
				$check = Shipment_Transporter::whereNull('driver_id')->where('transporter_id',$data->transporter_id)->where('shipment_no',$data->shipment_no)->delete();
				}
				else{
				$transporter = Shipment_Transporter::where('driver_id',$data->driver_id)->where('shipment_no',$data->shipment_no)->delete();
				}
			}

			if ($Request->role == "admin" || $Request->role == 'company') {

				$data = Shipment_Driver::findorfail($Request->id);

				$data->deleted_by = $Request->user_id;
				$data->save();

				$summary = new Shipment_Summary();
				$summary->shipment_no = $data->shipment_no;
				$summary->flag = "Delete Truck";
				$summary->company_id = $data->transporter_id;
				$summary->description = "Delete Truck No. " . $data->truck_no;
				$summary->created_by = $Request->user_id;
				$summary->save();

				$tra=Shipment_Transporter::whereNull('driver_id')->where('transporter_id',$data->transporter_id)->where('shipment_no',$data->shipment_no)->first();
				if($tra){
				$check = Shipment_Transporter::whereNull('driver_id')->where('transporter_id',$data->transporter_id)->where('shipment_no',$data->shipment_no)->delete();
				}
				else{
				$transporter = Shipment_Transporter::where('driver_id',$data->driver_id)->where('shipment_no',$data->shipment_no)->delete();
				}
			}

			$data->delete();

			$check = Shipment_Driver::where('shipment_no',$data->shipment_no)->count();
			if($check == 0) {
			$ship = Shipment::where('shipment_no',$data->shipment_no)->first();
			$ship->status = 0;
			$ship->save();
			}

			return response()->json(['status' => 'success', 'message' => 'Shipment Driver Deleted Successfully.', 'code' => '200'], 200);

		} catch (\Exception $e) {
			dd($e);

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//43
	public function ExpenseAdd(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$shipment_data = Shipment::where('shipment_no', $Request->shipment_no)->first();

			$account = new Account();
			if($Request->role == "admin"){
				$account->to_transporter = null;
				}else{
					$account->to_transporter = $Request->other_id;
				}
			$account->from_company = $shipment_data->company;
			$account->shipment_no = $Request->shipment_no;
			$account->description = $Request->shipment_no.' '.$shipment_data->date.''." Expense.";
			$account->dates = date('Y-m-d');
			$account->v_type = "debit";
			$account->debit = $Request->amount;
			$account->save();


			$expense = new Expense();
			$expense->dates = date('Y-m-d');
			$expense->account_id = $account->id;
			$expense->company_id = $shipment_data->company;
			$expense->transporter_id = $Request->other_id;
			$expense->reason = $Request->reason;
			$expense->amount = $Request->amount;
			$expense->shipment_no = $Request->shipment_no;
			$expense->created_by = $Request->user_id;
			$expense->save();


				if($Request->role == "admin"){
				$summary = new Shipment_Summary();
				$summary->shipment_no = $Request->shipment_no;
				$summary->flag = "Add Expense";
				$summary->description = "Add Expense. " . $Request->reason;
				$summary->save();
				}
				else{
				$summary = new Shipment_Summary();
				$summary->shipment_no = $Request->shipment_no;
				$summary->flag = "Add Expense";
				$summary->transporter_id = $Request->other_id;
				$summary->description = "Add Expense. " . $Request->reason;
				$summary->save();
				}

			return response()->json(['status' => 'success', 'message' => 'Expense Added Successfully.', 'data' => $expense, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//44
	public function ShipmentpendingList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				if ($Request->role == "admin" ) {

					$data1 = Shipment::where('status', 0)->whereNull('deleted_at')->orderby('created_at', 'desc');

					$data = array();
					$data1 = $data1->paginate($perPage);
					foreach ($data1 as $key => $value) {

						$data1[$key] = $value;

						$com = Company::withTrashed()->findorfail($value->company);
						$data1[$key]['company'] = $com->name;
						$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
						$data1[$key]['forwarder'] = $forw->name;
						if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data1[$key]['vehicle'] = $tk->name;
						} else {
							$data1[$key]['vehicle'] = '';

						}
					}

				}
				if ($Request->role == 'company') {

					$data1 = Shipment::where('company', $Request->other_id)->where('status', 0)->whereNull('deleted_at')->orderby('created_at', 'desc');

					$data = array();
					$data1 = $data1->paginate($perPage);
					foreach ($data1 as $key => $value) {

						$data1[$key] = $value;

						$com = Company::withTrashed()->findorfail($value->company);
						$data1[$key]['company'] = $com->name;
						$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
						$data1[$key]['forwarder'] = $forw->name;
						if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data1[$key]['vehicle'] = $tk->name;
						} else {
							$data1[$key]['vehicle'] = '';

						}
					}

				}

				if ($Request->role == "transporter") {
					$data2 = Shipment_Driver::where('transporter_id', $Request->other_id)
					->groupBy('shipment_no')->get();
					//dd($data2);
					$ids = array();
					foreach ($data2 as $key => $value){
						if($value->status == "1"){
							array_push($ids,$value->id);
						}
					}

					$data1 = Shipment_Driver::withTrashed()->whereIn('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->paginate($perPage);
					$data = array();
					foreach ($data1  as $key => $value) {

					$data[$key] = Shipment::withTrashed()->whereNull('deleted_at')->where('shipment_no', $value->shipment_no)->first();
					//dd($data[$key]);
					if($data[$key]){
					$data1[$key]['id'] = $data[$key]->id ;
					$data1[$key]['myid'] = $data[$key]->myid ;
					$data1[$key]['status'] = $data[$key]->status ;
					$data1[$key]['imports'] = $data[$key]->imports ;
					$data1[$key]['exports'] = $data[$key]->exports ;
					$data1[$key]['lcl'] = $data[$key]->lcl ;
					$data1[$key]['fcl'] = $data[$key]->fcl ;
					$data1[$key]['from1'] = $data[$key]->from1 ;
					$data1[$key]['to1'] = $data[$key]->to1 ;
					$data1[$key]['to2'] = $data[$key]->to2 ;
					$data1[$key]['date'] = $data[$key]->date ;
					$data1[$key]['consignor'] = $data[$key]->consignor ;
					$data1[$key]['consignor_address'] = $data[$key]->consignor_address ;
					$data1[$key]['consignee'] = $data[$key]->consignee ;
					$data1[$key]['consignee_address'] = $data[$key]->consignee_address ;
					 $data1[$key] = $value;
					 $data1[$key]['expense'] = $value->expense;

				}
			}

				}
				if ($Request->role == "driver") {


					$data1 = Shipment_Driver::leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')->where('shipment_driver.driver_id', $Request->user_id)->where('shipment_driver.transporter_id', $Request->other_id)->whereNull('shipment_driver.deleted_at')->where('shipment_driver.status', 1)
					->orderby('shipment_driver.created_at', 'desc');

					$data = array();
					$data1 = $data1->paginate($perPage);
					foreach ($data1 as $key => $value) {

						$data1[$key] = $value;

						$com = Company::withTrashed()->findorfail($value->company);
						$data1[$key]['company'] = $com->name;
						$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
						$data1[$key]['forwarder'] = $forw->name;
						if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data1[$key]['vehicle'] = $tk->name;
						} else {
							$data1[$key]['vehicle'] = '';

						}
					}

				}

				if ($Request->role == "forwarder") {

					$data1 = Shipment::where('status', 0)->whereNull('deleted_at')->where('forwarder', $Request->other_id);
					$data = array();
					$data1 = $data1->paginate($perPage);
					foreach ($data1 as $key => $value) {

						$data1[$key] = $value;

						$com = Company::withTrashed()->findorfail($value->company);
						$data1[$key]['company'] = $com->name;
						$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
						$data1[$key]['forwarder'] = $forw->name;
						if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data1[$key]['vehicle'] = $tk->name;
						} else {
							$data1[$key]['vehicle'] = '';

						}
					}
				}

				if (!empty($data1)) {
					$message = 'Shipment List Successfully.';
					$dataa = $data1;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}

			else{
			if ($Request->role == "admin") {

				$data1 = Shipment::where('status', 0)->whereNull('deleted_at')->orderby('created_at', 'desc')->get();

				$data = array();

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);
					$data[$key]['company'] = $com->name;
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data[$key]['forwarder'] = $forw->name;
					if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data[$key]['vehicle'] = $tk->name;
					} else {
						$data[$key]['vehicle'] = '';

					}

				}

			}
			if ($Request->role == 'company') {

				$data1 = Shipment::where('company', $Request->other_id)->where('status', 0)->whereNull('deleted_at')->orderby('created_at', 'desc')->get();

				$data = array();

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);
					$data[$key]['company'] = $com->name;
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data[$key]['forwarder'] = $forw->name;
					if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data[$key]['vehicle'] = $tk->name;
					} else {
						$data[$key]['vehicle'] = '';

					}

				}

			}

			if ($Request->role == "transporter") {
				$data2 = Shipment_Driver::where('transporter_id', $Request->other_id)
				->groupBy('shipment_no')->get();
				$ids = array();
				foreach ($data2 as $key => $value){
					if($value->status == "1"){
						array_push($ids,$value->id);
					}
				}
				$data2 = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
				$data = array();

				foreach ($data2 as $key => $value) {
						$data1 = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();
						//dd($data1);

					   $data[$key] = $data1;

						$data[$key]['expense'] = $value->expense;
						if($data1){
						$com = Company::withTrashed()->findorfail($data1->company);
						$data[$key]['company'] = $com->name;
						}
						else{
							$data[$key]['company'] = '';
						}
						//dd($data[$key]['company']);
						if($data1){
						$forw = Forwarder::withTrashed()->findorfail($data1->forwarder);

						$data[$key]['forwarder'] = $forw->name;
						}
						else{
							$data[$key]['forwarder'] = '';
						}

						if(isset($data1->trucktype) && $data1->trucktype !='' && $data1->trucktype != 'null' && $data1->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($data1->trucktype);
						$data[$key]['vehicle'] = $tk->name;
						} else {
							$data[$key]['vehicle'] = '';
						}
					}
			}
			if ($Request->role == "driver") {

				$data2 = Shipment_Driver::where('driver_id', $Request->user_id)->where('transporter_id', $Request->other_id)->whereNull('deleted_at')->where('status', 1)->orderby('created_at', 'desc')->get();
				//dd($Request->user_id,$data2);
				$data = array();

				foreach ($data2 as $key => $value) {

					$data1 = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();

					$data[$key] = $data1;
					$com = Company::withTrashed()->findorfail($data1->company);
					$data[$key]['company'] = $com->name;
					$forw = Forwarder::withTrashed()->findorfail($data1->forwarder);
					$data[$key]['forwarder'] = $forw->name;
					if($data1->trucktype !='' && $data1->trucktype != 'null' && $data1->trucktype != null) {
					$tk = Truck::withTrashed()->findorfail($data1->trucktype);
					$data[$key]['vehicle'] = $tk->name;
					} else {
						$data[$key]['vehicle'] = '';

					}
				}

			}

			if ($Request->role == "forwarder") {

				$data1 = Shipment::where('status', 0)->whereNull('deleted_at')->where('forwarder', $Request->other_id)->get();

				$data = array();

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);
					$data[$key]['company'] = $com->name;
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data[$key]['forwarder'] = $forw->name;
					if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
					$tk = Truck::withTrashed()->findorfail($value->trucktype);
					$data[$key]['vehicle'] = $tk->name;
					} else {
						$data[$key]['vehicle'] = '';

					}
				}

			}
		}
			return response()->json(['status' => 'success', 'message' => 'Shipment List Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {
			dd($e);

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//45 ShipmentOnTheWayList
	public function ShipmentOnTheWayList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				if ($Request->role == "admin") {
					$data1 = Shipment::where('status', 1)->whereNull('deleted_at')->orderby('created_at', 'desc');
					$data = array();
					$data1 = $data1->paginate($perPage);

				foreach ($data1 as $key => $value) {

					$data1[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);

					$data1[$key]['company'] = $com->name;

					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

					$data1[$key]['forwarder'] = $forw->name;

					if($value->trucktype != 'null' && $value->trucktype != '' && $value->trucktype != null ){

						$tk = Truck::withTrashed()->findorfail($value->trucktype);

						$data1[$key]['vehicle'] = $tk->name;
					} else {

						$data1[$key]['vehicle'] = '';
					}

				}
				}
				if ($Request->role == 'company') {
					$data1 = Shipment::where('status', 1)->where('company', $Request->other_id)->whereNull('deleted_at')->orderby('created_at', 'desc');
					$data = array();
					$data1 = $data1->paginate($perPage);

				foreach ($data1 as $key => $value) {

					$data1[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);

					$data1[$key]['company'] = $com->name;

					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

					$data1[$key]['forwarder'] = $forw->name;

					if($value->trucktype != 'null' && $value->trucktype != '' && $value->trucktype != null ){

						$tk = Truck::withTrashed()->findorfail($value->trucktype);

						$data1[$key]['vehicle'] = $tk->name;
					} else {

						$data1[$key]['vehicle'] = '';
					}

				}
				}
				if ($Request->role == "transporter") {
					$data2 = Shipment_Driver::withTrashed()->where('transporter_id', $Request->other_id)->whereNull('deleted_at')
					->groupBy('shipment_no')->get();
				$ids = array();
				foreach ($data2 as $key => $value){
					if($value->status == "2" || $value->status == "4" || $value->status == "5" || $value->status == "18"
					|| $value->status == "6" || $value->status == "7" || $value->status == "8" || $value->status == "9" || $value->status == "10"
					|| $value->status == "11" || $value->status == "12" || $value->status == "13" || $value->status == "14" || $value->status == "15"){
						array_push($ids,$value->id);
					}
				}
				$data1 = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->paginate($perPage);
				$data = array();
				foreach ($data1 as $key => $value) {
					$data[$key] = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();
					if($data[$key]){
					$data1[$key]['id'] = $data[$key]->id ;
					$data1[$key]['myid'] = $data[$key]->myid ;
					$data1[$key]['status'] = $data[$key]->status ;
					$data1[$key]['imports'] = $data[$key]->imports ;
					$data1[$key]['exports'] = $data[$key]->exports ;
					$data1[$key]['lcl'] = $data[$key]->lcl ;
					$data1[$key]['fcl'] = $data[$key]->fcl ;
					$data1[$key]['from1'] = $data[$key]->from1 ;
					$data1[$key]['to1'] = $data[$key]->to1 ;
					$data1[$key]['to2'] = $data[$key]->to2 ;
					$data1[$key]['date'] = $data[$key]->date ;
					$data1[$key]['consignor'] = $data[$key]->consignor ;
					$data1[$key]['consignor_address'] = $data[$key]->consignor_address ;
					$data1[$key]['consignee'] = $data[$key]->consignee ;
					$data1[$key]['consignee_address'] = $data[$key]->consignee_address ;
					$data1[$key] = $value;
					$data1[$key]['expense'] = $value->expense;

				}
			}
				}

				if ($Request->role == "driver") {

					$data1 = Shipment_Driver::leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
					->where('shipment_driver.driver_id', $Request->user_id)->whereNull('shipment_driver.deleted_at')
					->where('shipment_driver.transporter_id', $Request->other_id)->where('shipment_driver.is_trucktransfer',0)->whereIn('shipment_driver.status', [2,4,5,6,7,8,9,10,11,12,13,14,15,18,19,20])
					->orderby('shipment_driver.created_at', 'desc');
					$data = array();
					$data1 = $data1->paginate($perPage);

				foreach ($data1 as $key => $value) {

					$data1[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);

					$data1[$key]['company'] = $com->name;

					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

					$data1[$key]['forwarder'] = $forw->name;

					if($value->trucktype != 'null' && $value->trucktype != '' && $value->trucktype != null ){

						$tk = Truck::withTrashed()->findorfail($value->trucktype);

						$data1[$key]['vehicle'] = $tk->name;
					} else {

						$data1[$key]['vehicle'] = '';
					}

				}

				}

				if ($Request->role == "forwarder") {

					$data1 = Shipment::where('status', 1)->whereNull('deleted_at')->where('forwarder', $Request->other_id)->get();

					$data = array();
					$data1 = $data1->paginate($perPage);

					foreach ($data1 as $key => $value) {

						$data1[$key] = $value;

						$com = Company::withTrashed()->findorfail($value->company);

						$data1[$key]['company'] = $com->name;

						$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

						$data1[$key]['forwarder'] = $forw->name;

						if($value->trucktype != 'null' && $value->trucktype != '' && $value->trucktype != null ){

							$tk = Truck::withTrashed()->findorfail($value->trucktype);

							$data1[$key]['vehicle'] = $tk->name;
						} else {

							$data1[$key]['vehicle'] = '';
						}

					}
				}

				if (!empty($data1)) {
					$message = 'Shipment List Successfully.';
					$dataa = $data1;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{

			if ($Request->role == "admin") {

				$data1 = Shipment::where('status', 1)->whereNull('deleted_at')->orderby('created_at', 'desc')->get();

				$data = array();

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);

					$data[$key]['company'] = $com->name;

					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

					$data[$key]['forwarder'] = $forw->name;

					if($value->trucktype != 'null' && $value->trucktype != '' && $value->trucktype != null ){

						$tk = Truck::withTrashed()->findorfail($value->trucktype);

						$data[$key]['vehicle'] = $tk->name;
					} else {

						$data[$key]['vehicle'] = '';
					}

				}

			}
			if ($Request->role == 'company') {

				$data1 = Shipment::where('status', 1)->where('company', $Request->other_id)->whereNull('deleted_at')->orderby('created_at', 'desc')->get();

				$data = array();

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);

					$data[$key]['company'] = $com->name;

					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

					$data[$key]['forwarder'] = $forw->name;

					if($value->trucktype != 'null' && $value->trucktype != '' && $value->trucktype != null ){

						$tk = Truck::withTrashed()->findorfail($value->trucktype);

						$data[$key]['vehicle'] = $tk->name;
					} else {

						$data[$key]['vehicle'] = '';
					}

				}

			}

			if ($Request->role == "transporter") {
				$data2 = Shipment_Driver::withTrashed()->where('transporter_id', $Request->other_id)->whereNull('deleted_at')
				->groupBy('shipment_no')->get();
				//dd($data2);
				$ids = array();
				foreach ($data2 as $key => $value){
					if($value->status == "2" || $value->status == "4" || $value->status == "5" || $value->status == "18"
					|| $value->status == "6" || $value->status == "7" || $value->status == "8" || $value->status == "9" || $value->status == "10"
					|| $value->status == "11" || $value->status == "12" || $value->status == "13" || $value->status == "14" || $value->status == "15"){
						array_push($ids,$value->id);
					}
				}
				$data2 = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
				$data = array();

				foreach ($data2 as $key => $value) {
						$data1 = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();
						//dd($data1);

					   $data[$key] = $data1;

						$data[$key]['expense'] = $value->expense;
						if($data1){
						$com = Company::withTrashed()->findorfail($data1->company);
						$data[$key]['company'] = $com->name;
						}
						else{
							$data[$key]['company'] = '';
						}
						//dd($data[$key]['company']);
						if($data1){
						$forw = Forwarder::withTrashed()->findorfail($data1->forwarder);

						$data[$key]['forwarder'] = $forw->name;
						}
						else{
							$data[$key]['forwarder'] = '';
						}

						if(isset($data1->trucktype) && $data1->trucktype !='' && $data1->trucktype != 'null' && $data1->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($data1->trucktype);
						$data[$key]['vehicle'] = $tk->name;
						} else {
							$data[$key]['vehicle'] = '';
						}
					}
			}

			if ($Request->role == "driver") {

				$data2 = Shipment_Driver::where('driver_id', $Request->user_id)->where('transporter_id', $Request->other_id)
				->whereNull('deleted_at')->where('is_trucktransfer',0)->whereIn('status', [2,4,5,6,7,8,9,10,11,12,13,14,15,18,19,20])->orderby('created_at', 'desc')->get();

				$data = array();

				foreach ($data2 as $key => $value) {

					$data1 = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();
					//dd($data1->company);

					$data[$key] = $data1;
					$com = Company::withTrashed()->findorfail($data1->company);
					//dd($com);
					$data[$key]['company'] = $com->name;

					$forw = Forwarder::withTrashed()->findorfail($data1->forwarder);
					$data[$key]['forwarder'] = $forw->name;
					if($data1->trucktype != 'null' && $data1->trucktype != '' && $data1->trucktype != null ){
					$tk = Truck::withTrashed()->findorfail($data1->trucktype);
					$data[$key]['vehicle'] = $tk->name;
					} else {

						$data[$key]['vehicle'] = '';
					}

				}
				//dd($data);
			}

			if ($Request->role == "forwarder") {

				$data1 = Shipment::where('status', 1)->whereNull('deleted_at')->where('forwarder', $Request->other_id)->get();

				$data = array();

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);
					$data[$key]['company'] = $com->name;
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data[$key]['forwarder'] = $forw->name;
					if($value->trucktype != 'null' && $value->trucktype != '' && $value->trucktype != null ){
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data[$key]['vehicle'] = $tk->name;
					} else {
						$data[$key]['vehicle'] = '';
					}
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Shipment List Successfully.', 'data' => $data, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//46
	public function ShipmentDeliveryList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

			if ($Request->role == "admin") {

				$data1 = Shipment::where('status', 2)->whereNull('deleted_at')->orderby('created_at', 'desc');

				$data = array();
				$data1 = $data1->paginate($perPage);
				foreach ($data1 as $key => $value) {

					$data1[$key] = $value;
					$data1[$key]['expense'] = $value->expense;
					if($value){
					$com = Company::withTrashed()->findorfail($value->company);
					$data1[$key]['company'] = $com->name;
					}
					else{
						$data1[$key]['company'] = '';
					}

					if($value){
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data1[$key]['forwarder'] = $forw->name;
					}
					else{
						$data1[$key]['forwarder'] = '';
					}

					if(isset($value->trucktype) && $value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data1[$key]['vehicle'] = $tk->name;
					} else {
						$data1[$key]['vehicle'] = '';
					}
				//}
			}

			}
			if ($Request->role == 'company') {

				$data1 = Shipment::where('status', 2)->where('company', $Request->other_id)->whereNull('deleted_at')->orderby('created_at', 'desc');

				$data = array();
				$data1 = $data1->paginate($perPage);
				foreach ($data1 as $key => $value) {

					$data1[$key] = $value;
					$data1[$key]['expense'] = $value->expense;
					if($value){
					$com = Company::withTrashed()->findorfail($value->company);
					$data1[$key]['company'] = $com->name;
					}
					else{
						$data1[$key]['company'] = '';
					}

					if($value){
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data1[$key]['forwarder'] = $forw->name;
					}
					else{
						$data1[$key]['forwarder'] = '';
					}

					if(isset($value->trucktype) && $value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data1[$key]['vehicle'] = $tk->name;
					} else {
						$data1[$key]['vehicle'] = '';
					}
				//}
			}

			}
			if ($Request->role == "transporter") {

				$data2 = Shipment_Driver::withTrashed()->where('transporter_id', $Request->other_id)->whereNull('deleted_at')
				->groupBy('shipment_no')->get();
				$ids = array();
				foreach ($data2 as $key => $value){
					if($value->status == "3" || $value->status == "17"){
						array_push($ids,$value->id);
					}
				}
				$data1 = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->paginate($perPage);
				$data = array();
				foreach ($data1 as $key => $value) {
					$data[$key] = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();
					if($data[$key]){
					$data1[$key]['id'] = $data[$key]->id ;
					$data1[$key]['myid'] = $data[$key]->myid ;
					$data1[$key]['status'] = $data[$key]->status ;
					$data1[$key]['imports'] = $data[$key]->imports ;
					$data1[$key]['exports'] = $data[$key]->exports ;
					$data1[$key]['lcl'] = $data[$key]->lcl ;
					$data1[$key]['fcl'] = $data[$key]->fcl ;
					$data1[$key]['from1'] = $data[$key]->from1 ;
					$data1[$key]['to1'] = $data[$key]->to1 ;
					$data1[$key]['to2'] = $data[$key]->to2 ;
					$data1[$key]['date'] = $data[$key]->date ;
					$data1[$key]['consignor'] = $data[$key]->consignor ;
					$data1[$key]['consignor_address'] = $data[$key]->consignor_address ;
					$data1[$key]['consignee'] = $data[$key]->consignee ;
					$data1[$key]['consignee_address'] = $data[$key]->consignee_address ;
					$data1[$key] = $value;
					$data1[$key]['expense'] = $value->expense;

				}
			}
			}

			if ($Request->role == "driver") {


				$data1 = Shipment::rightJoin('shipment_driver','shipment_driver.shipment_no','=','shipment.shipment_no')->where('shipment_driver.transporter_id', $Request->other_id)->where('shipment_driver.driver_id', $Request->user_id)->whereNull('shipment_driver.deleted_at')->orderby('shipment_driver.created_at', 'desc')->whereIn('shipment_driver.status', ['3','17']);
				$data = array();
				$data1 = $data1->paginate($perPage);
				foreach ($data1 as $key => $value) {
					$data[$key] = Shipment::where('shipment_no', $value->shipment_no)->first();
					if($data[$key]){
					$data1[$key]['id'] = $data[$key]->id ;
					$data1[$key]['myid'] = $data[$key]->myid ;
					$data1[$key]['status'] = $data[$key]->status ;
					$data1[$key] = $value;
					$data1[$key]['expense'] = $value->expense;

					if($value){
					$com = Company::withTrashed()->findorfail($value->company);
					$data1[$key]['company'] = $com->name;
					}
					else{
						$data1[$key]['company'] = '';
					}

					if($value){
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data1[$key]['forwarder'] = $forw->name;
					}
					else{
						$data1[$key]['forwarder'] = '';
					}

					if(isset($value->trucktype) && $value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data1[$key]['vehicle'] = $tk->name;
					} else {
						$data1[$key]['vehicle'] = '';
					}
				}
			}
			}

			if ($Request->role == "forwarder") {

				$data1 = Shipment::where('status', 2)->whereNull('deleted_at')->where('forwarder', $Request->other_id);
				$data = array();
				$data1 = $data1->paginate($perPage);
				foreach ($data1 as $key => $value) {

					$data1[$key] = $value;
					$data1[$key]['expense'] = $value->expense;
					if($value){
					$com = Company::withTrashed()->findorfail($value->company);
					$data1[$key]['company'] = $com->name;
					}
					else{
						$data1[$key]['company'] = '';
					}

					if($value){
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data1[$key]['forwarder'] = $forw->name;
					}
					else{
						$data1[$key]['forwarder'] = '';
					}

					if(isset($value->trucktype) && $value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($value->trucktype);
						$data1[$key]['vehicle'] = $tk->name;
					} else {
						$data1[$key]['vehicle'] = '';
					}
				//}
			}
			}



				if (!empty($data1)) {
					$message = 'Shipment List Successfully.';
					$dataa = $data1;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}

			else{
			if ($Request->role == "admin" ) {

				$data1 = Shipment::where('status', 2)->whereNull('deleted_at')->orderby('created_at', 'desc')->get();

				$data = array();

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);
					$data[$key]['company'] = $com->name;
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data[$key]['forwarder'] = $forw->name;
					if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
					$tk = Truck::withTrashed()->findorfail($value->trucktype);
					$data[$key]['vehicle'] = $tk->name;
					} else {
						$data[$key]['vehicle'] = '';

					}

				}

			}
			if ($Request->role == 'company') {

				$data1 = Shipment::where('status', 2)->where('company', $Request->other_id)->whereNull('deleted_at')->orderby('created_at', 'desc')->get();

				$data = array();

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);
					$data[$key]['company'] = $com->name;
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data[$key]['forwarder'] = $forw->name;
					if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
					$tk = Truck::withTrashed()->findorfail($value->trucktype);
					$data[$key]['vehicle'] = $tk->name;
					} else {
						$data[$key]['vehicle'] = '';

					}

				}

			}

			if ($Request->role == "transporter") {
				$data2 = Shipment_Driver::withTrashed()->where('transporter_id', $Request->other_id)->whereNull('deleted_at')
				->groupBy('shipment_no')->get();
				$ids = array();
				foreach ($data2 as $key => $value){
					if($value->status == "3" || $value->status == "17"){
						array_push($ids,$value->id);
					}
				}
				$data2 = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();
				$data = array();

				foreach ($data2 as $key => $value) {
						$data1 = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();
						//dd($data1);

					   $data[$key] = $data1;

						$data[$key]['expense'] = $value->expense;
						if($data1){
						$com = Company::withTrashed()->findorfail($data1->company);
						$data[$key]['company'] = $com->name;
						}
						else{
							$data[$key]['company'] = '';
						}
						//dd($data[$key]['company']);
						if($data1){
						$forw = Forwarder::withTrashed()->findorfail($data1->forwarder);

						$data[$key]['forwarder'] = $forw->name;
						}
						else{
							$data[$key]['forwarder'] = '';
						}

						if(isset($data1->trucktype) && $data1->trucktype !='' && $data1->trucktype != 'null' && $data1->trucktype != null) {
						$tk = Truck::withTrashed()->findorfail($data1->trucktype);
						$data[$key]['vehicle'] = $tk->name;
						} else {
							$data[$key]['vehicle'] = '';
						}
					}
			}

			if ($Request->role == "driver") {
				$data2 = Shipment_Driver::where('driver_id', $Request->user_id)->where('transporter_id', $Request->other_id)->whereNull('deleted_at')->whereIn('status', ['3','17'])->orderby('created_at', 'desc')->get();
				$data = array();
				foreach ($data2 as $key => $value) {

					$data1 = Shipment::where('shipment_no', $value->shipment_no)->first();

						$data[$key] = $data1;
						$data[$key]['expense'] = $value->expense;
						if($data1){
						$com = Company::withTrashed()->findorfail($data1->company);
						$data[$key]['company'] = $com->name;
						}
						else{
							$data[$key]['company'] = '';
						}

						if($data1){
						$forw = Forwarder::withTrashed()->findorfail($data1->forwarder);
						$data[$key]['forwarder'] = $forw->name;
						}
						else{
							$data[$key]['forwarder'] = '';
						}

						if(isset($data1->trucktype) && $data1->trucktype !='' && $data1->trucktype != 'null' && $data1->trucktype != null) {
							$tk = Truck::withTrashed()->findorfail($data1->trucktype);
							$data[$key]['vehicle'] = $tk->name;
						} else {
							$data[$key]['vehicle'] = '';
						}
					//}
				}
			}

			if ($Request->role == "forwarder") {

				$data1 = Shipment::where('status', 2)->whereNull('deleted_at')->where('forwarder', $Request->other_id)->get();

				$data = array();

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);
					$data[$key]['company'] = $com->name;
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data[$key]['forwarder'] = $forw->name;
					if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
					$tk = Truck::withTrashed()->findorfail($value->trucktype);
					$data[$key]['vehicle'] = $tk->name;
					} else {
						$data[$key]['vehicle'] = '';

					}
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Shipment List Successfully.', 'data' => $data, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//47
	public function ShipmentDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::withTrashed()->where('shipment_no', $Request->shipment_no)->first();


			$desc = "<br>".$data->description;

			$data['description'] = str_replace("<br>", "\n",$desc);

			$comp = Company::withTrashed()->findorfail($data->company);

			$data->company_name = $comp->name;

			if ($data->forwarder != "" && $data->forwarder != null && $data->forwarder != 'null') {

				$for = Forwarder::withTrashed()->findorfail($data->forwarder);

				$data->forwarder_name = $for->name;

			} else {
				$data->forwarder_name = "";

			}
			// if ($data->transporter != "" && $data->transporter != null && $data->transporter != 'null') {
			// 	$tra = Transporter::withTrashed()->findorfail($data->transporter);
			// 	$data->transporter_name = $tra->name;
			// } else {
			// 	$data->transporter_name = "";

			// }

			if ($data->trucktype != "" && $data->trucktype != null && $data->trucktype != 'null') {
				$truck = Truck::withTrashed()->findorfail($data->trucktype);
				$data->trucktype_name = $truck->name;
			} else {
				$data->trucktype_name = "";

			}
			$tras_list = Shipment_Transporter::withTrashed()->where('shipment_no', $Request->shipment_no)->get();
			$t_list = "";
			foreach ($tras_list as $key => $value) {
				$tt = Transporter::withTrashed()->findorfail($value->transporter_id);
				if ($key == 0) {
					$t_list = $t_list . "" . $tt->name;
				} else {

					$t_list = $t_list . ", " . $tt->name;
				}
			}

			$data->transporter_name = $t_list;

			if ($Request->role == "transporter") {

				$data2 = Shipment_Driver::withTrashed()->where('shipment_no',$Request->shipment_no)->where('transporter_id', $Request->other_id)->whereNull('deleted_at')
				->orderby('id','desc')->first();
				//dd($data2);
			if($data2){
			foreach($data as $key => $value){
				if($data2->status == 3 || $data2->status == 17){
				$data->status = 2;
				}
				if($data2->status == 1){
					$data->status = 0;
				}
				if($data2->status == 2 || $data2->status == 4 || $data2->status == 5 || $data2->status == 6 || $data2->status == 7
				|| $data2->status == 8 || $data2->status == 9 || $data2->status == 10 || $data2->status == 11 || $data2->status == 12
				|| $data2->status == 13 || $data2->status == 14 || $data2->status == 15 || $data2->status == 18){
				$data->status = 1;
				}
			}
			}
			else{
				$data->status = $data->status;
			}

			}
			if ($Request->role == "driver") {
				$dd = Shipment_Driver::withTrashed()->where('shipment_no',$Request->shipment_no)->where('transporter_id', $Request->other_id)
				->where('driver_id',$Request->user_id)->whereNull('deleted_at')
				->orderby('id','desc')->get();

				$ids = array();
				foreach ($dd as $key => $value){
					if($value->status == 1 || $value->status == 2 || $value->status == 4 || $value->status == 5 || $value->status == 6 || $value->status == 7
					|| $value->status == 8 || $value->status == 9 || $value->status == 10 || $value->status == 11 || $value->status == 12
					|| $value->status == 13 || $value->status == 14 || $value->status == 15 || $value->status == 18 || $value->status == 3 || $value->status == 17){
						array_push($ids,$value->id);
					}
				}

		   	$data2 = Shipment_Driver::withTrashed()->where('id', $ids)->first();
			   if($data2){
			foreach($data as $key => $value){
				if($data2->status == 3 || $data2->status == 17){
				$data->status = 2;
				}
				if($data2->status == 1){
					$data->status = 0;
				}
				if($data2->status == 2 || $data2->status == 4 || $data2->status == 5 || $data2->status == 6 || $data2->status == 7
				|| $data2->status == 8 || $data2->status == 9 || $data2->status == 10 || $data2->status == 11 || $data2->status == 12
				|| $data2->status == 13 || $data2->status == 14 || $data2->status == 15 || $data2->status == 18){
				$data->status = 1;
				}
			}
		}
		else{
			$data->status = $data->status;
		}
			}

			if ($Request->role == "transporter") {

			$check = Shipment_Summary::where('shipment_no',$data->shipment_no)->where('transporter_id',$Request->other_id)->where('flag','View Shipment')->first();
			if(!$check){
			$summary = new Shipment_Summary();
			$summary->shipment_no = $data->shipment_no;
			$summary->flag = "View Shipment";
			$summary->transporter_id = $Request->other_id;
			$tra = Transporter::withTrashed()->findorfail($Request->other_id);
			$summary->description = "View Shipment List By. - ".$tra->name;
			$summary->save();
			}
		}
		if ($Request->role == "driver") {

			$check = Shipment_Summary::where('shipment_no',$data->shipment_no)->where('driver_id',$Request->user_id)->where('flag','View Shipment')->first();
			if(!$check){
			$summary = new Shipment_Summary();
			$summary->shipment_no = $data->shipment_no;
			$summary->flag = "View Shipment";
			$summary->driver_id = $Request->user_id;
			$tra = Driver::withTrashed()->findorfail($Request->user_id);
			$summary->description = "View Shipment List By. - ".$tra->name;
			$summary->save();
			}
		}
			if ($Request->role == "transporter") {

				$driver_list = Shipment_Driver::where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->other_id)->get();
				$d_list = "";

				foreach ($driver_list as $key2 => $value2) {
					if ($key2 == 0) {
						$d_list = $d_list . "" . $value2->truck_no . "(" . $value2->mobile . ")";

					} else {
						$d_list = $d_list . ", " . $value2->truck_no . "(" . $value2->mobile . ")";

					}

				}

				$data->truck_no = $d_list;

			} else {

				$driver_list = Shipment_Driver::where('shipment_no', $Request->shipment_no)->get();
				$d_list = "";

				foreach ($driver_list as $key2 => $value2) {
					if ($key2 == 0) {
						$d_list = $d_list . "" . $value2->truck_no;

					} else {
						$d_list = $d_list . ", " . $value2->truck_no;

					}

				}

				$data->truck_no = $d_list;

			}

			$all_truck_list = array();

			$all_truck_lists = Shipment_Driver::withTrashed()->whereNull('deleted_at')->where('shipment_no', $Request->shipment_no)->get();

			$data['all_truck_list'] = array();

			foreach ($all_truck_lists as $key => $value) {

				$tt = Transporter::withTrashed()->findorfail($value->transporter_id);
				$all_truck_list[$key] = $value;
				$all_truck_list[$key]['transporter_name'] = $tt->name;
			}

			$data['all_truck_list'] = $all_truck_list;

			return response()->json(['status' => 'success', 'message' => 'Shipment Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//48
	public function ShipmentChangeStatusAdmin(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment_Driver::findorfail($Request->id);

			$data->status = $Request->status;

			if ($Request->reason != "" && $Request->reason != "null" && $Request->reason == null) {
				$data->reason = $Request->reason;
			}

			$data->updated_by = $Request->user_id;

			$data->save();

			if ($Request->status == "2") {

				$ss = Shipment::where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				$ss->save();

				$transp = Shipment_Transporter::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();

			}

			if ($Request->status == "3") {
				$ss = Shipment::where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				$ss->save();

				$get_all_shipment = Shipment_Driver::where('shipment_no', $data->shipment_no)->where('status', 1)->orwhere('status', 2)->where('deleted_at', '')->count();

				if ($get_all_shipment == 0) {

					$transp = Shipment_Transporter::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
					$transp->status = 3;
					$transp->save();
				}

				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->unloaded_photo = $file_name;

				}

			}

			$cargo = Cargostatus::findorfail($Request->status);
			$summary = new Shipment_Summary();
			$summary->shipment_no = $data->shipment_no;
			$summary->flag = $data->truck_no . " is " . $cargo->name;
			$summary->transporter_id = $data->transporter_id;
			$summary->change_status_by = $Request->role;
			$summary->description = "Change Truck Shipment Status By Admin.\n" . $data->truck_no . " is " . $cargo->name;
			$summary->created_by = $Request->user_id;
			$summary->save();

			return response()->json(['status' => 'success', 'message' => 'Shipment Status Changed Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//changestatus
	public static function changeStatus(Request $request,$id)
	{
		$date = date('Y-m-d H:i:s');
		$data = Shipment_Driver::where('id',$id)->where('status','1');
		// $data_difference = strtotime($date) - strtotime($data['updated_at']);
      	// $data_differencetime = 900;

		$to_time = strtotime($date);
		$from_time = strtotime($data['updated_at']);
		$differnce= round(abs($to_time - $from_time) / 60,2). " minute";
      	if($data && $differnce <= 60) {
			$transporter=Transporter::where('id',$data->transporter_id)->first();
			$from_user = User::find($data->updated_by);
            $to_user = User::find($transporter['user_id']);
			$user=User::where('id',$data->updated_by)->first();
			$getStatus=Cargostatus::where('id',$data->status)->first();
            if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                $notification = new Notification();
                $notification->notification_from = $from_user->id;
                $notification->notification_to = $to_user->id;
                $notification->shipment_id = $data->id;
				$id = $data->shipment_no;
                $title= "Status changed";
				// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
                $message= $data["shipment_no"].' '."is".' '.$getStatus['name'].' ' ."by".' '.$user['username'];
				$notification->title = $title;
                $notification->message = $message;
                $notification->notification_type = '2';
				$notification->user_name_from = $user['username'];
                $notification->save();
				$notification_id = $notification->id;
				if($to_user->device_token != null){
				if($to_user->device_type == 'ios'){
                    GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                }else{
                    GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                    }
				}
            }

			$from_user1 = User::find($data->updated_by);
            $to_user1 = User::find(1);
			$user1=User::where('id',$data->updated_by)->first();
			$getStatus1=Cargostatus::where('id',$data->status)->first();
            if($from_user1['id'] != $to_user1['id'] && $from_user1 && $to_user1) {
                $notification = new Notification();
                $notification->notification_from = $from_user1->id;
                $notification->notification_to = $to_user1->id;
                $notification->shipment_id = $data->id;
				$id = $data->shipment_no;
                $title= "Status changed";
                $message= $data["shipment_no"].' '."is".' '.$getStatus1['name'].' ' ."by".' '.$user1['username'];
				$notification->title = $title;
                $notification->message = $message;
                $notification->notification_type = '2';
				$notification->user_name_from = $user1['username'];
                $notification->save();
				$notification_id = $notification->id;
				if($to_user1->device_token != null){
				if($to_user1->device_type == 'ios'){
                    GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
                }else{
                    GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
                    }
				}
            }
		}
		return 1;
	}
	//49
	public function ShipmentChangeStatusTransporter(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment_Driver::withTrashed()->findorfail($Request->id);
			//dd($data->shipment_no);

			if($Request->status == "19" || $Request->status == "20"){
				$data->status = 2;
				}else{
					$data->status = $Request->status;
				}
			$data->last_status_update_time=date('Y-m-d H:i:s');

			$path = public_path('/uploads');
			if ($Request->status == "1") {

				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();

				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				$ss->save();

				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->other_photo = $file_name;

				}

			}
			if ($Request->status == "2") {

				$data->load_time = date('Y-m-d H:i');
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				$ss->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->loaded_photo = $file_name;
				}
			}
			if ($Request->status == "19") {

				$data->damageload_time = date('Y-m-d H:i');
				//$data->is_damaged = 1;
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				$ss->is_damaged = 1;
				$ss->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->damageload_photo = $file_name;
				}
			}
			if ($Request->status == "20") {

				$data->missingload_time = date('Y-m-d H:i');
				//$data->is_missing = 1;
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				$ss->is_missing = 1;
				$ss->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->missingload_photo = $file_name;
				}
			}

			if ($Request->status == "3") {

				$data->unload_time = date('Y-m-d H:i');

				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;

				// $cargostatus = Shipment_Driver::where('shipment_no',$data->shipment_no)->latest()->take(1)->first();
                // if($data->id == $cargostatus->id)
                // {
                //     $ss->cargo_status = 2;
                // }
                // else{
                //     $ss->cargo_status = 1;
                // }

				$ss->save();

				$get_all_shipment = Shipment_Driver::withTrashed()->where('shipment_no', $data->shipment_no)->where('status', 1)->orwhere('status', 2)->where('deleted_at', '')->count();

				if ($get_all_shipment == 0) {

					$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
					$transp->status = 2;
					$transp->save();
				}

				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->unloaded_photo = $file_name;
				}

			}
			if ($Request->status == "17") {

				$data->unloadcontainer_time = date('Y-m-d H:i');

				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;

				// $cargostatus = Shipment_Driver::where('shipment_no',$data->shipment_no)->latest()->take(1)->first();
                // if($data->id == $cargostatus->id)
                // {
                //     $ss->cargo_status = 2;
                // }
                // else{
                //     $ss->cargo_status = 1;
                // }

				$ss->save();

				$get_all_shipment = Shipment_Driver::withTrashed()->where('shipment_no', $data->shipment_no)->where('status', 1)->orwhere('status', 2)->where('deleted_at', '')->count();

				if ($get_all_shipment == 0) {

					$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
					$transp->status = 2;
					$transp->save();
				}

				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->unloadedcontainer_photo = $file_name;

				}

			}
			if($Request->status == "4"){

				$data->hold_time = date('Y-m-d H:i');

				$ss =Shipment::withTrashed()->where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->hold_photo = $file_name;
				}

			}
			if($Request->status == "5"){

				$data->other_time = date('Y-m-d H:i');

				$ss =Shipment::withTrashed()->where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->other_photo = $file_name;
				}

			}
			if($Request->status == "11"){

				$data->truck_reachport_time = date('Y-m-d H:i');
				$data->is_trucktransfer = 1;
				$ss =Shipment::withTrashed()->where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->trucktransreachprt_photo = $file_name;
				}
				$truckexist = Driver::withTrashed()->where('truck_no',$Request->truck_no)->first();
				if($truckexist){
					$tras1 = Transporter::findorfail($truckexist->transporter_id);

					$shipdriver = new Shipment_Driver();
					$shipdriver->mobile = $truckexist->phone;
					$shipdriver->truck_no = $Request->truck_no;
					$shipdriver->shipment_no = $ss->shipment_no;
					$shipdriver->driver_id = $truckexist->id;
					$shipdriver->transporter_id = $truckexist->transporter_id;
					$shipdriver->created_by = $Request->user_id;
					$shipdriver->save();

					$shiptransporter = new Shipment_Transporter();
					$shiptransporter->shipment_no = $ss->shipment_no;
					$shiptransporter->shipment_id = $ss->id;
					$shiptransporter->transporter_id = $truckexist->transporter_id;
					$shiptransporter->driver_id = $truckexist->id;
					$shiptransporter->name = $tras1->name;
					$shiptransporter->created_by = $Request->user_id;
					$shiptransporter->save();

					$summary = new Shipment_Summary();
					$summary->shipment_no = $ss->shipment_no;
					$summary->flag = "Add Transporter";
					$summary->transporter_id = $truckexist->transporter_id;
					$summary->description = "Add Transporter. - ".$tras1->name;
					$summary->save();

					$summary = new Shipment_Summary();
					$summary->shipment_no = $ss->shipment_no;
					$summary->flag = "Add Driver";
					$summary->transporter_id = $truckexist->transporter_id;
					$summary->driver_id = $truckexist->id;
					$summary->description = "Add Driver. \n" . $Request->truck_no . "(Co.No." . $truckexist->phone . ").";
					$summary->save();
				}

				if(!$truckexist){
					return response()->json(['status' => 'success', 'message' => 'This Truck number is not exists.', 'data' => json_decode('{}'), 'code' => '500'], 200);
				}

			}
			if($Request->status == "12"){

				$data->reachatport_time = date('Y-m-d H:i');

				$ss =Shipment::withTrashed()->where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->reachprt_photo = $file_name;
				}

			}
			if($Request->status == "13"){

				$ss =Shipment::withTrashed()->where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

				$data->truck_reachcompany_time = date('Y-m-d H:i');
				$data->is_trucktransfer = 1;
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->trucktransreachcompany_photo = $file_name;
				}
				$truckexist = Driver::withTrashed()->where('truck_no',$Request->truck_no)->first();
				if($truckexist){
					$tras1 = Transporter::findorfail($truckexist->transporter_id);

					$shipdriver = new Shipment_Driver();
					$shipdriver->mobile = $truckexist->phone;
					$shipdriver->truck_no = $Request->truck_no;
					$shipdriver->shipment_no = $ss->shipment_no;
					$shipdriver->driver_id = $truckexist->id;
					$shipdriver->transporter_id = $truckexist->transporter_id;
					$shipdriver->created_by = $Request->user_id;
					$shipdriver->save();

					$shiptransporter = new Shipment_Transporter();
					$shiptransporter->shipment_no = $ss->shipment_no;
					$shiptransporter->shipment_id = $ss->id;
					$shiptransporter->transporter_id = $truckexist->transporter_id;
					$shiptransporter->driver_id = $truckexist->id;
					$shiptransporter->name = $tras1->name;
					$shiptransporter->created_by = $Request->user_id;
					$shiptransporter->save();

					$summary = new Shipment_Summary();
					$summary->shipment_no = $ss->shipment_no;
					$summary->flag = "Add Transporter";
					$summary->transporter_id = $truckexist->transporter_id;
					$summary->description = "Add Transporter. - ".$tras1->name;
					$summary->save();

					$summary = new Shipment_Summary();
					$summary->shipment_no = $ss->shipment_no;
					$summary->flag = "Add Driver";
					$summary->transporter_id = $truckexist->transporter_id;
					$summary->driver_id = $truckexist->id;
					$summary->description = "Add Driver. \n" . $Request->truck_no . "(Co.No." . $truckexist->phone . ").";
					$summary->save();
				}

				if(!$truckexist){
					return response()->json(['status' => 'success', 'message' => 'This Truck number is not exists.', 'data' => json_decode('{}'), 'code' => '500'], 200);
				}
			}
			if($Request->status == "14"){
				$ss =Shipment::withTrashed()->where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

				$data->loadcontainer_time = date('Y-m-d H:i');
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->loadcontainer_photo = $file_name;
				}
			}
			if($Request->status == "15"){

				$ss =Shipment::withTrashed()->where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

				$data->loadcargo_time = date('Y-m-d H:i');
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->loadcargo_photo = $file_name;
				}

			}
			if($Request->status == "18"){

				$ss =Shipment::withTrashed()->where('shipment_no',$data->shipment_no)->first();
                $ss->status =1;
                // $ss->cargo_status = 1;
                $ss->save();

				$data->unloadcargo_time = date('Y-m-d H:i');
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->unloadcargo_photo = $file_name;
				}

			}

			if ($Request->status == "6") {

				$data->pickup_conf_time = date('Y-m-d H:i');

				/*$transp = Shipment_Transporter::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();*/

				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				//dd($ss);
				$ss->status = 1;
				// $ss->cargo_status = 1;
				$ss->save();

				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();

				$transp->status = 2;
				$transp->save();

				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->pickup_conformation = $file_name;
				}

			}

			if ($Request->status == "7") {

				$data->reach_time = date('Y-m-d H:i');

				/*$transp = Shipment_Transporter::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = ;
				$transp->save();*/
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();

				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				// $ss->cargo_status = 1;
				$ss->save();

				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->reach_company = $file_name;

				}

			}

			if ($Request->status == "8") {

				$data->damage_time = date('Y-m-d H:i');

				/*$transp = Shipment_Transporter::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 8;
				$transp->save();*/
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;

				$transp->save();

				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				// $ss->cargo_status = 1;
				$ss->save();

				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->damage_cargo = $file_name;

				}

			}

			if ($Request->status == "9") {

				$data->document_time = date('Y-m-d H:i');

				/*$transp = Shipment_Transporter::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 9;
				$transp->save();*/
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();

				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				// $ss->cargo_status = 1;
				$ss->save();

				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->document_received = $file_name;

				}

			}

			if ($Request->status == "10") {

				$data->missing_time = date('Y-m-d H:i');

				/*$transp = Shipment_Transporter::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 10;
				$transp->save();
*/
				$transp = Shipment_Transporter::withTrashed()->where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();

				$ss = Shipment::withTrashed()->where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				// $ss->cargo_status = 1;
				$ss->save();

				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->missing_pkg = $file_name;

				}

			}


			$data->updated_by = $Request->user_id;

			$data->save();

			$cargo = Cargostatus::findorfail($Request->status);

			if($Request->role == 'driver'){
				$summary = new Shipment_Summary();
				$summary->shipment_no = $data->shipment_no;
				$summary->flag = $data->truck_no . " is " . $cargo->name;
				$summary->transporter_id = $data->transporter_id;
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$summary->image = $file_name;
				}
				$summary->change_status_by = $Request->role;
				$summary->description = "Change Truck Shipment Status By Driver.\n" . $data->truck_no . " is " . $cargo->name;
				$summary->driver_id = $data->driver_id;
				$summary->save();
			}
			else
			{
				$summary = new Shipment_Summary();
				$summary->shipment_no = $data->shipment_no;
				$summary->flag = $data->truck_no . " is " . $cargo->name;
				$summary->transporter_id = $data->transporter_id;
				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$summary->image = $file_name;
				}
				$summary->change_status_by = $Request->role;
				$summary->description = "Change Truck Shipment Status By ".$Request->role.".\n" . $data->truck_no . " is " . $cargo->name;
				$summary->save();
			}


		if($Request->role == 'driver' || $Request->role == 'company'){
			//transportor
			$transporter=Transporter::where('id',$data->transporter_id)->first();
			if($Request->role == "driver"){
			$from_user = Driver::find($data->updated_by);
			$from_user_name = $from_user['name'];
			}
			else{
				$from_user = User::find($data->updated_by);
				$from_user_name = $from_user['username'];
			}
            $to_user = User::find($transporter['user_id']);

			$getStatus=Cargostatus::where('id',$data->status)->first();
            if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
                $notification = new Notification();
                $notification->notification_from = $from_user->id;
                $notification->notification_to = $to_user->id;
                $notification->shipment_id = $ss->id;
				$id = $data->shipment_no;
                $title= "Status changed";
                $message= $data["shipment_no"].' '."is".' '.$getStatus['name'].' ' ."by".' '.$from_user_name;
				$notification->title = $title;
                $notification->message = $message;
                $notification->notification_type = '2';
				$notification->user_name_from = $from_user_name;
                $notification->save();
				$notification_id = $notification->id;
				if($to_user->device_token != null){
				if($to_user->device_type == 'ios'){
                    GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                }else{
                    GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                    }
				}
            }
		}

		if($Request->role == 'company'){
			//Driver
			$from_user = User::find($data->updated_by);
			$to_user = Driver::find($data->driver_id);
			$user=User::where('id',$data->updated_by)->first();
			$getStatus=Cargostatus::where('id',$data->status)->first();
			if($from_user['id'] != $to_user['id'] && $from_user && $to_user) {
				$notification = new Notification();
				$notification->notification_from = $from_user->id;
				$notification->notification_to = $to_user->id;
				$notification->shipment_id = $ss->id;
				$id = $data->shipment_no;
				$title= "Status changed";
				// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
				$message= $data["shipment_no"].' '."is".' '.$getStatus['name'].' ' ."by".' '.$user['username'];
				$notification->title = $title;
				$notification->message = $message;
				$notification->notification_type = '2';
				$notification->user_name_from = $user['username'];
				$notification->save();
				$notification_id = $notification->id;
				if($to_user->device_token != null){
				if($to_user->device_type == 'ios'){
					GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
				}else{
					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
					}
				}
			}
		}
			//admin
			// if($Request->role == "driver"){
			// 	$from_user1 = Driver::find($data->updated_by);
			// 	$from_user_name = $from_user1['name'];
			// 	}
			// 	else{
			// 		$from_user1 = User::find($data->updated_by);
			// 		$from_user_name = $from_user1['username'];
			// 	}
            // $to_user1 = User::find(1);

			// $getStatus1=Cargostatus::where('id',$data->status)->first();
            // if($from_user1['id'] != $to_user1['id'] && $from_user1 && $to_user1) {
            //     $notification = new Notification();
            //     $notification->notification_from = $from_user1->id;
            //     $notification->notification_to = $to_user1->id;
            //     $notification->shipment_id = $ss->id;
			// 	$id = $data->shipment_no;
            //     $title= "Status changed";
            //     $message= $data["shipment_no"].' '."is".' '.$getStatus1['name'].' ' ."by".' '.$from_user_name;
			// 	$notification->title = $title;
            //     $notification->message = $message;
            //     $notification->notification_type = '2';
            //     $notification->save();
			// 	$notification_id = $notification->id;
			// 	if($to_user1->device_type == 'ios'){
            //         GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
            //     }else{
            //         GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
            //         }
            // }

			return response()->json(['status' => 'success', 'message' => 'Shipment Status Changed Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {
			dd($e);
			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//50
	public function ShipmentFormEdit(Request $Request) {

		try {
			$data=$Request->all();
			$rules = array(
				'shipment_no' => 'unique:shipment,shipment_no,' . $Request->id,

			);

			$messages = [
				'shipment_no.unique' => "Shipment number already registered",

			];
			$validator = Validator::make($data, $rules, $messages);
			if ($validator->fails()) {
				return $this->APIResponse->respondOk(__($validator->errors()->first()));
			}
			else
			{
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::where('id', $Request->id)->first();
			$data->shipment_no = $Request->shipment_no;
			$data->lr_no = $Request->shipment_no."/".getenv('FIN_YEAR');
			$data->date = $Request->date;

			$data->company = $Request->company_id;

			if ($Request->type1 == "import") {

				$data->imports = 1;

				$data->exports = 0;

			} else {

				$data->exports = 1;

				$data->imports = 0;
			}

			if ($Request->type2 == "lcl") {

				$data->lcl = 1;

				$data->fcl = 0;

			} else {

				$data->fcl = 1;

				$data->lcl = 0;

			}

			$data->from1 = $Request->from;

			$data->to1 = $Request->to1;

			$data->to2 = $Request->to2;

			$data->trucktype = $Request->truck_type;

			// if ($Request->truck_no != $data->truck_no && $Request->truck_no != "null" && $Request->truck_no != null) {

			// 	$data->status = 1;

			// }

			$data->forwarder = $Request->forwarder_id;

			$data->show_detail = $Request->show_detail;

			$data->consignor = $Request->consignor;

			$data->consignor_address = $Request->consignor_address;

			$data->consignee = $Request->consignee;

			$data->consignee_address = $Request->consignee_address;

			$data->package = $Request->no_package;

			$data->description = $Request->cargo_desc;

			$data->weight = $Request->total_weight;

			$data->shipper_invoice = $Request->shipper_invoice_no;

			$data->forwarder_ref_no = $Request->forwarder_ref_no;

			$data->b_e_no = $Request->b_e_no;

			if ($Request->type2 == "fcl") {

				$data->container_type = $Request->container_type;

				if ($Request->de_stuffing_date != "" && $Request->de_stuffing_date != " " && $Request->de_stuffing_date != "null" && $Request->de_stuffing_date != null) {

					$data->destuffing_date = $Request->de_stuffing_date;

				}

				$data->container_no = $Request->container_no;

				$data->shipping_line = $Request->shipping_line;

				$data->cha = $Request->cha;

				$data->seal_no = $Request->seal_no;

				$data->pod = $Request->pod;

			}

			$data->invoice_amount = $Request->invoice_amount;

			$data->remark = $Request->remark;

			if ($Request->transporter_id != $data->transporter_id && $Request->transporter_id != null && $Request->transporter_id != '' && $Request->transporter_id != 'null') {

				$tra = Transporter::withTrashed()->findorfail($Request->transporter_id);

				// $transs = new Shipment_Transporter();
				// $transs->shipment_no = $Request->shipment_no;
				// $transs->shipment_id = $data->id;
				// $transs->transporter_id = $Request->transporter_id;
				// $transs->name = $tra->transporter_name;
				// $transs->created_by = $Request->user_id;
				// $transs->save();

				$data->all_transporter = $Request->transporter_id;

			}

			if ($Request->truck_no != $data->truck_no && $Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null') {

				// $driver = new Shipment_Driver();
				// $driver->shipment_no = $Request->shipment_no;
				// $driver->truck_no = $Request->truck_no;
				// $driver->mobile = $tra->transporter_mobile;
				// $driver->created_by = $Request->user_id;
				// $driver->save();
			}

			$data->save();

			$data1 = Shipment::findorfail($data->id);

			return response()->json(['status' => 'success', 'message' => 'Shipment Edited Successfully.', 'data' => $data1, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//51
	public function ShipmentFormDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::where('shipment_no', $Request->shipment_no)->first();

			$data->deleted_by = $Request->user_id;

			$data->save();

			$data->delete();

			return response()->json(['status' => 'success', 'message' => 'Shipment Deleted Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//52
	public function ShipmentAmountUpdate(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::where('shipment_no', $Request->shipment_no)->first();
			$data->invoice_amount = $Request->amount;
			$data->updated_by = $Request->user_id;
			$data->save();

			return response()->json(['status' => 'success', 'message' => 'Invoice Amount Successfully Updated.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//53
	public function DownloadLR(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::where('shipment_no', $Request->shipment_no)->first();

			$comp = Company::withTrashed()->findorfail($data->company);

			$data->company_name = $comp->name;

			$data->gst = $comp->gst_no;

			if ($data->forwarder != "" && $data->forwarder != null && $data->forwarder != 'null') {

				$for = Forwarder::findorfail($data->forwarder);

				$data->forwarder_name = $for->name;

			} else {
				$data->forwarder_name = "";

			}
			if ($data->transporter != "" && $data->transporter != null && $data->transporter != 'null') {
				$tra = Transporter::findorfail($data->transporter);
				$data->transporter_name = $tra->name;
			} else {
				$data->transporter_name = "";

			}

			if ($data->trucktype != "" && $data->trucktype != null && $data->trucktype != 'null') {
				$truck = Truck::findorfail($data->trucktype);
				$data->trucktype_name = $truck->name;
			} else {
				$data->trucktype_name = "";

			}
			$tras_list = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->get();
			$t_list = "";
			foreach ($tras_list as $key => $value) {
				$tt = Transporter::findorfail($value->transporter_id);
				if ($key == 0) {
					$t_list = $t_list . "" . $tt->name;
				} else {

					$t_list = $t_list . ", " . $tt->name;
				}
			}

			$data->transporters_list = $t_list;

			if ($Request->role == "transporter") {

				$driver_list = Shipment_Driver::where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->other_id)->get();
				$d_list = "";

				foreach ($driver_list as $key2 => $value2) {
					if ($key2 == 0) {
						$d_list = $d_list . "" . $value2->truck_no;

					} else {
						$d_list = $d_list . ", " . $value2->truck_no;

					}

				}

				$data->truck_no = $d_list;

			} else {

				$driver_list = Shipment_Driver::where('shipment_no', $Request->shipment_no)->get();
				$d_list = "";

				foreach ($driver_list as $key2 => $value2) {
					if ($key2 == 0) {
						$d_list = $d_list . "" . $value2->truck_no;

					} else {
						$d_list = $d_list . ", " . $value2->truck_no;

					}

				}

				$data->truck_no = $d_list;

			}

			$trucks = Shipment_Driver::where('shipment_no', $data->shipment_no)->get();

			if ($comp->lr == "yoginilr") {

				$pdf = PDF::loadView('lr.yoginilr', compact('data', 'trucks'));

				file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

				return response()->json(['status' => 'success', 'message' => 'LR PDF Successfully Updated.', 'data' => $path, 'code' => '200'], 200);

				//return $pdf->download($data->lr_no.'.pdf');

			} elseif ($comp->lr == "ssilr") {

				$pdf = PDF::loadView('lr.ssilr', compact('data', 'trucks'));

				file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

				return response()->json(['status' => 'success', 'message' => 'LR PDF Successfully Updated.', 'data' => $path, 'code' => '200'], 200);

			} elseif ($comp->lr == "hanshlr") {

				$pdf = PDF::loadView('lr.hanshlr', compact('data', 'trucks'));

				file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

				return response()->json(['status' => 'success', 'message' => 'LR PDF Successfully Updated.', 'data' => $path, 'code' => '200'], 200);

			} elseif ($comp->lr == "bmflr") {

				$pdf = PDF::loadView('lr.bmflr', compact('data', 'trucks'));

				file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

				return response()->json(['status' => 'success', 'message' => 'LR PDF Successfully Updated.', 'data' => $path, 'code' => '200'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//54
	public function ShipmentWarehouseList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;
				if($Request->role == 'company'){
					$data1 = Shipment::where('status', 3)->where('company',$Request->other_id)->whereNull('deleted_at');
				}
				else{
				$data1 = Shipment::where('status', 3)->whereNull('deleted_at');
				}
				$data = array();

				$data1 = $data1->paginate($perPage);

				foreach ($data1 as $key => $value) {

					$data1[$key] = $value;

					$com = Company::withTrashed()->findorfail($value->company);
					$data1[$key]['company_name'] = $com->name;
					$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
					$data1[$key]['forwarder'] = $forw->name;
					if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
					$tk = Truck::withTrashed()->findorfail($value->trucktype);
					$data1[$key]['vehicle'] = $tk->name;
					} else {
					$data1[$key]['vehicle'] = '';

						}
					$ware = Warehouse::withTrashed()->findorfail($value->warehouse_id);
					$data1[$key]['warehouse_name'] = $ware->name;

				}
				if (!empty($data1)) {
					$message = 'WareHouse Shipment List Successfully.';
					$dataa = $data1;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{
				if($Request->role == 'company'){
					$data1 = Shipment::where('status', 3)->where('company',$Request->other_id)->whereNull('deleted_at')->get();
				}
				else{
				$data1 = Shipment::where('status', 3)->whereNull('deleted_at')->get();
				}

			$data = array();

			foreach ($data1 as $key => $value) {

				$data[$key] = $value;

				$com = Company::withTrashed()->findorfail($value->company);
				$data[$key]['company_name'] = $com->name;
				$forw = Forwarder::withTrashed()->findorfail($value->forwarder);
				$data[$key]['forwarder'] = $forw->name;
				if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
				$tk = Truck::withTrashed()->findorfail($value->trucktype);
				$data[$key]['vehicle'] = $tk->name;
				} else {
				$data[$key]['vehicle'] = '';

					}
				$ware = Warehouse::withTrashed()->findorfail($value->warehouse_id);
				$data[$key]['warehouse_name'] = $ware->name;

			}

			return response()->json(['status' => 'success', 'message' => 'WareHouse Shipment List Successfully.', 'data' => $data, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//55
	public function ShipmentInWarehouse(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::where('shipment_no', $Request->shipment_no)->first();
			$data->status = 3;
			$data->warehouse_id = $Request->warehouse_id;
			$data->save();

			return response()->json(['status' => 'success', 'message' => 'Shipment Shift in Warehouse.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//56
	public function ShipmentOUTWarehouse(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::where('shipment_no', $Request->shipment_no)->first();
			$data->status = 1;
			$data->save();

			return response()->json(['status' => 'success', 'message' => 'Shipment Shift in Warehouse.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//57
	public function TransporterAddExpense(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$shipment_data = Shipment::where('shipment_no', $Request->shipment_no)->first();

			$account = new Account();
			$account->to_transporter = $Request->other_id;
			$account->shipment_no = $Request->shipment_no;
			$account->from_company = $shipment_data->company;
			$account->description = $Request->shipment_no .$shipment_data->date. " Expense,";
			$account->dates = date('Y-m-d');
			$account->v_type = "credit";
			$account->credit = $Request->amount;
			$account->save();

			$data = new Expense();
			$data->dates = date('Y-m-d');
			$data->company_id = $shipment_data->company;
			$data->shipment_no = $Request->shipment_no;
			$data->account_id = $account->id;
			$data->sub_amount = $Request->sub_amount;
			$data->detention_amount = $Request->detention_amount;
			$data->labour_amount = $Request->labour_amount;
			$data->amount = $Request->amount;
			$data->transporter_id = $Request->other_id;
			$data->created_by = $Request->user_id;
			$data->save();

			$ttrans = Shipment_Transporter::where('transporter_id', $Request->other_id)->where('shipment_no', $Request->shipment_no)->first();
			$ttrans->expense = $Request->amount;
			$ttrans->save();

			$summary = new Shipment_Summary();
			$summary->shipment_no = $Request->shipment_no;
			$summary->flag = "Add Expense";
			$summary->transporter_id = $Request->other_id;
			$summary->description = "Add Expense.";
			$summary->save();

			return response()->json(['status' => 'success', 'message' => 'Add Expense Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//58
	public function CargostatusList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				$data = array();

			if ($Request->role == "admin" ) {

				$data = Cargostatus::where('admin', 1);

			}
			if ($Request->role == "employee") {

				$data = Cargostatus::where('employee', 1);

			}
			if ($Request->role == "company") {

				if($Request->type == "import" && $Request->type1 == "FCL" ){
					$data = Cargostatus::whereIn('id',['6','12','14','9','7','18','17']);
				}
				if($Request->type == "import" && $Request->type1 == "LCL"){
					$data = Cargostatus::whereIn('id',['6','12','2','9','13','3']);
				}
				if($Request->type == "export" && $Request->type1 == "LCL"){
					$data = Cargostatus::whereIn('id',['6','7','2','9','11','3']);
				}
				if($Request->type == "export" && $Request->type1 == "FCL"){
					$data = Cargostatus::whereIn('id',['6','12','14','7','15','9','17']);
				}
			}

			// if ($Request->role == "transporter" || $Request->role == "driver") {
			// 	$data = Cargostatus::where('transporter', 1);
			// }
			if ($Request->role == "transporter" || $Request->role == "driver") {

				if($Request->type == "import" && $Request->type1 == "FCL" ){
					if($Request->status == "Pending"){
						//dd(1);
						$data = Cargostatus::where('id','6');
						$cargostatus = Cargostatus::where('id','6')->first();

						$cargostatus->image_required = 'false';
						$cargostatus->save();

					}
					if($Request->status == "Pickup Confirmed"){
						$data = Cargostatus::where('id','12');
					}
					if($Request->status == "Reach at Port"){
						$data = Cargostatus::where('id','14');
					}
					if($Request->status == "Load Container"){
						$data = Cargostatus::where('id','9');
					}
					if($Request->status == "Document Received"){
						$data = Cargostatus::where('id','7');
					}
					if($Request->status == "Reach at Company"){
						$data = Cargostatus::where('id','18');
					}
					if($Request->status == "Unload Cargo"){
						$data = Cargostatus::where('id','17');
					}
					if($Request->status == "Unload container"){
						$data = Cargostatus::where('admin', 0);
					}
				}

				if($Request->type == "import" && $Request->type1 == "LCL"){
					if($Request->status == "Pending"){
						$data = Cargostatus::where('id','6');
						$cargostatus = Cargostatus::where('id','6')->first();
						$cargostatus->image_required = 'true';
						$cargostatus->save();
					}
					if($Request->status == "Pickup Confirmed"){
						$data = Cargostatus::where('id','12');
					}
					if($Request->status == "Reach at Port"){
						$data = Cargostatus::where('id','2');
					}
					if($Request->status == "Loaded"){
						$data = Cargostatus::where('id','9');
					}
					if($Request->status == "Document Received"){
						$data = Cargostatus::where('id','13');
						$cargostatus = Cargostatus::where('id','13')->first();
						$cargostatus->truck_no = 'true';
						$cargostatus->save();
					}
					if($Request->status == "Truck Transfer and Reach at company"){
						$data = Cargostatus::where('id','3');
					}
					if($Request->status == "Unloaded"){
						$data = Cargostatus::where('admin', 0);
					}
				}
				if($Request->type == "export" && $Request->type1 == "LCL"){
					if($Request->status == "Pending"){
						$data = Cargostatus::where('id','6');
						$cargostatus = Cargostatus::where('id','6')->first();
						$cargostatus->image_required = 'false';
						$cargostatus->save();
					}
					if($Request->status == "Pickup Confirmed"){
						$data = Cargostatus::where('id','7');
					}
					if($Request->status == "Reach at Company"){
						$data = Cargostatus::where('id','2');
					}
					if($Request->status == "Loaded"){
						$data = Cargostatus::where('id','9');
					}
					if($Request->status == "Document Received"){
						$data = Cargostatus::where('id','11');
						$cargostatus = Cargostatus::where('id','11')->first();
						$cargostatus->truck_no = 'true';
						$cargostatus->save();
					}
					if($Request->status == "Truck Transfer and Reach at port"){
						$data = Cargostatus::where('id','3');
					}
					if($Request->status == "Unloaded"){
						$data = Cargostatus::where('admin', 0);
					}

				}
				if($Request->type == "export" && $Request->type1 == "FCL"){
					if($Request->status == "Pending"){
						$data = Cargostatus::where('id','6');
						$cargostatus = Cargostatus::where('id','6')->first();
						$cargostatus->image_required = 'false';
						$cargostatus->save();
					}
					if($Request->status == "Pickup Confirmed"){
						$data = Cargostatus::where('id','12');
					}
					if($Request->status == "Reach at Port"){
						$data = Cargostatus::where('id','14');
					}
					if($Request->status == "Load Container"){
						$data = Cargostatus::where('id','7');
					}
					if($Request->status == "Reach at Company"){
						$data = Cargostatus::where('id','15');
					}
					if($Request->status == "Load Cargo"){
						$data = Cargostatus::where('id','9');
					}
					if($Request->status == "Document Received"){
						$data = Cargostatus::where('id','17');
					}
					if($Request->status == "Unload container"){
						$data = Cargostatus::where('admin', 0);
					}
				}

			}
				$data = $data->paginate($perPage);


				if (!empty($data)) {
					$message = 'Cargo Status List Successfully.';
					$dataa = $data;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{
			$data = array();

			if ($Request->role == "admin") {

				$data = Cargostatus::where('admin', 1)->get();

			}
			if ($Request->role == "employee") {

				$data = Cargostatus::where('employee', 1)->get();

			}
			if ($Request->role == "company") {

				if($Request->type == "import" && $Request->type1 == "FCL" ){
					$data = Cargostatus::whereIn('id',['6','12','14','9','7','18','17'])->get();
				}
				if($Request->type == "import" && $Request->type1 == "LCL"){
					$data = Cargostatus::whereIn('id',['6','12','2','9','13','3'])->get();
				}
				if($Request->type == "export" && $Request->type1 == "LCL"){
					$data = Cargostatus::whereIn('id',['6','7','2','9','11','3'])->get();
				}
				if($Request->type == "export" && $Request->type1 == "FCL"){
					$data = Cargostatus::whereIn('id',['6','12','14','7','15','9','17'])->get();
				}
			}
			// if ($Request->role == "transporter" || $Request->role == "driver") {
			// 	$data = Cargostatus::where('transporter', 1)->get();
			// }
			if ($Request->role == "transporter" || $Request->role == "driver") {

				if($Request->type == "import" && $Request->type1 == "FCL" ){
					if($Request->status == "Pending"){
						$cargostatus = Cargostatus::where('id','6')->first();
						$cargostatus->image_required = 'false';
						$cargostatus->save();
						$data = Cargostatus::where('id','6')->get();

					}
					if($Request->status == "Pickup Confirmed"){
						$data = Cargostatus::where('id','12')->get();
					}
					if($Request->status == "Reach at Port"){
						$data = Cargostatus::where('id','14')->get();
					}
					if($Request->status == "Load Container"){
						$data = Cargostatus::where('id','9')->get();
					}
					if($Request->status == "Document Received"){
						$data = Cargostatus::where('id','7')->get();
					}
					if($Request->status == "Reach at Company"){
						$data = Cargostatus::where('id','18')->get();
					}
					if($Request->status == "Unload Cargo"){
						$data = Cargostatus::where('id','17')->get();
					}
					if($Request->status == "Unload container"){
						$data = Cargostatus::where('admin', 0)->get();
					}
				}

				if($Request->type == "import" && $Request->type1 == "LCL"){
					if($Request->status == "Pending"){
						$cargostatus = Cargostatus::where('id','6')->first();
						$cargostatus->image_required = 'true';
						$cargostatus->save();
						$data = Cargostatus::where('id','6')->get();

					}
					if($Request->status == "Pickup Confirmed"){
						$data = Cargostatus::where('id','12')->get();
					}
					if($Request->status == "Reach at Port"){
						$data = Cargostatus::where('id','2')->get();
					}
					if($Request->status == "Loaded"){
						$data = Cargostatus::where('id','9')->get();
					}
					if($Request->status == "Document Received"){
						$cargostatus = Cargostatus::where('id','13')->first();
						$cargostatus->truck_no = 'true';
						$cargostatus->save();
						$data = Cargostatus::where('id','13')->get();
					}
					if($Request->status == "Truck Transfer and Reach at company"){
						$data = Cargostatus::where('id','3')->get();
					}
					if($Request->status == "Unloaded"){
						$data = Cargostatus::where('admin', 0)->get();
					}
				}

				if($Request->type == "export" && $Request->type1 == "LCL"){
					if($Request->status == "Pending"){
						$cargostatus = Cargostatus::where('id','6')->first();
						$cargostatus->image_required = 'false';
						$cargostatus->save();
						$data = Cargostatus::where('id','6')->get();

					}
					if($Request->status == "Pickup Confirmed"){
						$data = Cargostatus::where('id','7')->get();
					}
					if($Request->status == "Reach at Company"){
						$data = Cargostatus::where('id','2')->get();
					}
					if($Request->status == "Loaded"){
						$data = Cargostatus::where('id','9')->get();
					}
					if($Request->status == "Document Received"){
						$cargostatus = Cargostatus::where('id','11')->first();
						$cargostatus->truck_no = 'true';
						$cargostatus->save();
						$data = Cargostatus::where('id','11')->get();
					}
					if($Request->status == "Truck Transfer and Reach at port"){
						$data = Cargostatus::where('id','3')->get();
					}
					if($Request->status == "Unloaded"){
						$data = Cargostatus::where('admin', 0)->get();
					}

				}
				if($Request->type == "export" && $Request->type1 == "FCL"){
					if($Request->status == "Pending"){
						$cargostatus = Cargostatus::where('id','6')->first();
						$cargostatus->image_required = 'false';
						$cargostatus->save();
						$data = Cargostatus::where('id','6')->get();

					}
					if($Request->status == "Pickup Confirmed"){
						$data = Cargostatus::where('id','12')->get();
					}
					if($Request->status == "Reach at Port"){
						$data = Cargostatus::where('id','14')->get();
					}
					if($Request->status == "Load Container"){
						$data = Cargostatus::where('id','7')->get();
					}
					if($Request->status == "Reach at Company"){
						$data = Cargostatus::where('id','15')->get();
					}
					if($Request->status == "Load Cargo"){
						$data = Cargostatus::where('id','9')->get();
					}
					if($Request->status == "Document Received"){
						$data = Cargostatus::where('id','17')->get();
					}
					if($Request->status == "Unload container"){
						$data = Cargostatus::where('admin', 0)->get();
					}
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Cargo Status List Successfully.', 'data' => $data, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//59
	public function ShipTruckList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				$data = array();

			if ($Request->role == "admin" || $Request->role == 'company') {

				$data1 = Shipment_Driver::where('shipment_no', $Request->shipment_no)->whereNull('deleted_at');

			}

			if ($Request->role == "transporter") {

				$data1 = Shipment_Driver::where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->other_id)->whereNull('deleted_at');

			}

			if ($Request->role == "driver") {

				$data1 = Shipment_Driver::where('driver_id', $Request->user_id)->where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->other_id)->whereNull('deleted_at');

			}



				$data1 = $data1->paginate($perPage);

				foreach ($data1 as $key => $value) {


					$trans = Driver::withTrashed()->findorfail($value->driver_id);
					if($trans){
					$data1[$key]['name'] = $trans->name;
					}else{
						$data1[$key]['name'] = null;
					}
					$cargo = Cargostatus::findorfail($value->status);
					$data1[$key]['status_name'] = $cargo->name;
				}

				if (!empty($data1)) {
					$message = 'Trucks List Successfully.';
					$dataa = $data1;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{
			$data = array();

			if ($Request->role == "admin" || $Request->role == 'company') {

				$data1 = Shipment_Driver::where('shipment_no', $Request->shipment_no)->whereNull('deleted_at')->get();

			}

			if ($Request->role == "transporter") {

				$data1 = Shipment_Driver::where('shipment_no', $Request->shipment_no)->whereNull('deleted_at')->where('transporter_id', $Request->other_id)->get();

			}

			if ($Request->role == "driver") {

				$data1 = Shipment_Driver::where('driver_id', $Request->user_id)->where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->other_id)->whereNull('deleted_at')->get();

			}

			if (count($data1) > 0) {

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;
					$trans = Driver::withTrashed()->findorfail($value->driver_id);
					if($trans){
					$data[$key]['name'] = $trans->name;
					}else{
						$data[$key]['name'] = null;
					}
					$cargo = Cargostatus::findorfail($value->status);
					$data[$key]['status_name'] = $cargo->name;
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Trucks List Successfully.', 'data' => $data, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {
			dd($e);

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//60

	public function Shipmentdelivered(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::where('shipment_no', $Request->shipment_no)->first();
			$data->status = 2;
			$data->updated_by = $Request->user_id;
			$data->save();

			$summary = new Shipment_Summary();
			$summary->shipment_no = $Request->shipment_no;
			$summary->flag = "Shiment Delivered";
			$summary->description = "Shipment Delivered by Admin.";
			$summary->created_by = $Request->user_id;
			$summary->save();

			/// For Forwarder

			$token = array();

			if ($data->forwarder_id != null && $data->forwarder_id != '' && $data->forwarder_id != 'null') {

				$tt = Forwarder::findorfail($data->forwarder_id);
				$tuser = User::findorfail($tt->user_id);
				if ($tuser->device_token != "") {

					array_push($token, $tuser->device_token);

					$title = "Your shipment is delivered.";

					$message = "We would like to inform you, Your order shipment number is " . $Request->shipment_no . "successfully Delivered.";

					$aa = new WebNotificationController();

					$aa->index($token, $title, $message, $Request->shipment_no);
				}

			}

			return response()->json(['status' => 'success', 'message' => ' Delivered Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//61 Shipment Summary

	public function ShipmentSummary(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if($Request->role == 'admin' || $Request->role == 'company'){
			$data = Shipment_Summary::where('shipment_no', $Request->shipment_no)->get();
			}
			if($Request->role == 'transporter'){
			$data = Shipment_Summary::where('shipment_no', $Request->shipment_no)->where('transporter_id',$Request->other_id)->get();
			}
			if($Request->role == 'driver'){
			$data = Shipment_Summary::where('shipment_no', $Request->shipment_no)->where('driver_id',$Request->user_id)->get();
			}

			return response()->json(['status' => 'success', 'message' => ' Summary Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	public function ReplaceShipment(Request $Request) {

		try {
			$data = $Request->all();
            $rules = [
                "from" => "required",
                "to" => "required",
            ];

            $messages = [
                "from.required" => "Please enter form address",
                "to.required" => "Please enter to address",
            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return $this->APIResponse->respondOk(
                    __($validator->errors()->first())
                );
            } else {
			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$ship_check = Shipment::where('shipment_no', $Request->new_shipment_no)->count();

			if ($ship_check > 0) {

				return response()->json(['status' => 'success', 'message' => 'Shipment Number Already Registred.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$olddata = Shipment::where('shipment_no', $Request->old_shipment_no)->first();
			$olddata->status = 2;
			$olddata->updated_by = $Request->user_id;
			$olddata->save();

			$summary = new Shipment_Summary();
			$summary->shipment_no = $Request->old_shipment_no;
			$summary->flag = "Shiment Delivered";
			$summary->description = "Shipment replace with New Shipment ID by Admin.";
			$summary->created_by = $Request->user_id;
			$summary->save();

			///////////////////////

			$data = new Shipment();

			$data->date = date('Y-m-d');

			$data->company = $olddata->company;

			if ($olddata->imports == 1) {

				$data->imports = 1;

				$data->exports = 0;

			} else {

				$data->exports = 1;

				$data->imports = 0;
			}

			if ($olddata->lcl == "lcl") {

				$data->lcl = 1;

				$data->fcl = 0;

			} else {

				$data->fcl = 1;

				$data->lcl = 0;

			}

			$data->from1 = $Request->from;

			$data->to1 = $Request->to;

			$data->to2 = "";

			$data->trucktype = $olddata->trucktype;

			$data->forwarder = $olddata->forwarder;

			$data->show_detail = $olddata->show_detail;

			$data->consignor = $olddata->consignor;

			$data->consignor_address = $olddata->consignor_address;

			$data->consignee = $olddata->consignee;

			$data->consignee_address = $olddata->consignee_address;

			$data->package = $olddata->package;

			$data->description = $olddata->description;

			$data->weight = $olddata->weight;

			$data->shipper_invoice = $olddata->shipper_invoice;

			$data->forwarder_ref_no = $olddata->forwarder_ref_no;

			$data->b_e_no = $olddata->b_e_no;

			if ($olddata->fcl == 1) {

				$data->container_type = $olddata->container_type;

				$data->destuffing_date = $olddata->destuffing_date;

				$data->container_no = $olddata->container_no;

				$data->shipping_line = $olddata->shipping_line;

				$data->cha = $olddata->cha;

				$data->seal_no = $olddata->seal_no;

				$data->pod = $olddata->pod;

			}

			$data->invoice_amount = $olddata->invoice_amount;

			$data->remark = $olddata->remark;

			$shipment_no = $Request->new_shipment_no;

			$data->shipment_no = $shipment_no;

			$data->lr_no = $shipment_no . "/" . getenv('FIN_YEAR');

			$data->myid = uniqid();

			$data->save();

			$company = Company::findorfail($olddata->company);

			$company->last_no = (int) filter_var($shipment_no, FILTER_SANITIZE_NUMBER_INT) + 1;

			$company->save();

			$summary = new Shipment_Summary();

			$summary->shipment_no = $shipment_no;

			$summary->flag = "New Shipment";

			$summary->description = "Shipment replace with new shipment";

			$summary->created_by = $Request->user_id;

			$summary->save();

			// Code For Notification start

			// For ALL Company Notification

			$token = array();

			$all_company = Company::get();

			foreach ($all_company as $key => $value) {

				$cuser = User::findorfail($value->user_id);

				if ($cuser->device_token != "") {

					array_push($token, $cuser->device_token);
				}

			}

			$title = "New Shipment Generated.";

			$message = "Its shipment number is " . $shipment_no;

			$aa = new WebNotificationController();

			$aa->index($token, $title, $message, $shipment_no);

			/// For Forwarder

			$token = array();

			if ($olddata->forwarder != null && $olddata->forwarder != '' && $olddata->forwarder != 'null') {

				$tt = Forwarder::findorfail($olddata->forwarder);
				$tuser = User::findorfail($tt->user_id);
				if ($tuser->device_token != "") {

					array_push($token, $tuser->device_token);

					$title = "Your shipment order is generated.";

					$message = "We would like to inform you, Your order is placed successfully & its shipment number is " . $shipment_no;

					$aa = new WebNotificationController();

					$aa->index($token, $title, $message, $shipment_no);
				}

			}

			return response()->json(['status' => 'success', 'message' => 'New Shipment Added Successfully.', 'data' => $data, 'code' => '200'], 200);

		}
	}catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	public function generateshipment(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			if($Request->other_id == 0){
				$shipmentno = Shipment::where('shipment_no',$Request->old_shipment_no)->first();
				$company = Company::where('id', $shipmentno->company)->first();
			}
			else{
			$company = Company::where('id', $Request->other_id)->first();
			}
			$data['shipment_no'] = $company->code.''.$company->last_no ;
			$company->last_no = $company->last_no +1;
			$company->save();


			return response()->json(['status' => 'success', 'message' => 'New Shipment generate Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//63
	public function Dashboard(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			if ($Request->role == "admin"  && $Request->other_id == "" && $Request->other_id == "" && $Request->other_id == "") {

				/* $from = date('2020-01-01');

					                    $to = date('Y-m-d');

					                    $total_credit1 = Account::where('to_company',$Request->company_id)->whereBetween('dates', [$from, $to])->sum('credit');

					                    $total_credit2 = Account::where('to_company',$Request->company_id)->whereBetween('dates', [$from, $to])->sum('debit');

				*/

				/*$all_company = Company::get();
				$comapny_credit = 0;
				foreach ($all_company as $key => $value) {

					$from = date('2020-01-01');

					$to = date('Y-m-d');

					$total_credit1 = Account::where('to_company', $value->id)->whereBetween('dates', [$from, $to])->sum('credit');

					$total_credit2 = Account::where('to_company', $value->id)->whereBetween('dates', [$from, $to])->sum('debit');

					$comapny_credit = $comapny_credit + $total_credit1 + $total_credit2;

				}

				$data['pl_report'] = $comapny_credit;*/


				$from = date('Y-04-01');

           		 $to = date('Y-m-d');

            	$total_credit1 = Account::whereBetween('dates', [$from, $to])->sum('credit');

            	$total_credit2 = Account::whereBetween('dates', [$from, $to])->sum('debit');

            	$total_credit = $total_credit1 + $total_credit2;

            	//$data['pl_report'] = $total_credit;

            	$data['pl_report'] = (int)($total_credit);

				$data['pending'] = Shipment::where('status', 0)->whereNull('deleted_at')->count();

				$data['ontheway'] = Shipment::where('status', 1)->whereNull('deleted_at')->count();

				$data['bill_status'] = Invoice::where('paid', 0)->whereNull('deleted_at')->count();

				//dd($data);

			} else if ($Request->role == "admin"  && $Request->other_id != "" && $Request->other_id != null && $Request->other_id != "null") {

				$from = date('Y-04-01');

				$to = date('Y-m-d');

				$total_credit1 = Account::where('to_company', $Request->other_id)->whereNull('deleted_at')->whereBetween('dates', [$from, $to])->sum('credit');

				$total_credit2 = Account::where('to_company', $Request->other_id)->whereNull('deleted_at')->whereBetween('dates', [$from, $to])->sum('debit');

				$total_credit = $total_credit1 + $total_credit2;

				$data['pl_report'] = (int)($total_credit);

				$data['pending'] = Shipment::where('status', 0)->whereNull('deleted_at')->where('company', $Request->other_id)->count();

				$data['ontheway'] = Shipment::where('status', 1)->whereNull('deleted_at')->where('company', $Request->other_id)->count();

				$data['bill_status'] = Invoice::where('paid', 0)->whereNull('deleted_at')->where('company_id', $Request->other_id)->count();

			} else if ($Request->role == "employee" && $Request->other_id != "" && $Request->other_id != null && $Request->other_id != "null") {

				$from = date('Y-04-01');

				$to = date('Y-m-d');

				$total_credit1 = Account::where('to_company', $Request->other_id)->whereNull('deleted_at')->whereBetween('dates', [$from, $to])->sum('credit');

				$total_credit2 = Account::where('to_company', $Request->other_id)->whereNull('deleted_at')->whereBetween('dates', [$from, $to])->sum('debit');

				$total_credit = $total_credit1 + $total_credit2;

				$data['pl_report'] = (int)($total_credit);

				$data['pending'] = Shipment::where('status', 0)->whereNull('deleted_at')->where('company', $Request->other_id)->count();

				$data['ontheway'] = Shipment::where('status', 1)->whereNull('deleted_at')->where('company', $Request->other_id)->count();

				$data['bill_status'] = Invoice::where('paid', 0)->whereNull('deleted_at')->where('company_id', $Request->other_id)->count();

			}

		else if ($Request->role == "transporter") {
		$pending = Shipment_Driver::withTrashed()->where('transporter_id', $Request->other_id)->whereNull('deleted_at')
        ->groupBy('shipment_no')->get();
        $ids = array();
		$pending1 = array();
		if(!$pending->count()){
            $data['pending'] = 0;
        }else{
        foreach ($pending as $key => $value){
            if($value->status == "1" ){
                array_push($ids,$value->id);
            }
        }
        $pending = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();

        foreach ($pending as $key => $value) {
            $pending1[$key] = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();

        }
        $data['pending'] = count($pending1);
		}

        $ontheway = Shipment_Driver::withTrashed()->where('transporter_id', $Request->other_id)->whereNull('deleted_at')
		->groupBy('shipment_no')->get();
        $ids = array();
		$ontheway1 = array();
		if(!$ontheway->count()){
            $data['ontheway'] = 0;
        }else{
        foreach ($ontheway as $key => $value){
            if($value->status == "2" || $value->status == "4" || $value->status == "5" || $value->status == "18"
            || $value->status == "6" || $value->status == "7" || $value->status == "8" || $value->status == "9" || $value->status == "10"
            || $value->status == "11" || $value->status == "12" || $value->status == "13" || $value->status == "14" || $value->status == "15"){
            array_push($ids,$value->id);
            }
        }
        $ontheway = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();

        foreach ($ontheway as $key => $value) {
            $ontheway1[$key] = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();

        }
        $data['ontheway'] = count($ontheway1);
		}

        $delivery = Shipment_Driver::withTrashed()->where('transporter_id', $Request->other_id)->whereNull('deleted_at')
		->groupBy('shipment_no')->get();
				$ids = array();
				$delivery1 = array();
				if(!$delivery->count()){
                    $data['delivery'] = 0;
                }else{
				foreach ($delivery as $key => $value){
					if($value->status == "3" || $value->status == "17"){
						array_push($ids,$value->id);
					}
				}
				$delivery = Shipment_Driver::withTrashed()->wherein('id', $ids)->whereNull('deleted_at')->orderby('id','desc')->get();

				foreach ($delivery as $key => $value) {
                    $delivery1[$key] = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();

                }
        	$data['delivery'] = count($delivery1);
			}
		}
			else if ($Request->role == "forwarder") {

				$data['total'] = Shipment::where('forwarder', $Request->other_id)->whereNull('deleted_at')->count();

				$data['pending'] = Shipment::where('status', 0)->whereNull('deleted_at')->where('forwarder', $Request->other_id)->count();

				$data['delivery'] = Shipment::where('status', 2)->whereNull('deleted_at')->where('forwarder', $Request->other_id)->count();

				$data['remaining'] = Shipment::where('paid', 0)->whereNull('deleted_at')->where('forwarder', $Request->other_id)->sum('invoice_amount');
			}
			else if ($Request->role == "company") {

				$data['total'] = Shipment::where('company',$Request->other_id)->whereNull('deleted_at')->count();

				$data['pending'] = Shipment::where('status',0)->whereNull('deleted_at')->where('company',$Request->other_id)->count();
				$data['ontheway'] = Shipment::where('status',1)->whereNull('deleted_at')->where('company',$Request->other_id)->count();
				$data['delivery'] = Shipment::where('status',2)->whereNull('deleted_at')->where('company',$Request->other_id)->count();
			}

			return response()->json(['status' => 'success', 'message' => 'Dashboard Data Success.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {
			dd($e);
			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//64
	public function TransporterAC(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				$data = array();

				if ($Request->month != "" && $Request->year != "") {


					$data1 = Shipment_Transporter::leftJoin('shipment','shipment.id','=','shipment_transporter.shipment_id')->where('shipment_transporter.transporter_id', $Request->other_id)->where('shipment_transporter.status', 3)->whereYear('shipment_transporter.created_at', $Request->year)->whereNull('shipment_transporter.deleted_at')->whereMonth('shipment_transporter.created_at', $Request->month);

				}
				$data1 = $data1->paginate($perPage);
				foreach ($data1 as $key => $value) {

					//$ship_data = Shipment::where('shipment_no', $value->shipment_no)->first();

					if ($value->status == 2) {
						$data1[$key] = $value;
						$data1[$key]['expense'] = $value->expense;
					}
				}

				if (!empty($data1)) {
					$message = 'Data Success.';
					$dataa = $data1;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{
			$data = array();

			if ($Request->month != "" && $Request->year != "") {

				$data1 = Shipment_Transporter::where('transporter_id', $Request->other_id)->where('status', 3)->whereYear('created_at', $Request->year)->whereNull('deleted_at')->whereMonth('created_at', $Request->month)->get();

				foreach ($data1 as $key => $value) {

					$ship_data = Shipment::where('shipment_no', $value->shipment_no)->first();

					if ($ship_data->status == 2) {
						$data1[$key] = $ship_data;
						$data1[$key]['expense'] = $value->expense;
					}
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Data Success.', 'data' => $data1, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//64
	public function Filters(Request $Request)
    {
        try {
            $check = $this->checkversion($Request->version);
            $all = $Request->all();
            if ($check == 1) {
                return response()->json(
                    [
                        "status" => "failed",
                        "message" => "Please update this application.",
                        "data" => json_decode("{}"),
                        "code" => "500",
                    ],
                    200
                );
            }
            if (isset($all["page"]) && $all["offset"]) {
                //pagination coding
                $page = 1;
                $perPage = 10;
                if (isset($all["page"]) && !empty($all["page"])) {
                    $page = $all["page"];
                }
                if (isset($all["offset"]) && !empty($all["offset"])) {
                    $perPage = $all["offset"];
                }
                $offset = ($page - 1) * $perPage;
                $data = [];

                if ($Request->role == "admin" || $Request->role == "employee") {
                    if (
                        $Request->keyword != "" &&
                        $Request->keyword != " " &&
                        $Request->keyword != "null" &&
                        $Request->keyword != null
                    ) {
                        $data1 = Shipment::where(
                            "shipment_no",
                            "like",
                            "%" . $Request->keyword . "%"
                        )
                            ->orwhere(
                                "from1",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "to1",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "to2",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "consignor",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "consignee",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "shipper_invoice",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "forwarder_ref_no",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "b_e_no",
                                "like",
                                "%" . $Request->keyword . "%"
                            )

                            ->whereNull("deleted_at")
                            ->orderby("id", "desc");
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        $data1 = $data1->paginate($perPage);
                        foreach ($data1 as $key => $value) {
                            $data1[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data1[$key]["company_name"] = $com->name;
                            } else {
                                $data1[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data1[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data1[$key]["forwarder_name"] = "";
                            }

                            if (
                                $value->trucktype &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data1[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->shipment_no != "" &&
                        $Request->shipment_no != " " &&
                        $Request->shipment_no != "null" &&
                        $Request->shipment_no != null
                    ) {
                        $data1 = Shipment::where(
                            "shipment_no",
                            $Request->shipment_no
                        )

                            ->whereNull("deleted_at")
                            ->paginate($perPage);
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        foreach ($data1 as $key => $value) {
                            $data1[$key] = $value;

                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data1[$key]["company_name"] = $com->name;
                                // dd($com->name);
                            } else {
                                $data1[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data1[$key]["forwarder_name"] = $forw->name;
                                //dd($forw->name);
                            } else {
                                $data1[$key]["forwarder_name"] = "";
                            }

                            if (
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                //dd(1);
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data1[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->forwarder != "" &&
                        $Request->forwarder != " " &&
                        $Request->forwarder != "null" &&
                        $Request->forwarder != null
                    ) {
                        $data1 = Shipment::where(
                            "forwarder",
                            $Request->forwarder
                        )
                            ->whereNull("deleted_at")
                            ->orderby("shipment_no", "desc");
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        $data1 = $data1->paginate($perPage);
                        foreach ($data1 as $key => $value) {
                            $data1[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data1[$key]["company_name"] = $com->name;
                            } else {
                                $data1[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data1[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data1[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data1[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->transporter != "" &&
                        $Request->transporter != " " &&
                        $Request->transporter != "null" &&
                        $Request->transporter != null
                    ) {
                        $data1 = Shipment::whereRaw("find_in_set('$Request->transporter' , all_transporter)")
                            ->whereNull("deleted_at")
                            ->orderby("id", "desc");
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        $data1 = $data1->paginate($perPage);
                        foreach ($data1 as $key => $value) {


                            $data1[$key] = $value;
                            if ($value->company) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data1[$key]["company_name"] = $com->name;
                            } else {
                                $data1[$key]["company_name"] = "";
                            }

                            if ($value->forwarder) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data1[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data1[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data1[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->date != "" &&
                        $Request->date != " " &&
                        $Request->date != "null" &&
                        $Request->date != null
                    ) {
                        $date = date(
                            "Y-m-d",
                            strtotime(
                                $Request->date .
                                    "-" .
                                    $Request->month .
                                    "-" .
                                    $Request->year
                            )
                        );

                        $data1 = Shipment::withTrashed()
                            ->where("date", $date)

                            ->whereNull("deleted_at")
                            ->orderBy("id", "desc");
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        $data1 = $data1->paginate($perPage);
                        foreach ($data1 as $key => $value) {
                            $data1[$key] = $value;

                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data1[$key]["company_name"] = $com->name;
                            } else {
                                $data1[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data1[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data1[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data1[$key]["status"] = "warehouse";
                            }
                        }
                    }
					elseif ($Request->month != "" && $Request->year != "") {
                        $data1 = Shipment::whereYear(
                            "created_at",
                            $Request->year
                        )
                            ->whereMonth("created_at", $Request->month)

                            ->whereNull("deleted_at")
                            ->orderby("id", "desc");
                        if ($Request->status == "pending") {
                            $data1 = $data1->where("status", "0");
                        }
                        if ($Request->status == "ontheway") {
                            $data1 = $data1->where("status", "1");
                        }
                        if ($Request->status == "delivered") {
                            $data1 = $data1->where("status", "2");
                        }
						 $data1 = $data1->paginate($perPage);


                    // $data1 = $data1->paginate($perPage);
                    foreach ($data1 as $key => $value) {
                        $data1[$key] = $value;

                        if ($value) {
                            $com = Company::withTrashed()->findorfail(
                                $value->company
                            );
                            $data1[$key]["company_name"] = $com->name;
                        } else {
                            $data1[$key]["company_name"] = "";
                        }

                        if ($value) {
                            $forw = Forwarder::withTrashed()->findorfail(
                                $value->forwarder
                            );
                            $data1[$key]["forwarder_name"] = $forw->name;
                        } else {
                            $data1[$key]["forwarder_name"] = "";
                        }

                        if (
                            isset($value->trucktype) &&
                            $value->trucktype != "" &&
                            $value->trucktype != "null" &&
                            $value->trucktype != null
                        ) {
                            $tk = Truck::withTrashed()->findorfail(
                                $value->trucktype
                            );

                            $data1[$key]["vehicle"] = $tk->name;
                        } else {
                            $data1[$key]["vehicle"] = "";
                        }

                        if ($value->status == 0) {
                            $data1[$key]["status"] = "pending";
                        } elseif ($value->status == 1) {
                            $data1[$key]["status"] = "ontheway";
                        } elseif ($value->status == 2) {
                            $data1[$key]["status"] = "delivery";
                        } elseif ($value->status == 3) {
                            $data1[$key]["status"] = "warehouse";
                        }
                    }
					}
				elseif ($Request->status != "") {
					$data1 = Shipment::
						whereNull("deleted_at")
						->orderby("id", "desc");
					if ($Request->status == "pending") {
						$data1 = $data1->where("status", "0");
					}
					if ($Request->status == "ontheway") {
						$data1 = $data1->where("status", "1");
					}
					if ($Request->status == "delivered") {
						$data1 = $data1->where("status", "2");
					}
					 $data1 = $data1->paginate($perPage);


				// $data1 = $data1->paginate($perPage);
				foreach ($data1 as $key => $value) {
					$data1[$key] = $value;

					if ($value) {
						$com = Company::withTrashed()->findorfail(
							$value->company
						);
						$data1[$key]["company_name"] = $com->name;
					} else {
						$data1[$key]["company_name"] = "";
					}

					if ($value) {
						$forw = Forwarder::withTrashed()->findorfail(
							$value->forwarder
						);
						$data1[$key]["forwarder_name"] = $forw->name;
					} else {
						$data1[$key]["forwarder_name"] = "";
					}

					if (
						isset($value->trucktype) &&
						$value->trucktype != "" &&
						$value->trucktype != "null" &&
						$value->trucktype != null
					) {
						$tk = Truck::withTrashed()->findorfail(
							$value->trucktype
						);

						$data1[$key]["vehicle"] = $tk->name;
					} else {
						$data1[$key]["vehicle"] = "";
					}

					if ($value->status == 0) {
						$data1[$key]["status"] = "pending";
					} elseif ($value->status == 1) {
						$data1[$key]["status"] = "ontheway";
					} elseif ($value->status == 2) {
						$data1[$key]["status"] = "delivery";
					} elseif ($value->status == 3) {
						$data1[$key]["status"] = "warehouse";
					}
				}
                }
			}
				elseif ($Request->role == "transporter") {
                    if ($Request->month != "" && $Request->year != "") {
                        $data1 = Shipment_Driver::where(
                            "transporter_id",
                            $Request->other_id
                        )
                            ->whereYear("created_at", $Request->year)
                            ->whereNull("deleted_at")
                            ->whereMonth("created_at", $Request->month)
                            ->groupBy("shipment_no");

                        $data1 = $data1->paginate($perPage);

                        $data = [];
                        foreach ($data1 as $key => $value) {
                            $data[$key] = Shipment::where(
                                "shipment_no",
                                $value->shipment_no
                            )
                                ->orderBy("shipment_no", "desc")
                                ->first();
                            if ($data[$key]) {
                                $data1[$key]["id"] = $data[$key]->id;
                                $data1[$key]["myid"] = $data[$key]->myid;

                                $data1[$key]["imports"] = $data[$key]->imports;
                                $data1[$key]["exports"] = $data[$key]->exports;
                                $data1[$key]["lcl"] = $data[$key]->lcl;
                                $data1[$key]["fcl"] = $data[$key]->fcl;
                                $data1[$key]["from1"] = $data[$key]->from1;
                                $data1[$key]["to1"] = $data[$key]->to1;
                                $data1[$key]["to2"] = $data[$key]->to2;
                                $data1[$key]["date"] = $data[$key]->date;
                                $data1[$key]["consignor"] =
                                    $data[$key]->consignor;
                                $data1[$key]["consignor_address"] =
                                    $data[$key]->consignor_address;
                                $data1[$key]["consignee"] =
                                    $data[$key]->consignee;
                                $data1[$key]["consignee_address"] =
                                    $data[$key]->consignee_address;

                                $data1[$key]["expense"] = $value->expense;
                            }

                            $data3 = Shipment_Driver::withTrashed()
                                ->where("shipment_no", $value->shipment_no)
                                ->where("transporter_id", $Request->other_id)
                                ->orderBy("id", "desc")
                                ->first();

                            if ($data3->status == 1) {
                                $data1[$key]["status"] = "pending";
                            } elseif (
                                $data3->status == 2 ||
                                $data3->status == 4 ||
                                $data3->status == 5 ||
                                $data3->status == 6 ||
                                $data3->status == 7 ||
                                $data3->status == 8 ||
                                $data3->status == 9 ||
                                $data3->status == 10 ||
                                $data3->status == 11 ||
                                $data3->status == 12 ||
                                $data3->status == 13 ||
                                $data3->status == 14 ||
                                $data3->status == 15 ||
                                $data3->status == 18
                            ) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif (
                                $data3->status == 3 ||
                                $data3->status == 17
                            ) {
                                $data1[$key]["status"] = "delivery";
                            }
                        }
                    }
                } elseif ($Request->role == "company")
				{
                    if (
                        $Request->keyword != "" &&
                        $Request->keyword != " " &&
                        $Request->keyword != "null" &&
                        $Request->keyword != null
                    ) {
                        $data1 = Shipment::where(
                            "shipment_no",
                            "like",
                            "%" . $Request->keyword . "%"
                        )
                            ->orwhere(
                                "from1",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "to1",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "to2",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "consignor",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "consignee",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "shipper_invoice",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "forwarder_ref_no",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "b_e_no",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->where("company", $Request->other_id)
                            ->whereNull("deleted_at")
                            ->orderby("id", "desc");
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        $data1 = $data1->paginate($perPage);
                        foreach ($data1 as $key => $value) {
                            $data1[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data1[$key]["company_name"] = $com->name;
                            } else {
                                $data1[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data1[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data1[$key]["forwarder_name"] = "";
                            }

                            if (
                                $value->trucktype &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data1[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->shipment_no != "" &&
                        $Request->shipment_no != " " &&
                        $Request->shipment_no != "null" &&
                        $Request->shipment_no != null
                    ) {
                        $data1 = Shipment::where(
                            "shipment_no",
                            $Request->shipment_no
                        )
                            ->where("company", $Request->other_id)
                            ->whereNull("deleted_at")
                            ->paginate($perPage);
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        foreach ($data1 as $key => $value) {
                            $data1[$key] = $value;

                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data1[$key]["company_name"] = $com->name;
                                // dd($com->name);
                            } else {
                                $data1[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data1[$key]["forwarder_name"] = $forw->name;
                                //dd($forw->name);
                            } else {
                                $data1[$key]["forwarder_name"] = "";
                            }

                            if (
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                //dd(1);
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data1[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->forwarder != "" &&
                        $Request->forwarder != " " &&
                        $Request->forwarder != "null" &&
                        $Request->forwarder != null
                    ) {
                        $data1 = Shipment::where(
                            "forwarder",
                            $Request->forwarder
                        )
                            ->whereNull("deleted_at")
                            ->orderby("shipment_no", "desc");
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        $data1 = $data1->paginate($perPage);
                        foreach ($data1 as $key => $value) {
                            $data1[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data1[$key]["company_name"] = $com->name;
                            } else {
                                $data1[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data1[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data1[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data1[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->transporter != "" &&
                        $Request->transporter != " " &&
                        $Request->transporter != "null" &&
                        $Request->transporter != null
                    ) {
                        $data1 = Shipment::whereRaw("find_in_set('$Request->transporter' , all_transporter)")
                            ->whereNull("deleted_at")
                            ->orderby("id", "desc");
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        $data1 = $data1->paginate($perPage);
                        foreach ($data1 as $key => $value) {


                            $data1[$key] = $value;
                            if ($value->company) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data1[$key]["company_name"] = $com->name;
                            } else {
                                $data1[$key]["company_name"] = "";
                            }

                            if ($value->forwarder) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data1[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data1[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data1[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->date != "" &&
                        $Request->date != " " &&
                        $Request->date != "null" &&
                        $Request->date != null
                    ) {
                        $date = date(
                            "Y-m-d",
                            strtotime(
                                $Request->date .
                                    "-" .
                                    $Request->month .
                                    "-" .
                                    $Request->year
                            )
                        );

                        $data1 = Shipment::withTrashed()
                            ->where("date", $date)
                            ->where("company", $Request->other_id)
                            ->whereNull("deleted_at")
                            ->orderBy("id", "desc");
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        $data1 = $data1->paginate($perPage);
                        foreach ($data1 as $key => $value) {
                            $data1[$key] = $value;

                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data1[$key]["company_name"] = $com->name;
                            } else {
                                $data1[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data1[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data1[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data1[$key]["status"] = "warehouse";
                            }
                        }
                    }
					elseif ($Request->month != "" && $Request->year != "") {
                        $data1 = Shipment::whereYear(
                            "created_at",
                            $Request->year
                        )
                            ->whereMonth("created_at", $Request->month)
                            ->where("company", $Request->other_id)
                            ->whereNull("deleted_at")
                            ->orderby("id", "desc");
                        if ($Request->status == "pending") {
                            $data1 = $data1->where("status", "0");
                        }
                        if ($Request->status == "ontheway") {
                            $data1 = $data1->where("status", "1");
                        }
                        if ($Request->status == "delivered") {
                            $data1 = $data1->where("status", "2");
                        }
						 $data1 = $data1->paginate($perPage);


                    // $data1 = $data1->paginate($perPage);
                    foreach ($data1 as $key => $value) {
                        $data1[$key] = $value;

                        if ($value) {
                            $com = Company::withTrashed()->findorfail(
                                $value->company
                            );
                            $data1[$key]["company_name"] = $com->name;
                        } else {
                            $data1[$key]["company_name"] = "";
                        }

                        if ($value) {
                            $forw = Forwarder::withTrashed()->findorfail(
                                $value->forwarder
                            );
                            $data1[$key]["forwarder_name"] = $forw->name;
                        } else {
                            $data1[$key]["forwarder_name"] = "";
                        }

                        if (
                            isset($value->trucktype) &&
                            $value->trucktype != "" &&
                            $value->trucktype != "null" &&
                            $value->trucktype != null
                        ) {
                            $tk = Truck::withTrashed()->findorfail(
                                $value->trucktype
                            );

                            $data1[$key]["vehicle"] = $tk->name;
                        } else {
                            $data1[$key]["vehicle"] = "";
                        }

                        if ($value->status == 0) {
                            $data1[$key]["status"] = "pending";
                        } elseif ($value->status == 1) {
                            $data1[$key]["status"] = "ontheway";
                        } elseif ($value->status == 2) {
                            $data1[$key]["status"] = "delivery";
                        } elseif ($value->status == 3) {
                            $data1[$key]["status"] = "warehouse";
                        }
                    }
				}
				elseif ($Request->status != "") {
					$data1 = Shipment::where("company", $Request->other_id)
						->whereNull("deleted_at")
						->orderby("id", "desc");
					if ($Request->status == "pending") {
						$data1 = $data1->where("status", "0");
					}
					if ($Request->status == "ontheway") {
						$data1 = $data1->where("status", "1");
					}
					if ($Request->status == "delivered") {
						$data1 = $data1->where("status", "2");
					}
					 $data1 = $data1->paginate($perPage);


				// $data1 = $data1->paginate($perPage);
				foreach ($data1 as $key => $value) {
					$data1[$key] = $value;

					if ($value) {
						$com = Company::withTrashed()->findorfail(
							$value->company
						);
						$data1[$key]["company_name"] = $com->name;
					} else {
						$data1[$key]["company_name"] = "";
					}

					if ($value) {
						$forw = Forwarder::withTrashed()->findorfail(
							$value->forwarder
						);
						$data1[$key]["forwarder_name"] = $forw->name;
					} else {
						$data1[$key]["forwarder_name"] = "";
					}

					if (
						isset($value->trucktype) &&
						$value->trucktype != "" &&
						$value->trucktype != "null" &&
						$value->trucktype != null
					) {
						$tk = Truck::withTrashed()->findorfail(
							$value->trucktype
						);

						$data1[$key]["vehicle"] = $tk->name;
					} else {
						$data1[$key]["vehicle"] = "";
					}

					if ($value->status == 0) {
						$data1[$key]["status"] = "pending";
					} elseif ($value->status == 1) {
						$data1[$key]["status"] = "ontheway";
					} elseif ($value->status == 2) {
						$data1[$key]["status"] = "delivery";
					} elseif ($value->status == 3) {
						$data1[$key]["status"] = "warehouse";
					}
				}
			}
                } elseif ($Request->role == "driver") {
                    if ($Request->month != "" && $Request->year != "") {
                        $data1 = Shipment::rightJoin(
                            "shipment_driver",
                            "shipment_driver.shipment_no",
                            "=",
                            "shipment.shipment_no"
                        )
                            ->where(
                                "shipment_driver.driver_id",
                                $Request->user_id
                            )
                            ->whereYear(
                                "shipment_driver.created_at",
                                $Request->year
                            )
                            ->whereMonth(
                                "shipment_driver.created_at",
                                $Request->month
                            )
                            ->whereIn("shipment.status", ["0", "1", "2"])
                            ->whereNull("shipment_driver.deleted_at")
                            ->orderby("shipment_driver.id", "desc");
                        //dd($data1);
                        $data1 = $data1->paginate($perPage);
                        foreach ($data1 as $key => $value) {
                            $data[$key] = Shipment::where(
                                "shipment_no",
                                $value->shipment_no
                            )->first();
                            if ($data[$key]) {
                                $data1[$key]["id"] = $data[$key]->id;
                                $data1[$key]["myid"] = $data[$key]->myid;
                                $data1[$key]["imports"] = $data[$key]->imports;
                                $data1[$key]["exports"] = $data[$key]->exports;
                                $data1[$key]["lcl"] = $data[$key]->lcl;
                                $data1[$key]["fcl"] = $data[$key]->fcl;
                                $data1[$key]["from1"] = $data[$key]->from1;
                                $data1[$key]["to1"] = $data[$key]->to1;
                                $data1[$key]["to2"] = $data[$key]->to2;
                                $data1[$key]["date"] = $data[$key]->date;
                                $data1[$key]["consignor"] =
                                    $data[$key]->consignor;
                                $data1[$key]["consignor_address"] =
                                    $data[$key]->consignor_address;
                                $data1[$key]["consignee"] =
                                    $data[$key]->consignee;
                                $data1[$key]["consignee_address"] =
                                    $data[$key]->consignee_address;
                            }

                            if ($value->status == 1) {
                                $data1[$key]["status"] = "pending";
                            } elseif (
                                $value->status == 2 ||
                                $value->status == 4 ||
                                $value->status == 5 ||
                                $value->status == 6 ||
                                $value->status == 7 ||
                                $value->status == 8 ||
                                $value->status == 9 ||
                                $value->status == 10 ||
                                $value->status == 11 ||
                                $value->status == 12 ||
                                $value->status == 13 ||
                                $value->status == 14 ||
                                $value->status == 15 ||
                                $value->status == 18
                            ) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif (
                                $value->status == 3 ||
                                $value->status == 17
                            ) {
                                $data1[$key]["status"] = "delivery";
                            }
                        }
                        //dd($data1);
                    }
                } elseif ($Request->role == "forwarder") {
                    if ($Request->month != "" && $Request->year != "") {
                        $data1 = Shipment::where(
                            "forwarder",
                            $Request->other_id
                        )
                            ->whereNull("deleted_at")
                            ->whereYear("date", $Request->year)
                            ->whereMonth("date", $Request->month);
                        $data1 = $data1->paginate($perPage);

                        foreach ($data1 as $key => $value) {
                            $data1[$key] = $value;

                            $com = Company::withTrashed()->findorfail(
                                $value->company
                            );

                            $data1[$key]["company_name"] = $com->name;

                            $forw = Forwarder::withTrashed()->findorfail(
                                $value->forwarder
                            );

                            $data1[$key]["forwarder_name"] = $forw->name;

                            if (
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data1[$key]["vehicle"] = $tk->name;
                            } else {
                                $data1[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data1[$key]["status"] = "pending";
                            } elseif (
                                $value->status == 1 ||
                                $value->status == 3
                            ) {
                                $data1[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data1[$key]["status"] = "delivery";
                            }
                        }
                    }
                }
                if (!empty($data1)) {
                    $message = "Data Success.";
                    $dataa = $data1;
                    return $this->APIResponse->successWithPagination(
                        $message,
                        $dataa
                    );
                } else {
                    return $this->APIResponse->respondNotFound(
                        __("No Record Found")
                    );
                }
            } else {
                $data = [];
                if ($Request->role == "admin" || $Request->role == "employee") {
                    if (
                        $Request->keyword != "" &&
                        $Request->keyword != " " &&
                        $Request->keyword != "null" &&
                        $Request->keyword != null
                    )
					{
                        $data = Shipment::where(
                            "shipment_no",
                            "like",
                            "%" . $Request->keyword . "%"
                        )
                            ->orwhere(
                                "from1",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "to1",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "to2",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "consignor",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "consignee",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "shipper_invoice",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "forwarder_ref_no",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "b_e_no",
                                "like",
                                "%" . $Request->keyword . "%"
                            )

                            ->whereNull("deleted_at")
                            ->orderby("id", "desc");
                        $data = $data->get();
						if ($Request->status == "pending") {
                            $data = $data->where("status", "0");
                        }
                        if ($Request->status == "ontheway") {
                            $data = $data->where("status", "1");
                        }
                        if ($Request->status == "delivered") {
                            $data = $data->where("status", "2");
                        }
                        foreach ($data as $key => $value) {
                            $data[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data[$key]["company_name"] = $com->name;
                            } else {
                                $data[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data[$key]["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->shipment_no != "" &&
                        $Request->shipment_no != " " &&
                        $Request->shipment_no != "null" &&
                        $Request->shipment_no != null
                    ) {
                        $data = Shipment::where(
                            "shipment_no",
                            $Request->shipment_no
                        )

                            ->whereNull("deleted_at")
                            ->orderBy("id", "desc")
                            ->get();
							if ($Request->status == "pending") {
								$data = $data->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data = $data->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data = $data->where("status", "2");
							}
                        foreach ($data as $key => $value) {
                            $data[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );

                                $data[$key]["company_name"] = $com->name;
                            } else {
                                $data[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $data1->trucktype
                                );

                                $data["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->forwarder != "" &&
                        $Request->forwarder != " " &&
                        $Request->forwarder != "null" &&
                        $Request->forwarder != null
                    ) {
                        $data = Shipment::where(
                            "forwarder",
                            $Request->forwarder
                        )
                            ->whereNull("deleted_at")
                            ->orderby("shipment_no", "desc");
                        $data = $data->get();
						if ($Request->status == "pending") {
                            $data = $data1->where("status", "0");
                        }
                        if ($Request->status == "ontheway") {
                            $data1 = $data1->where("status", "1");
                        }
                        if ($Request->status == "delivered") {
                            $data1 = $data1->where("status", "2");
                        }
                        foreach ($data as $key => $value) {
                            $data[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data[$key]["company_name"] = $com->name;
                            } else {
                                $data[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data[$key]["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data[$key]["status"] = "warehouse";
                            }
                        }
                    }
					elseif (
                        $Request->transporter != "" &&
                        $Request->transporter != " " &&
                        $Request->transporter != "null" &&
                        $Request->transporter != null
                    )
					{
                        $data = Shipment::whereRaw("find_in_set('$Request->transporter' , all_transporter)")
                            ->whereNull("deleted_at")
                            ->orderby("id", "desc");
                        $data = $data->get();
						if ($Request->status == "pending") {
                            $data = $data->where("status", "0");
                        }
                        if ($Request->status == "ontheway") {
                            $data = $data->where("status", "1");
                        }
                        if ($Request->status == "delivered") {
                            $data = $data->where("status", "2");
                        }
                        foreach ($data as $key => $value) {


                            $data[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data[$key]["company_name"] = $com->name;
                            } else {
                                $data[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data[$key]["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->date != "" &&
                        $Request->date != " " &&
                        $Request->date != "null" &&
                        $Request->date != null
                    ) {
                        $date = date(
                            "Y-m-d",
                            strtotime(
                                $Request->date .
                                    "-" .
                                    $Request->month .
                                    "-" .
                                    $Request->year
                            )
                        );

                        $data = Shipment::withTrashed()
                            ->where("date", $date)

                            ->whereNull("deleted_at")
                            ->orderBy("id", "desc");
                        $data = $data->get();
						if ($Request->status == "pending") {
							$data = $data->where("status", "0")->get();
						}
						if ($Request->status == "ontheway") {
							$data = $data->where("status", "1")->get();
						}
						if ($Request->status == "delivered") {
							$data = $data->where("status", "2")->get();
						}
                        //dd($data1);
                        foreach ($data as $key => $value) {
                            $data[$key] = $value;

                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data[$key]["company_name"] = $com->name;
                            } else {
                                $data[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data[$key]["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif ($Request->month != "" && $Request->year != "" ) {
                        $data = Shipment::whereYear(
                            "created_at",
                            $Request->year
                        )
                            ->whereMonth("created_at", $Request->month)

                            ->whereNull("deleted_at")
                            ->orderby("id", "desc")
                            ->get();
                        if ($Request->status == "pending") {
                            $data = $data->where("status", "0");
                        }
                        if ($Request->status == "ontheway") {
                            $data = $data->where("status", "1");
                        }
                        if ($Request->status == "delivered") {
                            $data = $data->where("status", "2");
                        }


                    foreach ($data as $key => $value) {
                        $data[$key] = $value;

                        $com = Company::withTrashed()->findorfail(
                            $value->company
                        );

                        $data[$key]["company_name"] = $com->name;

                        $forw = Forwarder::withTrashed()->findorfail(
                            $value->forwarder
                        );

                        $data[$key]["forwarder_name"] = $forw->name;

                        if (
                            $value->trucktype != "" &&
                            $value->trucktype != "null" &&
                            $value->trucktype != null
                        ) {
                            $tk = Truck::withTrashed()->findorfail(
                                $value->trucktype
                            );

                            $data[$key]["vehicle"] = $tk->name;
                        } else {
                            $data[$key]["vehicle"] = "";
                        }

                        if ($value->status == 0) {
                            $data[$key]["status"] = "pending";
                        } elseif ($value->status == 1) {
                            $data[$key]["status"] = "ontheway";
                        } elseif ($value->status == 2) {
                            $data[$key]["status"] = "delivery";
                        } elseif ($value->status == 3) {
                            $data[$key]["status"] = "warehouse";
                        }
                    }
				}
				elseif ($Request->status != "" ) {
					$data = Shipment::whereNull("deleted_at")
						->orderby("id", "desc")
						->get();
					if ($Request->status == "pending") {
						$data = $data->where("status", "0");
					}
					if ($Request->status == "ontheway") {
						$data = $data->where("status", "1");
					}
					if ($Request->status == "delivered") {
						$data= $data->where("status", "2");
					}


				foreach ($data as $key => $value) {
					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail(
						$value->company
					);

					$data[$key]["company_name"] = $com->name;

					$forw = Forwarder::withTrashed()->findorfail(
						$value->forwarder
					);

					$data[$key]["forwarder_name"] = $forw->name;

					if (
						$value->trucktype != "" &&
						$value->trucktype != "null" &&
						$value->trucktype != null
					) {
						$tk = Truck::withTrashed()->findorfail(
							$value->trucktype
						);

						$data[$key]["vehicle"] = $tk->name;
					} else {
						$data[$key]["vehicle"] = "";
					}

					if ($value->status == 0) {
						$data[$key]["status"] = "pending";
					} elseif ($value->status == 1) {
						$data[$key]["status"] = "ontheway";
					} elseif ($value->status == 2) {
						$data[$key]["status"] = "delivery";
					} elseif ($value->status == 3) {
						$data[$key]["status"] = "warehouse";
					}
				}
                }
			}
				elseif ($Request->role == "transporter") {
                    if ($Request->month != "" && $Request->year != "") {
                        $data1 = Shipment_Driver::where(
                            "transporter_id",
                            $Request->other_id
                        )
                            ->whereYear("created_at", $Request->year)
                            ->whereNull("deleted_at")
                            ->whereMonth("created_at", $Request->month)
                            ->groupBy("shipment_no")
                            ->get();

                        $data = [];
                        foreach ($data1 as $key => $value) {
                            $data1 = Shipment::withTrashed()
                                ->where("shipment_no", $value->shipment_no)
                                ->whereIn("status", ["0", "1", "2"])
                                ->first();
                            $data[$key] = $data1;

                            $data[$key]["id"] = $data1->id;
                            $data[$key]["myid"] = $data1->myid;

                            $data[$key]["imports"] = $data1->imports;
                            $data[$key]["exports"] = $data1->exports;
                            $data[$key]["lcl"] = $data1->lcl;
                            $data[$key]["fcl"] = $data1->fcl;
                            $data[$key]["from1"] = $data1->from1;
                            $data[$key]["to1"] = $data1->to1;
                            $data[$key]["to2"] = $data1->to2;
                            $data[$key]["date"] = $data1->date;
                            $data[$key]["consignor"] = $data1->consignor;
                            $data[$key]["consignor_address"] =
                                $data1->consignor_address;
                            $data[$key]["consignee"] = $data1->consignee;
                            $data[$key]["consignee_address"] =
                                $data1->consignee_address;

                            $data[$key]["expense"] = $data1->expense;
                            $data3 = Shipment_Driver::withTrashed()
                                ->where("shipment_no", $value->shipment_no)
                                ->where("transporter_id", $Request->other_id)
                                ->first();

                            if ($data3->status == 1) {
                                $data[$key]["status"] = "pending";
                            } elseif (
                                $data3->status == 2 ||
                                $data3->status == 4 ||
                                $data3->status == 5 ||
                                $data3->status == 6 ||
                                $data3->status == 7 ||
                                $data3->status == 8 ||
                                $data3->status == 9 ||
                                $data3->status == 10 ||
                                $data3->status == 11 ||
                                $data3->status == 12 ||
                                $data3->status == 13 ||
                                $data3->status == 14 ||
                                $data3->status == 15 ||
                                $data3->status == 18
                            ) {
                                $data[$key]["status"] = "ontheway";
                            } elseif (
                                $data3->status == 3 ||
                                $data3->status == 17
                            ) {
                                $data[$key]["status"] = "delivery";
                            }
                        }
                    }
                } elseif ($Request->role == "company") {
                    if (
                        $Request->keyword != "" &&
                        $Request->keyword != " " &&
                        $Request->keyword != "null" &&
                        $Request->keyword != null
                    )
					{
                        $data1 = Shipment::where(
                            "shipment_no",
                            "like",
                            "%" . $Request->keyword . "%"
                        )
                            ->orwhere(
                                "from1",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "to1",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "to2",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "consignor",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "consignee",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "shipper_invoice",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "forwarder_ref_no",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->orwhere(
                                "b_e_no",
                                "like",
                                "%" . $Request->keyword . "%"
                            )
                            ->where("company", $Request->other_id)
                            ->whereNull("deleted_at")
                            ->orderby("id", "desc");
                        $data1 = $data1->get();
						if ($Request->status == "pending") {
                            $data1 = $data1->where("status", "0");
                        }
                        if ($Request->status == "ontheway") {
                            $data1 = $data1->where("status", "1");
                        }
                        if ($Request->status == "delivered") {
                            $data1 = $data1->where("status", "2");
                        }
                        foreach ($data1 as $key => $value) {
                            $data[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data[$key]["company_name"] = $com->name;
                            } else {
                                $data[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data[$key]["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->shipment_no != "" &&
                        $Request->shipment_no != " " &&
                        $Request->shipment_no != "null" &&
                        $Request->shipment_no != null
                    ) {
                        $data1 = Shipment::where(
                            "shipment_no",
                            $Request->shipment_no
                        )
                            ->where("company", $Request->other_id)
                            ->whereNull("deleted_at")
                            ->orderBy("id", "desc")
                            ->get();
							if ($Request->status == "pending") {
								$data1 = $data1->where("status", "0");
							}
							if ($Request->status == "ontheway") {
								$data1 = $data1->where("status", "1");
							}
							if ($Request->status == "delivered") {
								$data1 = $data1->where("status", "2");
							}
                        foreach ($data1 as $key => $value) {
                            $data[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );

                                $data[$key]["company_name"] = $com->name;
                            } else {
                                $data[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $data1->trucktype
                                );

                                $data["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->forwarder != "" &&
                        $Request->forwarder != " " &&
                        $Request->forwarder != "null" &&
                        $Request->forwarder != null
                    ) {
                        $data1 = Shipment::where(
                            "forwarder",
                            $Request->forwarder
                        )
                            ->whereNull("deleted_at")
                            ->orderby("shipment_no", "desc");
                        $data1 = $data1->get();
						if ($Request->status == "pending") {
                            $data1 = $data1->where("status", "0");
                        }
                        if ($Request->status == "ontheway") {
                            $data1 = $data1->where("status", "1");
                        }
                        if ($Request->status == "delivered") {
                            $data1 = $data1->where("status", "2");
                        }
                        foreach ($data1 as $key => $value) {
                            $data[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data[$key]["company_name"] = $com->name;
                            } else {
                                $data[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data[$key]["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data[$key]["status"] = "warehouse";
                            }
                        }
                    }
					elseif (
                        $Request->transporter != "" &&
                        $Request->transporter != " " &&
                        $Request->transporter != "null" &&
                        $Request->transporter != null
                    )
					{
                        $data1 = Shipment::whereRaw("find_in_set('$Request->transporter' , all_transporter)")
                            ->whereNull("deleted_at")
                            ->orderby("id", "desc");
                        $data1 = $data1->get();
						if ($Request->status == "pending") {
                            $data1 = $data1->where("status", "0");
                        }
                        if ($Request->status == "ontheway") {
                            $data1 = $data1->where("status", "1");
                        }
                        if ($Request->status == "delivered") {
                            $data1 = $data1->where("status", "2");
                        }
                        foreach ($data1 as $key => $value) {


                            $data[$key] = $value;
                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data[$key]["company_name"] = $com->name;
                            } else {
                                $data[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data[$key]["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif (
                        $Request->date != "" &&
                        $Request->date != " " &&
                        $Request->date != "null" &&
                        $Request->date != null
                    ) {
                        $date = date(
                            "Y-m-d",
                            strtotime(
                                $Request->date .
                                    "-" .
                                    $Request->month .
                                    "-" .
                                    $Request->year
                            )
                        );

                        $data1 = Shipment::withTrashed()
                            ->where("date", $date)
                            ->where("company", $Request->other_id)
                            ->whereNull("deleted_at")
                            ->orderBy("id", "desc");
                        $data1 = $data1->get();
						if ($Request->status == "pending") {
							$data1 = $data1->where("status", "0")->get();
						}
						if ($Request->status == "ontheway") {
							$data1 = $data1->where("status", "1")->get();
						}
						if ($Request->status == "delivered") {
							$data1 = $data1->where("status", "2")->get();
						}
                        //dd($data1);
                        foreach ($data1 as $key => $value) {
                            $data[$key] = $value;

                            if ($value) {
                                $com = Company::withTrashed()->findorfail(
                                    $value->company
                                );
                                $data[$key]["company_name"] = $com->name;
                            } else {
                                $data[$key]["company_name"] = "";
                            }

                            if ($value) {
                                $forw = Forwarder::withTrashed()->findorfail(
                                    $value->forwarder
                                );
                                $data[$key]["forwarder_name"] = $forw->name;
                            } else {
                                $data[$key]["forwarder_name"] = "";
                            }

                            if (
                                isset($value->trucktype) &&
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data[$key]["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif ($value->status == 1) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            } elseif ($value->status == 3) {
                                $data[$key]["status"] = "warehouse";
                            }
                        }
                    } elseif ($Request->month != "" && $Request->year != "" ) {
                        $data1 = Shipment::whereYear(
                            "created_at",
                            $Request->year
                        )
                            ->whereMonth("created_at", $Request->month)
                            ->where("company", $Request->other_id)
                            ->whereNull("deleted_at")
                            ->orderby("id", "desc")
                            ->get();
                        if ($Request->status == "pending") {
                            $data1 = $data1->where("status", "0");
                        }
                        if ($Request->status == "ontheway") {
                            $data1 = $data1->where("status", "1");
                        }
                        if ($Request->status == "delivered") {
                            $data1 = $data1->where("status", "2");
                        }


                    foreach ($data1 as $key => $value) {
                        $data[$key] = $value;

                        $com = Company::withTrashed()->findorfail(
                            $value->company
                        );

                        $data[$key]["company_name"] = $com->name;

                        $forw = Forwarder::withTrashed()->findorfail(
                            $value->forwarder
                        );

                        $data[$key]["forwarder_name"] = $forw->name;

                        if (
                            $value->trucktype != "" &&
                            $value->trucktype != "null" &&
                            $value->trucktype != null
                        ) {
                            $tk = Truck::withTrashed()->findorfail(
                                $value->trucktype
                            );

                            $data[$key]["vehicle"] = $tk->name;
                        } else {
                            $data[$key]["vehicle"] = "";
                        }

                        if ($value->status == 0) {
                            $data[$key]["status"] = "pending";
                        } elseif ($value->status == 1) {
                            $data[$key]["status"] = "ontheway";
                        } elseif ($value->status == 2) {
                            $data[$key]["status"] = "delivery";
                        } elseif ($value->status == 3) {
                            $data[$key]["status"] = "warehouse";
                        }
                    }
				}
				elseif ($Request->status != "" ) {
					$data1 = Shipment::where("company", $Request->other_id)
						->whereNull("deleted_at")
						->orderby("id", "desc")
						->get();
					if ($Request->status == "pending") {
						$data1 = $data1->where("status", "0");
					}
					if ($Request->status == "ontheway") {
						$data1 = $data1->where("status", "1");
					}
					if ($Request->status == "delivered") {
						$data1 = $data1->where("status", "2");
					}


				foreach ($data1 as $key => $value) {
					$data[$key] = $value;

					$com = Company::withTrashed()->findorfail(
						$value->company
					);

					$data[$key]["company_name"] = $com->name;

					$forw = Forwarder::withTrashed()->findorfail(
						$value->forwarder
					);

					$data[$key]["forwarder_name"] = $forw->name;

					if (
						$value->trucktype != "" &&
						$value->trucktype != "null" &&
						$value->trucktype != null
					) {
						$tk = Truck::withTrashed()->findorfail(
							$value->trucktype
						);

						$data[$key]["vehicle"] = $tk->name;
					} else {
						$data[$key]["vehicle"] = "";
					}

					if ($value->status == 0) {
						$data[$key]["status"] = "pending";
					} elseif ($value->status == 1) {
						$data[$key]["status"] = "ontheway";
					} elseif ($value->status == 2) {
						$data[$key]["status"] = "delivery";
					} elseif ($value->status == 3) {
						$data[$key]["status"] = "warehouse";
					}
				}
			}
                } elseif ($Request->role == "driver") {
                    if ($Request->month != "" && $Request->year != "") {
                        $data1 = Shipment_Driver::where(
                            "driver_id",
                            $Request->user_id
                        )
                            ->whereYear("created_at", $Request->year)
                            ->whereNull("deleted_at")
                            ->whereMonth("created_at", $Request->month)
                            ->orderby("id", "desc")
                            ->get();

                        foreach ($data1 as $key => $value) {
                            //dd($value);
                            $data[$key] = Shipment::where(
                                "shipment_no",
                                $value->shipment_no
                            )
                                ->whereIn("status", ["0", "1", "2"])
                                ->orderby("id", "desc")
                                ->first();

                            $data[$key]["expense"] = $value->expense;

                            if ($value->status == 1) {
                                $data[$key]["status"] = "pending";
                            } elseif (
                                $value->status == 2 ||
                                $value->status == 4 ||
                                $value->status == 5 ||
                                $value->status == 6 ||
                                $value->status == 7 ||
                                $value->status == 8 ||
                                $value->status == 9 ||
                                $value->status == 10 ||
                                $value->status == 11 ||
                                $value->status == 12 ||
                                $value->status == 13 ||
                                $value->status == 14 ||
                                $value->status == 15 ||
                                $value->status == 18
                            ) {
                                $data[$key]["status"] = "ontheway";
                            } elseif (
                                $value->status == 3 ||
                                $value->status == 17
                            ) {
                                $data[$key]["status"] = "delivery";
                            }
                        }
                    }
                } elseif ($Request->role == "forwarder") {
                    if ($Request->month != "" && $Request->year != "") {
                        $data1 = Shipment::where(
                            "forwarder",
                            $Request->other_id
                        )
                            ->whereNull("deleted_at")
                            ->whereYear("date", $Request->year)
                            ->whereMonth("date", $Request->month)
                            ->orderby("id", "desc")
                            ->get();

                        foreach ($data1 as $key => $value) {
                            $data[$key] = $value;

                            $com = Company::withTrashed()->findorfail(
                                $value->company
                            );

                            $data[$key]["company_name"] = $com->name;

                            $forw = Forwarder::withTrashed()->findorfail(
                                $value->forwarder
                            );

                            $data[$key]["forwarder_name"] = $forw->name;

                            if (
                                $value->trucktype != "" &&
                                $value->trucktype != "null" &&
                                $value->trucktype != null
                            ) {
                                $tk = Truck::withTrashed()->findorfail(
                                    $value->trucktype
                                );

                                $data[$key]["vehicle"] = $tk->name;
                            } else {
                                $data[$key]["vehicle"] = "";
                            }

                            if ($value->status == 0) {
                                $data[$key]["status"] = "pending";
                            } elseif (
                                $value->status == 1 ||
                                $value->status == 3
                            ) {
                                $data[$key]["status"] = "ontheway";
                            } elseif ($value->status == 2) {
                                $data[$key]["status"] = "delivery";
                            }
                        }
                    }
                }

                return response()->json(
                    [
                        "status" => "success",
                        "message" => "Data Success.",
                        "data" => $data,
                        "code" => "200",
                    ],
                    200
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    "status" => "failed",
                    "message" => $e->getMessage(),
                    "data" => json_decode("{}"),
                    "code" => "500",
                ],
                200
            );
        }
    }

	//64
	public function ALLShipmentList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::whereNull('deleted_at')->get();

			return response()->json(['status' => 'success', 'message' => 'Data Success.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	// 67
	public function CreditReport(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			$thismonth = date('m');

			if ($thismonth >= 4) {

				$mystartdate = '01-04-' . date('Y') . '00:00:00';

				$startDate = date('Y-m-d H:i:s', strtotime($mystartdate));

				$myenddate = '31-03-' . date('Y', strtotime(date("Y") . " + 365 day")) . ' 23:59:59';

				$endDate = date('Y-m-d H:i:s', strtotime($myenddate));

			} else if ($thismonth <= 3) {

				$mystartdate = '01-04-' . date('Y', strtotime(date("Y") . " - 365 day")) . '00:00:00';

				$startDate = date('Y-m-d H:i:s', strtotime($mystartdate));

				$myenddate = '31-03-' . date('Y') . ' 23:59:59';

				$endDate = date('Y-m-d H:i:s', strtotime($myenddate));

			}

			$total_credit1 = Account::where('to_company', $Request->other_id)->whereBetween('dates', [$startDate, $endDate])->sum('credit');

			$total_debit1 = Account::where('from_company', $Request->other_id)->whereBetween('dates', [$startDate, $endDate])->sum('debit');

			$data['credit'] = (int)($total_credit1);

			$data['debit'] = (int)($total_debit1);

			$data['diff'] = (int)($total_credit1 - $total_debit1);

			return response()->json(['status' => 'success', 'message' => 'Data Success.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	// 68
	public function BillStatus(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;
				$data = array();
				if ($Request->other_id != '' && $Request->other_id != ' ' && $Request->other_id != null) {

					$data = Invoice::where('forwarder_id', $Request->other_id)->where('paid', 0)->paginate($perPage);

				} else {
					$data = Invoice::where('paid', 0)->paginate($perPage);
				}
				foreach ($data as $key => $value) {

					$invoice_detail = Invoice::findorfail($value->id);

					$data[$key]['no'] = $value->invoice_no;

					$data[$key]['date'] = date('d-m-Y', strtotime($value->invoice_date));

					$data[$key]['amount'] = $value->grand_total;

					$date1 = date_create($value->invoice_date);
					date_format($date1, "Y-m-d");
					$date2 = date_create(date('Y-m-d'));
					date_format($date2, "Y-m-d");
					$diff = date_diff($date1, $date2);

					$data[$key]['diff'] = "" . $diff->format("%a days");

				}
				if (!empty($data)) {
					$message = 'Data Success.';
					$dataa = $data;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{
			$data = array();

			if ($Request->other_id != '' && $Request->other_id != ' ' && $Request->other_id != null) {

				$invoice_lists = Invoice::where('forwarder_id', $Request->other_id)->where('paid', 0)->get();

				foreach ($invoice_lists as $key => $value) {

					$invoice_detail = Invoice::findorfail($value->id);

					$data[$key]['no'] = $value->invoice_no;

					$data[$key]['date'] = date('d-m-Y', strtotime($value->invoice_date));

					$data[$key]['amount'] = $value->grand_total;

					$date1 = date_create($value->invoice_date);
					date_format($date1, "Y-m-d");
					$date2 = date_create(date('Y-m-d'));
					date_format($date2, "Y-m-d");
					$diff = date_diff($date1, $date2);

					$data[$key]['diff'] = "" . $diff->format("%a days");

				}

			} else {

				$invoice_lists = Invoice::where('paid', 0)->get();

				foreach ($invoice_lists as $key => $value) {

					$invoice_detail = Invoice::findorfail($value->id);

					$data[$key]['no'] = $value->invoice_no;

					$data[$key]['date'] = date('d-m-Y', strtotime($value->invoice_date));

					$data[$key]['amount'] = $value->grand_total;

					$date1 = date_create($value->invoice_date);
					date_format($date1, "Y-m-d");
					$date2 = date_create(date('Y-m-d'));
					date_format($date2, "Y-m-d");
					$diff = date_diff($date1, $date2);

					$data[$key]['diff'] = "" . $diff->format("%a days");

				}

			}

			return response()->json(['status' => 'success', 'message' => 'Data Success.', 'data' => $data, 'code' => '200'], 200);
		}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	// 69
	public function ForwarderAC(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			if ($Request->month != "" && $Request->year != "") {

				$data1 = Invoice::where('forwarder_id', $Request->other_id)->whereYear('created_at', $Request->year)->whereMonth('created_at', $Request->month)->get();

				foreach ($data1 as $key => $value) {

					$data[$key]['id'] = $value->id;
					$data[$key]['no'] = $value->invoice_no;
					$data[$key]['date'] = date('d-m-Y', strtotime($value->invoice_date));
					$data[$key]['shipments'] = $value->ships;
					$data[$key]['amount'] = $value->grand_total;

				}

			}

			return response()->json(['status' => 'success', 'message' => 'Data Success.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);

		}

	}

	// 70
	public function ForwardersList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;

				$data = Forwarder::select('*');
				$data = $data->paginate($perPage);


				if (!empty($data)) {
					$message = 'Forwarder List Success.';
					$dataa = $data;
					return $this->APIResponse->successWithPagination($message, $dataa);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{
			$data = array();

			$data = Forwarder::get();

			return response()->json(['status' => 'success', 'message' => 'Forwarder List  Success.', 'data' => $data, 'code' => '200'], 200);
			}
		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);

		}

	}

	// 71
	public function ForwarderBillList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			$invoice_lists = Invoice::where('forwarder_id', $Request->other_id)->where('paid', 0)->get();

			foreach ($invoice_lists as $key => $value) {

				$invoice_detail = Invoice::findorfail($value->id);

				$data[$key]['no'] = $value->invoice_no;

				$data[$key]['date'] = date('d-m-Y', strtotime($value->invoice_date));

				$data[$key]['amount'] = $value->grand_total;

				$date1 = date_create($value->invoice_date);
				date_format($date1, "Y-m-d");
				$date2 = date_create(date('Y-m-d'));
				date_format($date2, "Y-m-d");
				$diff = date_diff($date1, $date2);

				$data[$key]['diff'] = "" . $diff->format("%a days");

			}

			return response()->json(['status' => 'success', 'message' => 'Forwarder List  Success.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);

		}

	}



	// 72
	public function InvoiceMail(Request $Request) {



		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}


			$data = Invoice::findorfail($Request->id)->first();

			$forw_data = Forwarder::withTrashed()->findorfail($data->forwarder_id);

			$comp_data = Company::withTrashed()->findorfail($data->company_id);

			$data->forwarder_name = $forw_data->name;
			$data->forwarder_address = $forw_data->address;
			$data->forwarder_phone = $forw_data->phone;
			$data->forwarder_email = $forw_data->email;
			$data->forwarder_gst = $forw_data->gst_no;

			$all_shipment = explode(',', $data->ships);
			$data->shipment_list = explode(',', $data->ships);
			$trucklist = array();

			foreach ($all_shipment as $key => $value) {

				$driver_list = Shipment_Driver::where('shipment_no', $value)->get();

				$d_list = "";

				foreach ($driver_list as $key2 => $value2) {
					if ($key2 == 0) {
						$d_list = $d_list . "" . $value2->truck_no;

					} else {
						$d_list = $d_list . ", " . $value2->truck_no;

					}

				}

				$mytrucks[$key] = $d_list;

				$shipdata = Shipment::where('shipment_no', $value)->first();

				$mydates[$key] = date('d-m-Y', strtotime($shipdata->date));

			}

			$data->trucklist = $mytrucks;

			$data->alldates = $mydates;

			$f_shipdata = Shipment::where('shipment_no', $all_shipment[0])->first();

			$data->from = $f_shipdata->from1;

			$data->lcl = $f_shipdata->lcl;

			$data->fcl = $f_shipdata->fcl;

			if ($f_shipdata->to2 != '') {

				$data->to = $f_shipdata->to1 . "," . $f_shipdata->to2;

			} else {

				$data->to = $f_shipdata->to1;
			}


			if ($comp_data->lr == 'yoginilr') {

				$pdf = PDF::loadView('bill.yoginibill', compact('data', 'comp_data'))->setPaper('a4');

				$invoice_file_name = str_replace("/","-",$data->invoice_no);

				file_put_contents("public/invoice/" . $invoice_file_name . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/invoice/" . $invoice_file_name. ".pdf";

				$data222 = array('invoice_no'=>$data->invoice_no,'email'=>$Request->email,'invoice_no'=>$data->invoice_no,'invoice_file_name'=>$invoice_file_name);

						$ssi_username = env('SSI_MAIL_USERNAME');
				     	$ssi_password = env('SSI_MAIL_PASSWORD');

				      	/*Config::set('mail.username', $ssi_username);
				      	Config::set('mail.password', $ssi_password);*/

				  $mail_service = env('MAIL_SERVICE');
						if($mail_service == 'on'){

				Mail::send('yoginimail', $data222, function($message) use ($data222) {
         			$message->to($data222['email'])->subject('REGARDING INVOICE DETAILS - '.$data222['invoice_no']);
                    $message->from('noreplay@yoginitransport.com','Yogini Transport');
         			$message->attach( public_path('/invoice').'/'.$data222['invoice_file_name'].'.pdf');
      			});

			}

			}

			if ($comp_data->lr == 'ssilr') {

				$pdf = PDF::loadView('bill.ssibill', compact('data', 'comp_data'))->setPaper('a4');

				$invoice_file_name = str_replace("/","-",$data->invoice_no);

				file_put_contents("public/invoice/" . $invoice_file_name . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/invoice/" . $invoice_file_name. ".pdf";

				$data222 = array('invoice_no'=>$data->invoice_no,'email'=>$Request->email,'invoice_no'=>$data->invoice_no,'invoice_file_name'=>$invoice_file_name);

				$ssi_username = env('SSI_MAIL_USERNAME');
				$ssi_password = env('SSI_MAIL_PASSWORD');
				/*Config::set('mail.username', $ssi_username);
				Config::set('mail.password', $ssi_password);*/

				$mail_service = env('MAIL_SERVICE');
						if($mail_service == 'on'){

				Mail::send('ssimail', $data222, function($message) use ($data222) {
         			$message->to($data222['email'])->subject('REGARDING INVOICE DETAILS - '.$data222['invoice_no']);
                    $message->from('noreplay@ssitransway.com','SSI Transway');
                    $message->attach( public_path('/invoice').'/'.$data222['invoice_file_name'].'.pdf');
      			});

				}
			}

			if ($comp_data->lr == 'hanshlr') {

				$pdf = PDF::loadView('bill.hanshlr', compact('data', 'comp_data'))->setPaper('a4');

				$invoice_file_name = str_replace("/","-",$data->invoice_no);

				file_put_contents("public/invoice/" . $invoice_file_name . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/invoice/" . $invoice_file_name. ".pdf";

				$data222 = array('invoice_no'=>$data->invoice_no,'email'=>$Request->email,'invoice_no'=>$data->invoice_no,'invoice_file_name'=>$invoice_file_name);

				$hansh_username = env('HANS_MAIL_USERNAME');
				$hansh_password = env('HANS_MAIL_PASSWORD');
				/*Config::set('mail.username', $hansh_username);
				Config::set('mail.password', $hansh_password);*/

				$mail_service = env('MAIL_SERVICE');
						if($mail_service == 'on'){

				Mail::send('hanshmail', $data222, function($message) use ($data222) {
         			$message->to($data222['email'])->subject('REGARDING INVOICE DETAILS - '.$data222['invoice_no']);
                    $message->from('noreplay@hanstransport.com','Hansh Transport');
                    $message->attach( public_path('/invoice').'/'.$data222['invoice_file_name'].'.pdf');
      			});

			}

			}

			if ($comp_data->lr == 'bmflr') {

				$pdf = PDF::loadView('bill.bmflr', compact('data', 'comp_data'))->setPaper('a4');

				$invoice_file_name = str_replace("/","-",$data->invoice_no);

				file_put_contents("public/invoice/" . $invoice_file_name . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/invoice/" . $invoice_file_name. ".pdf";

				$data222 = array('invoice_no'=>$data->invoice_no,'email'=>$Request->email,'invoice_no'=>$data->invoice_no,'invoice_file_name'=>$invoice_file_name);

				$mail_service = env('MAIL_SERVICE');
						if($mail_service == 'on'){

				Mail::send('bmfmail', $data222, function($message) use ($data222) {
         			$message->to($data222['email'])->subject('REGARDING INVOICE DETAILS - '.$data222['invoice_no']);
                    $message->from('noreplay@bmfreight.com','BMF Freight');
                    $message->attach( public_path('/invoice').'/'.$data222['invoice_file_name'].'.pdf');
      			});
			}

			}

			$data123 = array();

			return response()->json(['status' => 'success', 'message' => 'Invoice Send Success.', 'data' => $data123, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);

		}

	}

	public function Token_Update(Request $Request) {

		try {

			if ($Request->role == "admin" || $Request->role == 'company' || $Request->role == "transporter" || $Request->role == "employee" || $Request->role == "forwarder" || $Request->role == "transporter") {

				$data = User::findorfail($Request->user_id);

				$data->device_token = $Request->device_token;

				$data->device_type = $Request->device_type;

				$data->save();

				return response()->json(['status' => 'success', 'message' => 'Token Successfully Updated.', 'data' => $data, 'code' => '200'], 200);

			} else {

				$data2 = Driver::findorfail($Request->user_id);

				$data2->device_token = $Request->device_token;

				$data2->device_type = $Request->device_type;

				$data2->save();

				return response()->json(['status' => 'success', 'message' => 'Token Successfully Updated.', 'data' => $data2, 'code' => '200'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);

		}

	}

	//76 LR Mail
	public function LRMail(Request $Request)
	{

		try
		{	$data=$Request->all();
            $rules = array(
                'email' => 'required|email'
            );

            $messages = [

            ];
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                return $this->APIResponse->respondOk(__($validator->errors()->first()));
            }
            else
			{

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}



			$data = Shipment::where('shipment_no', $Request->shipment_no)->first();

			$comp = Company::withTrashed()->findorfail($data->company);

			$data->company_name = $comp->name;

			$data->gst = $comp->gst_no;

			$data->qr_code = '';
			$account_qr_id = ['1','3','4','5'];
			if(in_array($comp->id, $account_qr_id)){
				$data->qr_code = $comp->id.'_id.jpeg';
			}

			if ($data->forwarder != "" && $data->forwarder != null && $data->forwarder != 'null') {

				$for = Forwarder::findorfail($data->forwarder);

				$data->forwarder_name = $for->name;

			} else {
				$data->forwarder_name = "";

			}
			if ($data->transporter != "" && $data->transporter != null && $data->transporter != 'null') {
				$tra = Transporter::findorfail($data->transporter);
				$data->transporter_name = $tra->name;
			} else {
				$data->transporter_name = "";

			}

			if ($data->trucktype != "" && $data->trucktype != null && $data->trucktype != 'null') {
				$truck = Truck::findorfail($data->trucktype);
				$data->trucktype_name = $truck->name;
			} else {
				$data->trucktype_name = "";

			}
			$tras_list = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->get();
			$t_list = "";
			foreach ($tras_list as $key => $value) {
				$tt = Transporter::findorfail($value->transporter_id);
				if ($key == 0) {
					$t_list = $t_list . "" . $tt->name;
				} else {

					$t_list = $t_list . ", " . $tt->name;
				}
			}

			$data->transporters_list = $t_list;

				$driver_list = Shipment_Driver::where('shipment_no', $Request->shipment_no)->get();
				$d_list = "";

				foreach ($driver_list as $key2 => $value2) {
					if ($key2 == 0) {
						$d_list = $d_list . "" . $value2->truck_no;

					} else {
						$d_list = $d_list . ", " . $value2->truck_no;

					}

				}

				$data->truck_no = $d_list;

			$trucks = Shipment_Driver::where('shipment_no', $data->shipment_no)->get();



			if ($comp->lr == "yoginilr") {
				// dd(1);

				$pdf = PDF::loadView('lr.yoginilr', compact('data', 'trucks'));

				file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

				$shipment = $Request->shipment_no;
				$myemail = $Request->email;

				$data2 = array('shipment_no'=>$shipment,'email'=>$myemail);


				$yogini_username = env('YOGINI_MAIL_USERNAME');
				$yogini_password = env('YOGINI_MAIL_PASSWORD');
				Config::set('mail.username', $yogini_username);
				Config::set('mail.password', $yogini_password);


     			$mail_service = env('MAIL_SERVICE');
					if($mail_service == 'on'){
				 Mail::send('yoginimail', $data2, function($message) use ($data2) {
         			$message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
         			$message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
      			});
				// $emailSend='';
				// $emailSend->email=$Request->email;
				// $emailSend->shipment=$Request->shipment_no;
				// dispatch(new CertificateApproveJob($emailSend));
				}

				return response()->json(['status' => 'success', 'message' => 'LR Send on Mail successfull.', 'data' => $path, 'code' => '200'], 200);

				//return $pdf->download($data->lr_no.'.pdf');

			} elseif ($comp->lr == "ssilr") {

				$pdf = PDF::loadView('lr.ssilr', compact('data', 'trucks'));

				 file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";


				$shipment = $Request->shipment_no;
				$myemail = $Request->email;

				$data2 = array('shipment_no'=>$shipment,'email'=>$myemail);


				$ssi_username = env('SSI_MAIL_USERNAME');
				$ssi_password = env('SSI_MAIL_PASSWORD');
				Config::set('mail.username', $ssi_username);
				Config::set('mail.password', $ssi_password);


				 $mail_service = env('MAIL_SERVICE');
				if($mail_service == 'on'){

				 Mail::send('ssimail', $data2, function($message) use ($data2) {
         			$message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
         			$message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
      			});

			//	dispatch(new CertificateApproveJob($data2));
				}


				return response()->json(['status' => 'success', 'message' => 'LR Send on Mail successfull.', 'data' => $path, 'code' => '200'], 200);

			} elseif ($comp->lr == "hanshlr") {

				$pdf = PDF::loadView('lr.hanshlr', compact('data', 'trucks'));

				 file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

				$shipment = $Request->shipment_no;
				$myemail = $Request->email;

				$data2 = array('shipment_no'=>$shipment,'email'=>$myemail);

				$hansh_username = env('HANS_MAIL_USERNAME');
				$hansh_password = env('HANS_MAIL_PASSWORD');
				Config::set('mail.username', $hansh_username);
				Config::set('mail.password', $hansh_password);
     			$mail_service = env('MAIL_SERVICE');

				if($mail_service == 'on'){

				 Mail::send('hanshmail', $data2, function($message) use ($data2) {
         			$message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
         			$message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
      			});

				  //dispatch(new CertificateApproveJob($data2));
      			}

				return response()->json(['status' => 'success', 'message' => 'LR Send on Mail successfull.', 'data' => $path, 'code' => '200'], 200);

			} elseif ($comp->lr == "bmflr") {

				$pdf = PDF::loadView('lr.bmflr', compact('data', 'trucks'));

				 file_put_contents("pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "pdf/" . $Request->shipment_no . ".pdf";

				$shipment = $Request->shipment_no;
				$myemail = $Request->email;

				$data2 = array('shipment_no'=>$shipment,'email'=>$myemail);

     			$mail_service = env('MAIL_SERVICE');
				if($mail_service == 'on'){

				 Mail::send('bmfmail', $data2, function($message) use ($data2) {
         			$message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
         			$message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
      			});

				 // dispatch(new CertificateApproveJob($data2));
      			}

				return response()->json(['status' => 'success', 'message' => 'LR Send on Mail successfull.', 'data' => $path, 'code' => '200'], 200);

			}
		}
		} catch (\Exception $e) {
			dd($e);
			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//77 GET Transporter Driver
	public function GetTransporterDriver(Request $Request)
	{
		try {

			$check = $this->checkversion($Request->version);

			$data = Driver::where('transporter_id',$Request->other_id)->orderby('name','asc')->get();

			return response()->json(['status' => 'success', 'message' => 'Driver List successfull.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//78 Leger Mail Forwarder
	public function LegerMail(Request $Request)
	{

	try {

		$ff = Forwarder::find($Request->id);

		$myid= $Request->id;

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

		 	  $total_credit1 = Account::where('to_forwarder',$myid)->whereBetween('dates', [$from, $to])->sum('credit');
		 	  $total_credit2 = Account::where('to_forwarder',$myid)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_credit = $total_credit1 + $total_credit2;
		      $total_debit1 = Account::where('from_forwarder',$myid)->whereBetween('dates', [$from, $to])->sum('debit');
		 	  $total_debit2 = Account::where('from_forwarder',$myid)->whereBetween('dates', [$from, $to])->sum('credit');
			  $total_debit = $total_debit1 + $total_debit2;


		$nyllist = array();

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

			//return view('admin.accountdata',compact('total_credit','total_debit','nyllist','cc','dd'));

			$date_from = date('d-m-Y',strtotime($Request->from));
			$date_to = date('d-m-Y',strtotime($Request->to));

				$pdf = PDF::loadView('forwarder.ledgermail', compact('total_credit','total_debit','nyllist','cc','dd','date_from','date_to','ff'))->setPaper('a4');

				$invoice_file_name = str_replace("/","-",$ff->name);

				file_put_contents("public/ledger/" . $invoice_file_name . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/ledger/" . $invoice_file_name. ".pdf";

				$data222 = array('name'=>$ff->name,'email'=>$Request->email,'invoice_file_name'=>$invoice_file_name);

				$mail_service = env('MAIL_SERVICE');
				if($mail_service == 'on'){

				Mail::send('ledger', $data222, function($message) use ($data222) {
         			$message->to($data222['email'])->subject($data222['name'].' Ledger Account');
         			$message->attach( public_path('/ledger').'/'.$data222['invoice_file_name'].'.pdf');
      			});
				}

      			return response()->json(['status' => 'success', 'message' => 'Mail Send successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

      		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}



	//79 POD Mail Forwarder
	public function PodMail(Request $Request)
	{

	try {
			$data= Shipment_Driver::where('shipment_no',$Request->shipment_no)->get();

			$ship_data = Shipment::where('shipment_no', $Request->shipment_no)->first();

            $comp = Company::withTrashed()->findorfail($ship_data->company);

			$shipment_no = $Request->shipment_no;

			if(count($data) > 0 ) {

				$pdf = PDF::loadView('forwarder.podmail', compact('data','shipment_no'))->setPaper('a4');

				$invoice_file_name = $Request->shipment_no." POD";

				file_put_contents("public/pod/" . $invoice_file_name . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/pod/" . $invoice_file_name. ".pdf";

				$data222 = array('name'=>$shipment_no,'email'=>$Request->email,'invoice_file_name'=>$invoice_file_name);


				if ($comp->lr == "yoginilr") {

					$yogini_username = env('YOGINI_MAIL_USERNAME');
                    $yogini_password = env('YOGINI_MAIL_PASSWORD');
                    Config::set('mail.username', $yogini_username);
                    Config::set('mail.password', $yogini_password);

                    $mail_service = env('MAIL_SERVICE');
				if($mail_service == 'on'){

					Mail::send('yoginimail', $data222, function($message) use ($data222) {
         			$message->to($data222['email'])->subject('REGARDING POD DETAILS -'.$data222['name']);
         			$message->attach( public_path('/pod').'/'.$data222['invoice_file_name'].'.pdf');
      				});
				}


				}elseif ($comp->lr == "ssilr") {


						$ssi_username = env('SSI_MAIL_USERNAME');
                        $ssi_password = env('SSI_MAIL_PASSWORD');
                        Config::set('mail.username', $ssi_username);
                        Config::set('mail.password', $ssi_password);

                        $mail_service = env('MAIL_SERVICE');
				if($mail_service == 'on'){
                        Mail::send('ssimail', $data222, function($message) use ($data222) {
         			$message->to($data222['email'])->subject('REGARDING POD DETAILS -'.$data222['name']);
         			$message->attach( public_path('/pod').'/'.$data222['invoice_file_name'].'.pdf');
      				});
                    }


					} elseif ($comp->lr == "hanshlr") {

						$hansh_username = env('HANS_MAIL_USERNAME');
                        $hansh_password = env('HANS_MAIL_PASSWORD');
                        Config::set('mail.username', $hansh_username);
                        Config::set('mail.password', $hansh_password);

                        $mail_service = env('MAIL_SERVICE');
				if($mail_service == 'on'){

                        Mail::send('hanshmail', $data222, function($message) use ($data222) {
         			$message->to($data222['email'])->subject('REGARDING POD DETAILS -'.$data222['name']);
         			$message->attach( public_path('/pod').'/'.$data222['invoice_file_name'].'.pdf');
      				});
                    }


					} else {

						$mail_service = env('MAIL_SERVICE');
				if($mail_service == 'on'){

					Mail::send('bmfmail', $data222, function($message) use ($data222) {
         			$message->to($data222['email'])->subject('REGARDING POD DETAILS -'.$data222['name']);
         			$message->attach( public_path('/pod').'/'.$data222['invoice_file_name'].'.pdf');
      			});
				}

					}




			return response()->json(['status' => 'success', 'message' => 'Mail Send Successfully.', 'data' =>json_decode('{}'), 'code' => '200'], 200);

			} else {

			return response()->json(['status' => 'success', 'message' => 'No Detail For POD.', 'data' => json_decode('{}'), 'code' => '200'], 200);
			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	public function testNotification(Request $request)
    {
		// dd($request->all());
        try
        {
            $title = 'You have received a new message.';
            $message = 'Hello';
            $resultlist='dd';
            if ($request['device_type'] == 'ios')
            {
                GlobalHelper::sendFCMIOS($title, $message, $request['device_token'],1,$resultlist,0);
            }
            else
            {
				// dd($request->all());
                GlobalHelper::sendFCM($title, $message, $request['device_token'],1,$resultlist,0);
            }
            return $this
                ->APIResponse
                ->respondWithMessageAndPayload('Message Send successfully');
        }
        catch(\Exception $e)
        {
            return $this
                ->APIResponse
                ->handleAndResponseException($e);
        }
    }
	public function AccountData(Request $Request)
	{

		try {

			$check = $this->checkversion($Request->version);
			$all = $Request->all();
			//dd($all);
			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
			if (isset($all['page']) && ($all['offset'])) {
				//pagination coding
				$page = 1;
				$perPage = 10;
				if (isset($all['page']) && !empty($all['page'])) {
					$page = $all['page'];
				}
				if (isset($all['offset']) && !empty($all['offset'])) {
					$perPage = $all['offset'];
				}
				$offset = ($page - 1) * $perPage;
				if($Request->from_date != ''){
					$from_date = date('Y-m-d',strtotime($Request->from_date));
				} else {
					$from_date = date('Y-m-d');
				}
				if($Request->to_date != ''){
					$to_date = date('Y-m-d',strtotime($Request->to_date));
				} else {
					$to_date = date('Y-m-d');
				}
				if($Request->role == 'admin')
				{

				if ($Request->type == 'transporter')
					{
						$total_credit1 = Account::where('to_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
						$total_credit2 = Account::where('to_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
						$total_credit = (int)($total_credit1 + $total_credit2);

						$total_debit1 = Account::where('from_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
						$total_debit2 = Account::where('from_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
						$total_debit = (int)($total_debit1 + $total_debit2);

						$nyllist = array();

						$expense = array();
						$nyllist = Account::whereBetween('dates', [$from_date, $to_date])
							->where(function($query) use($Request)
								{
									$query->where('to_transporter', $Request->other_id)
										  ->orWhere('from_transporter', $Request->other_id);
								})
							->orderby('id','desc')->paginate($perPage);


					$cc = Account::whereBetween('dates', [$from_date, $to_date])
							->where(function($query) use($Request)
								{
									$query->where('to_transporter', $Request->other_id)
										  ->orWhere('from_transporter', $Request->other_id);
								})
							->sum('credit');

					$dd = Account::whereBetween('dates', [$from_date, $to_date])
							->where(function($query) use($Request)
								{
									$query->where('to_transporter', $Request->other_id)
										  ->orWhere('from_transporter', $Request->other_id);
								})
							->sum('debit');

					foreach ($nyllist as $key => $value)
					 {
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

									$nyllist[$key]['amount'] = $value->credit;
									// $nyllist[$key]['debitst'] = '';
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
									// $nyllist[$key]['total_credit'] = $cc;
									// $nyllist[$key]['total_debit'] = $dd;
									 $nyllist[$key]['amount'] = $value->credit;
									// $nyllist[$key]['debitst'] = '';
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
									// $nyllist[$key]['total_credit'] = $cc;
									// $nyllist[$key]['total_debit'] = $dd;
									 $nyllist[$key]['amount'] = $value->credit;
									// $nyllist[$key]['debitst'] = '';
								}
								$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));

								$nyllist[$key]['amount'] = $value->credit;
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
									// $nyllist[$key]['total_credit'] = $cc;
									// $nyllist[$key]['total_debit'] = $dd;
									// $nyllist[$key]['creditt'] = '';
									 $nyllist[$key]['amount'] = $value->debit;
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
									// $nyllist[$key]['total_credit'] = $cc;
									// $nyllist[$key]['total_debit'] = $dd;
									// $nyllist[$key]['creditt'] = '';
									$nyllist[$key]['amount'] = $value->debit;
								}
								$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));

								$nyllist[$key]['amount'] = $value->debit;
							}

					}
				}

				if($Request->type == 'company')
				{

					$total_credit1 = Account::where('to_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');

					 $total_credit2 = Account::where('to_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
					 $total_credit = (int)($total_credit1 + $total_credit2);

					$total_debit1 = Account::where('from_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
					 $total_debit2 = Account::where('from_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
					$total_debit = (int)($total_debit1 + $total_debit2);

					$nyllist = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_company', $Request->other_id)
								  ->orWhere('from_company', $Request->other_id);
						})
					->orderby('id','desc')->paginate($perPage);


			$cc = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_company', $Request->other_id)
								  ->orWhere('from_company', $Request->other_id);
						})
					->sum('credit');
			$dd = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_company', $Request->other_id)
								  ->orWhere('from_company', $Request->other_id);
						})
					->sum('debit');


					$expense = array();
			foreach ($nyllist as $key => $value)
			{
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
						$nyllist[$key]['amount'] = $value->credit;
						// $nyllist[$key]['debitst'] = '';
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 		$nyllist[$key]['total_debit'] = $dd;
						 $nyllist[$key]['amount'] = $value->credit;
						// $nyllist[$key]['debitst'] = '';
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 		$nyllist[$key]['total_debit'] = $dd;
						 $nyllist[$key]['amount'] = $value->credit;
						// $nyllist[$key]['debitst'] = '';
					}
					$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
					$nyllist[$key]['amount'] = $value->credit;
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
						$nyllist[$key]['amount'] = $value->debit;
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 		$nyllist[$key]['total_debit'] = $dd;
						// $nyllist[$key]['creditt'] = '';
						 $nyllist[$key]['amount'] = $value->debit;
					}
				$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));
				$nyllist[$key]['amount'] = $value->debit;
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
					// $nyllist[$key]['total_credit'] = $cc;
					// $nyllist[$key]['total_debit'] = $dd;
					// $nyllist[$key]['creditt'] = '';
					 $nyllist[$key]['amount'] = $value->debit;
				}
			}

			}

			  if($Request->type == 'forwarder')
			  {
				$total_credit1 = Account::where('to_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
				$total_credit2 = Account::where('to_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
				$total_credit = (int)($total_credit1 + $total_credit2);

				   $total_debit1 = Account::where('from_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
				$total_debit2 = Account::where('from_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
			   $total_debit = (int)($total_debit1 + $total_debit2);
			   $nyllist = array();

				$expense = array();
				$nyllist = Account::whereBetween('dates', [$from_date, $to_date])
						->where(function($query) use($Request)
							{
								$query->where('to_forwarder', $Request->other_id)
									  ->orWhere('from_forwarder', $Request->other_id);
							})
						->orderby('id','desc')->paginate($perPage);


				$cc = Account::whereBetween('dates', [$from_date, $to_date])
						->where(function($query) use($Request)
							{
								$query->where('to_forwarder', $Request->other_id)
									  ->orWhere('from_forwarder', $Request->other_id);
							})
						->sum('credit');
				$dd = Account::whereBetween('dates', [$from_date, $to_date])
						->where(function($query) use($Request)
							{
								$query->where('to_forwarder', $Request->other_id)
									  ->orWhere('from_forwarder', $Request->other_id);
							})
						->sum('debit');

				foreach ($nyllist as $key => $value) {
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

							 $nyllist[$key]['amount'] = $value->credit;
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
							// $nyllist[$key]['total_credit'] = $cc;
							// 	$nyllist[$key]['total_debit'] = $dd;
							// $nyllist[$key]['creditt'] = '';
							 $nyllist[$key]['amount'] = $value->credit;
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
							// $nyllist[$key]['total_credit'] = $cc;
							// 	$nyllist[$key]['total_debit'] = $dd;
							// $nyllist[$key]['creditt'] = '';
							 $nyllist[$key]['amount'] = $value->credit;
						}
						$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));

						$nyllist[$key]['amount'] = $value->credit;
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
							// $nyllist[$key]['total_credit'] = $cc;
							// 	$nyllist[$key]['total_debit'] = $dd;
							 $nyllist[$key]['amount'] = $value->debit;
							// $nyllist[$key]['debitst'] = '';
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
							// $nyllist[$key]['total_credit'] = $cc;
							// 	$nyllist[$key]['total_debit'] = $dd;
							 $nyllist[$key]['amount'] = $value->debit;
							// $nyllist[$key]['debitst'] = '';
						}
					}
				}

				}
			}
				if ($Request->role == 'transporter')
				{
					$total_credit1 = Account::where('to_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
					$total_credit2 = Account::where('to_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
					$total_credit = (int)($total_credit1 + $total_credit2);

					$total_debit1 = Account::where('from_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
					$total_debit2 = Account::where('from_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
					$total_debit = (int)($total_debit1 + $total_debit2);

					$nyllist = array();

					$expense = array();
					$nyllist = Account::whereBetween('dates', [$from_date, $to_date])
						->where(function($query) use($Request)
							{
								$query->where('to_transporter', $Request->other_id)
									  ->orWhere('from_transporter', $Request->other_id);
							})
						->orderby('id','desc')->paginate($perPage);


				$cc = Account::whereBetween('dates', [$from_date, $to_date])
						->where(function($query) use($Request)
							{
								$query->where('to_transporter', $Request->other_id)
									  ->orWhere('from_transporter', $Request->other_id);
							})
						->sum('credit');

				$dd = Account::whereBetween('dates', [$from_date, $to_date])
						->where(function($query) use($Request)
							{
								$query->where('to_transporter', $Request->other_id)
									  ->orWhere('from_transporter', $Request->other_id);
							})
						->sum('debit');

				foreach ($nyllist as $key => $value) {
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
								// $nyllist[$key]['total_credit'] = $cc;
								// $nyllist[$key]['total_debit'] = $dd;
								$nyllist[$key]['amount'] = $value->credit;
								// $nyllist[$key]['debitst'] = '';
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
								// $nyllist[$key]['total_credit'] = $cc;
								// $nyllist[$key]['total_debit'] = $dd;
								 $nyllist[$key]['amount'] = $value->credit;
								// $nyllist[$key]['debitst'] = '';
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
								// $nyllist[$key]['total_credit'] = $cc;
								// $nyllist[$key]['total_debit'] = $dd;
								 $nyllist[$key]['amount'] = $value->credit;
								// $nyllist[$key]['debitst'] = '';
							}
							$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));

							$nyllist[$key]['amount'] = $value->credit;
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
								// $nyllist[$key]['total_credit'] = $cc;
								// $nyllist[$key]['total_debit'] = $dd;
								// $nyllist[$key]['creditt'] = '';
								 $nyllist[$key]['amount'] = $value->debit;
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
								// $nyllist[$key]['total_credit'] = $cc;
								// $nyllist[$key]['total_debit'] = $dd;
								// $nyllist[$key]['creditt'] = '';
								$nyllist[$key]['amount'] = $value->debit;
							}
						}
				}

		}
			if($Request->role == 'company')
			{

				$total_credit1 = Account::where('to_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');

			 	$total_credit2 = Account::where('to_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
			 	$total_credit = (int)($total_credit1 + $total_credit2);

				$total_debit1 = Account::where('from_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
			 	$total_debit2 = Account::where('from_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
				$total_debit = (int)($total_debit1 + $total_debit2);

				$nyllist = Account::whereBetween('dates', [$from_date, $to_date])
				->where(function($query) use($Request)
					{
						$query->where('to_company', $Request->other_id)
							  ->orWhere('from_company', $Request->other_id);
					})
				->orderby('id','desc')->paginate($perPage);


		$cc = Account::whereBetween('dates', [$from_date, $to_date])
				->where(function($query) use($Request)
					{
						$query->where('to_company', $Request->other_id)
							  ->orWhere('from_company', $Request->other_id);
					})
				->sum('credit');
		$dd = Account::whereBetween('dates', [$from_date, $to_date])
				->where(function($query) use($Request)
					{
						$query->where('to_company', $Request->other_id)
							  ->orWhere('from_company', $Request->other_id);
					})
				->sum('debit');


				$expense = array();
		foreach ($nyllist as $key => $value)
		{
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
					// $nyllist[$key]['total_credit'] = $cc;
					// 		$nyllist[$key]['total_debit'] = $dd;
					$nyllist[$key]['amount'] = $value->credit;
					// $nyllist[$key]['debitst'] = '';
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
					// $nyllist[$key]['total_credit'] = $cc;
					// 		$nyllist[$key]['total_debit'] = $dd;
					 $nyllist[$key]['amount'] = $value->credit;
					// $nyllist[$key]['debitst'] = '';
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
					// $nyllist[$key]['total_credit'] = $cc;
					// 		$nyllist[$key]['total_debit'] = $dd;
					 $nyllist[$key]['amount'] = $value->credit;
					// $nyllist[$key]['debitst'] = '';
				}
				$nyllist[$key]['datess'] = date('d-m-Y',strtotime($value->dates));

				$nyllist[$key]['amount'] = $value->credit;
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
					// $nyllist[$key]['total_credit'] = $cc;
					// 		$nyllist[$key]['total_debit'] = $dd;
					// $nyllist[$key]['creditt'] = '';
					$nyllist[$key]['amount'] = $value->debit;
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
					// $nyllist[$key]['total_credit'] = $cc;
					// 		$nyllist[$key]['total_debit'] = $dd;
					// $nyllist[$key]['creditt'] = '';
					 $nyllist[$key]['amount'] = $value->debit;
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
				// $nyllist[$key]['total_credit'] = $cc;
				// $nyllist[$key]['total_debit'] = $dd;
				// $nyllist[$key]['creditt'] = '';
				 $nyllist[$key]['amount'] = $value->debit;
			}
		}

	  	}
		  if($Request->role == 'forwarder')
		  {
			$total_credit1 = Account::where('to_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
			$total_credit2 = Account::where('to_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
			$total_credit = (int)($total_credit1 + $total_credit2);

		   	$total_debit1 = Account::where('from_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
			$total_debit2 = Account::where('from_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
		   $total_debit = (int)($total_debit1 + $total_debit2);
		   $nyllist = array();

			$expense = array();
			$nyllist = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->other_id)
								  ->orWhere('from_forwarder', $Request->other_id);
						})
					->orderby('id','desc')->paginate($perPage);


			$cc = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->other_id)
								  ->orWhere('from_forwarder', $Request->other_id);
						})
					->sum('credit');
			$dd = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->other_id)
								  ->orWhere('from_forwarder', $Request->other_id);
						})
					->sum('debit');

			foreach ($nyllist as $key => $value) {
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 	$nyllist[$key]['total_debit'] = $dd;
						// $nyllist[$key]['creditt'] = '';
						 $nyllist[$key]['amount'] = $value->credit;
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 	$nyllist[$key]['total_debit'] = $dd;
						// $nyllist[$key]['creditt'] = '';
						 $nyllist[$key]['amount'] = $value->credit;
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 	$nyllist[$key]['total_debit'] = $dd;
						// $nyllist[$key]['creditt'] = '';
						 $nyllist[$key]['amount'] = $value->credit;
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 	$nyllist[$key]['total_debit'] = $dd;
						 $nyllist[$key]['amount'] = $value->debit;
						// $nyllist[$key]['debitst'] = '';
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 	$nyllist[$key]['total_debit'] = $dd;
						 $nyllist[$key]['amount'] = $value->debit;
						// $nyllist[$key]['debitst'] = '';
					}
				}
			}

	 	}

				if (!empty($nyllist)) {
					$message = 'Account Data List Successfully.';
					$dataa = $nyllist;
					$total_credit = (int)($cc);
					$total_debit = (int)($dd);
					return $this->APIResponse->successWithPaginationaccountlist($message, $dataa,$total_credit,$total_debit);
				}

			  else {
					return $this->APIResponse->respondNotFound(__('No Record Found'));
				}

			}
			else{

				if($Request->from_date != ''){
					$from_date = date('Y-m-d',strtotime($Request->from_date));
				} else {
					$from_date = date('Y-m-d');
				}
				if($Request->to_date != ''){
					$to_date = date('Y-m-d',strtotime($Request->to_date));
				} else {
					$to_date = date('Y-m-d');
				}
				if ($Request->role == 'admin')
				{
					if ($Request->type == 'transporter') {

						$total_credit1 = Account::where('to_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
						$total_credit2 = Account::where('to_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
						$total_credit = (int)($total_credit1 + $total_credit2);

						$total_debit1 = Account::where('from_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
						$total_debit2 = Account::where('from_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
						$total_debit = (int)($total_debit1 + $total_debit2);



						$nyllist = array();

						$expense = array();
						$data12 = Account::whereBetween('dates', [$from_date, $to_date])
							->where(function($query) use($Request)
								{
									$query->where('to_transporter', $Request->other_id)
										  ->orWhere('from_transporter', $Request->other_id);
								})
							->orderby('id','desc')->get();


					$cc = Account::whereBetween('dates', [$from_date, $to_date])
							->where(function($query) use($Request)
								{
									$query->where('to_transporter', $Request->other_id)
										  ->orWhere('from_transporter', $Request->other_id);
								})
							->sum('credit');

					$dd = Account::whereBetween('dates', [$from_date, $to_date])
							->where(function($query) use($Request)
								{
									$query->where('to_transporter', $Request->other_id)
										  ->orWhere('from_transporter', $Request->other_id);
								})
							->sum('debit');

					foreach ($data12 as $key => $value)
					{
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

									 $nyllist[$key]['amount'] = $value->credit;
									// $nyllist[$key]['debitst'] = '';
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

									 $nyllist[$key]['amount'] = $value->credit;
									// $nyllist[$key]['debitst'] = '';
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

									$nyllist[$key]['amount'] = $value->credit;
									// $nyllist[$key]['debitst'] = '';
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

									// $nyllist[$key]['creditt'] = '';
									$nyllist[$key]['amount'] = $value->debit;
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

									// $nyllist[$key]['creditt'] = '';
									 $nyllist[$key]['amount'] = $value->debit;
								}
							}
					}

				}
				if($Request->type == 'company'){

						$total_credit1 = Account::where('to_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');

						 $total_credit2 = Account::where('to_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
						 $total_credit = (int)($total_credit1 + $total_credit2);

						$total_debit1 = Account::where('from_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
						 $total_debit2 = Account::where('from_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
						$total_debit = (int)($total_debit1 + $total_debit2);

						$data12 = Account::whereBetween('dates', [$from_date, $to_date])
						->where(function($query) use($Request)
							{
								$query->where('to_company', $Request->other_id)
									  ->orWhere('from_company', $Request->other_id);
							})
						->orderby('id','desc')->get();


				$cc = Account::whereBetween('dates', [$from_date, $to_date])
						->where(function($query) use($Request)
							{
								$query->where('to_company', $Request->other_id)
									  ->orWhere('from_company', $Request->other_id);
							})
						->sum('credit');
				$dd = Account::whereBetween('dates', [$from_date, $to_date])
						->where(function($query) use($Request)
							{
								$query->where('to_company', $Request->other_id)
									  ->orWhere('from_company', $Request->other_id);
							})
						->sum('debit');

						$nyllist = array();
						$expense = array();
				foreach ($data12 as $key => $value)
				{
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
							// $nyllist[$key]['total_credit'] = $cc;
							// 		$nyllist[$key]['total_debit'] = $dd;
							$nyllist[$key]['amount'] = $value->credit;
							// $nyllist[$key]['creditt'] = $value->credit;
							// $nyllist[$key]['debitst'] = '';
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
							// $nyllist[$key]['total_credit'] = $cc;
							// 		$nyllist[$key]['total_debit'] = $dd;
							 $nyllist[$key]['amount'] = $value->credit;
							// $nyllist[$key]['debitst'] = '';
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
							// $nyllist[$key]['total_credit'] = $cc;
							// 		$nyllist[$key]['total_debit'] = $dd;
							$nyllist[$key]['amount'] = $value->credit;
							// $nyllist[$key]['debitst'] = '';
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
							// $nyllist[$key]['total_credit'] = $cc;
							// 		$nyllist[$key]['total_debit'] = $dd;
							// $nyllist[$key]['creditt'] = '';
							 $nyllist[$key]['amount'] = $value->debit;
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
							// $nyllist[$key]['total_credit'] = $cc;
							// 		$nyllist[$key]['total_debit'] = $dd;
							// $nyllist[$key]['creditt'] = '';
							 $nyllist[$key]['amount'] = $value->debit;
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
						// $nyllist[$key]['total_credit'] = $cc;
						// $nyllist[$key]['total_debit'] = $dd;
						// $nyllist[$key]['creditt'] = '';
						 $nyllist[$key]['amount'] = $value->debit;
					}
				}

				  }
				  if($Request->type == 'forwarder')
				   {
					$total_credit1 = Account::where('to_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
					$total_credit2 = Account::where('to_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
					$total_credit = (int)($total_credit1 + $total_credit2);

					   $total_debit1 = Account::where('from_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
					$total_debit2 = Account::where('from_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
				   $total_debit = (int)($total_debit1 + $total_debit2);
				   $nyllist = array();

					$expense = array();
					$data12 = Account::whereBetween('dates', [$from_date, $to_date])
							->where(function($query) use($Request)
								{
									$query->where('to_forwarder', $Request->other_id)
										  ->orWhere('from_forwarder', $Request->other_id);
								})
							->orderby('id','desc')->get();


					$cc = Account::whereBetween('dates', [$from_date, $to_date])
							->where(function($query) use($Request)
								{
									$query->where('to_forwarder', $Request->other_id)
										  ->orWhere('from_forwarder', $Request->other_id);
								})
							->sum('credit');
					$dd = Account::whereBetween('dates', [$from_date, $to_date])
							->where(function($query) use($Request)
								{
									$query->where('to_forwarder', $Request->other_id)
										  ->orWhere('from_forwarder', $Request->other_id);
								})
							->sum('debit');

					foreach ($data12 as $key => $value)
					{
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
								// $nyllist[$key]['total_credit'] = $cc;
								// 	$nyllist[$key]['total_debit'] = $dd;
								// $nyllist[$key]['creditt'] = '';
								 $nyllist[$key]['amount'] = $value->credit;
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
								// $nyllist[$key]['total_credit'] = $cc;
								// 	$nyllist[$key]['total_debit'] = $dd;
								// $nyllist[$key]['creditt'] = '';
								 $nyllist[$key]['amount'] = $value->credit;
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
								// $nyllist[$key]['total_credit'] = $cc;
								// 	$nyllist[$key]['total_debit'] = $dd;
								// $nyllist[$key]['creditt'] = '';
								 $nyllist[$key]['amount'] = $value->credit;
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
								// $nyllist[$key]['total_credit'] = $cc;
								// 	$nyllist[$key]['total_debit'] = $dd;
								 $nyllist[$key]['amount'] = $value->debit;
								// $nyllist[$key]['debitst'] = '';
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
								// $nyllist[$key]['total_credit'] = $cc;
								// 	$nyllist[$key]['total_debit'] = $dd;
								 $nyllist[$key]['amount'] = $value->debit;
								// $nyllist[$key]['debitst'] = '';
							}
						}
					}

				}
			}
			if ($Request->role == 'transporter') {

				$total_credit1 = Account::where('to_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
				$total_credit2 = Account::where('to_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
				$total_credit = (int)($total_credit1 + $total_credit2);

				$total_debit1 = Account::where('from_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
				$total_debit2 = Account::where('from_transporter',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
				$total_debit = (int)($total_debit1 + $total_debit2);



				$nyllist = array();

				$expense = array();
				$data12 = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_transporter', $Request->other_id)
								  ->orWhere('from_transporter', $Request->other_id);
						})
					->orderby('id','desc')->get();


			$cc = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_transporter', $Request->other_id)
								  ->orWhere('from_transporter', $Request->other_id);
						})
					->sum('credit');

			$dd = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_transporter', $Request->other_id)
								  ->orWhere('from_transporter', $Request->other_id);
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

							 $nyllist[$key]['amount'] = $value->credit;
							// $nyllist[$key]['debitst'] = '';
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

							 $nyllist[$key]['amount'] = $value->credit;
							// $nyllist[$key]['debitst'] = '';
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

							$nyllist[$key]['amount'] = $value->credit;
							// $nyllist[$key]['debitst'] = '';
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

							// $nyllist[$key]['creditt'] = '';
							$nyllist[$key]['amount'] = $value->debit;
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

							// $nyllist[$key]['creditt'] = '';
							 $nyllist[$key]['amount'] = $value->debit;
						}
					}
			}

		}
		if($Request->role == 'company'){

				$total_credit1 = Account::where('to_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');

			 	$total_credit2 = Account::where('to_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
			 	$total_credit = (int)($total_credit1 + $total_credit2);

				$total_debit1 = Account::where('from_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
			 	$total_debit2 = Account::where('from_company',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
				$total_debit = (int)($total_debit1 + $total_debit2);

				$data12 = Account::whereBetween('dates', [$from_date, $to_date])
				->where(function($query) use($Request)
					{
						$query->where('to_company', $Request->other_id)
							  ->orWhere('from_company', $Request->other_id);
					})
				->orderby('id','desc')->get();


		$cc = Account::whereBetween('dates', [$from_date, $to_date])
				->where(function($query) use($Request)
					{
						$query->where('to_company', $Request->other_id)
							  ->orWhere('from_company', $Request->other_id);
					})
				->sum('credit');
		$dd = Account::whereBetween('dates', [$from_date, $to_date])
				->where(function($query) use($Request)
					{
						$query->where('to_company', $Request->other_id)
							  ->orWhere('from_company', $Request->other_id);
					})
				->sum('debit');

				$nyllist = array();
				$expense = array();
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
					// $nyllist[$key]['total_credit'] = $cc;
					// 		$nyllist[$key]['total_debit'] = $dd;
					$nyllist[$key]['amount'] = $value->credit;
					// $nyllist[$key]['creditt'] = $value->credit;
					// $nyllist[$key]['debitst'] = '';
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
					// $nyllist[$key]['total_credit'] = $cc;
					// 		$nyllist[$key]['total_debit'] = $dd;
					 $nyllist[$key]['amount'] = $value->credit;
					// $nyllist[$key]['debitst'] = '';
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
					// $nyllist[$key]['total_credit'] = $cc;
					// 		$nyllist[$key]['total_debit'] = $dd;
					$nyllist[$key]['amount'] = $value->credit;
					// $nyllist[$key]['debitst'] = '';
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
					// $nyllist[$key]['total_credit'] = $cc;
					// 		$nyllist[$key]['total_debit'] = $dd;
					// $nyllist[$key]['creditt'] = '';
					 $nyllist[$key]['amount'] = $value->debit;
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
					// $nyllist[$key]['total_credit'] = $cc;
					// 		$nyllist[$key]['total_debit'] = $dd;
					// $nyllist[$key]['creditt'] = '';
					 $nyllist[$key]['amount'] = $value->debit;
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
				// $nyllist[$key]['total_credit'] = $cc;
				// $nyllist[$key]['total_debit'] = $dd;
				// $nyllist[$key]['creditt'] = '';
				 $nyllist[$key]['amount'] = $value->debit;
			}
		}

	  	}
		  if($Request->role == 'forwarder') {
			$total_credit1 = Account::where('to_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
			$total_credit2 = Account::where('to_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
			$total_credit = (int)($total_credit1 + $total_credit2);

		   	$total_debit1 = Account::where('from_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('debit');
			$total_debit2 = Account::where('from_forwarder',$Request->other_id)->whereBetween('dates', [$from_date, $to_date])->sum('credit');
		   $total_debit = (int)($total_debit1 + $total_debit2);
		   $nyllist = array();

			$expense = array();
			$data12 = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->other_id)
								  ->orWhere('from_forwarder', $Request->other_id);
						})
					->orderby('id','desc')->get();


			$cc = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->other_id)
								  ->orWhere('from_forwarder', $Request->other_id);
						})
					->sum('credit');
			$dd = Account::whereBetween('dates', [$from_date, $to_date])
					->where(function($query) use($Request)
						{
							$query->where('to_forwarder', $Request->other_id)
								  ->orWhere('from_forwarder', $Request->other_id);
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 	$nyllist[$key]['total_debit'] = $dd;
						// $nyllist[$key]['creditt'] = '';
						 $nyllist[$key]['amount'] = $value->credit;
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 	$nyllist[$key]['total_debit'] = $dd;
						// $nyllist[$key]['creditt'] = '';
						 $nyllist[$key]['amount'] = $value->credit;
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 	$nyllist[$key]['total_debit'] = $dd;
						// $nyllist[$key]['creditt'] = '';
						 $nyllist[$key]['amount'] = $value->credit;
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 	$nyllist[$key]['total_debit'] = $dd;
						 $nyllist[$key]['amount'] = $value->debit;
						// $nyllist[$key]['debitst'] = '';
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
						// $nyllist[$key]['total_credit'] = $cc;
						// 	$nyllist[$key]['total_debit'] = $dd;
						 $nyllist[$key]['amount'] = $value->debit;
						// $nyllist[$key]['debitst'] = '';
					}
				}
			}

	 	}

}
	return response()->json(['status' => 'success', 'message' => 'Account Data List Successfully.', 'data' => $nyllist,'total_credit'=> (int)($cc),'total_debit'=> (int)($dd), 'code' => '200'], 200);
	}
	catch (\Exception $e) {
		//dd($e);
			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	// public static function changeStatus_Load_To_DocumentReceived()
	// {
	// 	$current_time = strtotime(date('Y-m-d H:i:s'));
	// 	$shipments = Shipment_Driver::leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
	// 	->select('shipment_driver.*','shipment.id as shipment_id','shipment.company')
	// 	->whereIn('shipment_driver.status',['2','3','4','5','6','7','8'])
	// 	->where('shipment.imports','1')
	// 	->where('shipment.lcl','1')
	// 	->whereNotNull('shipment_driver.last_status_update_time')
	// 	->whereNull('shipment.deleted_at')
	// 	->get();
	// 	foreach ($shipments as $key => $shipment)
	// 	{
	// 		// dd($shipment);
	// 		// $shipment_data=Shipment::withTrashed()->where('shipment_no',$shipment['shipment_no'])->first();
	// 		$getStatus=Cargostatus::where('id',$shipment->status)->first();
	// 		$i = 0;
	// 		$last_status_update_time = strtotime($shipment->last_status_update_time);
	// 		$diffrence = $current_time - $last_status_update_time;
	// 		if(!$shipment->last_notification_time) {
	// 			if($diffrence >= 3600) {
	// 				$i = 1;
	// 				$shipment->last_notification_time = date('Y-m-d H:i:s');
	// 				$shipment->last_notification_time_difference = 60;
	// 				$shipment->save();
	// 			}
	// 		} elseif($diffrence >= 5400 && ($shipment->last_notification_time < 90)) {
	// 			$i = 1;
	// 			$shipment->last_notification_time = date('Y-m-d H:i:s');
	// 			$shipment->last_notification_time_difference = 90;
	// 			$shipment->save();
	// 		} elseif($diffrence >= 6000) {
	// 			$last_notification_time = strtotime($shipment->last_notification_time);
	// 			$notification_diffrence = $current_time - $last_notification_time;
	// 			if($notification_diffrence >= 600) {
	// 				$i = 1;
	// 				$shipment->last_notification_time = date('Y-m-d H:i:s');
	// 				$shipment->last_notification_time_difference = (int) $diffrence / 60;
	// 				$shipment->save();
	// 			}
	// 		}
	// 		if($i == 1) {
	// 			$from_user = User::find($shipment->updated_by);
	// 			//transportor
	// 			$transporter=Transporter::where('id',$shipment->transporter_id)->first();
	// 			$to_user = User::find($transporter['user_id']);
	// 			if($from_user){
	// 			$updatedByName='';
	// 			if($from_user)
	// 			{
	// 				$updatedByName=$from_user['username'];
	// 			}

	// 			if(($from_user['id'] != $to_user['id']) && $from_user && $to_user) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user->device_token != null){
	// 				if($to_user->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 			//driver
	// 			if($shipment){
	// 			if($shipment['driver_id']){
	// 			$to_user3=Driver::where('id',$shipment['driver_id'])->first();
	// 			if($to_user3){
	// 			if(($from_user['id'] != $to_user3['id']) && $from_user && $to_user3) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user3->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user3->device_token != null){
	// 				if($to_user3->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}
	// 		}
	// 		}
	// 		}

	// 			//admin
	// 			$to_user1 = User::find(1);
	// 			if($from_user['id'] != $to_user1['id'] && $from_user && $to_user1) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user1->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user1->device_token != null){
	// 				if($to_user1->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 			//company

	// 			$company_user = Company::where('id',$shipment['company'])->first();
    //             $to_user2=User::find($company_user['user_id']);
	// 			if($from_user['id'] != $to_user2['id'] && $from_user && $to_user2) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user2->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user2->device_token != null){
	// 				if($to_user2->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 		}
	// 		}
	// 	}
	// 	return 1;
	// }

	// public static function changeStatus_ReachAtPort_To_Unload()
	// {
	// 	$current_time = strtotime(date('Y-m-d H:i:s'));
	// 	// $shipments = Shipment_Driver::leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
	// 	// ->select('shipment_driver.*','shipment.id as shipment_id','shipment.company')
	// 	// ->whereIn('shipment_driver.status',['12','13','14','15','17'])
	// 	// ->where('shipment.imports','1')
	// 	// ->where('shipment.lcl','1')

	// 	// // where('shipment_no','Y11')
	// 	// ->whereNotNull('shipment_driver.last_status_update_time')
	// 	// ->whereNull('shipment.deleted_at')
	// 	// ->get();
	// 	$shipments = Shipment_Driver::withTrashed()
	// 	->leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
	// 	->select('shipment_driver.*','shipment.id as shipment_id','shipment.company')
	// 	->whereIn('shipment_driver.status',['12','13','14','15','17'])
	// 	->where('shipment.imports','1')
	// 	->where('shipment.lcl','1')
	// 	->whereNotNull('shipment_driver.last_status_update_time')
	// 	->whereNull('shipment.deleted_at')
	// 	->whereRaw('shipment_driver.id IN (select MAX(shipment_driver.id) FROM shipment_driver GROUP BY shipment_driver.shipment_no)')
	// 	// ->pluck('shipment_no')
	// 	->get();
	// 	foreach ($shipments as $key => $shipment)
	// 	{
	// 		// $shipment_data=Shipment::withTrashed()->where('shipment_no',$shipment['shipment_no'])->first();
	// 		$getStatus=Cargostatus::where('id',$shipment->status)->first();
	// 		$i = 0;
	// 		$last_status_update_time = strtotime($shipment->last_status_update_time);
	// 		$diffrence = $current_time - $last_status_update_time;
	// 		if(!$shipment->last_notification_time) {
	// 			if($diffrence >= 1800) {
	// 				$i = 1;
	// 				$shipment->last_notification_time = date('Y-m-d H:i:s');
	// 				$shipment->last_notification_time_difference = 30;
	// 				$shipment->save();
	// 			}
	// 		}elseif($diffrence >= 2700) {
	// 			$last_notification_time = strtotime($shipment->last_notification_time);
	// 			$notification_diffrence = $current_time - $last_notification_time;
	// 			if($notification_diffrence >= 900) {
	// 				$i = 1;
	// 				$shipment->last_notification_time = date('Y-m-d H:i:s');
	// 				$shipment->last_notification_time_difference = (int) ($notification_diffrence / 60);
	// 				$shipment->save();
	// 			}
	// 		}
	// 		if($i == 1) {
	// 			$from_user = User::find($shipment->updated_by);
	// 			//transportor
	// 			$transporter=Transporter::where('id',$shipment->transporter_id)->first();
	// 			$to_user = User::find($transporter['user_id']);
	// 			if($from_user){
	// 			$updatedByName='';
	// 			if($from_user)
	// 			{
	// 				$updatedByName=$from_user['username'];
	// 			}
	// 			if(($from_user['id'] != $to_user['id']) && $from_user && $to_user) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user->device_token != null){
	// 				if($to_user->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 			//driver
	// 			if($shipment){
	// 			if($shipment['driver_id']){
	// 			$to_user3=Driver::where('id',$shipment['driver_id'])->first();
	// 			if($to_user3){
	// 			if(($from_user['id'] != $to_user3['id']) && $from_user && $to_user3) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user3->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user3->device_token != null){
	// 				if($to_user3->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}
	// 		}
	// 		}
	// 		}

	// 			//admin
	// 			$to_user1 = User::find(1);
	// 			if($from_user['id'] != $to_user1['id'] && $from_user && $to_user1) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user1->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user1->device_token != null){
	// 				if($to_user1->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 			//company

	// 			$company_user = Company::where('id',$shipment['company'])->first();
    //             $to_user2=User::find($company_user['user_id']);
	// 			if($from_user['id'] != $to_user2['id'] && $from_user && $to_user2) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user2->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user2->device_token != null){
	// 				if($to_user2->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 		}
	// 		}
	// 	}
	// 	return 1;
	// }

	// public static function changeStatus_ReachAtPort_To_DocumentReceived()
	// {
	// 	$current_time = strtotime(date('Y-m-d H:i:s'));
	// 	$shipments = Shipment_Driver::leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
	// 	->select('shipment_driver.*','shipment.id as shipment_id','shipment.company')
	// 	->whereIn('shipment_driver.status',['7','8'])
	// 	->where('shipment.exports','1')
	// 	->where('shipment.lcl','1')
	// 	->whereNotNull('shipment_driver.last_status_update_time')
	// 	->whereNull('shipment.deleted_at')
	// 	->get();
	// 	foreach ($shipments as $key => $shipment)
	// 	{
	// 		// $shipment_data=Shipment::withTrashed()->where('shipment_no',$shipment['shipment_no'])->first();
	// 		$getStatus=Cargostatus::where('id',$shipment->status)->first();
	// 		$i = 0;
	// 		$last_status_update_time = strtotime($shipment->last_status_update_time);
	// 		$diffrence = $current_time - $last_status_update_time;
	// 		if(!$shipment->last_notification_time) {
	// 			if($diffrence >= 1800) {
	// 				$i = 1;
	// 				$shipment->last_notification_time = date('Y-m-d H:i:s');
	// 				$shipment->last_notification_time_difference = 30;
	// 				$shipment->save();
	// 			}
	// 		}elseif($diffrence >= 2700) {
	// 			$last_notification_time = strtotime($shipment->last_notification_time);
	// 			$notification_diffrence = $current_time - $last_notification_time;
	// 			if($notification_diffrence >= 900) {
	// 				$i = 1;
	// 				$shipment->last_notification_time = date('Y-m-d H:i:s');
	// 				$shipment->last_notification_time_difference = (int) $diffrence / 60;
	// 				$shipment->save();
	// 			}
	// 		}
	// 		if($i == 1) {
	// 			$from_user = User::find($shipment->updated_by);
	// 			//transprtor
	// 			$transporter=Transporter::where('id',$shipment->transporter_id)->first();
	// 			$to_user = User::find($transporter['user_id']);
	// 			if($from_user){
	// 			$updatedByName='';
	// 			if($from_user)
	// 			{
	// 				$updatedByName=$from_user['username'];
	// 			}
	// 			if(($from_user['id'] != $to_user['id']) && $from_user && $to_user) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user->device_token != null){
	// 				if($to_user->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 			//driver
	// 			if($shipment){
	// 			if($shipment['driver_id']){
	// 			$to_user3=Driver::where('id',$shipment['driver_id'])->first();
	// 			if($to_user3){
	// 			if(($from_user['id'] != $to_user3['id']) && $from_user && $to_user3) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user3->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user3->device_token != null){
	// 				if($to_user3->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}
	// 		}
	// 		}
	// 		}

	// 			//admin
	// 			$to_user1 = User::find(1);
	// 			if($from_user['id'] != $to_user1['id'] && $from_user && $to_user1) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user1->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user1->device_token != null){
	// 				if($to_user1->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 			//company

	// 			$company_user = Company::where('id',$shipment['company'])->first();
    //             $to_user2=User::find($company_user['user_id']);
	// 			if($from_user['id'] != $to_user2['id'] && $from_user && $to_user2) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user2->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user2->device_token != null){
	// 				if($to_user2->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 		}
	// 		}
	// 	}
	// 	return 1;
	// }

	// public static function changeStatus_ReachAtCompany_To_Unload()
	// {
	// 	$current_time = strtotime(date('Y-m-d H:i:s'));
	// 	$shipments = Shipment_Driver::withTrashed()
	// 	->leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
	// 	->select('shipment_driver.*','shipment.id as shipment_id','shipment.company')
	// 	->where('shipment.exports','1')
	// 	->where('shipment.lcl','1')
	// 	->whereIn('shipment_driver.status',['7','8','9','10','11','12','13','14','15','16','17'])
	// 	->whereNotNull('shipment_driver.last_status_update_time')
	// 	->whereNull('shipment.deleted_at')
	// 	->get();
	// 	foreach ($shipments as $key => $shipment)
	// 	{
	// 		// $shipment_data=Shipment::where('shipment_no',$shipment['shipment_no'])->first();
	// 		$getStatus=Cargostatus::where('id',$shipment->status)->first();
	// 		$i = 0;
	// 		$last_status_update_time = strtotime($shipment->last_status_update_time);
	// 		$diffrence = $current_time - $last_status_update_time;
	// 		if(!$shipment->last_notification_time) {
	// 			if($diffrence >= 3600) {
	// 				$i = 1;
	// 				$shipment->last_notification_time = date('Y-m-d H:i:s');
	// 				$shipment->last_notification_time_difference = 60;
	// 				$shipment->save();
	// 			}
	// 		} elseif($diffrence >= 5400 && ($shipment->last_notification_time < 90)) {
	// 			$i = 1;
	// 			$shipment->last_notification_time = date('Y-m-d H:i:s');
	// 			$shipment->last_notification_time_difference = 90;
	// 			$shipment->save();
	// 		} elseif($diffrence >= 6000) {
	// 			$last_notification_time = strtotime($shipment->last_notification_time);
	// 			$notification_diffrence = $current_time - $last_notification_time;
	// 			if($notification_diffrence >= 600) {
	// 				$i = 1;
	// 				$shipment->last_notification_time = date('Y-m-d H:i:s');
	// 				$shipment->last_notification_time_difference = (int) $diffrence / 60;
	// 				$shipment->save();
	// 			}
	// 		}
	// 		if($i == 1) {
	// 			$from_user = User::find($shipment->updated_by);
	// 			//transportor
	// 			$transporter=Transporter::where('id',$shipment->transporter_id)->first();
	// 			$to_user = User::find($transporter['user_id']);
	// 			if($from_user){
	// 			$updatedByName='';
	// 			if($from_user)
	// 			{
	// 				$updatedByName=$from_user['username'];
	// 			}
	// 			if(($from_user['id'] != $to_user['id']) && $from_user && $to_user) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user->device_token != null){
	// 				if($to_user->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 			//driver
	// 			if($shipment){
	// 			if($shipment['driver_id']){
	// 			$to_user3=Driver::where('id',$shipment['driver_id'])->first();
	// 			if($to_user3){
	// 			if(($from_user['id'] != $to_user3['id']) && $from_user && $to_user3) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user3->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user3->device_token != null){
	// 				if($to_user3->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}
	// 		}
	// 		}
	// 		}
	// 			//admin
	// 			$to_user1 = User::find(1);
	// 			if($from_user['id'] != $to_user1['id'] && $from_user && $to_user1) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user1->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user1->device_token != null){
	// 				if($to_user1->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}
	// 			//company

	// 			$company_user = Company::where('id',$shipment['company'])->first();
    //             $to_user2=User::find($company_user['user_id']);
	// 			if($from_user['id'] != $to_user2['id'] && $from_user && $to_user2) {
	// 				$notification = new Notification();
	// 				$notification->notification_from = $from_user->id;
	// 				$notification->notification_to = $to_user2->id;
	// 				$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
	// 				$id = $shipment["shipment_no"];
	// 				$title= "Delayed";
	// 				$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
	// 				$notification->title = $title;
	// 				$notification->message = $message;
	// 				$notification->notification_type = '2';
	// 				$notification->user_name_from = $updatedByName;
	// 				$notification->save();
	// 				$notification_id = $notification->id;
	// 				if($to_user2->device_token != null){
	// 				if($to_user2->device_type == 'ios'){
	// 					GlobalHelper::sendFCMIOS($title, $message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
	// 				}else{
	// 					GlobalHelper::sendFCM($notification->title, $notification->message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
	// 					}
	// 				}
	// 			}

	// 		}
	// 		}
	// 	}
	// 	return 1;
	// }

	//Reach at port se document received
	public static function changeStatus_ReachAtPort_To_Documentreceived_lcl_imports()
	{
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$shipments = Shipment_Driver::leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
		->select('shipment_driver.*','shipment.id as shipment_id','shipment.company')
		->whereIn('shipment_driver.status',['2','12'])
		->where('shipment.imports','1')
		->where('shipment.lcl','1')
		->whereRaw('shipment_driver.id IN (select MAX(shipment_driver.id) FROM shipment_driver GROUP BY shipment_driver.shipment_no)')
		->whereNotNull('shipment_driver.last_status_update_time')
		->whereNull('shipment.deleted_at')
		->get();
		// $data2 = Shipment_Driver::withTrashed()->where('transporter_id', $Request->transporter)->whereNull('deleted_at')->whereRaw('id IN (select MAX(id) FROM shipment_driver GROUP BY shipment_no)')->pluck('shipment_no')->toArray();
		foreach ($shipments as $key => $shipment)
		{
			// $shipment_data=Shipment::withTrashed()->where('shipment_no',$shipment['shipment_no'])->first();
			$getStatus=Cargostatus::where('id',$shipment->status)->first();
			$i = 0;
			$last_status_update_time = strtotime($shipment->last_status_update_time);
			$diffrence = $current_time - $last_status_update_time;
			if(!$shipment->last_notification_time) {
				if($diffrence >= 1800) {
					$i = 1;
					$shipment->last_notification_time = date('Y-m-d H:i:s');
					$shipment->last_notification_time_difference = 30;
					$shipment->save();
				}
			}elseif($diffrence >= 2700) {
				$last_notification_time = strtotime($shipment->last_notification_time);
				$notification_diffrence = $current_time - $last_notification_time;
				if($notification_diffrence >= 900) {
					$i = 1;
					$shipment->last_notification_time = date('Y-m-d H:i:s');
					$shipment->last_notification_time_difference = (int) ($notification_diffrence / 60);
					$shipment->save();
				}
			}
			if($i == 1) {
				$from_user = User::find($shipment->updated_by);
				//transportor
				$transporter=Transporter::where('id',$shipment->transporter_id)->first();
				$to_user = User::find($transporter['user_id']);
				if($from_user){
				$updatedByName='';
				if($from_user)
				{
					$updatedByName=$from_user['username'];
				}
				if(($from_user['id'] != $to_user['id']) && $from_user && $to_user) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user->device_token != null){
					if($to_user->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

				//driver
				if($shipment){
				if($shipment['driver_id']){
				$to_user3=Driver::where('id',$shipment['driver_id'])->first();
				if($to_user3){
				if(($from_user['id'] != $to_user3['id']) && $from_user && $to_user3) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user3->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user3->device_token != null){
					if($to_user3->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}
			}
			}
			}

				//admin
				$to_user1 = User::find(1);
				if($from_user['id'] != $to_user1['id'] && $from_user && $to_user1) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user1->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user1->device_token != null){
					if($to_user1->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

				//company

				$company_user = Company::where('id',$shipment['company'])->first();
                $to_user2=User::find($company_user['user_id']);
				if($from_user['id'] != $to_user2['id'] && $from_user && $to_user2) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user2->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user2->device_token != null){
					if($to_user2->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

			}
			}
		}
		return 1;
	}

	//Load and document received ke bichme
	public static function changeStatus_Load_To_DocumentReceived()
	{
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$shipments = Shipment_Driver::leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
		->select('shipment_driver.*','shipment.id as shipment_id','shipment.company')
		->whereIn('shipment_driver.status',['2'])
		->where('shipment.exports','1')
		->where('shipment.lcl','1')
		->whereRaw('shipment_driver.id IN (select MAX(shipment_driver.id) FROM shipment_driver GROUP BY shipment_driver.shipment_no)')
		->whereNotNull('shipment_driver.last_status_update_time')
		->whereNull('shipment.deleted_at')
		->get();
		foreach ($shipments as $key => $shipment)
		{
			$getStatus=Cargostatus::where('id',$shipment->status)->first();
			$i = 0;
			$last_status_update_time = strtotime($shipment->last_status_update_time);
			$diffrence = $current_time - $last_status_update_time;
			if(!$shipment->last_notification_time) {
				if($diffrence >= 3600) {
					$i = 1;
					$shipment->last_notification_time = date('Y-m-d H:i:s');
					$shipment->last_notification_time_difference = 60;
					$shipment->save();
				}
			} elseif($diffrence >= 5400 && ($shipment->last_notification_time < 90)) {
				$i = 1;
				$shipment->last_notification_time = date('Y-m-d H:i:s');
				$shipment->last_notification_time_difference = 90;
				$shipment->save();
			} elseif($diffrence >= 6000) {
				$last_notification_time = strtotime($shipment->last_notification_time);
				$notification_diffrence = $current_time - $last_notification_time;
				if($notification_diffrence >= 600) {
					$i = 1;
					$shipment->last_notification_time = date('Y-m-d H:i:s');
					$shipment->last_notification_time_difference = (int) $diffrence / 60;
					$shipment->save();
				}
			}
			if($i == 1) {
				$from_user = User::find($shipment->updated_by);
				//transportor
				$transporter=Transporter::where('id',$shipment->transporter_id)->first();
				$to_user = User::find($transporter['user_id']);
				if($from_user){
				$updatedByName='';
				if($from_user)
				{
					$updatedByName=$from_user['username'];
				}

				if(($from_user['id'] != $to_user['id']) && $from_user && $to_user) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user->device_token != null){
					if($to_user->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

				//driver
				if($shipment){
				if($shipment['driver_id']){
				$to_user3=Driver::where('id',$shipment['driver_id'])->first();
				if($to_user3){
				if(($from_user['id'] != $to_user3['id']) && $from_user && $to_user3) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user3->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user3->device_token != null){
					if($to_user3->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}
			}
			}
			}

				//admin
				$to_user1 = User::find(1);
				if($from_user['id'] != $to_user1['id'] && $from_user && $to_user1) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user1->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user1->device_token != null){
					if($to_user1->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

				//company

				$company_user = Company::where('id',$shipment['company'])->first();
                $to_user2=User::find($company_user['user_id']);
				if($from_user['id'] != $to_user2['id'] && $from_user && $to_user2) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user2->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user2->device_token != null){
					if($to_user2->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

			}
			}
		}
		return 1;
	}

	//React at port se document received(fcl imports)
	public static function changeStatus_ReachAtPort_To_Documentreceived_fcl_imports()
	{
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$shipments = Shipment_Driver::leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
		->select('shipment_driver.*','shipment.id as shipment_id','shipment.company')
		->whereIn('shipment_driver.status',['2','12'])
		->where('shipment.imports','1')
		->where('shipment.fcl','1')
		->whereRaw('shipment_driver.id IN (select MAX(shipment_driver.id) FROM shipment_driver GROUP BY shipment_driver.shipment_no)')
		->whereNotNull('shipment_driver.last_status_update_time')
		->whereNull('shipment.deleted_at')
		->get();
		// $data2 = Shipment_Driver::withTrashed()->where('transporter_id', $Request->transporter)->whereNull('deleted_at')->whereRaw('id IN (select MAX(id) FROM shipment_driver GROUP BY shipment_no)')->pluck('shipment_no')->toArray();
		foreach ($shipments as $key => $shipment)
		{
			// $shipment_data=Shipment::withTrashed()->where('shipment_no',$shipment['shipment_no'])->first();
			$getStatus=Cargostatus::where('id',$shipment->status)->first();
			$i = 0;
			$last_status_update_time = strtotime($shipment->last_status_update_time);
			$diffrence = $current_time - $last_status_update_time;
			if(!$shipment->last_notification_time) {
				if($diffrence >= 1800) {
					$i = 1;
					$shipment->last_notification_time = date('Y-m-d H:i:s');
					$shipment->last_notification_time_difference = 30;
					$shipment->save();
				}
			}elseif($diffrence >= 2700) {
				$last_notification_time = strtotime($shipment->last_notification_time);
				$notification_diffrence = $current_time - $last_notification_time;
				if($notification_diffrence >= 900) {
					$i = 1;
					$shipment->last_notification_time = date('Y-m-d H:i:s');
					$shipment->last_notification_time_difference = (int) ($notification_diffrence / 60);
					$shipment->save();
				}
			}
			if($i == 1) {
				$from_user = User::find($shipment->updated_by);
				//transportor
				$transporter=Transporter::where('id',$shipment->transporter_id)->first();
				$to_user = User::find($transporter['user_id']);
				if($from_user){
				$updatedByName='';
				if($from_user)
				{
					$updatedByName=$from_user['username'];
				}
				if(($from_user['id'] != $to_user['id']) && $from_user && $to_user) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user->device_token != null){
					if($to_user->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

				//driver
				if($shipment){
				if($shipment['driver_id']){
				$to_user3=Driver::where('id',$shipment['driver_id'])->first();
				if($to_user3){
				if(($from_user['id'] != $to_user3['id']) && $from_user && $to_user3) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user3->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user3->device_token != null){
					if($to_user3->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}
			}
			}
			}

				//admin
				$to_user1 = User::find(1);
				if($from_user['id'] != $to_user1['id'] && $from_user && $to_user1) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user1->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user1->device_token != null){
					if($to_user1->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

				//company

				$company_user = Company::where('id',$shipment['company'])->first();
                $to_user2=User::find($company_user['user_id']);
				if($from_user['id'] != $to_user2['id'] && $from_user && $to_user2) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user2->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user2->device_token != null){
					if($to_user2->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

			}
			}
		}
		return 1;
	}

	//Reach at company and Unload Cargo
	public static function changeStatus_ReachAtCompany_To_UnloadCargo()
	{
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$shipments = Shipment_Driver::leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
		->select('shipment_driver.*','shipment.id as shipment_id','shipment.company')
		->whereIn('shipment_driver.status',['7'])
		->where('shipment.imports','1')
		->where('shipment.fcl','1')
		->whereRaw('shipment_driver.id IN (select MAX(shipment_driver.id) FROM shipment_driver GROUP BY shipment_driver.shipment_no)')
		->whereNotNull('shipment_driver.last_status_update_time')
		->whereNull('shipment.deleted_at')
		->get();
		foreach ($shipments as $key => $shipment)
		{
			$getStatus=Cargostatus::where('id',$shipment->status)->first();
			$i = 0;
			$last_status_update_time = strtotime($shipment->last_status_update_time);
			$diffrence = $current_time - $last_status_update_time;
			if(!$shipment->last_notification_time) {
				if($diffrence >= 3600) {
					$i = 1;
					$shipment->last_notification_time = date('Y-m-d H:i:s');
					$shipment->last_notification_time_difference = 60;
					$shipment->save();
				}
			} elseif($diffrence >= 5400 && ($shipment->last_notification_time < 90)) {
				$i = 1;
				$shipment->last_notification_time = date('Y-m-d H:i:s');
				$shipment->last_notification_time_difference = 90;
				$shipment->save();
			} elseif($diffrence >= 6000) {
				$last_notification_time = strtotime($shipment->last_notification_time);
				$notification_diffrence = $current_time - $last_notification_time;
				if($notification_diffrence >= 600) {
					$i = 1;
					$shipment->last_notification_time = date('Y-m-d H:i:s');
					$shipment->last_notification_time_difference = (int) $diffrence / 60;
					$shipment->save();
				}
			}
			if($i == 1) {
				$from_user = User::find($shipment->updated_by);
				//transportor
				$transporter=Transporter::where('id',$shipment->transporter_id)->first();
				$to_user = User::find($transporter['user_id']);
				if($from_user){
				$updatedByName='';
				if($from_user)
				{
					$updatedByName=$from_user['username'];
				}

				if(($from_user['id'] != $to_user['id']) && $from_user && $to_user) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user->device_token != null){
					if($to_user->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

				//driver
				if($shipment){
				if($shipment['driver_id']){
				$to_user3=Driver::where('id',$shipment['driver_id'])->first();
				if($to_user3){
				if(($from_user['id'] != $to_user3['id']) && $from_user && $to_user3) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user3->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user3->device_token != null){
					if($to_user3->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}
			}
			}
			}

				//admin
				$to_user1 = User::find(1);
				if($from_user['id'] != $to_user1['id'] && $from_user && $to_user1) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user1->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user1->device_token != null){
					if($to_user1->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

				//company

				$company_user = Company::where('id',$shipment['company'])->first();
                $to_user2=User::find($company_user['user_id']);
				if($from_user['id'] != $to_user2['id'] && $from_user && $to_user2) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user2->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Unload Cargo".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user2->device_token != null){
					if($to_user2->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

			}
			}
		}
		return 1;
	}

	//Load Cargo and document received
	public static function changeStatus_LoadCargo_To_Documentreceived()
	{
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$shipments = Shipment_Driver::leftJoin('shipment','shipment.shipment_no','=','shipment_driver.shipment_no')
		->select('shipment_driver.*','shipment.id as shipment_id','shipment.company')
		->whereIn('shipment_driver.status',['15'])
		->where('shipment.exports','1')
		->where('shipment.fcl','1')
		->whereRaw('shipment_driver.id IN (select MAX(shipment_driver.id) FROM shipment_driver GROUP BY shipment_driver.shipment_no)')
		->whereNotNull('shipment_driver.last_status_update_time')
		->whereNull('shipment.deleted_at')
		->get();
		foreach ($shipments as $key => $shipment)
		{
			$getStatus=Cargostatus::where('id',$shipment->status)->first();
			$i = 0;
			$last_status_update_time = strtotime($shipment->last_status_update_time);
			$diffrence = $current_time - $last_status_update_time;
			if(!$shipment->last_notification_time) {
				if($diffrence >= 3600) {
					$i = 1;
					$shipment->last_notification_time = date('Y-m-d H:i:s');
					$shipment->last_notification_time_difference = 60;
					$shipment->save();
				}
			} elseif($diffrence >= 5400 && ($shipment->last_notification_time < 90)) {
				$i = 1;
				$shipment->last_notification_time = date('Y-m-d H:i:s');
				$shipment->last_notification_time_difference = 90;
				$shipment->save();
			} elseif($diffrence >= 6000) {
				$last_notification_time = strtotime($shipment->last_notification_time);
				$notification_diffrence = $current_time - $last_notification_time;
				if($notification_diffrence >= 600) {
					$i = 1;
					$shipment->last_notification_time = date('Y-m-d H:i:s');
					$shipment->last_notification_time_difference = (int) $diffrence / 60;
					$shipment->save();
				}
			}
			if($i == 1) {
				$from_user = User::find($shipment->updated_by);
				//transportor
				$transporter=Transporter::where('id',$shipment->transporter_id)->first();
				$to_user = User::find($transporter['user_id']);
				if($from_user){
				$updatedByName='';
				if($from_user)
				{
					$updatedByName=$from_user['username'];
				}

				if(($from_user['id'] != $to_user['id']) && $from_user && $to_user) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user->device_token != null){
					if($to_user->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

				//driver
				if($shipment){
				if($shipment['driver_id']){
				$to_user3=Driver::where('id',$shipment['driver_id'])->first();
				if($to_user3){
				if(($from_user['id'] != $to_user3['id']) && $from_user && $to_user3) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user3->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					// "New Shipment" .' '. $driver->shipment_no .' '. "Added";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user3->device_token != null){
					if($to_user3->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user3->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}
			}
			}
			}

				//admin
				$to_user1 = User::find(1);
				if($from_user['id'] != $to_user1['id'] && $from_user && $to_user1) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user1->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user1->device_token != null){
					if($to_user1->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user1->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

				//company

				$company_user = Company::where('id',$shipment['company'])->first();
                $to_user2=User::find($company_user['user_id']);
				if($from_user['id'] != $to_user2['id'] && $from_user && $to_user2) {
					$notification = new Notification();
					$notification->notification_from = $from_user->id;
					$notification->notification_to = $to_user2->id;
					$notification->shipment_id = isset($shipment['shipment_id']) ? $shipment['shipment_id'] : '';
					$id = $shipment["shipment_no"];
					$title= "Delayed";
					$message= $shipment["shipment_no"].' '."is delayed for".' '."Document Received".' ' ."by".' '.$updatedByName;
					$notification->title = $title;
					$notification->message = $message;
					$notification->notification_type = '2';
					$notification->user_name_from = $updatedByName;
					$notification->save();
					$notification_id = $notification->id;
					if($to_user2->device_token != null){
					if($to_user2->device_type == 'ios'){
						GlobalHelper::sendFCMIOS($title, $message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
					}else{
						GlobalHelper::sendFCM($notification->title, $notification->message, $to_user2->device_token,$notification->notification_type,$id,$notification_id);
						}
					}
				}

			}
			}
		}
		return 1;
	}
}
