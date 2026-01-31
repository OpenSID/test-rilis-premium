<?php

/**
 * CI3 Global Bootstrap
 * This file bootstraps CodeIgniter 3 and makes it available globally
 * Called very early in Laravel bootstrap process
 */

// Define get_instance function early in global scope
if (!function_exists('get_instance')) {
    function &get_instance() {
        // Static variable to hold the reference
        static $instance = null;
        
        // Prioritize CI_Controller static instance (set at the start of __construct)
        // This is crucial during bootstrap when auto-loaded libraries need get_instance()
        if (class_exists('CI_Controller', false)) {
            $instance = &\CI_Controller::get_instance();
            return $instance;
        }
        
        // Fallback to global CI3 variable (but only if it's a real CI object, not a stub)
        if (isset($GLOBALS['CI3']) && is_object($GLOBALS['CI3']) && !($GLOBALS['CI3'] instanceof \stdClass)) {
            $instance = &$GLOBALS['CI3'];
            return $instance;
        }
        
        // Return null reference if nothing available
        $instance = null;
        return $instance;
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
