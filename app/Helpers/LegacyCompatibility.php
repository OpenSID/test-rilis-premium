<?php

/*
 * Routes URL Generation Helper Adapter
 * 
 * File ini menyediakan helper untuk backward compatibility
 * antara CI3 ci_route() dan Laravel route()
 */

if (!function_exists('ci_route')) {
    /**
     * Generate route URL dengan backward compatibility
     * 
     * Support both CI3 dan Laravel route patterns
     * 
     * @param string $routeName Route name (Laravel style: 'sms.form')
     * @param mixed $parameters Route parameters
     * @return string Generated URL
     * 
     * Examples:
     * - ci_route('sms.form', ['tipe' => 1, 'id' => 5])
     * - ci_route('sms.delete.1', 5)  // Old CI3 style (deprecated)
     */
    function ci_route(string $routeName, $parameters = []): string
    {
        // Handle old CI3 format: 'controller.method.param'
        if (str_contains($routeName, '.')) {
            $parts = explode('.', $routeName);
            
            // New format: already Laravel style
            if (route_exists($routeName)) {
                return route($routeName, $parameters);
            }
            
            // Old format: convert to new
            // Example: 'sms.form.1' -> ['sms.form', ['tipe' => 1]]
            if (count($parts) >= 3) {
                $baseRoute = implode('.', array_slice($parts, 0, -1));  // 'sms.form'
                $param = end($parts);  // '1'
                
                if (route_exists($baseRoute)) {
                    // Build parameters array
                    if (is_array($parameters)) {
                        $parameters['tipe'] = (int) $param;
                    } else {
                        $parameters = ['tipe' => (int) $param, 'id' => $parameters];
                    }
                    
                    return route($baseRoute, $parameters);
                }
            }
        }
        
        // Standard Laravel routing
        return route($routeName, $parameters);
    }
}

if (!function_exists('route_exists')) {
    /**
     * Check if route exists
     */
    function route_exists(string $routeName): bool
    {
        return \Illuminate\Support\Facades\Route::has($routeName);
    }
}

if (!function_exists('tgl_indo2')) {
    /**
     * Format tanggal ke format Indonesia
     * Helper yang sudah ada di CI3, tetapi untuk Laravel
     */
    function tgl_indo2($tanggal): ?string
    {
        if (empty($tanggal)) {
            return null;
        }
        
        $date = \Carbon\Carbon::parse($tanggal);
        
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
        
        return $date->date . ' ' . $bulan[(int) $date->month] . ' ' . $date->year;
    }
}

if (!function_exists('bilangan')) {
    /**
     * Convert string to integer (sanitize)
     */
    function bilangan($value): int
    {
        return (int) str_replace(['-', '+', ' '], '', $value ?? 0);
    }
}

if (!function_exists('identitas')) {
    /**
     * Get desa identity/configuration
     */
    function identitas(?string $field = null)
    {
        $config = \App\Models\Config::first();
        
        if ($field) {
            return $config->$field ?? null;
        }
        
        return $config?->toArray() ?? [];
    }
}

if (!function_exists('setting')) {
    /**
     * Get application setting
     */
    function setting(string $key): ?string
    {
        $setting = \App\Models\Setting::where('key', $key)->first();
        
        return $setting?->value;
    }
}

if (!function_exists('can')) {
    /**
     * Check if user has permission
     */
    function can(string $permission): bool
    {
        return auth('admin')->check() && auth('admin')->user()->canAccess($permission);
    }
}

if (!function_exists('ci_auth')) {
    /**
     * Get current authenticated user
     */
    function ci_auth()
    {
        return auth('admin')->user();
    }
}

if (!function_exists('show_404')) {
    /**
     * Show 404 error page
     */
    function show_404(): never
    {
        abort(404);
    }
}

if (!function_exists('set_session')) {
    /**
     * Set flash session message
     */
    function set_session(string $type, string $message): void
    {
        session()->flash($type, $message);
    }
}

if (!function_exists('redirect_with')) {
    /**
     * Redirect with flash message
     */
    function redirect_with(string $type, string $message, string $url = '/'): \Illuminate\Http\RedirectResponse
    {
        return redirect($url)->with($type, $message);
    }
}

if (!function_exists('SebutanDesa')) {
    /**
     * Get sebutan desa (Desa, Kelurahan, etc)
     */
    function SebutanDesa(?string $default = null): string
    {
        $config = \App\Models\Config::first();
        
        return $config?->sebutan_desa ?? $default ?? 'Desa';
    }
}
