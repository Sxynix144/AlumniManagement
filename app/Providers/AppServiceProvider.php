<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS on production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // ✅ Register verification email listener
        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class,
        );
    }
}