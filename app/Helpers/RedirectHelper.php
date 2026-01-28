<?php

/*
 * File ini bagian dari OpenSID
 * Single redirect() function to support both CI3 and Laravel redirects
 */

namespace App\Helpers;

use Illuminate\Support\Facades\Redirect;

class RedirectHelper
{
    /**
     * Redirect to URL or route with dual system support (CI3 + Laravel)
     * 
     * @param string $location The URL or route name to redirect to
     * @param string $method The HTTP method (GET, POST, etc.)
     * @param int $code The HTTP status code
     * @return void|object
     */
    public static function redirect($location = '', $method = 'location', $code = 302)
    {
        // Handle empty location
        if (empty($location)) {
            $location = '/';
        }

        // Check if we're in Laravel context (has request in app container)
        $isLaravel = app()->has('request');

        if ($isLaravel) {
            // Laravel redirect
            return self::laravelRedirect($location, $code);
        } else {
            // CI3 redirect
            return self::ci3Redirect($location, $method, $code);
        }
    }

    /**
     * Redirect using Laravel method
     */
    private static function laravelRedirect($location, $code = 302)
    {
        try {
            // If location starts with /, it's definitely a URL
            if (strpos($location, '/') === 0) {
                return Redirect::to($location)->setStatusCode($code);
            }

            // Check if location looks like a route name
            // Route names typically contain only alphanumeric, dash, dot, colon, underscore
            if (preg_match('/^[a-zA-Z0-9._:\-]+$/', $location)) {
                try {
                    // Try to redirect to route by name first
                    return Redirect::route($location)->setStatusCode($code);
                } catch (\Throwable $e) {
                    // Route not found, try as URL
                    return Redirect::to($location)->setStatusCode($code);
                }
            }

            // Default to URL redirect
            return Redirect::to($location)->setStatusCode($code);
        } catch (\Throwable $e) {
            // Fallback to direct header redirect
            return self::headerRedirect($location, $code);
        }
    }

    /**
     * Redirect using CI3 method
     */
    private static function ci3Redirect($location, $method = 'location', $code = 302)
    {
        // In CI3, redirect() is a global helper function from CodeIgniter
        if (function_exists('redirect')) {
            return \redirect($location, $method, $code);
        }

        // Fallback to header redirect if CI3 function not available
        return self::headerRedirect($location, $code);
    }

    /**
     * Direct header-based redirect (fallback method)
     */
    private static function headerRedirect($location, $code = 302)
    {
        $code = (int) $code;
        $codes = [
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',
        ];

        if (isset($codes[$code])) {
            header('HTTP/1.1 ' . $code . ' ' . $codes[$code]);
        } else {
            header('HTTP/1.1 302 Found');
        }

        header('Location: ' . $location);
        exit;
    }
}
