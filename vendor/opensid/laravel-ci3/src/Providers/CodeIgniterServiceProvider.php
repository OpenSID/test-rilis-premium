<?php

namespace OpenSID\LaravelCI3\Providers;

use Illuminate\Support\ServiceProvider;
use OpenSID\LaravelCI3\Services\CI3Bootstrap;
use OpenSID\LaravelCI3\Services\CI3Instance;
use OpenSID\LaravelCI3\Console\InstallCommand;
use OpenSID\LaravelCI3\Console\TestCommand;
use OpenSID\LaravelCI3\Console\ConfigCommand;

class CodeIgniterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Merge config
        $configPath = __DIR__ . '/../../config/ci3.php';
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'ci3');
        }
        
        // Bootstrap CI3 lazily when first accessed
        $this->app->singleton('ci3.booted', function ($app) {
            return CI3Bootstrap::boot();
        });
        
        // Register CI3 instance as 'ci' in container
        $this->app->singleton('ci', function ($app) {
            // Ensure CI3 is booted first
            $app->make('ci3.booted');
            
            // Return CI3Instance wrapper
            return new CI3Instance();
        });
        
        // Register alias for convenience
        $this->app->alias('ci', CI3Instance::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/ci3.php' => config_path('ci3.php'),
        ], 'ci3-config');
        
        // Note: Helper files (laravel_helper, laravel_facades_helper, module_routes_helper)
        // are now auto-loaded via Composer from src/helpers.php
        // No need to publish them separately
        
        // Publish CI3 config files
        $this->publishes([
            __DIR__ . '/../../stubs/config/hooks.php' => base_path('application/config/hooks.php'),
            __DIR__ . '/../../stubs/config/modules.php' => base_path('application/config/modules.php'),
        ], 'ci3-config-files');
        
        // Publish CI3 core classes
        $this->publishes([
            __DIR__ . '/../../stubs/core/MY_Controller.php' => base_path('application/core/MY_Controller.php'),
        ], 'ci3-core');
        
        // Publish CI3 libraries
        $this->publishes([
            __DIR__ . '/../../stubs/libraries/MY_Session.php' => base_path('application/libraries/MY_Session.php'),
        ], 'ci3-libraries');
        
        // Publish all CI3 files at once
        $this->publishes([
            __DIR__ . '/../../stubs/helpers' => base_path('application/helpers'),
            __DIR__ . '/../../stubs/config' => base_path('application/config'),
            __DIR__ . '/../../stubs/core' => base_path('application/core'),
            __DIR__ . '/../../stubs/libraries' => base_path('application/libraries'),
        ], 'ci3-all');
        
        // Register artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                TestCommand::class,
                ConfigCommand::class,
            ]);
        }
        
        // Bootstrap CI3 as soon as Laravel boots
        $this->app->make('ci3.booted');
    }
}
