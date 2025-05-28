<?php

namespace App\Providers;

//use App\Channels\BaleChannel;
use App\Channels\BaleChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Notification::extend('bale', function ($app) {
            return new BaleChannel();
        });
    }
}
