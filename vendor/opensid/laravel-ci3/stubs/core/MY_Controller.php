<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use OpenSID\LaravelCI3\Traits\LaravelBridge;

/**
 * MY_Controller - Extended Base Controller
 *
 * Menambahkan helper Laravel lewat trait LaravelBridge.
 */
class MY_Controller extends CI_Controller 
{
    use LaravelBridge;

    public function __construct()
    {
        parent::__construct();

        // Initialize Laravel Bridge
        $this->initLaravelBridge();
        
        // DON'T load session here - it will be assigned directly in CodeIgniterFallback
        // to avoid ini_set() error when Laravel session is already active
    }
}
