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
        /*
        if (app()->environment('local') && env('MAIL_ALWAYS_TO')) {
            \Illuminate\Support\Facades\Mail::alwaysTo(env('MAIL_ALWAYS_TO'));
        }
        */

        // Fix for "No hint path defined for [mail]" in Laravel 11/12
        \Illuminate\Support\Facades\View::addNamespace('mail', resource_path('views/vendor/mail'));
        \Illuminate\Support\Facades\Blade::componentNamespace('Illuminate\\Mail\\View\\Components', 'mail');
    }
}
