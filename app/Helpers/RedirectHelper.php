<?php

/*
 * File ini bagian dari OpenSID
 * Helper untuk Laravel redirect
 */

namespace App\Helpers;

use Illuminate\Support\Facades\Redirect;

class RedirectHelper
{
    /**
     * Get the redirector instance atau redirect ke URL
     * 
     * Laravel style redirect:
     * - redirect()->to('url')
     * - redirect('url')
     * 
     * @param string|null $location The URL or route to redirect to
     * @param int $status HTTP status code
     * @param array $headers Response headers
     * @param bool|null $secure Use HTTPS
     * @return Illuminate\Routing\Redirector|Illuminate\Http\RedirectResponse
     */
    public static function redirect($location = null, $status = 302, $headers = [], $secure = null)
    {
        // Jika location null, return redirector instance (untuk chainable: redirect()->to())
        if (is_null($location)) {
            return app('redirect');
        }

        // redirect dengan cara laravel
        /// jika berupa url
        if (! filter_var($location, FILTER_VALIDATE_URL)) {
            $location = app('url')->to($location);
        }

        // dd('RedirectHelper::redirect to ' . $location);

        return Redirect::to($location, $status, $headers, $secure);
    }
}
