<?php

namespace OpenSID\LaravelCI3\Services;

/**
 * CI3 Bootstrap - Load CodeIgniter 3 as a library
 * This bootstraps CI3 completely and stores it in a global variable
 */
class CI3Bootstrap
{
    protected static $booted = false;
    protected static $booting = false;  // Guard against recursive bootstrap
    protected static $ci = null;

    /**
     * Bootstrap CodeIgniter 3
     */
    public static function boot()
    {
        if (self::$booted) {
            return self::$ci;
        }
        
        // Guard against recursive bootstrapping
        if (self::$booting) {
            throw new \RuntimeException('Recursive CI3 bootstrap detected. Check for circular dependencies in MY_Controller constructor.');
        }
        
        self::$booting = true;
        
        try {
            $system_path = config('ci3.system_path', base_path('vendor/codeigniter/framework/system'));
            $application_path = config('ci3.application_path', base_path('application'));
        } catch (\Exception $e) {
            // Fallback if Laravel config is not available yet
            $system_path = base_path('vendor/codeigniter/framework/system');
            $application_path = base_path('application');
        }
        
        // Determine environment - prioritize Laravel's environment
        $environment = 'development'; // default
        try {
            if (function_exists('app') && app()->bound('env')) {
                $laravelEnv = app()->environment();
                // Map Laravel environment to CI3 environment
                $envMap = [
                    'local' => 'development',
                    'development' => 'development',
                    'staging' => 'testing',
                    'testing' => 'testing',
                    'production' => 'production',
                ];
                try {
                    $envMapConfig = config('ci3.environment_map', $envMap);
                    $envMap = $envMapConfig;
                } catch (\Exception $e) {
                    // Use default mapping if config not available
                }
                $environment = $envMap[$laravelEnv] ?? 'development';
            } elseif (defined('APP_ENV')) {
                $environment = constant('APP_ENV');
            } elseif (isset($_ENV['APP_ENV'])) {
                $environment = $_ENV['APP_ENV'];
            }
        } catch (\Exception $e) {
            $environment = 'development';
        }
        
        // Set environment variables for CI3
        $_SERVER['CI_ENV'] = $environment;
        
        // Set dummy REQUEST_URI for artisan to avoid CI3 URI validation errors
        if (php_sapi_name() === 'cli' && !isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = '/';
            $_SERVER['REQUEST_METHOD'] = 'GET';
        }
        
        // Start output buffering to capture CI3 output
        ob_start();
        
        try {
            // Change to project root to keep relative paths stable
            $original_dir = getcwd();
            chdir(base_path());
            
            // Define CI3 constants
            if (!defined('BASEPATH')) {
                define('BASEPATH', rtrim($system_path, '/\\') . '/');
            }
            if (!defined('APPPATH')) {
                define('APPPATH', rtrim($application_path, '/\\') . '/');
            }
            if (!defined('VIEWPATH')) {
                define('VIEWPATH', APPPATH . 'views/');
            }
            if (!defined('ENVIRONMENT')) {
                define('ENVIRONMENT', $_SERVER['CI_ENV']);
            }
            
            // Set error reporting - hide deprecation noise from CI3 on PHP 8.2+
            if (ENVIRONMENT === 'production') {
                ini_set('display_errors', '0');
                error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_STRICT);
            } else {
                ini_set('display_errors', '1');
                error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
            }
            
            // Load bootstrap file
            require_once BASEPATH . 'core/Common.php';
            
            // Load constants
            if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/constants.php')) {
                require_once APPPATH . 'config/' . ENVIRONMENT . '/constants.php';
            }
            if (file_exists(APPPATH . 'config/constants.php')) {
                require_once APPPATH . 'config/constants.php';
            }
            
            // Define ICONV and MB constants
            if (!defined('ICONV_ENABLED')) {
                define('ICONV_ENABLED', extension_loaded('iconv'));
            }
            if (!defined('MB_ENABLED')) {
                define('MB_ENABLED', extension_loaded('mbstring'));
            }
            
            // Load core classes
            require_once BASEPATH . 'core/Benchmark.php';
            require_once BASEPATH . 'core/Hooks.php';
            require_once BASEPATH . 'core/Config.php';
            require_once BASEPATH . 'core/Utf8.php';
            require_once BASEPATH . 'core/URI.php';
            require_once BASEPATH . 'core/Router.php';
            require_once BASEPATH . 'core/Output.php';
            require_once BASEPATH . 'core/Security.php';
            require_once BASEPATH . 'core/Input.php';
            require_once BASEPATH . 'core/Lang.php';
            
            // Load Controller BEFORE Loader (Loader needs get_instance())
            require_once BASEPATH . 'core/Controller.php';
            
            // get_instance() is already defined in bootstrap/ci3.php
            // Just verify it exists
            if (!function_exists('get_instance')) {
                throw new \RuntimeException('get_instance() function must be defined before bootstrapping CI3');
            }
            
            // Now load Loader
            require_once BASEPATH . 'core/Loader.php';
            
            // Load Session library base classes (needed for MY_Session)
            if (file_exists(BASEPATH . 'libraries/Session/Session.php')) {
                require_once BASEPATH . 'libraries/Session/Session.php';
            }
            if (file_exists(BASEPATH . 'libraries/Session/Session_driver.php')) {
                require_once BASEPATH . 'libraries/Session/Session_driver.php';
            }

            if (file_exists(APPPATH . 'config/autoload.php')) {
                require_once APPPATH . 'config/autoload.php';
            }
            
            // Load MY_Controller if exists
            if (file_exists(APPPATH . 'core/MY_Controller.php')) {
                require_once APPPATH . 'core/MY_Controller.php';
            }
            
            // Initialize core components
            $BM  =& load_class('Benchmark', 'core');
            $EXT =& load_class('Hooks', 'core');
            $CFG =& load_class('Config', 'core');

            // Expose config early for MX Modules that expect global $CFG during Router load
            $GLOBALS['CFG'] =& $CFG;

            // Run pre_system hook BEFORE Router so OpenSID router can compile routes
            $EXT->call_hook('pre_system');

            $UNI =& load_class('Utf8', 'core');
            $URI =& load_class('URI', 'core');
            $RTR =& load_class('Router', 'core');
            $OUT =& load_class('Output', 'core');
            $SEC =& load_class('Security', 'core');
            $IN  =& load_class('Input', 'core');
            $LANG =& load_class('Lang', 'core');

            // Expose additional instances for legacy components that rely on globals
            $GLOBALS['URI'] =& $URI;
            $GLOBALS['RTR'] =& $RTR;
            
            // Mark benchmark
            $BM->mark('total_execution_time_start');
            $BM->mark('loading_time:_base_classes_start');

            // Mark benchmark (pre_system already executed)
            $BM->mark('loading_time:_base_classes_end');
            
            // Mark benchmark (pre_system already executed)
            $BM->mark('loading_time:_base_classes_end');
            
            // Create controller instance - prefer MY_Controller to get all properties initialized
            // MY_Controller constructor will set all the properties needed
            // EXCEPT when running in CLI mode (artisan) - use base CI_Controller to avoid web middleware
            if (php_sapi_name() !== 'cli' && class_exists('MY_Controller')) {
                $CI = new \MY_Controller();
            } else {
                $CI = new \CI_Controller();
            }
            
            // Store in global variable
            $GLOBALS['CI3'] =& $CI;
            self::$ci =& $CI;
            
            // Load session library IMMEDIATELY to setup $_SESSION bridge
            // This ensures $_SESSION works with Laravel session everywhere
            try {
                $CI->load->library('session');
                // Session bridge is now active - $_SESSION synced with Laravel
            } catch (\Exception $e) {
                if (config('ci3.debug')) {
                    logger()->warning("Failed to load session library: {$e->getMessage()}");
                }
            }
            
            // Load helpers from config
            $autoLoadHelpers = $autoload['helper'];
            foreach ($autoLoadHelpers as $helper) {
                try {
                    $CI->load->helper($helper);
                } catch (\Exception $e) {
                    if (config('ci3.debug')) {
                        logger()->warning("Failed to load helper: {$helper}", ['error' => $e->getMessage()]);
                    }
                }
            }
            
            // Clean output buffer
            self::$booting = false;
            
            return self::$ci;
            
        } catch (\Exception $e) {
            self::$booting = false;  // Reset booting flag on error
            
            self::$booted = true;
            
            return self::$ci;
            
        } catch (\Exception $e) {
            ob_end_clean();
            if (isset($original_dir)) {
                chdir($original_dir);
            }
            throw new \RuntimeException('Failed to bootstrap CI3: ' . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Get CI3 instance
     */
    public static function getInstance()
    {
        if (!self::$booted) {
            self::boot();
        }
        
        return self::$ci;
    }
    
    /**
     * Check if CI3 is booted
     */
    public static function isBooted()
    {
        return self::$booted;
    }
    
    /**
     * Auto-initialize properties dari MY_Controller menggunakan Reflection
     * Sistem ini otomatis mendeteksi semua public properties yang didefinisikan
     * di MY_Controller tanpa perlu registrasi manual satu per satu
     * 
     * @param \CI_Controller $instance
     * @return void
     */
    protected static function autoInitializeMYControllerProperties($instance)
    {
        // Cek apakah MY_Controller class exists
        if (!class_exists('MY_Controller')) {
            return;
        }
        
        try {
            // Gunakan Reflection untuk mendapatkan semua public properties dari MY_Controller
            $reflection = new \ReflectionClass('MY_Controller');
            $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
            
            foreach ($properties as $property) {
                $propertyName = $property->getName();
                
                // Skip jika property sudah di-set
                if (isset($instance->$propertyName)) {
                    continue;
                }
                
                // Skip setting dan list_setting - akan di-handle oleh initializeSettings()
                if (in_array($propertyName, ['setting', 'list_setting'])) {
                    continue;
                }
                
                // PENTING: Skip property yang TIDAK punya default value
                // Asumsi: property tanpa default value akan di-set di constructor
                // Ini memungkinkan constructor MY_Controller set nilai sendiri
                if (!$property->isDefault() || !self::hasDefaultValue($property)) {
                    continue;
                }
                
                // Initialize property berdasarkan nama atau tipe
                $instance->$propertyName = self::getDefaultPropertyValue($propertyName, $instance, $property);
            }
            
        } catch (\Exception $e) {
            if (config('ci3.debug')) {
                logger()->warning("Failed to auto-initialize MY_Controller properties: {$e->getMessage()}");
            }
        }
    }
    
}
