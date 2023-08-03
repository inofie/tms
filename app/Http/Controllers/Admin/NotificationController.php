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
        // dd(request()->ajax());
        // $data = Notification::where('notification_to',Auth::user()->id)->orderBy('id','desc')->take(30)->get();
        // // dd($data);
        // $role = User::where('id',Auth::user()->id)->first();
        // foreach ($data as $key => $value) {
        //     $data[$key]=$value;
        //     //  $noti_from = User::withTrashed()->where('id',$value->notification_from);
        //      $shipmentno = Shipment::withTrashed()->findorfail($value->shipment_id);
        //     //  $data[$key]['user_name_from']=$noti_from['username'];
        //      $data[$key]['myid'] = $shipmentno['myid'];
        // }

    	return view('admin.notificationlist');
    }

    public function getNotification(Request $Request)
    {
        $start = $Request->start;
        $length = $Request->length;
        $total = Notification::where('notification_to',Auth::user()->id)->count();
        $data = Notification::leftJoin('shipment','shipment.id','=','notification.shipment_id')
        ->leftJoin('users','users.id','=','notification.notification_to')
        ->select('notification.*',\DB::raw('CONCAT(shipment.myid,"_",users.role) AS role'))
        ->where('notification_to',Auth::user()->id)
        ->orderBy('id','desc')
        ->skip($start)
        ->take($length)
        ->get();


        $role = User::where('id',Auth::user()->id)->first();
        // foreach ($data as $key => $value) {
        //     $data[$key]=$value;
        //      $shipmentno = Shipment::withTrashed()->findorfail($value->shipment_id);
        //      $data[$key]['myid'] = $shipmentno['myid'];
        // }
        return ['data'=>$data,'draw'=>$Request['draw'] ,'recordsTotal'=>$total,'recordsFiltered'=>$total];
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