<?php

/**
 * OpenSID Laravel-CI3 Integration Helpers
 * 
 * Helper functions for seamless Laravel-CI3 integration:
 * - Laravel core helpers and facades
 * - Session bridge ($_SESSION ↔ Laravel session)
 * - Module routing support
 * - View rendering utilities
 */

// ============================================================================
// LARAVEL CORE HELPERS
// ============================================================================

if (!function_exists('module_path')) {
    function module_path($module = '')
    {
        $path = config('ci3.modules_path', FCPATH . '../Modules/');
        return $module ? $path . $module . '/' : $path;
    }
}

if (!function_exists('config')) {
    function config($key = null, $default = null)
    {
        if (is_null($key)) return app('config');
        return app()->bound('config') ? app('config')->get($key, $default) : $default;
    }
}

if (!function_exists('cache')) {
    function cache($key = null, $default = null)
    {
        if (is_null($key)) return app('cache');
        if (is_array($key)) return app('cache')->put(key($key), reset($key), $default);
        return app('cache')->get($key, $default);
    }
}

if (!function_exists('session')) {
    function session($key = null, $default = null)
    {
        if (is_null($key)) return app('session');
        if (is_array($key)) return app('session')->put($key);
        return app('session')->get($key, $default);
    }
}

// ============================================================================
// LARAVEL FACADES
// ============================================================================

if (!class_exists('Session')) {
    class Session {
        public static function __callStatic($method, $args) {
            return app('session')->$method(...$args);
        }
    }
}

if (!class_exists('Cache')) {
    class Cache {
        public static function __callStatic($method, $args) {
            return app('cache')->$method(...$args);
        }
    }
}

if (!class_exists('Config')) {
    class Config {
        public static function __callStatic($method, $args) {
            return app('config')->$method(...$args);
        }
    }
}

if (!class_exists('DB')) {
    class DB {
        public static function __callStatic($method, $args) {
            return app('db')->$method(...$args);
        }
    }
}

if (!class_exists('Log')) {
    class Log {
        public static function __callStatic($method, $args) {
            return app('log')->$method(...$args);
        }
    }
}

// ============================================================================
// SESSION BRIDGE - $_SESSION ↔ Laravel Session
// ============================================================================

if (!defined('SESSION_BRIDGE_ENABLED')) {
    define('SESSION_BRIDGE_ENABLED', true);
    
    class SessionArrayAccess implements ArrayAccess, Countable, IteratorAggregate
    {
        protected $session;
        
        public function __construct() {
            $this->session = app()->bound('session') ? app('session') : null;
        }
        
        public function offsetExists($offset): bool {
            return $this->session?->has($offset) ?? false;
        }
        
        public function offsetGet($offset): mixed {
            return $this->session?->get($offset);
        }
        
        public function offsetSet($offset, $value): void {
            $this->session?->put($offset, $value);
        }
        
        public function offsetUnset($offset): void {
            $this->session?->forget($offset);
        }
        
        public function count(): int {
            return $this->session ? count($this->session->all()) : 0;
        }
        
        public function getIterator(): Traversable {
            return new ArrayIterator($this->session?->all() ?? []);
        }
    }
    
    // Auto-bridge $_SESSION to Laravel session
    if (function_exists('app') && app()->bound('session')) {
        $_SESSION = new SessionArrayAccess();
        
        register_shutdown_function(function() {
            $session = app('session');
            if ($session->isStarted()) $session->save();
        });
    }
}

// Session helper functions - unified implementation
if (!function_exists('session_set')) {
    function session_set($key, $value = null) {
        $session = app('session');
        is_array($key) ? $session->put($key) : $session->put($key, $value);
    }
}

if (!function_exists('session_get')) {
    function session_get($key, $default = null) {
        return app('session')->get($key, $default);
    }
}

if (!function_exists('session_has')) {
    function session_has($key) {
        return app('session')->has($key);
    }
}

if (!function_exists('session_delete')) {
    function session_delete($key) {
        is_array($key) ? app('session')->forget($key) : app('session')->forget([$key]);
    }
}

if (!function_exists('session_flash')) {
    function session_flash($key, $value = null) {
        $session = app('session');
        is_array($key) 
            ? array_walk($key, fn($v, $k) => $session->flash($k, $v))
            : $session->flash($key, $value);
    }
}

if (!function_exists('session_all')) {
    function session_all() {
        return app('session')->all();
    }
}

if (!function_exists('session_destroy_all')) {
    function session_destroy_all() {
        $session = app('session');
        $session->flush();
        $session->regenerate(true);
    }
}

// ============================================================================
// VIEW RENDERING HELPERS
// ============================================================================
// Note: Module routes helpers (autoload_module_routes, load_module_*_routes)
// are now handled by opensid/router package in vendor/opensid/router/src/helpers.php
// which provides smarter auto-detection: loadModuleRoutes() and autoloadModuleRoutes()

if (!function_exists('ci3_view')) {
    function ci3_view($view, $data = []) {
        return \OpenSID\LaravelCI3\Services\CI3ViewRenderer::make($view, $data);
    }
}

if (!function_exists('blade_view')) {
    function blade_view($view, $data = []) {
        return view($view, $data);
    }
}
