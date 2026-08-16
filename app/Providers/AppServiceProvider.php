<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($user && ($user->role === 'admin' || strtolower($user->role ?? '') === 'admin' || strtolower($user->name ?? '') === 'admin' || $user->hasRole('admin'))) {
                return true;
            }
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            $agent = request()->userAgent() ?: 'Unknown';
            $device = 'Unknown';
            if (stripos($agent, 'Windows') !== false) {
                $device = 'Windows';
            } elseif (stripos($agent, 'Android') !== false) {
                $device = 'Android';
            } elseif (stripos($agent, 'iPhone') !== false || stripos($agent, 'iPad') !== false) {
                $device = 'iOS';
            } elseif (stripos($agent, 'Macintosh') !== false) {
                $device = 'MacOS';
            } elseif (stripos($agent, 'Linux') !== false) {
                $device = 'Linux';
            }

            \App\Models\LoginLog::create([
                'type' => 'Login',
                'user_name' => $event->user->name ?? 'Demo',
                'device' => $device,
                'ip' => request()->ip() ?: '127.0.0.1',
                'time' => now(),
            ]);
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            if (!$event->user) return;
            $agent = request()->userAgent() ?: 'Unknown';
            $device = 'Unknown';
            if (stripos($agent, 'Windows') !== false) {
                $device = 'Windows';
            } elseif (stripos($agent, 'Android') !== false) {
                $device = 'Android';
            } elseif (stripos($agent, 'iPhone') !== false || stripos($agent, 'iPad') !== false) {
                $device = 'iOS';
            } elseif (stripos($agent, 'Macintosh') !== false) {
                $device = 'MacOS';
            } elseif (stripos($agent, 'Linux') !== false) {
                $device = 'Linux';
            }

            \App\Models\LoginLog::create([
                'type' => 'Logout',
                'user_name' => $event->user->name ?? 'Demo',
                'device' => $device,
                'ip' => request()->ip() ?: '127.0.0.1',
                'time' => now(),
            ]);
        });
    }
}
