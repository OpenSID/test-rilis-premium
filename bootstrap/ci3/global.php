<?php

/**
 * CI3 Global Bootstrap
 * This file bootstraps CodeIgniter 3 and makes it available globally
 * Called very early in Laravel bootstrap process
 */

// Define get_instance function early in global scope
if (!function_exists('get_instance')) {
    function &get_instance() {
        if (isset($GLOBALS['CI3'])) {
            return $GLOBALS['CI3'];
        }
        // Fallback to CI_Controller if available
        if (class_exists('CI_Controller', false)) {
            return CI_Controller::get_instance();
        }
        // Return null reference if nothing available
        $null = null;
        return $null;
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
