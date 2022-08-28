<?php

namespace App\Core;

use App\Http\Controllers\Controller;

/**
 * @property CI_Benchmark        $benchmark
 * @property CI_Config           $config
 * @property CI_DB_query_builder $db
 * @property CI_Input            $input
 * @property CI_Lang             $lang
 * @property CI_Loader           $loader
 * @property CI_log              $log
 * @property CI_Output           $output
 * @property CI_Router           $router
 * @property CI_Security         $security
 * @property CI_Session          $session
 * @property CI_URI              $uri
 * @property CI_Utf8             $utf8
 */
class CI_Controller
{
    private static $instance;

    public $load;

    public function __construct()
    {
        self::$instance = &$this;

        foreach (is_loaded() as $var => $class) {
            $this->{$var} = &load_class($class);
        }

        $this->load = &load_class('Loader', 'core');
        $this->load->initialize();
        log_message('info', 'Controller Class Initialized');
    }

    public static function &get_instance()
    {
        return self::$instance;
    }
}
