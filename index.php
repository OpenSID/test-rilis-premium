<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Early CI3 Routing Check
|--------------------------------------------------------------------------
|
| Check if request should be handled by CI3 by reading route files directly.
| This avoids calling getRoutes() which requires CI3 bootstrap.
|
*/

$ci_uri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');

if ($ci_uri) {
    // Get application path from env or use default
    $appPath = $_ENV['CI3_APPLICATION_PATH'] ?? $_SERVER['CI3_APPLICATION_PATH'] ?? 'donjo-app';
    $hooksHelper = __DIR__ . '/' . $appPath . '/helpers/hooks_helper.php';
    
    if (file_exists($hooksHelper)) {
        require_once $hooksHelper;
        
        // Get routes without triggering loadModuleRoutes()
        if (class_exists('OpenSID\Route')) {
            $routes = OpenSID\Route::getRoutes();
            
            foreach (array_keys($routes) as $pattern) {
                $prefix = strtok(ltrim((string) $pattern, '/'), '/');
                if ($prefix && $prefix !== 'default_controller' && str_starts_with($ci_uri, $prefix)) {
                    require_once __DIR__.'/bootstrap/ci3/index.php';
                    exit;
                }
            }
        }
    }
}

/*
 |--------------------------------------------------------------------------
 | Run The Application
 |--------------------------------------------------------------------------
 |
 | Once we have the application, we can handle the incoming request using
 | the application's HTTP kernel. Then, we will send the response back
 | to this client's browser, allowing them to enjoy our application.
 |
 */

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
