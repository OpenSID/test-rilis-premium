<?php

/**
 * Get hook router
 * 
 * @param array
 */
function getHooks($config = [])
{
    return OpenSID\Hook::getHooks($config);
}

/**
 * Get all routes
 * 
 * Auto-loads module routes before returning compiled routes
 * 
 * @return array
 */
function getRoutes()
{
    // Auto-load module routes if not yet loaded
    static $modulesLoaded = false;
    if (!$modulesLoaded && function_exists('loadModuleRoutes')) {
        loadModuleRoutes();
        
        // Re-compile routes after loading modules
        if (class_exists('OpenSID\RouteBuilder')) {
            \OpenSID\RouteBuilder::compileAll();
        }
        
        $modulesLoaded = true;
    }
    
    return OpenSID\Route::getRoutes();
}

/**
 * Load routes from modules
 * 
 * This function will auto-load routes from:
 * 1. Application routes (application/Routes or application/routes)
 * 2. Module routes (Modules/ModuleName/Routes or Modules/ModuleName/routes)
 * 
 * Based on current request type (web, api, console)
 * 
 * @return void
 */
function loadModuleRoutes()
{
    // Set RouteBuilder as alias for OpenSID\RouteBuilder (if not exists)
    if (!class_exists('Route', false)) {
        class_alias(OpenSID\RouteBuilder::class, 'Route');
    }

    // Detect route type based on request
    $routeType = 'web'; // default
    
    if (is_cli()) {
        $routeType = 'console';
    } elseif (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') === 0) {
        $routeType = 'api';
    }

    // 1. Load application routes first (from application/Routes/ or application/routes/)
    $appRoutesFile = APPPATH . 'Routes/' . $routeType . '.php';
    if (!file_exists($appRoutesFile)) {
        $appRoutesFile = APPPATH . 'routes/' . $routeType . '.php';
    }
    if (file_exists($appRoutesFile)) {
        include_once $appRoutesFile;
    }

    // 2. Load module routes (from Modules/*/Routes/ or Modules/*/routes/)
    $modulesPath = defined('FCPATH') ? FCPATH . '../Modules' : dirname(APPPATH) . '/Modules';
    
    if (is_dir($modulesPath)) {
        foreach (scandir($modulesPath) as $moduleName) {
            if ($moduleName === '.' || $moduleName === '..') {
                continue;
            }
            
            // Check both 'Routes' and 'routes' folder (case-insensitive)
            $moduleRoutesFile = $modulesPath . '/' . $moduleName . '/Routes/' . $routeType . '.php';
            if (!file_exists($moduleRoutesFile)) {
                $moduleRoutesFile = $modulesPath . '/' . $moduleName . '/routes/' . $routeType . '.php';
            }
            
            if (file_exists($moduleRoutesFile)) {
                include_once $moduleRoutesFile;
            }
        }
    }
}

/**
 * Auto-load module routes and re-compile
 * 
 * This function loads all module routes and re-compiles them.
 * 
 * @return void
 */
function autoloadModuleRoutes()
{
    loadModuleRoutes();
    
    // Re-compile routes after loading modules
    if (class_exists('OpenSID\RouteBuilder')) {
        \OpenSID\RouteBuilder::compileAll();
    }
}