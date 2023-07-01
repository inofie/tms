<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\User;
use App\Helper\GlobalHelper;
use App\Notification;
use DB;
use App\Shipment;
use App\Shipment_Driver;
use App\Shipment_Summary;
use App\Shipment_Transporter;
use App\Transporter;
use App\Truck;
use App\Warehouse;
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

class ChangeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:ChangeStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ChangeStatus';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
                $notification->save();
				$notification_id = $notification->id;
				if($to_user->device_type == 'ios'){
                    GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                }else{
                    GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
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
                $message= $data["shipment_no"].' '."is".' '.$getStatus['name'].' ' ."by".' '.$user['username'];
				$notification->title = $title;
                $notification->message = $message;
                $notification->notification_type = '2';
                $notification->save();
				$notification_id = $notification->id;
				if($to_user->device_type == 'ios'){
                    GlobalHelper::sendFCMIOS($title, $message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                }else{
                    GlobalHelper::sendFCM($notification->title, $notification->message, $to_user->device_token,$notification->notification_type,$id,$notification_id);
                    }
            }
		}
		return 1;
    }
}