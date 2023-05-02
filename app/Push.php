<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
	
    public function sendWebNotification($tokens, $shipment_id)
    {

        
          
        $data = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => "Testing 123",
                "body" => "Push Notification From Website.",

            ],
            "data"=>["temp"=>$shipment_id], 

        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key='.getenv('PUSH_SERVER_KEY'),
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        

        // Close connection
        curl_close($ch);

        // FCM response
        //dd($result);        
    }
}