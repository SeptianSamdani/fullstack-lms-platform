<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter as RateLimiterFacade;
use Illuminate\Auth\Notifications\ResetPassword;


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
        RateLimiterFacade::for('auth', fn (Request $r) => Limit::perMinute(5)->by($r->ip()));
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return rtrim(env('FRONTEND_URL', 'http://localhost:5173'), '/')
                . '/reset-password?token=' . $token . '&email=' . urlencode($notifiable->email);
        });
    }
}