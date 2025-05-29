<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BaleChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toBale($notifiable);


        $token = Cache::remember('tokenForOTP', now()->addSeconds((int)env('OTP_TIME_EXPIRATION')), function () {
            $response = Http::asForm()->post('http://safir.bale.ai/api/v2/auth/token', [
                'grant_type' => 'client_credentials',
                'client_secret' => env('BALE_CLIENT_SECRET'),
                'scope' => 'read',
                'client_id' => env('BALE_CLIENT_ID')
            ]);
            return $response->json('access_token');
        });

        Cache::put(((string)$notifiable->phoneNumber),$data['otp'], Carbon::now()->addSeconds(1200));

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('https://safir.bale.ai/api/v2/send_otp', [
            'phone' => $data['phone'],
            'otp' => $data['otp']
        ]);
    }
}
