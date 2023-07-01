<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
use App\Notification;
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;
use Config;


class NotificationController extends Controller
{

	public function __construct()
    {

    }

    public function List(Request $Request)
    {

        $data = Notification::where('notification_to',Auth::user()->id)->get();
        $role = User::where('id',Auth::user()->id)->first();
        
        // $data= array();
        foreach ($data as $key => $value) {
            $data[$key]=$value;
            $noti_from = User::withTrashed()->findorfail($value->notification_from);
            $noti_to = User::withTrashed()->findorfail($value->notification_to);
            $shipmentno = Shipment::withTrashed()->findorfail($value->shipment_id);
            $data[$key]->user_name_from=$noti_from->username;
            $data[$key]->user_name_to=$noti_to->username;
            $data[$key]->myid = $shipmentno->myid;
        }
    	return view('admin.notificationlist',compact('data','role'));
    }
    //  public function Delete(Request $Request)
    // {

    //     $data = Company::where('myid',$Request->id)->first();
    //     $data->deleted_by = Auth::user()->id;
    //     $data->save();

    //     $user = User::findorfail($data->user_id);
    //     $user->deleted_by = Auth::user()->id;
    //     $user->save();

    //     $data->delete();
    //     $user->delete();

    //     return redirect()->route('companylist')->with('success','Company Deleted Successfully.');



    // }
}