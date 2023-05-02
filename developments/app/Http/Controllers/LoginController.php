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

				return ;//redirect()->route('employeedashboard');
			
			} else if(Auth::user()->role == 'transporter') {

				return ;//redirect()->route('signin');

			} else if(Auth::user()->role == 'forwarder') {

				return ;//redirect()->route('signin');
			}

		} else {

		
			return view('login');
		}
		
	}


	public function LoginCheck(request $request)
	
	{

		$check = User::where('username',$request->username)->first();

		//dd(Hash::make($request->password));

		$f_check = (array)$check;

		

		if(count($f_check) == 0 ) { 

		 return redirect()->back()->withInput()->withError("This Username is not registed in Our System.");
		}
		
		$credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {

        		if($check->role == 'admin') {

        				return redirect()->route('admindashboard'); 
        			
        		}                 
            	

        } else {

			return redirect()->back()->withInput()->withError("Username & Password Are Not Match");

        }
	
	}


	public function Logout(Request $request) {
		
		 Auth::logout();
		Session::flush();
		 
		return redirect()->route('login');
	}




}