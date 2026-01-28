# Publishing Package Assets

## Config File

Publish the CI3 configuration file to your application:

```bash
php artisan vendor:publish --tag=ci3-config
```

This will create `config/ci3.php` with the following configurations:
- `system_path` - Path to CI3 system directory
- `application_path` - Path to CI3 application directory
- `modules_path` - Path to modules directory
- `environment_map` - Laravel to CI3 environment mapping
- `auto_load_helpers` - Helpers to auto-load during bootstrap
- `debug` - Debug mode flag

## All Available Publish Tags

```bash
# Publish config file only
php artisan vendor:publish --tag=ci3-config

# Publish helper files
php artisan vendor:publish --tag=ci3-helpers

# Publish CI3 config files (hooks.php, modules.php)
php artisan vendor:publish --tag=ci3-config-files

# Publish core classes (MY_Controller.php)
php artisan vendor:publish --tag=ci3-core

# Publish libraries (MY_Session.php)
php artisan vendor:publish --tag=ci3-libraries

# Publish all CI3 files at once
php artisan vendor:publish --tag=ci3-all
```

## Usage Example

After publishing the config:

```php
// In your .env file
CI3_SYSTEM_PATH=/path/to/ci3/system
CI3_APPLICATION_PATH=/path/to/ci3/application
CI3_MODULES_PATH=/path/to/modules
CI3_DEBUG=true

// Access in code
$systemPath = config('ci3.system_path');
$helpers = config('ci3.auto_load_helpers');
```

## Classes Available After Publishing

### Controllers (from package)
- `OpenSID\LaravelCI3\Http\Controllers\CodeIgniterFallbackController` - Fallback controller for CI3 routes

### Middleware (from package)  
- `OpenSID\LaravelCI3\Http\Middleware\CI3RequestHandler` - Handles CI3 request routing
- `OpenSID\LaravelCI3\CodeIgniterFallback` - Fallback middleware for unmatched routes

### Usage in Routes

```php
use OpenSID\LaravelCI3\Http\Controllers\CodeIgniterFallbackController;
use OpenSID\LaravelCI3\Http\Middleware\CI3RequestHandler;

// Use middleware
Route::middleware(CI3RequestHandler::class)->group(function () {
    Route::get('/ci3-route', [CodeIgniterFallbackController::class, 'handle']);
});
```
