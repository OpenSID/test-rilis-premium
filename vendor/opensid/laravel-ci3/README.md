# OpenSID Laravel-CI3 Bridge

Complete Laravel-CodeIgniter 3 Integration Package - Middleware, Service Providers, Traits, and utilities for seamlessly running CI3 within Laravel.

## 🚀 Instalasi Otomatis (1 Perintah)

```bash
composer require opensid/laravel-ci3
php artisan ci3:install
```

**Selesai!** Setup lengkap dalam 30 detik. 

**Available Commands:**
- `php artisan ci3:install` - Install & configure otomatis
- `php artisan ci3:test` - Validasi setup
- `php artisan ci3:config` - Kelola konfigurasi

Lihat [Quick Setup](QUICK_SETUP.md) atau [Panduan Automation](AUTOMATION_GUIDE.md) untuk detail lengkap.

## 📚 Documentation

📖 **Complete documentation untuk semua scenarios:**

### Getting Started
- **[QUICK_SETUP](QUICK_SETUP.md)** - ⚡ **START HERE!** 1 perintah setup (30 detik)
- **[AUTOMATION_GUIDE](AUTOMATION_GUIDE.md)** - 🤖 Automation tools lengkap (ci3:install, ci3:test, ci3:config)
- **[MIGRATION_GUIDE](MIGRATION_GUIDE.md)** - 📖 Migrasi project CI3 yang sudah ada ke Laravel (Step-by-Step)
- **[QUICK_START](../../QUICK_START_CI3_MIGRATION.md)** - 🚀 Quick start 10 menit untuk existing CI3 project
- **[ARCHITECTURE](ARCHITECTURE.md)** - 🏗️ Diagram arsitektur dan flow integrasi

### Setup & Configuration
- **[CI3_SETUP](CI3_SETUP.md)** - Panduan lengkap setup CI3 project (helpers, config, core classes)
- **[README](README.md)** - Package installation and configuration

### Reference
- **[CI3_HELPERS_REFERENCE](CI3_HELPERS_REFERENCE.md)** - Quick reference untuk semua helper functions dan facades
- **[MODULE_NAMESPACE_GUIDE](MODULE_NAMESPACE_GUIDE.md)** - 📂 Panduan namespace untuk Module routing
- **[MODULE_REGISTRATION_GUIDE](MODULE_REGISTRATION_GUIDE.md)** - 📝 Cara register module routes (explicit, tidak auto-load)
- **[FAQ](FAQ.md)** - 🤔 Frequently Asked Questions & troubleshooting
- **[CHANGELOG](CHANGELOG.md)** - Version history

### Quick Links
- 🆘 [Troubleshooting](CI3_SETUP.md#troubleshooting)
- ✅ [Checklist Migrasi](MIGRATION_GUIDE.md#checklist-migrasi)
- 💡 [Best Practices](MIGRATION_GUIDE.md#best-practices)

## Features

### Core Components

- **CodeIgniterFallback Middleware**: Intercepts 404 responses and tries CI3 routing
- **CodeIgniterServiceProvider**: Bootstraps CI3 and registers it in Laravel container
- **CI3RouteServiceProvider**: Registers CI3 routes into Laravel routing system
- **LaravelBridge Trait**: Provides Laravel helpers access within CI3 controllers

### Capabilities

- **Module Support**: Automatically handles CI3 module routes via OpenSID ModuleRouter
- **Response Handling**: Supports Laravel Response, View, and CI3 output
- **DebugBar Integration**: Automatically injects Laravel DebugBar into CI3 responses
- **Session Bridge**: Seamlessly shares session between Laravel and CI3
- **Service Container**: CI3 instance accessible via `app('ci')` or `ci3()` helper

## Installation

```bash
composer require opensid/laravel-ci3
```

### Quick Setup

```bash
# 1. Publish Laravel config
php artisan vendor:publish --tag=ci3-config

# 2. Publish all CI3 files (helpers, config, core, libraries)
php artisan vendor:publish --tag=ci3-all

# 3. Update application/config/autoload.php
# Add 'laravel' and 'laravel_facades' to helper array
```

**That's it!** Package will auto-copy all required files to your CI3 application folder.

### Published Files

When you run `php artisan vendor:publish --tag=ci3-all`, these files will be created:

- `application/helpers/hooks_helper.php` - OpenSID Router integration
- `application/helpers/laravel_helper.php` - Laravel helper functions
- `application/helpers/laravel_facades_helper.php` - Laravel Facades (Session, Cache, Log, DB)
- `application/helpers/module_routes_helper.php` - Module routing automation
- `application/config/hooks.php` - CI3 hooks configuration
- `application/config/modules.php` - Modules location configuration
- `application/core/MY_Controller.php` - Base controller with LaravelBridge trait
- `application/libraries/MY_Session.php` - Laravel session integration

### Publish Options

```bash
# Publish everything at once (recommended)
php artisan vendor:publish --tag=ci3-all

# Or publish selectively:
php artisan vendor:publish --tag=ci3-config      # Laravel config only
php artisan vendor:publish --tag=ci3-helpers     # CI3 helpers only
php artisan vendor:publish --tag=ci3-config-files # CI3 config files only
php artisan vendor:publish --tag=ci3-core        # CI3 core classes only
php artisan vendor:publish --tag=ci3-libraries   # CI3 libraries only
```

## Configuration

### 1. Register Service Providers

Add to `config/app.php`:

```php
'providers' => [
    // ...
    OpenSID\LaravelCI3\Providers\CodeIgniterServiceProvider::class,
],
```

### 2. Register Middleware

Add to `app/Http/Kernel.php`:

```php
protected $middleware = [
    // ... other middleware
    \Illuminate\Session\Middleware\StartSession::class, // MUST be before CodeIgniterFallback
    \OpenSID\LaravelCI3\CodeIgniterFallback::class,
];
```

### 3. Update Autoload (IMPORTANT!)

Edit `application/config/autoload.php`:

```php
$autoload['helper'] = array(
    'laravel',           // Laravel helper functions
    'laravel_facades',   // Laravel Facades (Session, Cache, Log, etc.)
    // ... other helpers ...
);
```

### 4. Customize Paths (Optional)

If your CI3 paths are different, edit `config/ci3.php`:

```php
return [
    'system_path' => base_path('vendor/codeigniter/framework/system'),
    'application_path' => base_path('application'),
    'modules_path' => base_path('Modules'),
    
    // Or use environment variables
    'system_path' => env('CI3_SYSTEM_PATH', base_path('vendor/codeigniter/framework/system')),
];
```

You can also set in `.env`:

```env
CI3_SYSTEM_PATH=/path/to/system
CI3_APPLICATION_PATH=/path/to/application
CI3_MODULES_PATH=/path/to/modules
CI3_DEBUG=true
```

## Usage

### In CI3 Controllers

```php
class DemoController extends MY_Controller 
{
    public function index() 
    {
        // Use Laravel view() helper
        return view('demo.index', ['title' => 'Hello']);
    }
    
    public function api() 
    {
        // Use Laravel response() helper
        return response()->json(['status' => 'ok']);
    }
    
    public function cache_example() 
    {
        // Use LaravelBridge methods
        $data = $this->remember('cache_key', function() {
            return expensive_operation();
        }, 3600);
        
        // Use Laravel config
        $appName = $this->config('app.name');
        
        // Use Laravel auth
        $user = $this->user();
        $isLoggedIn = $this->is_logged_in();
    }
}
```

### Access CI3 from Laravel

```php
// In Laravel code
$ci = app('ci'); // Get CI3 instance
$ci->load->model('user_model');
$users = $ci->user_model->get_all();

// Or use helper
$ci = ci3();
```

## Package Structure

```
src/
├── CodeIgniterFallback.php          # Main middleware
├── Providers/
│   ├── CodeIgniterServiceProvider.php   # Bootstraps CI3
│   └── CI3RouteServiceProvider.php      # Routes registration
├── Services/
│   ├── CI3Bootstrap.php                 # CI3 bootstrap logic
│   ├── CI3Instance.php                  # CI3 instance wrapper
│   └── DebugBar.php                     # Debug bar renderer
└── Traits/
    └── LaravelBridge.php                # Laravel helpers for CI3
```

## LaravelBridge Methods

The trait provides these helper methods in CI3 controllers:

- `config($key, $default)` - Access Laravel config
- `remember($key, $callback, $ttl)` - Cache helper
- `log($message, $level, $context)` - Logger helper
- `user()` - Get authenticated user
- `is_logged_in()` - Check authentication
- `validate($data, $rules, $messages)` - Laravel validation
- `request($key, $default)` - Access request data
- `store_file($path, $content, $disk)` - File storage
- `flash($type, $message)` - Flash messages

## CodeIgniter 3 Project Setup

Package ini sudah include semua file yang dibutuhkan dan bisa di-publish otomatis!

### Quick Setup (3 Steps)

1. **Publish all CI3 files:**
   ```bash
   php artisan vendor:publish --tag=ci3-all
   ```

2. **Update autoload.php:**
   Edit `application/config/autoload.php`:
   ```php
   $autoload['helper'] = array('laravel', 'laravel_facades');
   ```

3. **Done!** Sekarang bisa langsung pakai facades Laravel di CI3:
   ```php
   // Di controller CI3 - langsung pakai Session::, Cache::, Log::
   Session::put('user_id', 123);
   Cache::put('key', 'value', 3600);
   Log::info('User logged in');
   DB::table('users')->get();
   ```

### Published Files Structure

```
application/
├── config/
│   ├── hooks.php          # ✅ Auto-published
│   └── modules.php        # ✅ Auto-published
├── core/
│   └── MY_Controller.php  # ✅ Auto-published (with LaravelBridge trait)
├── helpers/
│   ├── hooks_helper.php   # ✅ Auto-published
│   ├── laravel_helper.php # ✅ Auto-published
│   └── laravel_facades_helper.php # ✅ Auto-published
└── libraries/
    └── MY_Session.php     # ✅ Auto-published (Laravel session sync)
```

**📖 Lihat [CI3_SETUP.md](CI3_SETUP.md) untuk panduan lengkap dan template code.**

## Available Facades (No Prefix!)

Setelah publish, Anda bisa langsung pakai facades Laravel tanpa prefix "L":

```php
// Langsung pakai Session, Cache, Log - tanpa prefix!
Session::put('key', 'value');
$value = Session::get('key');

Cache::put('key', 'value', 3600);
$value = Cache::get('key');

Log::info('This is a message');
Log::error('Something went wrong');

DB::table('users')->where('active', 1)->get();

Config::get('app.name');
```

> **No more LSession, LCache!** Sekarang langsung `Session::`, `Cache::`, `Log::`

## Requirements

- PHP 8.1+
- Laravel 10.x or 11.x
- CodeIgniter 3.x
- opensid/router package

## How It Works

1. **Bootstrap**: `CodeIgniterServiceProvider` boots CI3 early in Laravel lifecycle
2. **Routing**: `CodeIgniterFallback` middleware intercepts 404s and tries CI3 routing
3. **Module Routes**: Automatically loads from `Modules/*/Routes/web.php` via `OpenSID\ModuleRouter`
4. **Session Sharing**: Both frameworks share the same Laravel session
5. **Response Handling**: CI3 can return Laravel Response, View, or traditional output

## License

MIT
