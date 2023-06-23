<?php

namespace App\Mail\admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateApproveMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $name,$certificate_name,$role)
    {
        $this->email = $email;
        $this->name = $name;
        $this->certificate_name = $certificate_name;
        $this->role = $role;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Certificate Added')->from('no-reply@sakani.com', 'Sakani')->view('emails.certificate_email')
            ->with([
                'name'=> $this->name,
                'email' => $this->email,
                'certificate_name' => $this->certificate_name,
                'role' => $this->role,
            ]) ;
    }
}
