<?php

namespace OpenSID\LaravelCI3\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use OpenSID\LaravelCI3\Services\CI3Bootstrap;
use Symfony\Component\HttpFoundation\Response;

class CI3RequestHandler
{
    /**
     * Handle an incoming request and check if it should be handled by CI3.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the current route
        $route = $request->route();

        // If route is registered as CI3 route, bootstrap CI3
        if ($route && str_starts_with($route->getName() ?? '', 'ci3.')) {
            // Ensure CI3 is bootstrapped
            if (!defined('BASEPATH')) {
                CI3Bootstrap::boot();
            }
        }

        return $next($request);
    }
}
