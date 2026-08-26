<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Pegawai;

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

        // Self-healing database initialization for cloud environments (Railway / container)
        try {
            if (Schema::hasTable('users')) {
                $admin = User::where('email', 'admin@dispanperta.sidoarjo.go.id')->first();
                if (!$admin) {
                    User::create([
                        'name' => 'Admin Kepegawaian',
                        'email' => 'admin@dispanperta.sidoarjo.go.id',
                        'password' => 'password',
                        'email_verified_at' => now(),
                    ]);
                }
            }

            if (Schema::hasTable('pegawai') && Pegawai::count() === 0) {
                Artisan::call('db:seed', ['--force' => true]);
            }
        } catch (\Throwable $e) {
            // Ignore during initial build/migrations
        }
    }
}
