<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SendOTPViaBale extends Notification
{
    use Queueable;

    protected $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['bale'];
    }

    public function toBale($notifiable)
    {
        return [
            'phone' => '98' . $notifiable->phoneNumber,
            'otp' => $this->otp,
        ];
    }
}
