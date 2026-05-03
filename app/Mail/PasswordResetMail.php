<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    // Constructor to pass the otp
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    // Build the email
    public function build()
    {
        return $this->view('backend.layout.emails.password_reset')
            ->with(['otp' => $this->otp])
            ->subject('Password Reset Request OTP');
    }
}






