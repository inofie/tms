<?php

namespace App\Mail\admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateApproveAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$certificate_name,$property)
    {
        $this->email = $email;
        $this->certificate_name = $certificate_name;
        $this->property = $property;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Certificate Approved')->from('no-reply@sakani.com', 'Sakani')->view('emails.property_email')
            ->with([
                'email' => $this->email,
                'certificate_name' => $this->certificate_name,
                'property' => $this->property,
            ]) ;
    }
}
