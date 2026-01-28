<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load helpers and configuration
include_once APPPATH . 'config/modules.php';

// Get hooks from OpenSID router with modules configuration
if (function_exists('getHooks')) {
    $hook = getHooks(['modules_location' => $config['modules_locations'] ?? []]);
}
