<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
<<<<<<< HEAD

        URL::forceScheme('https');
=======
        if (env('APP_ENV') == 'local') {
            URL::forceScheme('https');
        }
>>>>>>> 63efed96cf958799dea8e254639c088d2225410f
    }
}
