<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

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
        Paginator::useBootstrap();
        // When running on production (or behind a proxy like Render), force HTTPS
        // This ensures generated URLs and form actions use the https scheme and
        // avoids the browser warning about insecure form submission.
        if (app()->environment('production') || env('RENDER', false)) {
            URL::forceScheme('https');
        }
    }
}
