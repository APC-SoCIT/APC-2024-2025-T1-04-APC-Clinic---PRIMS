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
        if (App::environment(['staging', 'production'])) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $user = Auth::user();
            $patient = $user ? $user->patient : null;

            $view->with('patient', $patient);
        });
    }
}
