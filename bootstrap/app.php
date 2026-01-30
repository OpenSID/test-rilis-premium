<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Override Public Path (Root Directory Structure)
|--------------------------------------------------------------------------
|
| Since we moved public folder to root, override the public_path()
| to return root directory instead of public/
|
*/

$app->usePublicPath(dirname(__DIR__));

/*
|--------------------------------------------------------------------------
| Bootstrap CodeIgniter 3
|--------------------------------------------------------------------------
|
| Bootstrap CI3 early so it's available as a global variable throughout
| the Laravel application. This allows CI3 to be used via app('ci').
|
*/

require_once __DIR__ . '/ci3/global.php';

/*
|--------------------------------------------------------------------------
| Load CI3 Constants
|--------------------------------------------------------------------------
|
| All CI3 constants are defined in a centralized location for easier
| maintenance and to ensure they're available throughout the application.
|
*/

require_once __DIR__ . '/ci3/constants.php';

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
