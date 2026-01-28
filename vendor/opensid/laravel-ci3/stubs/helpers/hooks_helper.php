<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use OpenSID\Hook;
use OpenSID\RouteBuilder;

// NOTE: getHooks() and getRoutes() already defined in vendor/opensid/router/src/helpers.php
// We wrap them to add module auto-loading logic

if (!function_exists('loadModuleRoutes')) {
    /**
     * Load routes from modules
     * 
     * This function will auto-load routes from all modules in Modules/ directory
     * based on current request type (web, api, console)
     * 
     * @return void
     */
    function loadModuleRoutes()
    {
        // Set RouteBuilder as alias for OpenSID\RouteBuilder (if not exists)
        if (!class_exists('Route', false)) {
            class_alias(RouteBuilder::class, 'Route');
        }

        // Auto-load module routes
        $modulesPath = defined('FCPATH') ? FCPATH . '../Modules' : dirname(APPPATH) . '/Modules';
        
        if (is_dir($modulesPath)) {
            // Detect route type based on request
            $routeType = 'web'; // default
            
            if (is_cli()) {
                $routeType = 'console';
            } elseif (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') === 0) {
                $routeType = 'api';
            }
            
            foreach (scandir($modulesPath) as $moduleName) {
                if ($moduleName === '.' || $moduleName === '..') {
                    continue;
                }
                
                $moduleRoutesFile = $modulesPath . '/' . $moduleName . '/Routes/' . $routeType . '.php';
                
                if (file_exists($moduleRoutesFile)) {
                    include $moduleRoutesFile;
                }
            }
        }
    }
}
