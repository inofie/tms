<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Config;

class MailController extends Controller {


   //"https://www.google.com/accounts/DisplayUnlockCaptcha"


   public function basic_email() {

      Config::set('mail.driver', 'smtp');
      Config::set('mail.host', 'mail.ssiwebsql.com');
      Config::set('mail.port', '25');
      Config::set('mail.username', 'noreplay@yoginitransport.com');
      Config::set('mail.password', 'k8Iz0c4*');
      Config::set('mail.encryption', '');

      $data = array('name'=>"Test Mail");
      
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('tanmay.technoscatter@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('noreplay@yoginitransport.com','Tanmay Patel');
      });
      echo "Basic Email Sent. Check your inbox.";
   }


   public function html_email() {

      $yogini_username = env('YOGINI_MAIL_USERNAME');
      $yogini_password = env('YOGINI_MAIL_PASSWORD');
      //Config::set('mail.username', $yogini_username);
      //Config::set('mail.password', $yogini_password);
      Config::set('mail.username', 'noreply.yoginitransport@gmail.com');
      Config::set('mail.password', 'ylrfhzobacxpegqg');
   
      $data = array('name'=>"HTML Mail");
      Mail::send('testmail', $data, function($message) {
         $message->to('tanmay.technoscatter@gmail.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
         $message->from('info@gmail.com','Tanmay Patel');
      });
      echo "HTML Email Sent. Check your inbox.";
   }


   public function attachment_email() {
      Config::set('mail.username', 'noreply.yoginitransport@gmail.com');
      Config::set('mail.password', 'ylrfhzobacxpegqg');
      
      $data = array('name'=>"Virat Gandhi");

      Mail::send('testmail', $data, function($message) {
         $message->to('tanmay.technoscatter@gmail.com', 'Tanmay Patel')->subject
            ('Laravel Testing Mail with Attachment');
         $message->attach( public_path('/uploads').'/Tanmay.jpg');
        // $message->attach('C:\Users\Technoscatter\OneDrive\Pictures\59.jpg');
         $message->from('noreplay@ssi.com','SSI Transport');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }

   
}
