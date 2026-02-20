<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp; // Kode OTP 6 karakter 
    }

    public function build()
    {
        return $this->subject('Kode OTP Verifikasi Anda')
                    ->html("<h3>Kode OTP Anda adalah: <b>{$this->otp}</b></h3>");
    }
}