<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('selected', static function ($condition) {
            return "<?= ({$condition}) ? 'selected' : ''; ?>";
        });

        Blade::directive('checked', static function ($condition) {
            return "<?= ({$condition}) ? 'checked' : ''; ?>";
        });

        Blade::directive('disabled', static function ($condition) {
            return "<?= ({$condition}) ? 'disabled' : ''; ?>";
        });

        Blade::directive('active', static function ($condition) {
            return "<?= ({$condition}) ? 'active' : ''; ?>";
        });

        Blade::directive('display', static function ($condition) {
            return "<?= ({$condition}) ? 'show' : 'hide'; ?>";
        });
    }
}
