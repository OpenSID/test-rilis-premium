<?php

/*
 * File ini bagian dari OpenSID
 * Single route() function to support both CI3 and Laravel routes
 */

namespace App\Helpers;

use Illuminate\Support\Facades\Route as LaravelRoute;
use Illuminate\Support\Facades\URL;

class RouteHelper
{
    /**
     * Get route URL by name with dual system support (CI3 + Laravel)
     * Try CI3 first, then Laravel, gracefully handle errors
     *
     * @param string $name
     * @param array $params
     * @return string|null
     */
    public static function route($name = null, $params = [])
    {
        // Handle null name - get current Laravel route
        if ($name === null) {
            $route = LaravelRoute::current();
            return $route ? $route->url($params) : '/';
        }

        // Skip CI3 if we're in CLI context to avoid early bootstrap issues
        $isWeb = php_sapi_name() !== 'cli' && app()->has('request');
        
        // Try CI3 route first (only in web context)
        if ($isWeb && self::ci3RouteExists($name)) {
            try {
                return self::getCi3Route($name, $params);
            } catch (\Throwable $e) {
                // Continue to Laravel if CI3 fails
            }
        }

        // Try Laravel route as fallback
        try {
            return URL::route($name, $params);
        } catch (\Throwable $e) {
            // Route not found in either system, return error URL or current URL
            error_log("Route not found: {$name}");
            return '#';
        }
    }

    /**
     * Check if CI3 route exists
     */
    private static function ci3RouteExists($name): bool
    {
        if (function_exists('route_exists')) {
            return \route_exists($name);
        }
        return false;
    }

    /**
     * Get CI3 route URL
     */
    private static function getCi3Route($name, $params = [])
    {
        try {
            if (class_exists('Route')) {
                $route = \Route::getByName($name);
                return $route->buildUrl($params);
            }
        } catch (\Throwable $e) {
            error_log("CI3 route error: " . $e->getMessage());
            throw $e;
        }
        throw new \RuntimeException("CI3 Router not available");
    }
}
