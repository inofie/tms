<?php
namespace App\Helper;

use Auth;
use App\Permission;
use Illuminate\Support\Facades\DB;
use App\Helper\GlobalHelper;
use DateTime;
use DateInterval;
use DatePeriod;
use App\User;
use App\Order;
use App\Shipment;
use App\Account;
use App\Company;
use App\Transporter;
use App\Forwarder;
use App\Notification;
use URL;
use Twilio;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class GlobalHelper
{
  /**
  * Developed By : Krunal
  * Date         :
  * Description  : Time ago
  */
  public static function humanTiming($time){
    $time = time() - strtotime($time); // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
      31536000 => 'year',
      2592000 => 'month',
      604800 => 'week',
      86400 => 'day',
      3600 => 'hour',
      60 => 'minute',
      1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
      if ($time < $unit) continue;
      $numberOfUnits = floor($time / $unit);
      return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
  }

  /**
  * Developed By : Kaushal Adhiya
  * Date         : 19-11-2019
  * Description  : removeNull
  */
  public static function removeNull($array){
    foreach ($array as $key => $value){
      if(is_array($value)){
        $array[$key] = GlobalHelper::removeNull($value);
      }else{
        if (is_null($value))
        $array[$key] = "";
      }
    }
    return $array;
  }

  public static function removeNullMultiArray($model){
    foreach($model as $rsKey => $rs){
      foreach($rs as $key => $value){
        if(is_null($value)){
          $model[$rsKey][$key] = "";
        }
      }
    }
    return $model;
  }

  /**
  * Developed By : Ajarudin Gugna
  * Date         :
  * Description  : Get formated date
  */
  public static function getFormattedDate($date)
  {
    if(!empty($date)){
      $date = date_create($date);
      return date_format($date, "d-M-Y");
    }
    else {
      return "";
    }
  }

  public static function getFormattedDatefilter($date)
  {
    if(!empty($date)){
      $date = date_create($date);
      return date_format($date, "d/m/Y");
    }
    else {
      return "";
    }
  }

  /**
  * Developed By : Ajarudin Gugna
  * Date         :
  * Description  : Get user by id
  */
  public static function getUserById($id)
  {
    $user = User::where('id','=',$id)
    ->first();
    return $user;
  }


  /**
  * Developed By : Krunal
  * Date         : 25-8-17
  * Description  : generateRandomNumber
  */
  public static function generateRandomNumber($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  /**
  * Developed By : Jignasa
  * Date         :
  * Description  : sentence teaser
  * this function will cut the string by how many words you want
  */
  public static function word_teaser($string, $count){
    $original_string = $string;
    $words = explode(' ', $original_string);

    if (count($words) > $count){
      $words = array_slice($words, 0, $count);
      $string = implode(' ', $words);
    }

    return $string.'...';
  }

  /**
  * Developed By : Jignasa
  * Date         :
  * Description  : Get user profile image by id
  */
  public static function getUserImageById($id)
  {
    $user = User::select('profile_image')->where('id','=',$id)->first();
    if($user && $user->profile_image){
      return URL::asset('/resources/uploads/profile').'/'.$user->profile_image;
    }else{
      return URL::asset('/resources/uploads/profile/default.jpg');
    }
  }

  /**
  * Description  : Use to convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc
  */
  public static function number_format_short( $n ) {

    if ($n >= 0 && $n < 1000) {
      // 1 - 999
      $n_format = floor($n);
      $suffix = '';
    } else if ($n >= 1000 && $n < 10000) {
      // 1k-999k
      $n_format = floor($n);
      $suffix = '';
    }else if ($n >= 10000 && $n < 1000000) {
      // 1k-999k
      $n_format = floor($n / 1000);
      $suffix = 'K+';
    } else if ($n >= 1000000 && $n < 1000000000) {
      // 1m-999m
      $n_format = floor($n / 1000000);
      $suffix = 'M+';
    } else if ($n >= 1000000000 && $n < 1000000000000) {
      // 1b-999b
      $n_format = floor($n / 1000000000);
      $suffix = 'B+';
    } else if ($n >= 1000000000000) {
      // 1t+
      $n_format = floor($n / 1000000000000);
      $suffix = 'T+';
    }

    return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
  }

  /**
     * Developed By :
     * Date         :
     * Description  : Send FCM For android
     */
    public static function sendFCM($title, $message, $target = 0, $notification_type,$id=0,$notification_id=0,$data = NULL)
    {
      // dd($title);
      // fVOK2RqfQOer-_jzbuq7G1:APA91bHxYxV2NABSqUvow4huv9zkBPIYGGDoH1aOQVWZ_Czb7RE1C5rB9x1tUzb1XM_bAVMUDtEryI3R7Jk6fACiF-FRp6qB_o-hVgCU7CHj6v27ROVqzNrTUqs6yJ-rdh9AJwUhzdwz

      // AIzaSyCymK3XGg7M7afuIl45F9aihZ4feG5YNtU


        //$baseurl="http://".url();
        //FCM api URL
        //dd($title);
        $url = 'https://fcm.googleapis.com/fcm/send';
        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAzwNS6xw:APA91bHgXazooSjv_NvM7mlwk-_qaF809j1l3LThNPdRk0hNmlBdDLwQEr7QAYLpq5mkV4XQ8W9vEvq2WkMyUGTcVsgnVzvlft9NzqwW3x3yszWNAnfObNbp7grqiijLhKWgEd1m5fF8';
        // dd($server_key);

        $fields = array();
        $fields['content_available'] = true;
        $fields['data'] = array();
        $fields['notification']['body'] = $message;
        $fields['notification']['title'] = $title;
        $fields['data']['notification_type'] = $notification_type;
        $fields['data']['id'] = $id;
        $fields['data']['notification_id'] = $notification_id;
        $fields['data']['data'] = $data;

        $fields['data']['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
        $fields['data']['sound'] = 'default';

        // if(is_array($target)){
        // $fields['registration_ids'] = $target;
        // }else{
        $fields['to'] = (string)$target;
        // }
        $fields['priority'] = "high";
        // dd($fields);
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false)
        {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        //dd($result);
        return $result;
    }

    public static function sendFCMIOS($title, $message, $target = 0, $notification_type,$id=0,$notification_id=0,$data = NULL)
    {
        //$baseurl="http://".url();
        //FCM api URL
        $url = 'https://fcm.googleapis.com/fcm/send';
        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAzwNS6xw:APA91bHgXazooSjv_NvM7mlwk-_qaF809j1l3LThNPdRk0hNmlBdDLwQEr7QAYLpq5mkV4XQ8W9vEvq2WkMyUGTcVsgnVzvlft9NzqwW3x3yszWNAnfObNbp7grqiijLhKWgEd1m5fF8';

        $fields = array();
        $fields['notification'] = array();
        $fields['notification']['body'] = $message;
        $fields['notification']['title'] = $title;
        $fields['notification']['notification_type'] = $notification_type;
        $fields['notification']['id'] = $id;
        $fields['notification']['notification_id'] = $notification_id;
        $fields['notification']['data'] = $data;
        // if($image != ""){
        //   $fields['notification']['image'] = $image;
        // }
        $fields['notification']['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
        $fields['notification']['sound'] = 'default';
        $fields['notification']['aps']['alert']['body'] = $message;
        $fields['notification']['aps']['alert']['title'] = $title;

        $fields['content_available'] = true;
        $fields['data']['aps']['alert']['body'] = $message;
        $fields['data']['aps']['alert']['title'] = $title;
        $fields['data'] = array();
        $fields['data']['body'] = $message;
        $fields['data']['title'] = $title;
        $fields['data']['notification_type'] = $notification_type;
        $fields['data']['id'] = $id;
        $fields['data']['notification_id'] = $notification_id;
        $fields['data']['data'] = $data;

        $fields['data']['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
        $fields['data']['sound'] = 'default';
        // if(is_array($target)){
        // $fields['registration_ids'] = $target;
        // }else{
        $fields['to'] = $target;
        // }
        $fields['priority'] = "high";
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false)
        {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        // dd($result);
        return $result;
    }

    /**
     * Developed By :
     * Date         :
     * Description  : Send GCM for iphone
     */
    public static function sendGCM($title, $message, $deviceToken, $app_type, $notification_type, $detail)
    {

        // Put your device token here (without spaces):
        // $title = 'Hello';
        // $app_type = 'debug';
        // $deviceToken = '243540a20a1d934f7cd0fac714a45f9173eca6dfda978d116d14a7250d04f004';
        // Put your private key's passphrase here:
        $passphrase = '';

        // Put your alert message here:
        // $message = 'My cQpon push notification!';
        // $ctx = stream_context_create();
        // stream_context_set_option($ctx, 'ssl', 'local_cert', 'tys_debug_Push_certificate.pem');
        // stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        if ($app_type == 'debug')
        {
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', 'NextTec_PUSH.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
            $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        }
        else
        {
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', 'NextTec_PUSH.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
            $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        }

        // if (!$fp)
        // exit("Failed to connect: $err $errstr" . PHP_EOL);
        //
        // echo 'Connected to APNS' . PHP_EOL;
        // Create the payload body
        $body['aps'] = array(
            'alert' => array(
                'title' => $title,
                'body' => $message,
                'notification_type' => $notification_type,
                'detail' => $detail,
            ) ,
            'mutable-content' => 1,
            'sound' => 'default',
            'content-available' => 1
        );

        //$body['image'] = $image;
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        if (strlen($deviceToken) == '64')
        {
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        }
        else
        {
            $msg = chr(0) . pack('H*', str_replace(' ', '', sprintf('%u', CRC32($deviceToken)))) . pack('n', strlen($payload)) . $payload;
        }
        //$msg = chr(0) . pack('H*', str_replace(' ', '', sprintf('%u', CRC32($deviceToken)))) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        // if (!$result)
        // echo 'Message not delivered' . PHP_EOL;
        // else
        // echo 'Message successfully delivered' . PHP_EOL;
        // Close the connection to the server
        fclose($fp);
    }


  public static function getPermissionByCategory($category){
      $getPermissions = Permission::where("category",$category)->where('status','1')->get();
      return $getPermissions;
  }
  public static function getPermissionByCategoryapp($role_id){
    $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role_id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    return $rolePermissions;
}
public static function getshippername($employees){
 
  	  $shipment_list = explode(',',$employees);
			$shipdata = Shipment::withTrashed()->wherein("shipment_no", $shipment_list)->get();
			
			$d_list = "";
			foreach($shipdata as $key2 => $value2){
			if($value2->imports == 1){
			$shippername = $value2->consignee;
			}
			else{
			$shippername = $value2->consignor;
			}
			if($key2 == 0) {
			$d_list = $d_list."".$shippername;	
			}
			else{
			$d_list = $d_list.", ".$shippername;	
			}
		}
  return $d_list;
}
public static function getfromname($employees){
$data1 = Account::where('id',$employees)->get();
//dd($data1);

foreach ($data1 as $key => $value) {

			if($value->from_company != "" && $value->from_company != null){
				$company_data = Company::withTrashed()->findorfail($value->from_company);
				$datas= $company_data->name;
			} else if($value->from_transporter != "" && $value->from_transporter != null){
				$transporter_data = Transporter::withTrashed()->findorfail($value->from_transporter);
				$datas= $transporter_data->name;
			} else if($value->from_forwarder != "" && $value->from_forwarder != null){
				$forwarder_data = Forwarder::withTrashed()->findorfail($value->from_forwarder);
				$datas= $forwarder_data->name;
			}else{
        $datas= "";
      }
}
return $datas;
}

public static function gettoname($employees){
  $data1 = Account::where('id',$employees)->get();
  
  foreach ($data1 as $key => $value) {
  
    if($value->to_company != "" && $value->to_company != null){
      		$company_data = Company::withTrashed()->findorfail($value->to_company);
      		$datav= $company_data->name;
      	} else if($value->to_transporter != "" && $value->to_transporter != null){
      		$transporter_data = Transporter::withTrashed()->findorfail($value->to_transporter);
      		$datav= $transporter_data->name;
      	} else if($value->to_forwarder != "" && $value->to_forwarder != null){
      		$forwarder_data = Forwarder::withTrashed()->findorfail($value->to_forwarder);
      		$datav= $forwarder_data->name;
      	}else{
          $datav= "";
        }
  }
  return $datav;
  }
  public static function gettype($employees){
    $data1 = Account::where('id',$employees)->get();
    
    foreach ($data1 as $key => $value) {
      if($value->credit != "" && $value->credit != null){
        		$type = 'Credit';
        	} else if($value->debit != "" && $value->debit != null){
        		$type = 'Debit';
        	}
          else{
            $type = '';
          }
    }
    
    return $type;
  }
  public static function getamount($employees){
    $data1 = Account::where('id',$employees)->get();
    
    foreach ($data1 as $key => $value) {
      if($value->credit != "" && $value->credit != null){
          $amount = $value->credit;
        	} else if($value->debit != "" && $value->debit != null){
        		$amount = $value->debit;
        	}
          else{
            $amount = '';
          } 
    }
    return $amount;
  }
 // Add data in fire base
  public static function firebaseSaveNotification($title, $message,$reciver_id,$sender_id) {
    $serviceAccount = ServiceAccount::fromJsonFile(env('FIREBASE_JSON_FILE_LOCATION'));
    $firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->withDatabaseUri(env('FIREBASE_DATABASEURL'))
    ->create();

    $database = $firebase->getDatabase();

    $newPost = $database
    ->getReference('ewt')
    ->push([
      'title' => (string) $title,
      'message' => $message,
      'reciver_id' => (string) $reciver_id,
      'sender_id' => (string) $sender_id
    ]);
    // dd($newPost->getvalue());
    return $newPost->getvalue();
  }

  public static function orderDataFormat($id)
  {
    $order = Order::where('order_id',$id)->with('details.productdata','details.servicedata','businessData','partnerData','addressData.cityData','addressData.islandData','clientData')->withcount('details')->first();
    return $order;
  }

  public static function getTimeAgo($carbonObject) {
    return
        $carbonObject->diffForHumans(null, true);

}
}
