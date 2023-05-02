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
			
			} else if(Auth::user()->role == 'forwarder') {

				return redirect()->route('forwarderdashboard');
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

		if($userdata->role == "transporter"){

		return redirect()->back()->withError("You have no permission for login.\n\r Please don't try again.");	

		}


		
		$credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {

        		if(Auth::user()->role == 'admin') {

        				return redirect()->route('admindashboard'); 
        			
        		} elseif(Auth::user()->role == 'employee') {

        				return redirect()->route('admindashboard'); 
        			
        		} elseif(Auth::user()->role == 'forwarder') {

        				return redirect()->route('forwarderdashboard'); 
        			
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