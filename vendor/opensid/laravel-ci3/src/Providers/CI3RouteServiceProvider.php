<?php

namespace OpenSID\LaravelCI3\Providers;

use Illuminate\Support\Facades\Route as LaravelRoute;
use Illuminate\Support\ServiceProvider;

class CI3RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services - Register CI3 routes to Laravel router.
     * This wraps OpenSID router routes into Laravel routing system.
     */
    public function boot(): void
    {
        // Register routes after CI3 is bootstrapped
        // This happens in CodeIgniterServiceProvider boot
        $this->app->booted(function () {
            $this->registerCI3Routes();
        });
    }

    /**
     * Register CI3 routes to Laravel router.
     */
    protected function registerCI3Routes(): void
    {
        // Only register if CI3 is bootstrapped
        if (!defined('BASEPATH')) {
            return;
        }

        // Get all CI3 routes from OpenSID router
        $ci3Routes = $this->getCI3Routes();
        
        if (empty($ci3Routes)) {
            return;
        }

        // Register each route to Laravel router
        LaravelRoute::group(['middleware' => ['web']], function () use ($ci3Routes) {
            foreach ($ci3Routes as $pattern => $target) {
                // Skip special CI3 routes
                if (in_array($pattern, ['default_controller', '404_override', 'translate_uri_dashes'])) {
                    continue;
                }

                // Parse route method from pattern or default to any
                $methods = $this->parseRouteMethods($pattern);
                
                // Convert OpenSID pattern to Laravel pattern
                $laravelPattern = $this->convertPattern($pattern);
                
                // Register the route
                LaravelRoute::match($methods, $laravelPattern, function () {
                    return $this->handleCI3Route();
                })->name('ci3.' . $this->makeRouteName($pattern));
            }
        });
    }

    /**
     * Get CI3 routes from OpenSID router.
     */
    protected function getCI3Routes(): array
    {
        try {
            // Force reload and compile routes for Laravel integration
            if (class_exists('\\OpenSID\\Hook')) {
                \OpenSID\Hook::loadRoutes(true); // Force reload
            }
            
            // Get compiled routes
            if (class_exists('\\OpenSID\\RouteBuilder')) {
                return \OpenSID\RouteBuilder::getRoutes();
            }
            
            // Fallback to helper function
            if (function_exists('getRoutes')) {
                return getRoutes();
            }
        } catch (\Exception $e) {
            logger()->warning('Failed to get CI3 routes', ['error' => $e->getMessage()]);
        }

        return [];
    }

    /**
     * Parse HTTP methods from route pattern.
     */
    protected function parseRouteMethods(string $pattern): array
    {
        // Default to all common methods
        return ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
    }

    /**
     * Convert OpenSID route pattern to Laravel route pattern.
     */
    protected function convertPattern(string $pattern): string
    {
        // OpenSID uses similar pattern to Laravel
        // Convert (:num), (:any), (:alpha) to Laravel equivalents if needed
        $pattern = str_replace('(:num)', '{id}', $pattern);
        $pattern = str_replace('(:any)', '{any}', $pattern);
        $pattern = str_replace('(:alpha)', '{alpha}', $pattern);
        
        return $pattern;
    }

    /**
     * Make a valid route name from pattern.
     */
    protected function makeRouteName(string $pattern): string
    {
        return str_replace(['/', '{', '}', ':', '(', ')'], ['.', '', '', '', '', ''], $pattern);
    }

    /**
     * Handle CI3 route execution.
     * This is called when a ci3.* route matches in Laravel router.
     */
    protected function handleCI3Route()
    {
        // Get current request
        $request = request();
        
        // Use the existing fallback executor to run CI3
        $fallback = app(\OpenSID\LaravelCI3\CodeIgniterFallback::class);
        $ciResponse = $fallback->handleCI3($request);

        if ($ciResponse === null) {
            abort(404, 'CI3 route not found');
        }

        return $ciResponse;
    }
}
