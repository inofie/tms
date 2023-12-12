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
use App\level;
use App\Transporter;
use App\Truck;
use App\Warehouse;
use Hash;
use Session;

use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{

	public function index(Request $Request)
	{
		
		if(Auth::check()){

			if(Auth::user()->role == 'admin'){

				return redirect()->route('admindashboard');
			
			} else if(Auth::user()->role == 'employee'){

				return redirect()->route('admindashboard');
			
			} 
			else if(Auth::user()->role == 'transporter'){

				return redirect()->route('transporterdashboard');
			
			}else if(Auth::user()->role == 'forwarder') {
				
				return redirect()->route('forwarderdashboard');
			}
			else if(Auth::user()->role == 'Forwarder_level1') {

				return redirect()->route('userlist2');
			}
			else if(Auth::user()->role == 'Forwarder_level2') {

				return redirect()->route('userlist2');
			}
			else if(Auth::user()->role == 'Forwarder_level3') {

				return redirect()->route('userlist2');
			}
			else if(Auth::user()->role == 'Forwarder_level4') {

				return redirect()->route('userlist2');
			}
			else if(Auth::user()->role == 'Forwarder_level5') {

				return redirect()->route('userlist2');
			}
			else if(Auth::user()->role == 'Forwarder_level6') {

				return redirect()->route('userlist2');
			}
			else if(Auth::user()->role == 'Forwarder_level7') {

				return redirect()->route('userlist2');
			}
			else if(Auth::user()->role == 'Forwarder_level8') {

				return redirect()->route('userlist2');
			}
			else if(Auth::user()->role == 'Forwarder_level9') {

				return redirect()->route('userlist2');
			}
			else if(Auth::user()->role == 'Forwarder_level10') {

				return redirect()->route('userlist2');
			}
			else if(Auth::user()->role == 'warehouse') {

				return redirect()->route('warehousedashboard');
			}
			else if(Auth::user()->role == 'company'){

				return redirect()->route('companydashboard');
			
			}
			else{
                if(AUth::user()->can('dashboard-list')) {
                    return redirect('admin/dashboard');
                } 
				elseif(AUth::user()->can('shipment-list')) {
                    return redirect('admin/shipment');
                } 
                elseif(Auth::user()->can('invoice-list')) {
                    return redirect('admin/invoice');
                }
                elseif(Auth::user()->can('voucher-list')) {
                    return redirect('admin/voucher');
                }
                elseif(Auth::user()->can('expense-list')) {
                    return redirect('admin/expense');
                }
                elseif(Auth::user()->can('account-list')) {
                    return redirect('admin/account');
                }
                elseif(Auth::user()->can('transporter-list')) {
                    return redirect('admin/transporter');
                }
                elseif(Auth::user()->can('company-list')) {
                    return redirect('admin/company');
                }
                elseif(Auth::user()->can('forwarder-list')) {
                    return redirect('admin/forwarder');
                }
                elseif(Auth::user()->can('employee-list')) {
                    return redirect('admin/employee');
                }
				elseif(Auth::user()->can('roleuser-list')) {
                    return redirect('admin/roleuser');
                }
				elseif(Auth::user()->can('roles-list')) {
                    return redirect('admin/roles');
                }
				elseif(Auth::user()->can('driver-list')) {
                    return redirect('admin/driver');
                }
				elseif(Auth::user()->can('warehouse-list')) {
                    return redirect('admin/warehouse');
                }
               
            }

		} else {

		
			return view('login');
		}
		
	}


	public function LoginCheck(request $request)
	
	{

		//dd(Hash::make($request->password));

		$check = User::where('username',$request->username)->count();
		$check2 = User::where('username',$request->username)->first();
		if($check == 0 ) { 

		 return redirect()->back()->withInput()->withError("This Username is not registed in Our System.");
		}
		if($check2->status == 1) { 

			return redirect()->back()->withInput()->withError("Your account is deactivated. Please contact admin to active your account.");
		   }
		$userdata = User::where('username',$request->username)->first();

		// if($userdata->role == "transporter"){

		// return redirect()->back()->withError("You have no permission for login.\n\r Please don't try again.");	

		// }


		
		$credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {

        		if(Auth::user()->role == 'admin') {

        				return redirect()->route('admindashboard'); 
        			
        		} elseif(Auth::user()->role == 'employee') {

        				return redirect()->route('admindashboard'); 
        			
        		} elseif(Auth::user()->role == 'forwarder') {
					$check = DB::table('levels')->where('forwarder_id',Auth::id())->first();
					if(!$check){
					DB::table('levels')->insert(
						array([
						'name'   =>   'Level 1',
						'level_name'   =>   '1',
						'forwarder_id'   =>   Auth::id(),
						],
						[
							'name'   =>   'Level 2',
							'level_name'   =>   '2',
							'forwarder_id'   =>   Auth::id(),
						],
						[
							'name'   =>   'Level 3',
							'level_name'   =>   '3',
							'forwarder_id'   =>   Auth::id(),
						],
						[
							'name'   =>   'Level 4',
							'level_name'   =>   '4',
							'forwarder_id'   =>   Auth::id(),
						],
						[
							'name'   =>   'Level 5',
							'level_name'   =>   '5',
							'forwarder_id'   =>   Auth::id(),
						],
						[
							'name'   =>   'Level 6',
							'level_name'   =>   '6',
							'forwarder_id'   =>   Auth::id(),
						],
						[
							'name'   =>   'Level 7',
							'level_name'   =>   '7',
							'forwarder_id'   =>   Auth::id(),
						],
						[
							'name'   =>   'Level 8',
							'level_name'   =>   '8',
							'forwarder_id'   =>   Auth::id(),
						],
						[
							'name'   =>   'Level 9',
							'level_name'   =>   '9',
							'forwarder_id'   =>   Auth::id(),
						],
						[
							'name'   =>   'Level 10',
							'level_name'   =>   '10',
							'forwarder_id'   =>   Auth::id(),
						],
					)	
				);
				}
        				return redirect()->route('forwarderdashboard'); 
        			
        		} 
				else if(Auth::user()->role == 'Forwarder_level1') {

					return redirect()->route('userlist2');
				}
				else if(Auth::user()->role == 'Forwarder_level2') {
	
					return redirect()->route('userlist2');
				}
				else if(Auth::user()->role == 'Forwarder_level3') {
	
					return redirect()->route('userlist2');
				}
				else if(Auth::user()->role == 'Forwarder_level4') {
	
					return redirect()->route('userlist2');
				}
				else if(Auth::user()->role == 'Forwarder_level5') {
	
					return redirect()->route('userlist2');
				}
				else if(Auth::user()->role == 'Forwarder_level6') {
	
					return redirect()->route('userlist2');
				}
				else if(Auth::user()->role == 'Forwarder_level7') {
	
					return redirect()->route('userlist2');
				}
				else if(Auth::user()->role == 'Forwarder_level8') {
	
					return redirect()->route('userlist2');
				}
				else if(Auth::user()->role == 'Forwarder_level9') {
	
					return redirect()->route('userlist2');
				}
				else if(Auth::user()->role == 'Forwarder_level10') {
	
					return redirect()->route('userlist2');
				}
				elseif(Auth::user()->role == 'transporter') {

					return redirect()->route('transporterdashboard'); 
				
			}   
			elseif(Auth::user()->role == 'warehouse') {

				return redirect()->route('warehousedashboard'); 
			
		}   
		elseif(Auth::user()->role == 'company') {

			return redirect()->route('companydashboard'); 
		
		}   else{
			if(AUth::user()->can('dashboard-list')) {
				return redirect()->route('admindashboard');
			} 
			elseif(AUth::user()->can('shipment-list')) {
				return redirect('admin/shipment/list');
			} 
			elseif(Auth::user()->can('invoice-list')) {
				return redirect('admin/invoice/list');
			}
			elseif(Auth::user()->can('voucher-list')) {
				return redirect('admin/voucher/list');
			}
			elseif(Auth::user()->can('expense-list')) {
				return redirect('admin/expense/list');
			}
			elseif(Auth::user()->can('account-list')) {
				return redirect('admin/account/list');
			}
			elseif(Auth::user()->can('transporter-list')) {
				return redirect('admin/transporter/list');
			}
			elseif(Auth::user()->can('company-list')) {
				return redirect('admin/company/list');
			}
			elseif(Auth::user()->can('forwarder-list')) {
				return redirect('admin/forwarder/list');
			}
			elseif(Auth::user()->can('employee-list')) {
				return redirect('admin/employee/list');
			}
			elseif(Auth::user()->can('roleuser-list')) {
				return redirect('admin/roleuser/list');
			}
			elseif(Auth::user()->can('roles-list')) {
				return redirect('admin/roles/list');
			}
			elseif(Auth::user()->can('driver-list')) {
				return redirect('admin/driver/list');
			}
			elseif(Auth::user()->can('warehouse-list')) {
				return redirect('admin/warehouse/list');
			}
		   
		}      	

        } else {

			return redirect()->back()->withInput()->withError("Username & Password are not match");

        }
	
	}


	public function Logout(Request $request) {
		
		 Auth::logout();
		Session::flush();
		 
		return redirect()->route('login');
	}

	public function Privacy(Request $Request)
	{

		return view('web.privecypolicy');

	}




}