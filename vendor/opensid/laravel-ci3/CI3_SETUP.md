# CodeIgniter 3 Project Setup Guide

Panduan ini menjelaskan apa saja yang harus disesuaikan di project CodeIgniter 3 Anda agar bisa berintegrasi dengan Laravel menggunakan package `opensid/laravel-ci3`.

## Daftar Isi

1. [Helper Files](#1-helper-files)
2. [Config Files](#2-config-files)
3. [Core Classes](#3-core-classes)
4. [Libraries (Opsional)](#4-libraries-opsional)
5. [Struktur Direktori](#5-struktur-direktori)

---

## 1. Helper Files

### 1.1. hooks_helper.php

**Lokasi:** `application/helpers/hooks_helper.php`

Helper ini digunakan untuk menghubungkan OpenSID Router dengan CI3.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use OpenSID\Hook;
use OpenSID\RouteBuilder;

/**
 * Get hooks from OpenSID router
 * 
 * @return array
 */
function getHooks()
{
    // Get modules_locations from config
    $modulesLocations = get_instance()->config->item('modules_locations');
    
    // Fallback if CFG not ready yet
    if (empty($modulesLocations)) {
        $modulesLocations = [
            FCPATH . '../Modules/' => '../Modules/',
        ];
    }

    return Hook::registerFromModules($modulesLocations);
}

/**
 * Get routes from OpenSID router
 * 
 * @return void
 */
function getRoutes()
{
    // Set RouteBuilder as alias for OpenSID\RouteBuilder
    if (!class_exists('Route', false)) {
        class_alias(RouteBuilder::class, 'Route');
    }

    // Get modules_locations from config
    $modulesLocations = get_instance()->config->item('modules_locations');
    
    // Fallback if CFG not ready yet
    if (empty($modulesLocations)) {
        $modulesLocations = [
            FCPATH . '../Modules/' => '../Modules/',
        ];
    }

    RouteBuilder::autoload($modulesLocations);
}
```

### 1.2. laravel_helper.php

**Lokasi:** `application/helpers/laravel_helper.php`

Helper yang menyediakan fungsi-fungsi Laravel untuk digunakan di CI3.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('module_path')) {
    /**
     * Get the path to a module
     *
     * @param string $module Module name
     * @return string
     */
    function module_path($module = '')
    {
        $modulesPath = config('ci3.modules_path', FCPATH . '../Modules/');
        
        if (empty($module)) {
            return $modulesPath;
        }
        
        return $modulesPath . $module . '/';
    }
}

if (!function_exists('config')) {
    /**
     * Get Laravel config value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key, $default = null)
    {
        if (function_exists('app') && app()->bound('config')) {
            return app('config')->get($key, $default);
        }
        
        return $default;
    }
}

if (!function_exists('cache')) {
    /**
     * Get value from cache or store in cache
     *
     * @param string|array $key
     * @param mixed $default
     * @return mixed
     */
    function cache($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('cache');
        }

        if (is_array($key)) {
            return app('cache')->put(key($key), reset($key), $default);
        }

        return app('cache')->get($key, $default);
    }
}

if (!function_exists('session')) {
    /**
     * Get session instance or value
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('session');
        }

        return app('session')->get($key, $default);
    }
}
```

### 1.3. laravel_facades_helper.php

**Lokasi:** `application/helpers/laravel_facades_helper.php`

Helper yang menyediakan class-class facade Laravel untuk CI3. **Langsung pakai `Session`, `Cache`, `Log`, `DB` tanpa prefix!**

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Session - Laravel Session Facade for CI3
 * 
 * Usage: Session::get('key'), Session::put('key', 'value')
 */
if (!class_exists('Session')) {
    class Session
    {
        public static function __callStatic($method, $args)
        {
            return call_user_func_array([app('session'), $method], $args);
        }
    }
}

/**
 * Cache - Laravel Cache Facade for CI3
 * 
 * Usage: Cache::get('key'), Cache::put('key', 'value', 3600)
 */
if (!class_exists('Cache')) {
    class Cache
    {
        public static function __callStatic($method, $args)
        {
            return call_user_func_array([app('cache'), $method], $args);
        }
    }
}

/**
 * Log - Laravel Log Facade for CI3
 * 
 * Usage: Log::info('message')
 */
if (!class_exists('Log')) {
    class Log
    {
        public static function __callStatic($method, $args)
        {
            return call_user_func_array([app('log'), $method], $args);
        }
    }
}

/**
 * Config - Laravel Config Facade for CI3
 * 
 * Usage: Config::get('app.name')
 */
if (!class_exists('Config')) {
    class Config
    {
        public static function __callStatic($method, $args)
        {
            return call_user_func_array([app('config'), $method], $args);
        }
    }
}

/**
 * DB - Laravel DB Facade for CI3
 * 
 * Usage: DB::table('users')->get()
 */
if (!class_exists('DB')) {
    class DB
    {
        public static function __callStatic($method, $args)
        {
            return call_user_func_array([app('db'), $method], $args);
        }
    }
}
```

**Cara Penggunaan:**

```php
// Di controller CI3 - langsung pakai tanpa prefix "L"!
class Welcome extends MY_Controller
{
    public function index()
    {
        // Menggunakan Laravel Session - langsung Session::
        Session::put('key', 'value');
        $value = Session::get('key');
        
        // Menggunakan Laravel Cache - langsung Cache::
        Cache::put('key', 'value', 3600);
        $value = Cache::get('key');
        
        // Menggunakan Laravel Log - langsung Log::
        Log::info('This is a log message');
        
        // Menggunakan Laravel DB - langsung DB::
        $users = DB::table('users')->get();
    }
}
```

> **Note:** Jika ada konflik dengan property CI3 (`$this->session`, `$this->cache`), gunakan static facades di atas (`Session::`, `Cache::`). Property `$this->session` dan `$this->cache` tetap bisa digunakan untuk CI3 native methods.

---

## 2. Config Files

### 2.1. hooks.php

**Lokasi:** `application/config/hooks.php`

Config untuk mendaftarkan hooks dari OpenSID Router.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Require hooks_helper
require_once APPPATH . 'helpers/hooks_helper.php';

// Get hooks from OpenSID router
$hook = getHooks();
```

### 2.2. routes.php

**Lokasi:** `application/config/routes.php`

Config untuk mendaftarkan routes dari OpenSID Router (jika menggunakan module routing).

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// ... existing routes config ...

// Load module routes (if using OpenSID Router)
if (file_exists(APPPATH . 'helpers/hooks_helper.php')) {
    require_once APPPATH . 'helpers/hooks_helper.php';
    getRoutes();
}
```

### 2.3. autoload.php

**Lokasi:** `application/config/autoload.php`

Update autoload helper untuk memuat helper-helper Laravel.

```php
$autoload['helper'] = array(
    'laravel',           // Laravel helper functions (config, cache, session, module_path)
    'laravel_facades',   // Laravel Facades (Session, Cache, Log, DB, Config)
    // ... other helpers ...
);
```

> **Penting:** Setelah publish ci3-all, jangan lupa update autoload.php untuk memuat helper Laravel!

### 2.4. modules.php (Opsional)

**Lokasi:** `application/config/modules.php`

Config untuk modules locations jika menggunakan modular CI3.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Modules locations
$config['modules_locations'] = [
    FCPATH . '../Modules/' => '../Modules/',
];
```

---

## 3. Core Classes

### 3.1. MY_Controller.php

**Lokasi:** `application/core/MY_Controller.php`

Base controller yang menggunakan LaravelBridge trait untuk mengakses helper Laravel.

```php
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
```

**LaravelBridge Trait** menyediakan method-method berikut di controller CI3:

```php
// Config
$this->config('app.name');
$this->config('app.env', 'production');

// Cache
$this->cache('key', 'value', 3600);
$this->cache('key');

// Log
$this->log('info', 'Message');
$this->log('error', 'Error message');

// User Authentication
$user = $this->user();
$userId = $this->userId();

// Validation
$validator = $this->validate($request->all(), [
    'email' => 'required|email',
    'password' => 'required|min:6',
]);

// Response
return $this->json(['status' => 'success']);
return $this->json(['error' => 'Not found'], 404);

// Redirect
return $this->redirectTo('/home');
return $this->redirectToRoute('dashboard');
```

---

## 4. Libraries (Opsional)

### 4.1. MY_Session.php

**Lokasi:** `application/libraries/MY_Session.php`

Extended session library yang menggunakan Laravel session secara langsung. **OPSIONAL** - hanya jika Anda ingin session CI3 sync dengan Laravel.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Session - Extended Session Library
 * 
 * Override CI_Session methods to use Laravel session directly
 */
class MY_Session extends CI_Session
{
    /**
     * Laravel session instance
     * @var mixed
     */
    protected $laravelSession;
    
    /**
     * Singleton flag to prevent multiple initialization
     * @var bool
     */
    protected static $initialized = false;

    /**
     * Constructor
     *
     * @param array $params Configuration parameters
     * @return void
     */
    public function __construct($params = array())
    {
        // Get Laravel session instance
        if (function_exists('app') && app()->bound('session')) {
            $this->laravelSession = app('session');
            
            // CRITICAL: DO NOT start session here!
            // Laravel's StartSession middleware has already started the session
            
            // Register shutdown function to save Laravel session (only once)
            if (!self::$initialized) {
                register_shutdown_function(array($this, 'save_session'));
                self::$initialized = true;
            }
        } else {
            // Fallback to native CI session
            parent::__construct($params);
        }
    }

    /**
     * Set session data
     *
     * @param mixed $data
     * @param mixed $value
     * @return void
     */
    public function set_userdata($data, $value = NULL)
    {
        if ($this->laravelSession) {
            if (is_array($data)) {
                foreach ($data as $key => $val) {
                    $this->laravelSession->put($key, $val);
                }
            } else {
                $this->laravelSession->put($data, $value);
            }
        } else {
            parent::set_userdata($data, $value);
        }
    }

    /**
     * Get session data
     *
     * @param string $key
     * @return mixed
     */
    public function userdata($key = NULL)
    {
        if ($this->laravelSession) {
            if ($key === NULL) {
                return $this->laravelSession->all();
            }
            return $this->laravelSession->get($key);
        }
        
        return parent::userdata($key);
    }

    /**
     * Check if session key exists
     *
     * @param string $key
     * @return bool
     */
    public function has_userdata($key)
    {
        if ($this->laravelSession) {
            return $this->laravelSession->has($key);
        }
        
        return parent::has_userdata($key);
    }

    /**
     * Remove session data
     *
     * @param string $key
     * @return void
     */
    public function unset_userdata($key)
    {
        if ($this->laravelSession) {
            if (is_array($key)) {
                foreach ($key as $k) {
                    $this->laravelSession->forget($k);
                }
            } else {
                $this->laravelSession->forget($key);
            }
        } else {
            parent::unset_userdata($key);
        }
    }

    /**
     * Save Laravel session
     */
    public function save_session()
    {
        if ($this->laravelSession && $this->laravelSession->isStarted()) {
            $this->laravelSession->save();
        }
    }
}
```

---

## 5. Struktur Direktori

Struktur minimal yang dibutuhkan:

```
application/
├── config/
│   ├── autoload.php       # Auto-load helpers
│   ├── hooks.php          # Register hooks from OpenSID Router
│   ├── routes.php         # (optional) Register module routes
│   └── modules.php        # (optional) Modules locations
├── core/
│   └── MY_Controller.php  # Base controller with LaravelBridge
├── helpers/
│   ├── hooks_helper.php   # OpenSID Router hooks
│   ├── laravel_helper.php # Laravel helper functions
│   └── laravel_facades_helper.php # Laravel Facades (LSession, LCache)
└── libraries/
    └── MY_Session.php     # (optional) Laravel session integration
```

---

## Cara Setup dari Awal

### 1. Install Package

Tambahkan ke `composer.json` Laravel Anda:

```json
{
    "require": {
        "opensid/laravel-ci3": "^1.0"
    }
}
```

```bash
composer update
```

### 2. Publish Config Laravel

```bash
php artisan vendor:publish --tag=ci3-config
```

Edit `config/ci3.php` sesuai kebutuhan:

```php
return [
    'system_path' => env('CI3_SYSTEM_PATH', base_path('vendor/codeigniter/framework/system')),
    'application_path' => env('CI3_APPLICATION_PATH', base_path('application')),
    'modules_path' => env('CI3_MODULES_PATH', base_path('Modules')),
    // ...
];
```

### 3. Register Service Provider

Di `config/app.php`:

```php
'providers' => [
    // ...
    OpenSID\LaravelCI3\Providers\CodeIgniterServiceProvider::class,
],
```

### 4. Register Middleware

Di `app/Http/Kernel.php`:

```php
protected $middleware = [
    // ...
    \OpenSID\LaravelCI3\CodeIgniterFallback::class,
];
```

### 5. Publish CI3 Files

**Option A: Publish semua file sekaligus (Recommended)**

```bash
php artisan vendor:publish --tag=ci3-all
```

Ini akan meng-copy semua file berikut ke project CI3 Anda:
- `application/helpers/hooks_helper.php`
- `application/helpers/laravel_helper.php`
- `application/helpers/laravel_facades_helper.php`
- `application/config/hooks.php`
- `application/config/modules.php`
- `application/core/MY_Controller.php`
- `application/libraries/MY_Session.php`

**Option B: Publish file per file**

```bash
# Publish helpers only
php artisan vendor:publish --tag=ci3-helpers

# Publish config files only
php artisan vendor:publish --tag=ci3-config-files

# Publish core classes only
php artisan vendor:publish --tag=ci3-core

# Publish libraries only
php artisan vendor:publish --tag=ci3-libraries
```

### 6. Update CI3 Autoload

Edit `application/config/autoload.php`:

```php
$autoload['helper'] = array(
    'laravel',           // Laravel helper functions
    'laravel_facades',   // Laravel Facades (Session, Cache, Log, etc.)
    // ... other helpers ...
);
```

### 7. Test

```bash
php artisan serve
```

Akses route CI3 Anda, misalnya: `http://localhost:8000/welcome`

---

## Troubleshooting

### Session Error "ini_set() has already been run"

**Solusi:** Jangan load session di `MY_Controller::__construct()`. Session akan di-assign otomatis oleh `CodeIgniterFallback` middleware.

### CI3 Routes Tidak Ditemukan

**Solusi:**
1. Pastikan `CodeIgniterFallback` middleware terdaftar di `app/Http/Kernel.php`
2. Pastikan `hooks_helper.php` sudah dibuat dan di-require di `application/config/hooks.php`
3. Clear cache: `php artisan config:clear`

### Module Routes Tidak Bekerja

**Solusi:**
1. Pastikan `modules_locations` sudah di-define di `application/config/modules.php`
2. Pastikan path modules benar di `config/ci3.php`
3. Pastikan `getRoutes()` dipanggil di `application/config/routes.php`

---

## Tips & Best Practices

1. **Gunakan `php artisan vendor:publish --tag=ci3-all`** untuk setup awal yang lebih cepat - semua file ter-copy otomatis!

2. **Gunakan Environment Variables** untuk path CI3 agar lebih fleksibel:
   ```env
   CI3_SYSTEM_PATH=/path/to/ci3/system
   CI3_APPLICATION_PATH=/path/to/ci3/application
   CI3_MODULES_PATH=/path/to/modules
   ```

3. **Gunakan LaravelBridge Trait** di semua controller CI3 untuk akses helper Laravel.

4. **Gunakan Static Facades** (`Session::`, `Cache::`, `Log::`) untuk Laravel services - langsung tanpa prefix "L"!

5. **Jangan Load Session** di constructor controller - biarkan middleware yang handle.

6. **Gunakan logger()** Laravel daripada CI3 `log_message()` untuk konsistensi logging.

7. **Re-publish jika ada update package:**
   ```bash
   php artisan vendor:publish --tag=ci3-all --force
   ```

---

## Lihat Juga

- [README.md](README.md) - Dokumentasi utama package
- [Laravel Documentation](https://laravel.com/docs)
- [CodeIgniter 3 Documentation](https://codeigniter.com/userguide3)
