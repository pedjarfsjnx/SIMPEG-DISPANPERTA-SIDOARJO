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
        // Force HTTPS only for remote cloud deployments (e.g. Railway) or when behind an SSL reverse proxy.
        // Never force HTTPS for localhost or private local network (LAN / Wi-Fi) IPs.
        $host = request()->getHost();
        $isLocalHost = in_array($host, ['127.0.0.1', 'localhost', '::1'])
            || str_ends_with($host, '.test')
            || str_ends_with($host, '.local');
        $isPrivateIp = filter_var($host, FILTER_VALIDATE_IP)
            && !filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
        $isForwardedHttps = request()->header('x-forwarded-proto') === 'https' || request()->isSecure();

        if (env('FORCE_HTTPS', false) || (! $isLocalHost && ! $isPrivateIp && $isForwardedHttps)) {
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

            if (Schema::hasTable('pegawai') && Pegawai::count() < 149) {
                Artisan::call('db:seed', ['--force' => true]);
            }
        } catch (\Throwable $e) {
            // Ignore during initial build/migrations
        }
    }
}
