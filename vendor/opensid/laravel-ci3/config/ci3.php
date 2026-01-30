<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CodeIgniter 3 System Path
    |--------------------------------------------------------------------------
    |
    | Path to CodeIgniter 3 system directory. Usually located in vendor directory
    | if installed via Composer.
    |
    */

    'system_path' => env('CI3_SYSTEM_PATH', base_path('vendor/codeigniter/framework/system')),

    /*
    |--------------------------------------------------------------------------
    | CodeIgniter 3 Application Path
    |--------------------------------------------------------------------------
    |
    | Path to CodeIgniter 3 application directory containing config, controllers,
    | models, views, etc.
    |
    */

    'application_path' => env('CI3_APPLICATION_PATH', base_path('application')),

    /*
    |--------------------------------------------------------------------------
    | CodeIgniter 3 Modules Path
    |--------------------------------------------------------------------------
    |
    | Path to modules directory. Set to null if not using modules.
    |
    */

    'modules_path' => env('CI3_MODULES_PATH', base_path('Modules')),

    /*
    |--------------------------------------------------------------------------
    | CodeIgniter 3 Environment Mapping
    |--------------------------------------------------------------------------
    |
    | Map Laravel environments to CodeIgniter environments.
    | CI3 supports: development, testing, production
    |
    */

    'environment_map' => [
        'local' => 'development',
        'development' => 'development',
        'staging' => 'testing',
        'testing' => 'testing',
        'production' => 'production',
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-load Helpers
    |--------------------------------------------------------------------------
    |
    | List of CI3 helpers to load automatically during bootstrap.
    |
    */

    'auto_load_helpers' => [
        'url',
        'laravel',
        'laravel_facades',
    ],

    /*
    |--------------------------------------------------------------------------
    | Enable Debug Mode
    |--------------------------------------------------------------------------
    |
    | Enable debug logging for CI3 bootstrap process.
    |
    */

    'debug' => env('CI3_DEBUG', false),

];
