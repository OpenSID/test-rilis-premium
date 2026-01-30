<?php

/*
|--------------------------------------------------------------------------
| CI3 Constants Definition
|--------------------------------------------------------------------------
|
| Centralized location for defining all CodeIgniter 3 constants that are
| required throughout the Laravel-CI3 integrated application.
|
| This file is loaded early in the bootstrap process to ensure all
| constants are available when needed.
|
*/

// Define BASEPATH - CI3 system path
if (!defined('BASEPATH')) {
    $ci3_system_path = getenv('CI3_SYSTEM_PATH') ?: dirname(__DIR__, 2) . '/vendor/codeigniter/framework/system';
    define('BASEPATH', rtrim($ci3_system_path, '/\\') . '/');
}

// Define FCPATH - Front controller path (project root)
if (!defined('FCPATH')) {
    define('FCPATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
}

// Define APPPATH - Application path (CI3 app folder)
if (!defined('APPPATH')) {
    define('APPPATH', dirname(__DIR__, 2) . '/donjo-app' . DIRECTORY_SEPARATOR);
}

// Define ENVIRONMENT - Application environment
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', getenv('CI_ENV') ?: getenv('APP_ENV') ?: 'development');
}

// Load CI3 constants from donjo-app/config/constants.php
$ci3_constants_path = dirname(__DIR__, 2) . '/donjo-app/config/constants.php';
if (file_exists($ci3_constants_path) && !defined('STORAGEPATH')) {
    require_once $ci3_constants_path;
}
