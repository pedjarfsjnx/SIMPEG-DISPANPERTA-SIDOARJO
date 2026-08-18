<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Unconditionally force HTTPS for any remote/cloud deployment
        $host = request()->getHost();
        if ($host !== '127.0.0.1' && $host !== 'localhost' && $host !== '::1') {
            URL::forceScheme('https');
            if (isset($_SERVER)) {
                $_SERVER['HTTPS'] = 'on';
            }
        }
    }
}