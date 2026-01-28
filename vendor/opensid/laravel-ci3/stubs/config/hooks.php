<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Require hooks_helper (if exists and not already loaded)
if (file_exists(APPPATH . 'helpers/hooks_helper.php') && !function_exists('getHooks')) {
    require_once APPPATH . 'helpers/hooks_helper.php';
}

// Get hooks from OpenSID router (if function available)
if (function_exists('getHooks')) {
    $hook = getHooks();
}
