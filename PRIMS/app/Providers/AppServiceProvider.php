<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
    public function boot()
    {
        if (App::environment(['staging', 'production'])) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $user = Auth::user();
            $patient = $user ? $user->patient : null;
            $clinicstaff = $user ? $user->clinicstaff : null;

            $view->with('patient', $patient);
            $view->with('clinicstaff', $clinicstaff);
        });

    }
}
