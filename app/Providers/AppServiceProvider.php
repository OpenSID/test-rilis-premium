<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            base_path('vendor/themes/esensi') => public_path('vendor/themes/esensi'),
        ], 'tema-opensid');

        $this->publishes([
            base_path('vendor/themes/natra') => public_path('vendor/themes/natra'),
        ], 'tema-opensid');
    }
}
