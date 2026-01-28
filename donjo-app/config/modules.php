<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Modules locations
// Note: Use full path or relative to APPPATH
$config['modules_locations'] = [
    defined('FCPATH') ? FCPATH . '../Modules/' : dirname(APPPATH) . '/Modules/' => '../Modules/',
];
