<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // added this

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
        // --- ADD THIS BLOCK OF CODE ---
        if (config('app.ngrok_url')) {
            URL::forceRootUrl(config('app.ngrok_url'));
        }
        // --- END OF BLOCK ---
    }
}
