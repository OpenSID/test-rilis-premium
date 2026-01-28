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
     * - redirect('akas') // akan convert ke URL lengkap
     * 
     * @param string|null $location The URL or route to redirect to
     * @param int $status HTTP status code
     * @param array $headers Response headers
     * @param bool|null $secure Use HTTPS
     * @return Illuminate\Routing\Redirector|Illuminate\Http\RedirectResponse
     */
    public static function redirect($location, $status = 302, $headers = [], $secure = null)
    {
        if (filter_var($location, FILTER_VALIDATE_URL)) {
            // Sudah full URL
            return Redirect::to($location, $status, $headers, $secure);
        } else {
            // Convert ke full URL menggunakan url() helper
            $fullUrl = url($location, [], $secure);
            return Redirect::to($fullUrl, $status, $headers, $secure);
        }

        dd('RedirectHelper: Converting to full URL for location: ' . $location);
    }
}
