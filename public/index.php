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

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
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

require __DIR__.'/../vendor/autoload.php';

// Load OpenSID router helpers early
if (file_exists(__DIR__.'/../vendor/opensid/router/src/helpers.php')) {
    require_once __DIR__.'/../vendor/opensid/router/src/helpers.php';
}

// Early CI3 routing check to avoid double bootstrapping
$ci_uri = $_SERVER['REQUEST_URI'] ?? '/';
$ci_uri = trim(parse_url($ci_uri, PHP_URL_PATH), '/');

// Derive CI3 route prefixes from OpenSID router definitions
$ci_prefixes = [];
try {
    if (!defined('APPPATH')) {
        define('APPPATH', rtrim(base_path('donjo-app'), '/\\').'/' );
    }
    $hooksHelper = APPPATH.'helpers/hooks_helper.php';
    if (file_exists($hooksHelper)) {
        require_once $hooksHelper;
        if (function_exists('getRoutes')) {
            $ci_routes = getRoutes();
            if (is_array($ci_routes)) {
                $prefixMap = [];
                foreach (array_keys($ci_routes) as $routePattern) {
                    $pattern = ltrim((string) $routePattern, '/');
                    if ($pattern === '' || $pattern === 'default_controller') {
                        continue;
                    }
                    $first = strtok($pattern, '/');
                    if ($first) {
                        $prefixMap[$first] = true;
                    }
                }
                $ci_prefixes = array_keys($prefixMap);
            }
        }
    }
} catch (\Throwable $e) {
    // Silently fall back to Laravel if routes cannot be read
    $ci_prefixes = [];
}

// Check if should use CI3
// Default root path to CI3 if no specific Laravel route matches
$should_use_ci = false;

// Root path goes to CI3 by default
if ($ci_uri === '' || $ci_uri === '/') {
    $should_use_ci = true;
} else {
    // Check if URI matches any CI3 prefix
    foreach ($ci_prefixes as $prefix) {
        if (strpos($ci_uri, $prefix) === 0) {
            $should_use_ci = true;
            break;
        }
    }
}

if ($should_use_ci) {
    require_once __DIR__.'/../bootstrap/ci3_index.php';
    exit;
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

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
