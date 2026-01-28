<?php

namespace OpenSID;

/**
 * Module Router for CodeIgniter 3
 * Handles routing for modules with Laravel-style route definitions
 * 
 * @package OpenSID
 * @author OpenDesa Team
 */
class ModuleRouter
{
    protected $modulesPath;
    protected $routes = [];
    protected $loadedModules = [];

    public function __construct($modulesPath = null)
    {
        $this->modulesPath = $modulesPath;
    }

    /**
     * Load all module routes
     * 
     * @return array
     */
    public function loadRoutes()
    {
        if (!is_dir($this->modulesPath)) {
            return [];
        }

        $modules = $this->getModules();
        
        // Load all routes into RouteBuilder without compiling yet
        foreach ($modules as $module) {
            $this->loadModuleRoutesFile($module);
        }
        
        // Compile all routes once
        if (class_exists('OpenSID\\RouteBuilder')) {
            RouteBuilder::compileAll();
            $this->extractCompiledRoutes();
        }

        return $this->routes;
    }

    /**
     * Get all modules from Modules directory
     * 
     * @return array
     */
    protected function getModules()
    {
        if (!is_dir($this->modulesPath)) {
            return [];
        }
        
        $entries = @scandir($this->modulesPath);
        if ($entries === false) {
            return [];
        }
        
        return array_filter($entries, function($entry) {
            return $entry !== '.' && $entry !== '..' && is_dir($this->modulesPath . DIRECTORY_SEPARATOR . $entry);
        });
    }

    /**
     * Load routes file from a specific module (without compiling)
     * 
     * @param string $moduleName
     * @return void
     */
    private function loadModuleRoutesFile($moduleName)
    {
        $routeFile = $this->modulesPath . '/' . $moduleName . '/Routes/web.php';
        
        if (!file_exists($routeFile)) {
            return;
        }

        // Ensure Route alias is available
        if (class_exists('OpenSID\\RouteBuilder') && !class_exists('Route', false)) {
            class_alias('OpenSID\\RouteBuilder', 'Route');
        }

        // Load routes file - this populates RouteBuilder::$routes via Route::get(), etc
        require $routeFile;
        
        $this->loadedModules[] = $moduleName;
    }

    /**
     * Extract compiled routes from RouteBuilder into this router's routes collection
     */
    private function extractCompiledRoutes()
    {
        if (!class_exists('OpenSID\\RouteBuilder')) {
            return;
        }
        
        $compiledRoutes = RouteBuilder::$compiled['routes'] ?? [];
        
        // Routes structure is: $compiled['routes'][$path][$method] = $target
        foreach ($compiledRoutes as $path => $methods) {
            if (is_array($methods)) {
                // This is nested: path => [method => target]
                foreach ($methods as $method => $target) {
                    // Store with proper route data structure for match()
                    $this->routes[$path] = [
                        'module' => null,  // Will be determined when matching
                        'target' => $target,
                        'pattern' => $path,
                    ];
                    break; // Use first method, typically GET
                }
            } else if (is_string($methods)) {
                // This is flat: path => target
                $this->routes[$path] = [
                    'module' => null,
                    'target' => $methods,
                    'pattern' => $path,
                ];
            }
        }
    }

    /**
     * Load routes from a specific module (deprecated - use loadRoutes instead)
     * 
     * @param string $moduleName
     * @return void
     */
    protected function loadModuleRoutes($moduleName)
    {
        $routeFile = $this->modulesPath . '/' . $moduleName . '/Routes/web.php';
        
        if (!file_exists($routeFile)) {
            return;
        }

        // Ensure Route alias is available
        if (class_exists('OpenSID\\RouteBuilder') && !class_exists('Route', false)) {
            class_alias('OpenSID\\RouteBuilder', 'Route');
        }

        // Load routes file - this populates RouteBuilder::$routes via Route::get(), etc
        require $routeFile;
        
        // Get compiled routes from RouteBuilder and store them
        if (class_exists('OpenSID\\RouteBuilder')) {
            // Compile all routes to populate $compiled['routes']
            RouteBuilder::compileAll();
            $compiledRoutes = RouteBuilder::$compiled['routes'] ?? [];
            
            foreach ($compiledRoutes as $pattern => $target) {
                $this->routes[$pattern] = [
                    'module' => $moduleName,
                    'target' => $target,
                ];
            }
        }

        $this->loadedModules[] = $moduleName;
    }

    /**
     * Match a URI against module routes
     * 
     * @param string $uri
     * @return array|null
     */
    public function match($uri)
    {
        if (empty($this->routes)) {
            $this->loadRoutes();
        }

        foreach ($this->routes as $pattern => $routeData) {
            $regex = $this->patternToRegex($pattern);
            
            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                return [
                    'module' => $routeData['module'],
                    'target' => $routeData['target'],
                    'params' => $matches,
                    'pattern' => $pattern,
                ];
            }
        }

        return null;
    }

    /**
     * Convert CI3 route pattern to regex
     * 
     * @param string $pattern
     * @return string
     */
    protected function patternToRegex($pattern)
    {
        // Escape forward slashes
        $pattern = str_replace('/', '\/', $pattern);
        
        // Convert CI3 wildcards to regex
        $pattern = str_replace('(:any)', '([^\/]+)', $pattern);
        $pattern = str_replace('(:num)', '([0-9]+)', $pattern);
        $pattern = str_replace('(:alpha)', '([a-zA-Z]+)', $pattern);
        $pattern = str_replace('(:alphanum)', '([a-zA-Z0-9]+)', $pattern);
        
        return '/^' . $pattern . '$/';
    }

    /**
     * Parse target to get controller and method
     * 
     * @param string $target
     * @param array $params
     * @return array
     */
    public function parseTarget($target, $params = [])
    {
        // Replace parameter placeholders like $1, $2
        foreach ($params as $index => $param) {
            $target = str_replace('$' . ($index + 1), $param, $target);
        }

        // Split controller@method
        if (strpos($target, '@') !== false) {
            $parts = explode('@', $target, 2);
            
            return [
                'controller' => $parts[0],
                'method' => $parts[1],
                'params' => $params,
            ];
        }

        // If no @, assume it's controller/method format
        return [
            'controller' => $target,
            'method' => 'index',
            'params' => $params,
        ];
    }

    /**
     * Get loaded modules
     * 
     * @return array
     */
    public function getLoadedModules()
    {
        return $this->loadedModules;
    }

    /**
     * Get all routes
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
