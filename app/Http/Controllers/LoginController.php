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

		if($check == 0 ) { 

		 return redirect()->back()->withInput()->withError("This Username is not registed in Our System.");
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

        				return redirect()->route('forwarderdashboard'); 
        			
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