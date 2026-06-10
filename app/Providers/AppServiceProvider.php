<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Extensions\FirebaseUserProvider;
use App\Services\FirebaseService;

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
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Auth::provider('firebase', function ($app, array $config) {
            return new FirebaseUserProvider($app->make(FirebaseService::class));
        });

        \Illuminate\Support\Facades\Mail::extend('resend-http', function (array $config = []) {
            return new \App\Mail\Transport\ResendHttpTransport(
                config('services.resend.key')
            );
        });
    }
}
