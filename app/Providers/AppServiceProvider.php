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

        // Force Firebase requests to use IPv4 to avoid OpenSSL SSL_connect syscall errors on Railway
        $middlewares = config('firebase.projects.app.http_client_options.guzzle_middlewares', []);
        $middlewares[] = [
            'middleware' => new \App\Http\Middleware\GuzzleForceIpv4Middleware(),
            'name' => 'force_ipv4',
        ];
        config(['firebase.projects.app.http_client_options.guzzle_middlewares' => $middlewares]);
    }
}
