<?php

namespace App\Http\Controllers\API;


use App\Helper\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Http\Response\APIResponse;
use App\Notifications\ForgotPassword;
use App\OauthAccessToken;
use App\User;
use File;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Lang;
use Lcobucci\JWT\Parser;
use Str;
use URL;
use Validator;
use Mail;
use App\Notification;
use carbon;
use App\Account;
use App\Cargostatus;
use App\Company;
use App\Driver;
use App\Employee;
use App\Expense;
use App\Forwarder;
use App\Invoice;
use App\Shipment;
use App\Shipment_Driver;
use App\Shipment_Summary;
use App\Shipment_Transporter;
use App\Transporter;
use App\Truck;

class NotificationController extends Controller
{
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
    public function notificationList(Request $request)
    {
        // return 1;
        $data = $request->all();
        try{
            $check = $this->checkversion($request->version);
			if ($check == 1) {
				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
            $rules = array(
                'page' => 'numeric',
                'offset' => 'numeric',
            );
            $messages = [
            ];
            $validator = Validator::make($data, $rules,$messages);
            if($validator->fails()) {
                return $this->APIResponse->respondValidationError(__($validator->errors()->first()));
            }else
            {

            if (isset($data['page']) && ($data['offset'])) {
              $page = 1;
              $perPage = 10;
              if(isset($data['page']) && !empty($data['page'])){
                  $page = $data['page'];
              }
              if(isset($data['offset']) && !empty($data['offset'])){
                  $perPage = $data['offset'];
              }
              $offset = ($page - 1) * $perPage;
              $resultList=Notification::where('notification_to',$request['user_id'])->orderBy('id','desc');
              $resultList=$resultList->paginate($perPage);
              foreach ($resultList as $key => $value) {
                $shipmentno = Shipment::where('id',$value['shipment_id'])->first();
                if($shipmentno){
                $resultList[$key]['shipment_no']=$shipmentno->shipment_no;
                }
                $resultList[$key]['date']=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value['created_at'])->format('Y-m-d');
                $lastSeen = GlobalHelper::getTimeAgo($value['created_at']);
                $resultList[$key]['ago'] = $lastSeen;
            }
            $otherData = [];
            $otherData['unread_count']=Notification::where('notification_to',$request['user_id'])->where('read_status','unread')->count();
              if(!empty($resultList)){
                  $message='Notification List';
                  $data=$resultList;
                  return $this->APIResponse->successWithPagination($message,$data,$otherData);
              }
              else {
                return $this->APIResponse->respondNotFound(__('No Record Found'));
            }
            }
            
        else{
            $resultList=Notification::where('notification_to',$request['user_id'])->orderBy('id','desc')->get();
            foreach ($resultList as $key => $value) {
                $shipmentno = Shipment::where('id',$value['shipment_id'])->first();
                if($shipmentno){
                $resultList[$key]['shipment_no']=$shipmentno->shipment_no;
                }
                $resultList[$key]['date']=Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value['created_at'])->format('Y-m-d');
                $lastSeen = GlobalHelper::getTimeAgo($value['created_at']);
                $resultList[$key]['ago'] = $lastSeen;
            }
            $otherData = [];
            $otherData['unread_count']=Notification::where('notification_to',$request['user_id'])->where('read_status','unread')->count();
            return response()->json(['status' => 'success', 'message' => 'Notification List.', 'data' => $resultList, 'code' => '200'], 200);
        }
    }      
    }catch (\Exception $e) {
           dd($e);
              return $this->APIResponse->handleAndResponseException($e);
          }
    }

    public function readAllNotifications(Request $request){
        try{
            
        
            $check = $this->checkversion($request->version);
			if ($check == 1) {
				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
            $notification = Notification::where('notification_to', $request['user_id'])->update(['read_status' => 'read']);
            
            return response()->json(['status' => 'success', 'message' => 'All Notifications marked as read.', 'data' =>json_decode('{}'), 'code' => '200'], 200);
        }catch (\Exception $e) {
          return $this->APIResponse->handleAndResponseException($e);
        }
    }

    public function deleteSingleNotification(Request $request) {
        $data = $request->json()->get('data');
        try{
            $check = $this->checkversion($request->version);
			if ($check == 1) {
				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
            if(empty($data)){
                return $this->APIResponse->respondNotFound(__('Data key not found or Empty'));
            }else{
                $rules = array(
                    'id' => 'required',
                );
                $messages = [
                ];
                $validator = Validator::make($data,$rules,$messages);
                if($validator->fails()){
                    return $this->APIResponse->respondValidationError(__($validator->errors()->first()));
                }else{
                    $notification = Notification::find($data['id']);
                    if($notification){
                        $notification->delete();
                        return $this->APIResponse->respondWithMessage('Notification delete');
                        
                    }else{
                        return $this->APIResponse->respondNotFound('Oops! No Notification Found');
                    }
                }
            }
        }catch(\Exception $e){
            return $this->APIResponse->handleAndResponseException($e);
        }
    }

    public function deleteAllNotifications(Request $request){
        try{
            $check = $this->checkversion($request->version);
			if ($check == 1) {
				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
            $checkNotification = Notification::where('notification_to', $request['user_id'])->get();
            if(count($checkNotification) != 0){
                $notification = Notification::where('notification_to', $request['user_id'])->delete();
                return $this->APIResponse->respondWithMessage('Notification delete successfully');
            }
            else{
                return $this->APIResponse->respondNotFound('Oops! No Notification Found');
            }
        }catch (\Exception $e) {
          return $this->APIResponse->handleAndResponseException($e);
        }
    }

    public function readSingleNotifications(Request $request) {
        $data = $request->all();
        try{
            $check = $this->checkversion($request->version);
			if ($check == 1) {
				return response()->json(['status' => 'failed', 'message' => 'Please update this application.', 'data' => json_decode('{}'), 'code' => '500'], 200);
			}
            if(empty($data)){
                return $this->APIResponse->respondNotFound(__('Data key not found or Empty'));
            }else{
                $rules = array(
                    'id' => 'required',
                );
                $messages = [
                ];
                $validator = Validator::make($data,$rules,$messages);
                if($validator->fails()){
                    return $this->APIResponse->respondValidationError(__($validator->errors()->first()));
                }else{
                    $notification = Notification::find($data['id']);
                    if($notification){
                        $notification->read_status = 'read';
                        $notification->save();
                        
                        return response()->json(['status' => 'success', 'message' => 'Notification marked as read.', 'data' => json_decode('{}'), 'code' => '200'], 200);
                    }else{
                        return $this->APIResponse->respondNotFound('Oops! No Notification Found');
                    }
                }
            }
        }catch(\Exception $e){
            return $this->APIResponse->handleAndResponseException($e);
        }
    }
}
