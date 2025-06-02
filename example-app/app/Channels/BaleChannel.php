<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;


class BaleChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toBale($notifiable);

        // گرفتن توکن با cache (همچنان بمونه)
        $token = Cache::remember('tokenForOTP', now()->addSeconds((int)env('OTP_TIME_EXPIRATION')), function () {
            $response = Http::asForm()->post('http://safir.bale.ai/api/v2/auth/token', [
                'grant_type' => 'client_credentials',
                'client_secret' => env('BALE_CLIENT_SECRET'),
                'scope' => 'read',
                'client_id' => env('BALE_CLIENT_ID')
            ]);
            return $response->json('access_token');
        });

        // ذخیره OTP در Redis با کلید شماره موبایل و زمان انقضا 20 دقیقه
        Redis::setex((string)$notifiable->phoneNumber, 1200, $data['otp']);

        // ارسال OTP به API Bale
        Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('https://safir.bale.ai/api/v2/send_otp', [
            'phone' => $data['phone'],
            'otp' => $data['otp']
        ]);
    }
}


