<?php

namespace App\Mail\admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($newuser)
    {
        $this->newuser = $newuser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sent = $this->subject('Registration Verification')->from('sakaniapp2022@gmail.com', 'Sakani')->view('emails.welcome_email')
            ->with([
                'name'=> $this->newuser->name,
                'email' => $this->newuser->email,
                'user_mobile' => $this->newuser->user_mobile,
                'id' => $this->newuser->id
            ]);
        return Mail::to($this->newuser->email)->send($sent);
    }
}
