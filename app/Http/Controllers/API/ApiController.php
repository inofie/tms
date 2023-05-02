<?php

namespace App\Http\Controllers\API;

use App\Account;
use App\Cargostatus;
use App\Company;
use App\Driver;
use App\Employee;
use App\Expense;
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


class ApiController extends Controller {

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '420'], 200);
			}

			$user = User::withTrashed()->findorfail($Request->user_id);

			if ($user->status == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Your Are Blocked By Admin. Please Contact To Administrator.', 'data' => json_decode('{}'), 'code' => '420'], 200);

			}

			if ($Request->role == 'admin') {

				$comp = Company::withTrashed()->where('user_id', $Request->user_id)->first();

				if ($comp->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your Are Blocked By Admin. Please Contact To Administrator.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

			} else if ($Request->role == 'transporter') {

				$trans = Transporter::withTrashed()->where('user_id', $Request->user_id)->first();

				if ($trans->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your Are Blocked By Admin. Please Contact To Administrator.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

			} else if ($Request->role == 'forwarder') {

				$forwa = Forwarder::withTrashed()->where('user_id', $Request->user_id)->first();

				if ($forwa->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your Are Blocked By Admin. Please Contact To Administrator.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

			} else if ($Request->role == 'employee') {

				$emp = Employee::withTrashed()->where('user_id', $Request->user_id)->first();

				if ($emp->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your Are Blocked By Admin. Please Contact To Administrator.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

				$comp = Company::withTrashed()->findorfail($emp->company_id);

				if ($comp->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'Your Are Blocked By Admin. Please Contact To Administrator.', 'data' => json_decode('{}'), 'code' => '420'], 200);

				}

			}

			return response()->json(['status' => 'success', 'message' => 'Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}






	//2
	public function Login(Request $Request) {

		try {

			//dd(env('MYAPP_VERSION'));

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$ucount = User::where('username', $Request->username)->count();

			$dcount = 0;

			if ($ucount == 0) {

				$dcount = Driver::where('phone', $Request->username)->count();

				if ($dcount == 0) {

					return response()->json(['status' => 'failed', 'message' => 'Username Not Registered.', 'data' => json_decode('{}'), 'code' => '500'], 200);
				}

			}

			if ($ucount > 0) {

				$data = User::where('username', $Request->username)->first();

				if ($data->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'This User Blocked By Admin ot Transporter, Please Contact to Administrator Or Transporter.', 'data' => json_decode('{}'), 'code' => '500'], 200);

				}

				$credentials = $Request->only('username', 'password');

				if (Auth::attempt($credentials)) {

					$data->device_token = $Request->device_token;

					$data->device_type = $Request->device_type;

					$data->save();

					$tokens = [$Request->device_token];

					if ($data->role == "admin") {

						$com = Company::where('user_id', $data->id)->first();

						$data['other_id'] = $com->id;

					}

					if ($data->role == "employee") {

						$emp = Employee::where('user_id', $data->id)->first();

						$com = Company::where('user_id', $emp->company_id)->first();

						// $data['other_id']=$com->id;
						$data['other_id'] = $emp->company_id;

					}

					if ($data->role == "transporter") {

						$detail = Transporter::where("user_id", $data->id)->first();

						$data['other_id'] = $detail->id;

					}

					if ($data->role == "forwarder") {

						$detail = Forwarder::where("user_id", $data->id)->first();

						$data['other_id'] = $detail->id;

					}

					return response()->json(['status' => 'success', 'message' => 'Login Successfully.', 'data' => $data, 'code' => '200'], 200);

				} else {

					return response()->json(['status' => 'failed', 'message' => 'Email & Password Are Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

				}

			}

			if ($dcount > 0) {
/*
				$pass = Hash::make($Request->password);
				return response()->json(['status' => 'success', 'message' => 'Login Successfully.', 'data' => $pass, 'code' => '200'], 200);
*/



				$data2 = Driver::where('phone', $Request->username)->first();

				if ($data2->status == 1) {

					return response()->json(['status' => 'failed', 'message' => 'This User Blocked By Admin ot Transporter, Please Contact to Administrator Or Transporter.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

					return response()->json(['status' => 'success', 'message' => 'Login Successfully.', 'data' => $mydata, 'code' => '200'], 200);

				} else {

					return response()->json(['status' => 'failed', 'message' => 'Username & Password Are Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

				}

			}

			return response()->json(['status' => 'failed', 'message' => 'Username & Password Are Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

						$driver_data->device_token ='';

						$driver_data->save();

						return response()->json(['status' => 'success', 'message' => ' Logout Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);


					} else {

						return response()->json(['status' => 'success', 'message' => ' Logout Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);
					}

				
				} else {

					$user_dataa = User::where('device_token', $Request->device_token)->count();

					if($user_dataa > 0) {

						$user_data = User::where('device_token', $Request->device_token)->first();

						$user_data->device_token ='';

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

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Company::all();

			return response()->json(['status' => 'success', 'message' => 'Company List Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//4
	public function CompanyAdd(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = User::where('username', $Request->username)->count();

			if ($data > 0) {

				return response()->json(['status' => 'failed', 'message' => 'This Username Already Registred In Our System.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			} else {

				$user = new User();
				$user->name = $Request->name;
				$user->username = $Request->username;
				$user->password = Hash::make($Request->password);
				$user->role = "admin";
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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = User::where('username', $Request->username)->where('id', '!=', $Request->user_id2)->count();

			if ($data > 0) {

				return response()->json(['status' => 'failed', 'message' => 'This Username Already Registred In Our System.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$aa = Shipment::where('status', 0)->orwhere('status', 1)->where('company', $Request->id)->count();

			if ($aa > 0) {

				return response()->json(['status' => 'failed', 'message' => 'Unable To Delete.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//8
	public function ForwarderList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Forwarder::all();

			return response()->json(['status' => 'success', 'message' => 'Forwarder List Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//9
	public function ForwarderAdd(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = User::where('username', $Request->username)->count();

			if ($data > 0) {

				return response()->json(['status' => 'failed', 'message' => 'This Username Already Registred In Our System.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = Forwarder::findorfail($Request->id);

			$uu = User::withTrashed()->findorfail($comapny->user_id);

			if ($Request->username != $uu->username) {

				$data = User::where('username', $Request->username)->count();

				if ($data > 0) {

					return redirect()->back()->withInput()->with('error', 'This Username Allready Registred in Our System.');
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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$aa = Shipment::where('status', 0)->orwhere('status', 1)->where('forwarder', $Request->id)->count();

			if ($aa > 0) {

				return response()->json(['status' => 'failed', 'message' => 'Unable To Delete.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Forwarder::findorfail($Request->id);
			$data->deleted_by = $Request->user_id;
			$data->save();
			$user = User::findorfail($data->user_id);
			$user->deleted_by = $Request->user_id;
			$user->save();

			if ($user->delete() && $data->delete()) {

				return response()->json(['status' => 'success', 'message' => 'Forwarder deleted Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//13
	public function TruckList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Truck::where('status', 0)->get();

			return response()->json(['status' => 'success', 'message' => 'Truck Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//14
	public function TruckDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Transporter::all();

			return response()->json(['status' => 'success', 'message' => 'Transporter Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//16
	public function TransporterDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = User::where('username', $Request->username)->count();

			if ($data > 0) {

				return response()->json(['status' => 'failed', 'message' => 'This Username Already Registred In Our System.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//20
	public function WarehouseList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();
			$data1 = Warehouse::all();

			foreach ($data1 as $key => $value) {

				$data[$key] = $value;

				$detail = Company::findorfail($value->company_id);
				$data[$key]['company_name'] = $detail->name;
			}

			return response()->json(['status' => 'success', 'message' => 'Warehouse Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//21
	public function WarehouseDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = new Warehouse();

			$comapny->name = $Request->name;

			$comapny->address = $Request->address;

			$comapny->phone = $Request->phone;

			$comapny->gst = $Request->gst;

			$comapny->pan = $Request->pan_no;

			$comapny->created_by = $Request->user_id;

			$comapny->company_id = $Request->company_id;

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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = Warehouse::findorfail($Request->id);

			$comapny->name = $Request->name;

			$comapny->address = $Request->address;

			$comapny->phone = $Request->phone;

			$comapny->gst = $Request->gst;

			$comapny->pan = $Request->pan_no;

			$comapny->status = $Request->status;

			$comapny->updated_by = $Request->user_id;

			$path = public_path('/uploads');

			if ($Request->hasFile('address_proof') && !empty($Request->file('address_proof'))) {
				$file_name = time() . "1" . $Request->address_proof->getClientOriginalName();
				$Request->address_proof->move($path, $file_name);
				$comapny->address_proof = $file_name;
			}

			if ($comapny->save()) {

				return response()->json(['status' => 'success', 'message' => 'Warehouse Upadated Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Warehouse::findorfail($Request->id);
			$data->deleted_by = $Request->user_id;
			$data->save();

			if ($data->delete()) {

				return response()->json(['status' => 'success', 'message' => 'Warehouse deleted Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//25
	public function DriverList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			if ($Request->role == 'admin' || $Request->role == 'employee') {

				$data1 = Driver::where('self',0)->get();

				foreach ($data1 as $key => $value) {
					$data[$key] = $value;
					$details = Transporter::findorfail($value->transporter_id);
					$data[$key]['transporter_name'] = $details->name;
				}

			}

			if ($Request->role == 'transporter') {

				$data1 = Driver::where('transporter_id', $Request->other_id)->where('self', 0)->get();

				foreach ($data1 as $key => $value) {
					$data[$key] = $value;
					$details = Transporter::findorfail($value->transporter_id);
					$data[$key]['transporter_name'] = $details->name;
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Driver List Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//26
	public function DriverDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = new Driver();

			$comapny->name = $Request->name;

			$comapny->phone = $Request->phone;

			$comapny->truck_no = $Request->truck_no;

			$comapny->licence_no = $Request->licence_no;

			$comapny->pan = $Request->pan_no;

			$comapny->transporter_id = $Request->transporter_id;

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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//28
	public function DriverEdit(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = Driver::findorfail($Request->id);

			$comapny->name = $Request->name;

			$comapny->phone = $Request->phone;

			$comapny->truck_no = $Request->truck_no;

			$comapny->licence_no = $Request->licence_no;

			$comapny->pan = $Request->pan_no;

			$comapny->transporter_id = $Request->transporter_id;

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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Driver::findorfail($Request->id);
			$data->deleted_by = $Request->user_id;
			$data->save();

			if ($data->delete()) {

				return response()->json(['status' => 'success', 'message' => 'Driver deleted Successfully.', 'data' => json_decode('{}'), 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//30
	public function EmployeeList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();
			$data1 = Employee::all();

			foreach ($data1 as $key => $value) {
				$data[$key] = $value;
				$company = Company::withTrashed()->findorfail($value->company_id);
				$data[$key]['comapny_name'] = $company->name;
			}

			return response()->json(['status' => 'success', 'message' => 'Employee Detail Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//31
	public function EmployeeDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = User::where('username', $Request->username)->count();

			if ($data > 0) {

				return response()->json(['status' => 'failed', 'message' => 'This Username Already Registred In Our System.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

			$comapny->company_id = $Request->company_id;

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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$comapny = Employee::findorfail($Request->id);

			$comapny->name = $Request->name;

			$comapny->phone = $Request->phone;

			$comapny->address = $Request->address;

			$comapny->email = $Request->email;

			$comapny->company_id = $Request->company_id;

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

				return response()->json(['status' => 'success', 'message' => 'Driver Updated Successfully.', 'data' => $comapny, 'code' => '200'], 200);

			} else {

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				return response()->json(['status' => 'failed', 'message' => 'Something Wrong.', 'data' => json_decode('{}'), 'code' => '500'], 200);

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

	//36
	public function ShipmentFormAdd(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$ship_check = Shipment::where('shipment_no', $Request->shipment_no)->count();

			if ($ship_check > 0) {

				return response()->json(['status' => 'success', 'message' => 'Shipment Number Already Registred.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

			$data->b_e_no = $Request->be_no;

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

                        file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

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

                        file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

                        
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

                        file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

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

                        file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

                        $path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

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

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//37
	public function ShipmentTransporterList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data1 = Shipment_Transporter::where('shipment_no', $Request->shipment_no)->get();

			$data = array();

			foreach ($data1 as $key => $value) {

				$data[$key] = $value;

				$tras = Transporter::withTrashed()->findorfail($value->transporter_id);

				$data[$key]['name'] = $tras->name;

			}

			return response()->json(['status' => 'success', 'message' => 'Shipment Transporter List Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//38
	public function ShipmentTransporterSave(Request $Request) {

		try {

			$tras = Transporter::findorfail($Request->transporter_id);

			$ship = Shipment::where('shipment_no', $Request->shipment_no)->first();

			$data = new Shipment_Transporter();

			if ($Request->truck_no != "" && $Request->truck_no != null && $Request->truck_no != "null") {
				$ship->status = 1;
			}
			if ($ship->all_transporter != "" && $ship->all_transporter != "null" && $ship->all_transporter != null) {
				$ship->all_transporter = $ship->all_transporter . ", " . $Request->transporter_id;
			} else {
				$ship->all_transporter = $Request->transporter_id;
			}

			$ship->save();

			$data->shipment_no = $Request->shipment_no;

			$data->shipment_id = $ship->id;

			$data->transporter_id = $tras->id;

			$data->name = $tras->name;

			$data->created_by = $Request->user_id;

			$data->save();

			$summary = new Shipment_Summary();

			$summary->shipment_no = $Request->shipment_no;

			$summary->flag = "Add Transporter";

			$summary->transporter_id = $Request->transporter_id;

			$summary->description = "Add Transporter. - " . $tras->name;

			$summary->save();



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
                        $driver->shipment_no = $Request->shipment_no;
                        $driver->transporter_id = $Request->transporter_id;
                        $driver->driver_id = $mydriverdetails->id;
                        $driver->truck_no = $mytruckno;
                        $driver->mobile = $tt->phone;
                        $driver->created_by = $Request->user_id;
                        $driver->myid = uniqid();
                        $driver->save();
 
                        $summary = new Shipment_Summary();
                        $summary->shipment_no = $Request->shipment_no;
                        $summary->flag = "Add Driver";
                        $summary->transporter_id = $Request->transporter_id;
                        $summary->description = "Add Driver. \n" . $mytruckno . "(Co.No." . $tt->phone . ").";
                        $summary->save();

                        $summary1 = new Shipment_Summary();
                        $summary1->shipment_no =  $Request->shipment_no;
                        $summary1->flag = "Add Truck";
                        $summary1->transporter_id = $Request->transporter_id;
                        $summary1->description = "Add Driver & Truck No. ".$mytruckno;
                        $summary1->save(); 


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

			if ($Request->transporter_id != null && $Request->transporter_id != '' && $Request->transporter_id != 'null') {

				$tuser = User::findorfail($tras->user_id);

				if ($tuser->device_token != "") {

					array_push($token, $tuser->device_token);

					$title = "New Shipment Assigned.";

					$message = "We would like to inform, the shipment " . $Request->shipment_no . " is assigned to you.";

					$aa = new WebNotificationController();

					$aa->index($token, $title, $message, $Request->shipment_no);
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Shipment Transporter Addedd Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//39
	public function ShipmentTransporterDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {
				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment_Transporter::findorfail($Request->id);
			$data->deleted_by = $Request->user_id;
			$data->save();

			$dd = Shipment_Driver::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->delete();

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data1 = Shipment_Driver::where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->other_id)->orderby('created_at', 'desc')->get();

			$data2 = array();

			// foreach ($data1 as $key => $value) {

			//$data[$key]=$value;

			//$tras = Transporter::withTrashed()->findorfail($value->transporter_id);

			//$data[$key]['name']= $tras->name;

			//}

			$data2 = Driver::where('transporter_id', $Request->other_id)->get();

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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
			$summary->flag = "add";
			$summary->transporter_id = $Request->other_id;
			$summary->description = "Add Driver & Truck No. " . $Request->truck_no;
			$summary->save();

			/// For Transporter

			$token = array();

			if ($Request->driver_id != null && $Request->driver_id != '' && $Request->driver_id != 'null') {

				$tuser = Driver::findorfail($Request->driver_id);

				if ($tuser->device_token != "") {

					array_push($token, $tuser->device_token);

					$title = "New Shipment Assigned.";

					$message = "We would like to inform, the shipment number " . $Request->shipment_no . " is assigned to you.";

					$aa = new WebNotificationController();

					$aa->index($token, $title, $message, $Request->shipment_no);
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Shipment Driver Addedd Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//42
	public function ShipmentDriverDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			if ($Request->role == "transporter") {

				$data = Shipment_Driver::findorfail($Request->id);
				$data->deleted_by = $Request->user_id;
				$data->save();

				$summary = new Shipment_Summary();
				$summary->shipment_no = $data->shipment_no;
				$summary->flag = "Delete Driver";
				$summary->transporter_id = $data->other_id;
				$summary->description = "Delete Driver & Truck No. " . $data->truck_no;
				$summary->created_by = $data->user_id;
				$summary->save();

			}

			if ($Request->role == "admin") {

				$data = Shipment_Driver::findorfail($Request->id);
				$data->deleted_by = $Request->user_id;
				$data->save();

				$summary = new Shipment_Summary();
				$summary->shipment_no = $data->shipment_no;
				$summary->flag = "Delete Truck";
				$summary->company_id = $data->other_id;
				$summary->description = "Delete Truck No. " . $data->truck_no;
				$summary->created_by = $data->user_id;
				$summary->save();

			}

			$data->delete();

			return response()->json(['status' => 'success', 'message' => 'Shipment Driver Deleted Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//43
	public function ExpenseAdd(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$shipment_data = Shipment::where('shipment_no', $Request->shipment_no)->first();

			$account = new Account();
			$account->to_transporter = $Request->transporter_id;
			$account->from_company = $shipment_data->company;
			$account->description = $Request->shipment_no . " Expense."." - ".$shipment_data->date." / " . $Request->reason;
			$account->dates = date('Y-m-d');
			$account->v_type = "debit";
			$account->debit = $Request->amount;
			$account->save();


			$expense = new Expense();
			$expense->account_id = $account->id;
			$expense->transporter_id = $Request->transporter_id;
			$expense->reason = $Request->reason;
			$expense->amount = $Request->amount;
			$expense->shipment_no = $Request->shipment_no;
			$expense->created_by = $Request->user_id;
			$expense->save();

			


			$summary = new Shipment_Summary();
			$summary->shipment_no = $Request->shipment_no;
			$summary->flag = "Add Expense";
			$summary->transporter_id = $Request->transporter_id;
			$summary->description = "Add Expense. " . $Request->reason;
			$summary->save();

			return response()->json(['status' => 'success', 'message' => 'Expense Addedd Successfully.', 'data' => $expense, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//44
	public function ShipmentpendingList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			if ($Request->role == "admin") {

				$data1 = Shipment::where('status', 0)->orderby('created_at', 'desc')->get();

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
				$data2 = Shipment_Transporter::where('transporter_id', $Request->other_id)->where('status', 1)->orderby('created_at', 'desc')->get();
				//dd($data2);
				$data = array();
				//dd($data2);
				foreach ($data2 as $key => $value) {
					//$data1 = Shipment::where('shipment_no', $value->shipment_no)->first();
					$data1 = Shipment::withTrashed()->where('shipment_no', $value->shipment_no)->first();
					$data[$key] = $data1;
					$com = Company::withTrashed()->findorfail($data1->company);
					if($com && $data1->company != 3 && $data1->company != 1){
				//		dd($data1);
					}
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
			if ($Request->role == "driver") {

				$data2 = Shipment_Driver::where('driver_id', $Request->user_id)->where('status', 1)->orderby('created_at', 'desc')->get();
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

				$data1 = Shipment::where('status', 0)->where('forwarder', $Request->other_id)->get();

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

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//45 ShipmentOnTheWayList
	public function ShipmentOnTheWayList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			if ($Request->role == "admin") {

				$data1 = Shipment::where('status', 1)->orderby('created_at', 'desc')->get();

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
				$data2 = Shipment_Transporter::where('transporter_id', $Request->other_id)->where('status', 2)->orderby('created_at', 'desc')->get();
				$data = array();
				foreach ($data2 as $key => $value) {
					$data1 = Shipment::where('shipment_no', $value->shipment_no)->first();
					$data[$key] = $data1;
					$com = Company::withTrashed()->findorfail($data1->company);
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
			}

			if ($Request->role == "driver") {

				$data2 = Shipment_Driver::where('driver_id', $Request->user_id)->where('transporter_id', $Request->other_id)->whereIn('status', [2,4,5,6,7,8,9,10])->orderby('created_at', 'desc')->get();

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

				$data1 = Shipment::where('status', 1)->where('forwarder', $Request->other_id)->get();

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

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//46
	public function ShipmentDeliveryList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			if ($Request->role == "admin") {

				$data1 = Shipment::where('status', 2)->orderby('created_at', 'desc')->get();

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
				$data2 = Shipment_Transporter::where('transporter_id', $Request->other_id)->where('status', 3)->whereNull('expense')->orderby('created_at', 'desc')->get();
				$data = array();
				foreach ($data2 as $key => $value) {
					//$expense = Expense::where('shipment_no', $value->shipment_no)->count();
					//dd($expense);
					//$data1 = Shipment::where('shipment_no', $value->shipment_no)->first();
					//$date1=date_create($data1->date);
					//$date2=date_create(date('Y-m-d'));
					//$diff=date_diff($date1,$date2);
					//if($expense == 0){
						$data1 = Shipment::where('shipment_no', $value->shipment_no)->first();
						$data[$key] = $data1;
						$data[$key]['expense'] = $value->expense;
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
					//}
				}
			}

			if ($Request->role == "driver") {
				$data2 = Shipment_Driver::where('driver_id', $Request->user_id)->where('transporter_id', $Request->other_id)->where('status', 3)->orderby('created_at', 'desc')->get();
				$data = array();
				foreach ($data2 as $key => $value) {
					//$expense = Expense::where('shipment_no', $value->shipment_no)->count();
					$data1 = Shipment::where('shipment_no', $value->shipment_no)->first();
					$date0=date_create($data1->date);
					$date2=date_create(date('Y-m-d'));
					$diff=date_diff($date0,$date2);
					if($diff->format("%a") < 8){
						$data1 = Shipment::where('shipment_no', $value->shipment_no)->first();
						$data[$key] = $data1;
						$data[$key]['expense'] = $value->expense;
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
			}

			if ($Request->role == "forwarder") {

				$data1 = Shipment::where('status', 2)->where('forwarder', $Request->other_id)->get();

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

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//47
	public function ShipmentDetail(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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
			if ($data->transporter != "" && $data->transporter != null && $data->transporter != 'null') {
				$tra = Transporter::withTrashed()->findorfail($data->transporter);
				$data->transporter_name = $tra->name;
			} else {
				$data->transporter_name = "";

			}

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

			$data->transporters_list = $t_list;

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

			$all_truck_lists = Shipment_Driver::where('shipment_no', $Request->shipment_no)->get();

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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
			$summary->description = "Change Truck Shipment Status By Admin.\n" . $data->truck_no . " is " . $cargo->name;
			$summary->created_by = $Request->user_id;
			$summary->save();

			return response()->json(['status' => 'success', 'message' => 'Shipment Status Changed Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}
	//49
	public function ShipmentChangeStatusTransporter(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment_Driver::findorfail($Request->id);

			$data->status = $Request->status;

			$path = public_path('/uploads');

			if ($Request->status == "2") {

				$data->load_time = date('Y-m-d H:i');

				$transp = Shipment_Transporter::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();

				$ss = Shipment::where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				$ss->save();

				if ($Request->hasFile('image') && !empty($Request->file('image'))) {
					$file_name = time() . $Request->image->getClientOriginalName();
					$Request->image->move($path, $file_name);
					$data->loaded_photo = $file_name;

				}

			}

			if ($Request->status == "3") {

				$data->unload_time = date('Y-m-d H:i');

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


			if ($Request->status == "6") {

				$data->pickup_conf_time = date('Y-m-d H:i');

				/*$transp = Shipment_Transporter::where('shipment_no', $data->shipment_no)->where('transporter_id', $data->transporter_id)->first();
				$transp->status = 2;
				$transp->save();*/

				$ss = Shipment::where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
				$ss->save();

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

				$ss = Shipment::where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
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

				$ss = Shipment::where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
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

				$ss = Shipment::where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
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
				$ss = Shipment::where('shipment_no', $data->shipment_no)->first();
				$ss->status = 1;
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

			$summary = new Shipment_Summary();

			$summary->shipment_no = $data->shipment_no;

			$summary->flag = $data->truck_no . " is " . $cargo->name;

			$summary->transporter_id = $data->transporter_id;

			$summary->description = "Change Truck Shipment Status By Transporter.\n" . $data->truck_no . " is " . $cargo->name;

			$summary->created_by = $Request->user_id;

			$summary->save();

			return response()->json(['status' => 'success', 'message' => 'Shipment Status Changed Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//50
	public function ShipmentFormEdit(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::where('shipment_no', $Request->shipment_no)->first();

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

			if ($Request->truck_no != $data->truck_no && $Request->truck_no != "null" && $Request->truck_no != null) {

				$data->status = 1;

			}

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

			$data->b_e_no = $Request->be_no;

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

				$transs = new Shipment_Transporter();
				$transs->shipment_no = $Request->shipment_no;
				$transs->shipment_id = $data->id;
				$transs->transporter_id = $Request->transporter_id;
				$transs->name = $tra->transporter_name;
				$transs->created_by = $Request->user_id;
				$transs->save();

				$data->transporter = $Request->transporter_id;

			}

			if ($Request->truck_no != $data->truck_no && $Request->truck_no != null && $Request->truck_no != '' && $Request->truck_no != 'null') {

				$driver = new Shipment_Driver();
				$driver->shipment_no = $Request->shipment_no;
				$driver->truck_no = $Request->truck_no;
				$driver->mobile = $tra->transporter_mobile;
				$driver->created_by = $Request->user_id;
				$driver->save();
			}

			$data->save();

			$data1 = Shipment::findorfail($data->id);

			return response()->json(['status' => 'success', 'message' => 'Shipment Edited Successfully.', 'data' => $data1, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//51
	public function ShipmentFormDelete(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

				return response()->json(['status' => 'success', 'message' => 'LR PDF Successfully Updated.', 'data' => $path, 'code' => '200'], 200);

				//return $pdf->download($data->lr_no.'.pdf');

			} elseif ($comp->lr == "ssilr") {

				$pdf = PDF::loadView('lr.ssilr', compact('data', 'trucks'));

				file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

				return response()->json(['status' => 'success', 'message' => 'LR PDF Successfully Updated.', 'data' => $path, 'code' => '200'], 200);

			} elseif ($comp->lr == "hanshlr") {

				$pdf = PDF::loadView('lr.hanshlr', compact('data', 'trucks'));

				file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

				return response()->json(['status' => 'success', 'message' => 'LR PDF Successfully Updated.', 'data' => $path, 'code' => '200'], 200);

			} elseif ($comp->lr == "bmflr") {

				$pdf = PDF::loadView('lr.bmflr', compact('data', 'trucks'));

				file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

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

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data1 = Shipment::where('status', 3)->get();

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

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//55
	public function ShipmentInWarehouse(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$shipment_data = Shipment::where('shipment_no', $Request->shipment_no)->first();

			$account = new Account();
			$account->to_transporter = $Request->other_id;
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
			$summary->transporter_id = $Request->transporter_id;
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

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			if ($Request->role == "admin") {

				$data = Cargostatus::where('admin', 1)->get();

			}
			if ($Request->role == "employee") {

				$data = Cargostatus::where('employee', 1)->get();

			}
			if ($Request->role == "transporter" || $Request->role == "driver") {

				$data = Cargostatus::where('transporter', 1)->get();

			}

			return response()->json(['status' => 'success', 'message' => 'Cargo Status List Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//59
	public function ShipTruckList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			if ($Request->role == "admin") {

				$data1 = Shipment_Driver::where('shipment_no', $Request->shipment_no)->get();

			}

			if ($Request->role == "transporter") {

				$data1 = Shipment_Driver::where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->other_id)->get();

			}

			if ($Request->role == "driver") {

				$data1 = Shipment_Driver::where('driver_id', $Request->user_id)->where('shipment_no', $Request->shipment_no)->where('transporter_id', $Request->other_id)->get();

			}

			if (count($data1) > 0) {

				foreach ($data1 as $key => $value) {

					$data[$key] = $value;
					$trans = Transporter::findorfail($value->transporter_id);
					$data[$key]['name'] = $trans->name;
					$cargo = Cargostatus::findorfail($value->status);
					$data[$key]['status_name'] = $cargo->name;
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Trucks List Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//60

	public function Shipmentdelivered(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::where('shipment_no', $Request->shipment_no)->first();
			$data->status = 2;
			$data->updated_by = $Request->user_id;
			$data->save();

			$summary = new Shipment_Summary();
			$summary->shipment_no = $Request->shipment_no;
			$summary->flag = "Shiment Deleivered";
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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment_Summary::where('shipment_no', $Request->shipment_no)->get();

			return response()->json(['status' => 'success', 'message' => ' Summary Successfully.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	public function ReplaceShipment(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//63
	public function Dashboard(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			if ($Request->role == "admin" && $Request->company_id == "" && $Request->transporter_id == "" && $Request->forwarder_id == "") {

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

            	$data['pl_report'] = $total_credit;

				$data['pending'] = Shipment::where('status', 0)->count();

				$data['ontheway'] = Shipment::where('status', 1)->count();

				$data['bill_status'] = Invoice::where('paid', 0)->count();

				//dd($data);

			} else if ($Request->role == "admin" && $Request->company_id != "" && $Request->company_id != null && $Request->company_id != "null") {

				$from = date('Y-04-01');

				$to = date('Y-m-d');

				$total_credit1 = Account::where('to_company', $Request->company_id)->whereBetween('dates', [$from, $to])->sum('credit');

				$total_credit2 = Account::where('to_company', $Request->company_id)->whereBetween('dates', [$from, $to])->sum('debit');

				$total_credit = $total_credit1 + $total_credit2;

				$data['pl_report'] = $total_credit;

				$data['pending'] = Shipment::where('status', 0)->where('company', $Request->company_id)->count();

				$data['ontheway'] = Shipment::where('status', 1)->where('company', $Request->company_id)->count();

				$data['bill_status'] = Invoice::where('paid', 0)->where('company_id', $Request->company_id)->count();

			} else if ($Request->role == "employee" && $Request->company_id != "" && $Request->company_id != null && $Request->company_id != "null") {

				$from = date('Y-04-01');

				$to = date('Y-m-d');

				$total_credit1 = Account::where('to_company', $Request->company_id)->whereBetween('dates', [$from, $to])->sum('credit');

				$total_credit2 = Account::where('to_company', $Request->company_id)->whereBetween('dates', [$from, $to])->sum('debit');

				$total_credit = $total_credit1 + $total_credit2;

				$data['pl_report'] = $total_credit;

				$data['pending'] = Shipment::where('status', 0)->where('company', $Request->company_id)->count();

				$data['ontheway'] = Shipment::where('status', 1)->where('company', $Request->company_id)->count();

				$data['bill_status'] = Invoice::where('paid', 0)->where('company_id', $Request->company_id)->count();

			} else if ($Request->role == "transporter") {
				$pending = Shipment_Transporter::where('status', 1)->where('transporter_id', $Request->transporter_id)->get();
				$pending1 = array();
				foreach ($pending as $key => $value) {
					$pending1[$key] = Shipment::where('shipment_no', $value->shipment_no)->first();
				}
				$data['pending'] = count($pending1);
				$ontheway = Shipment_Transporter::where('status', 2)->where('transporter_id', $Request->transporter_id)->get();
				$ontheway1 = array();
				foreach ($ontheway as $key => $value) {
					$ontheway1[$key] = Shipment::where('shipment_no', $value->shipment_no)->first();
				}
				$data['ontheway'] = count($ontheway1);
				$delivery = Shipment_Transporter::where('status', 3)->where('transporter_id', $Request->transporter_id)->get();
				$delivery1 = array();
				foreach ($delivery as $key => $value) {
					$delivery1[$key] = Shipment::where('shipment_no', $value->shipment_no)->first();
				}
				$data['delivery'] = count($delivery1);
			} else if ($Request->role == "forwarder") {

				$data['total'] = Shipment::where('forwarder', $Request->forwarder_id)->count();

				$data['pending'] = Shipment::where('status', 0)->where('forwarder', $Request->forwarder_id)->count();

				$data['delivery'] = Shipment::where('status', 2)->where('forwarder', $Request->forwarder_id)->count();

				$data['remaining'] = Shipment::where('paid', 0)->where('forwarder', $Request->forwarder_id)->sum('invoice_amount');
			}

			return response()->json(['status' => 'success', 'message' => 'Dashboard Data Success.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//64
	public function TransporterAC(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			if ($Request->month != "" && $Request->year != "") {

				$data1 = Shipment_Transporter::where('transporter_id', $Request->transporter_id)->where('status', 3)->whereYear('created_at', $Request->year)->whereMonth('created_at', $Request->month)->get();

				foreach ($data1 as $key => $value) {

					$ship_data = Shipment::where('shipment_no', $value->shipment_no)->first();

					if ($ship_data->status == 2) {
						$data[$key] = $ship_data;
						$data[$key]['expense'] = $value->expense;
					}
				}

			}

			return response()->json(['status' => 'success', 'message' => 'Data Success.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//64
	public function Filters(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			if ($Request->role == "admin" || $Request->role == "employee") {

				if ($Request->keyword != "" && $Request->keyword != " " && $Request->keyword != "null" && $Request->keyword != null) {

					$data1 = Shipment::where('shipment_no', 'like', '%' . $Request->keyword . '%')->orwhere('from1', 'like', '%' . $Request->keyword . '%')->orwhere('to1', 'like', '%' . $Request->keyword . '%')->orwhere('to2', 'like', '%' . $Request->keyword . '%')->orwhere('consignor', 'like', '%' . $Request->keyword . '%')->orwhere('consignee', 'like', '%' . $Request->keyword . '%')->orwhere('shipper_invoice', 'like', '%' . $Request->keyword . '%')->orwhere('forwarder_ref_no', 'like', '%' . $Request->keyword . '%')->orwhere('b_e_no', 'like', '%' . $Request->keyword . '%')->orderby('shipment_no', 'desc')->get();

					foreach ($data1 as $key => $value) {

						$data[$key] = $value;

						$com = Company::withTrashed()->findorfail($value->company);

						$data[$key]['company_name'] = $com->name;

						$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

						$data[$key]['forwarder_name'] = $forw->name;

						if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {

						$tk = Truck::withTrashed()->findorfail($value->trucktype);

						$data[$key]['vehicle'] = $tk->name;

						} else {

						$data[$key]['vehicle'] = '';

						}

						if ($value->status == 0) {

							$data[$key]['mystatus'] = "Pending";

						} elseif ($value->status == 1) {

							$data[$key]['mystatus'] = "OnTheWay";

						} elseif ($value->status == 2) {

							$data[$key]['mystatus'] = "Delivery";

						} elseif ($value->status == 3) {

							$data[$key]['mystatus'] = "InWarehouse";

						}

					}

				} else if ($Request->shipment_no != "" && $Request->shipment_no != " " && $Request->shipment_no != "null" && $Request->shipment_no != null) {

					$data1 = Shipment::where('shipment_no', $Request->shipment_no)->first();

					$com = Company::withTrashed()->findorfail($data1->company);

					$data1['company_name'] = $com->name;

					$forw = Forwarder::withTrashed()->findorfail($data1->forwarder);

					$data1['forwarder_name'] = $forw->name;

					if($data1->trucktype !='' && $data1->trucktype != 'null' && $data1->trucktype != null) {

					$tk = Truck::withTrashed()->findorfail($data1->trucktype);

					$data1['vehicle'] = $tk->name;

					} else {

						$data1[$key]['vehicle'] = '';

					}

					if ($data1->status == 0) {

						$data1['mystatus'] = "Pending";

					} elseif ($data1->status == 1) {

						$data1['mystatus'] = "OnTheWay";

					} elseif ($data1->status == 2) {

						$data1['mystatus'] = "Delivery";

					} elseif ($data1->status == 3) {

						$data1['mystatus'] = "InWarehouse";

					}

					$data = array($data1);

				} elseif ($Request->forwarder != "" && $Request->forwarder != " " && $Request->forwarder != "null" && $Request->forwarder != null) {

					$data1 = Shipment::where('forwarder', $Request->forwarder)->orderby('shipment_no', 'desc')->get();

					foreach ($data1 as $key => $value) {

						$data[$key] = $value;

						$com = Company::withTrashed()->findorfail($value->company);

						$data[$key]['company_name'] = $com->name;

						$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

						$data[$key]['forwarder_name'] = $forw->name;

						if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {

						$tk = Truck::withTrashed()->findorfail($value->trucktype);

						$data[$key]['vehicle'] = $tk->name;

						} else {
						
						$data[$key]['vehicle'] = '';

						}

						if ($value->status == 0) {

							$data[$key]['mystatus'] = "Pending";

						} elseif ($value->status == 1) {

							$data[$key]['mystatus'] = "OnTheWay";

						} elseif ($value->status == 2) {

							$data[$key]['mystatus'] = "Delivery";

						} elseif ($value->status == 3) {

							$data[$key]['mystatus'] = "InWarehouse";

						}

					}

				} elseif ($Request->transporter != "" && $Request->transporter != " " && $Request->transporter != "null" && $Request->transporter != null) {

					$data1 = Shipment_Transporter::where('transporter_id', $Request->transporter)->orderby('shipment_no', 'desc')->get();

					foreach ($data1 as $key => $value) {

						$data2 = Shipment::where('shipment_no', $value->shipment_no)->first();

						$data[$key] = $data2;

						$com = Company::withTrashed()->findorfail($data2->company);

						$data[$key]['company_name'] = $com->name;

						$forw = Forwarder::withTrashed()->findorfail($data2->forwarder);

						$data[$key]['forwarder_name'] = $forw->name;

						if($data2->trucktype !='' && $data2->trucktype != 'null' && $data2->trucktype != null) {

						$tk = Truck::withTrashed()->findorfail($data2->trucktype);

						$data[$key]['vehicle'] = $tk->name;

						} else {
						
						$data[$key]['vehicle'] = '';

						}

						if ($data2->status == 0) {

							$data[$key]['mystatus'] = "Pending";

						} elseif ($data2->status == 1) {

							$data[$key]['mystatus'] = "OnTheWay";

						} elseif ($data2->status == 2) {

							$data[$key]['mystatus'] = "Delivery";

						} elseif ($data2->status == 3) {

							$data[$key]['mystatus'] = "InWarehouse";

						}

					}

				} elseif ($Request->date != "" && $Request->date != " " && $Request->date != "null" && $Request->date != null) {

					$date = date('d-m-Y', strtotime($Request->date . "-" . $Request->month . "-" . $Request->year));

					$data1 = Shipment::where('date', $date)->get();

					foreach ($data1 as $key => $value) {

						$data2 = Shipment::where('shipment_no', $value->shipment_no)->first();

						$data[$key] = $data2;

						$com = Company::withTrashed()->findorfail($data2->company);

						$data[$key]['company_name'] = $com->name;

						$forw = Forwarder::withTrashed()->findorfail($data2->forwarder);

						$data[$key]['forwarder_name'] = $forw->name;

						if($data2->trucktype !='' && $data2->trucktype != 'null' && $data2->trucktype != null) {

						$tk = Truck::withTrashed()->findorfail($data2->trucktype);

						$data[$key]['vehicle'] = $tk->name;

						} else {

						$data[$key]['vehicle'] = '';

						}

						if ($data2->status == 0) {

							$data[$key]['mystatus'] = "Pending";

						} elseif ($data2->status == 1) {

							$data[$key]['mystatus'] = "OnTheWay";

						} elseif ($data2->status == 2) {

							$data[$key]['mystatus'] = "Delivery";

						} elseif ($data2->status == 3) {

							$data[$key]['mystatus'] = "InWarehouse";

						}

					}

				} elseif ($Request->month != "" && $Request->month != " " && $Request->month != "null" && $Request->month != null) {

					$data1 = Shipment::whereYear('date', $Request->year)->whereMonth('date', $Request->month)->orderby('date', 'desc')->get();

					foreach ($data1 as $key => $value) {

						$data[$key] = $value;

						$com = Company::withTrashed()->findorfail($value->company);

						$data[$key]['company_name'] = $com->name;

						$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

						$data[$key]['forwarder_name'] = $forw->name;

						if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {

						$tk = Truck::withTrashed()->findorfail($value->trucktype);

						$data[$key]['vehicle'] = $tk->name;

						} else {

						$data[$key]['vehicle'] = '';

					}

						if ($value->status == 0) {

							$data[$key]['mystatus'] = "Pending";

						} elseif ($value->status == 1) {

							$data[$key]['mystatus'] = "OnTheWay";

						} elseif ($value->status == 2) {

							$data[$key]['mystatus'] = "Delivery";

						} elseif ($value->status == 3) {

							$data[$key]['mystatus'] = "InWarehouse";

						}

					}

				} elseif ($Request->year != "" && $Request->year != " " && $Request->year != "null" && $Request->year != null) {

					$data1 = Shipment::whereYear('date', $Request->year)->orderby('date', 'desc')->get();

					foreach ($data1 as $key => $value) {

						$data[$key] = $value;

						$com = Company::withTrashed()->findorfail($value->company);

						$data[$key]['company_name'] = $com->name;

						$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

						$data[$key]['forwarder_name'] = $forw->name;

						if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {

						$tk = Truck::withTrashed()->findorfail($value->trucktype);

						$data[$key]['vehicle'] = $tk->name;

						} else {

						$data[$key]['vehicle'] = '';

					}

						if ($value->status == 0) {

							$data[$key]['mystatus'] = "Pending";

						} elseif ($value->status == 1) {

							$data[$key]['mystatus'] = "OnTheWay";

						} elseif ($value->status == 2) {

							$data[$key]['mystatus'] = "Delivery";

						} elseif ($value->status == 3) {

							$data[$key]['mystatus'] = "InWarehouse";

						}

					}

				}

			} elseif ($Request->role == "transporter") {

				if ($Request->month != "" && $Request->year != "") {

					$data1 = Shipment_Transporter::where('transporter_id', $Request->transporter_id)->whereYear('created_at', $Request->year)->whereMonth('created_at', $Request->month)->get();

					foreach ($data1 as $key => $value) {

						$data[$key] = Shipment::where('shipment_no', $value->shipment_no)->first();

						$data[$key]['expense'] = $value->expense;

						if ($value->status == 1) {

							$data[$key]['mystatus'] = "Pending";

						} elseif ($value->status == 2) {

							$data[$key]['mystatus'] = "OnTheWay";

						} elseif ($value->status == 3) {

							$data[$key]['mystatus'] = "Delivery";

						}

					}

				}

			} elseif ($Request->role == "forwarder") {

				if ($Request->month != "" && $Request->year != "") {

					$data1 = Shipment::where('forwarder', $Request->forwarder_id)->whereYear('date', $Request->year)->whereMonth('date', $Request->month)->get();

					foreach ($data1 as $key => $value) {

						$data[$key] = $value;

						$com = Company::withTrashed()->findorfail($value->company);

						$data[$key]['company_name'] = $com->name;

						$forw = Forwarder::withTrashed()->findorfail($value->forwarder);

						$data[$key]['forwarder_name'] = $forw->name;

						if($value->trucktype !='' && $value->trucktype != 'null' && $value->trucktype != null) {
							
						$tk = Truck::withTrashed()->findorfail($value->trucktype);

						$data[$key]['vehicle'] = $tk->name;

						} else {

						$data[$key]['vehicle'] = '';

						}

						if ($value->status == 0) {

							$data[$key]['mystatus'] = "Pending";

						} elseif ($value->status == 1 || $value->status == 3) {

							$data[$key]['mystatus'] = "OnTheWay";

						} elseif ($value->status == 2) {

							$data[$key]['mystatus'] = "Delivery";

						}

					}

				}
			}

			return response()->json(['status' => 'success', 'message' => 'Data Success.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//64
	public function ALLShipmentList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = Shipment::get();

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

			$total_credit1 = Account::where('to_company', $Request->company_id)->whereBetween('dates', [$startDate, $endDate])->sum('credit');

			$total_debit1 = Account::where('from_company', $Request->company_id)->whereBetween('dates', [$startDate, $endDate])->sum('debit');

			$data['credit'] = $total_credit1;

			$data['debit'] = $total_debit1;

			$data['diff'] = $total_credit1 - $total_debit1;

			return response()->json(['status' => 'success', 'message' => 'Data Success.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	// 68
	public function BillStatus(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			if ($Request->forwarder_id != '' && $Request->forwarder_id != ' ' && $Request->forwarder_id != null) {

				$invoice_lists = Invoice::where('forwarder_id', $Request->forwarder_id)->where('paid', 0)->get();

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

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	// 69
	public function ForwarderAC(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			if ($Request->month != "" && $Request->year != "") {

				$data1 = Invoice::where('forwarder_id', $Request->forwarder_id)->whereYear('created_at', $Request->year)->whereMonth('created_at', $Request->month)->get();

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

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			$data = Forwarder::get();

			return response()->json(['status' => 'success', 'message' => 'Forwarder List  Success.', 'data' => $data, 'code' => '200'], 200);

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);

		}

	}

	// 71
	public function ForwarderBillList(Request $Request) {

		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}

			$data = array();

			$invoice_lists = Invoice::where('forwarder_id', $Request->forwarder_id)->where('paid', 0)->get();

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

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

			if ($Request->role == "admin" || $Request->role == "transporter" || $Request->role == "employee" || $Request->role == "forwarder") {

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
		
		try {

			$check = $this->checkversion($Request->version);

			if ($check == 1) {

				return response()->json(['status' => 'failed', 'message' => 'Please Update This Application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
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

				$pdf = PDF::loadView('lr.yoginilr', compact('data', 'trucks'));

				file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

				$shipment = $Request->shipment_no;
				$myemail = $Request->email;

				$data2 = array('shipment_no'=>$shipment,'email'=>$myemail);

				$yogini_username = env('YOGINI_MAIL_USERNAME');
				$yogini_password = env('YOGINI_MAIL_PASSWORD');
				Config::set('mail.username', $yogini_username);
				Config::set('mail.password', $yogini_password);	

				//dd($data2);		
     			$mail_service = env('MAIL_SERVICE');
						if($mail_service == 'on'){
				 Mail::send('yoginimail', $data2, function($message) use ($data2) {
         			$message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
         			$message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
      			});
				}
      			
				return response()->json(['status' => 'success', 'message' => 'LR Send on Mail successfull.', 'data' => $path, 'code' => '200'], 200);

				//return $pdf->download($data->lr_no.'.pdf');

			} elseif ($comp->lr == "ssilr") {

				$pdf = PDF::loadView('lr.ssilr', compact('data', 'trucks'));

				file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

				
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

				}
					

				return response()->json(['status' => 'success', 'message' => 'LR Send on Mail successfull.', 'data' => $path, 'code' => '200'], 200);

			} elseif ($comp->lr == "hanshlr") {

				$pdf = PDF::loadView('lr.hanshlr', compact('data', 'trucks'));

				file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

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
      			}		
				
				return response()->json(['status' => 'success', 'message' => 'LR Send on Mail successfull.', 'data' => $path, 'code' => '200'], 200);

			} elseif ($comp->lr == "bmflr") {

				$pdf = PDF::loadView('lr.bmflr', compact('data', 'trucks'));

				file_put_contents("public/pdf/" . $Request->shipment_no . ".pdf", $pdf->output());

				$path = env('APP_URL') . "/public/pdf/" . $Request->shipment_no . ".pdf";

				$shipment = $Request->shipment_no;
				$myemail = $Request->email;

				$data2 = array('shipment_no'=>$shipment,'email'=>$myemail);
     			
     			$mail_service = env('MAIL_SERVICE');
				if($mail_service == 'on'){   

				 Mail::send('bmfmail', $data2, function($message) use ($data2) {
         			$message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
         			$message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
      			});	
      			}				
				
				return response()->json(['status' => 'success', 'message' => 'LR Send on Mail successfull.', 'data' => $path, 'code' => '200'], 200);

			}

		} catch (\Exception $e) {

			return response()->json(['status' => 'failed', 'message' => $e->getMessage(), 'data' => json_decode('{}'), 'code' => '500'], 200);
		}

	}

	//77 GET Transporter Driver
	public function GetTransporterDriver(Request $Request)
	{	
		try {

			$check = $this->checkversion($Request->version);

			$data = Driver::where('transporter_id',$Request->transporter_id)->orderby('name','asc')->get();

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



}
