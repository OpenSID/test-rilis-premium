<?php

/**
 * CI3 Global Bootstrap
 * This file bootstraps CodeIgniter 3 and makes it available globally
 * Called very early in Laravel bootstrap process
 */

// Define get_instance function early in global scope
if (!function_exists('get_instance')) {
    function &get_instance() {
        // CI_Controller::$instance is set immediately in __construct
        // Just return it directly without any intermediate logic
        return \CI_Controller::get_instance();
    }
}

// Only define helper function - bootstrap will be handled by ServiceProvider
// Don't bootstrap here to avoid early config() calls

/**
 * Global helper function to access CI3
 */
if (!function_exists('ci3')) {
    function ci3() {
        return $GLOBALS['CI3'] ?? null;
    }
}
