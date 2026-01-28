# CI3 Helpers Quick Reference

Quick reference untuk semua helper functions dan facades yang tersedia di CI3 setelah integrasi dengan Laravel.

## 📋 Table of Contents

1. [LaravelBridge Trait Methods](#1-laravelbridge-trait-methods)
2. [Laravel Helper Functions](#2-laravel-helper-functions)
3. [Laravel Facades (L-Prefix)](#3-laravel-facades-l-prefix)
4. [OpenSID Router Functions](#4-opensid-router-functions)

---

## 1. LaravelBridge Trait Methods

Semua method ini tersedia di controller CI3 yang extends `MY_Controller`:

### Configuration

```php
// Get config value
$value = $this->config('app.name');
$value = $this->config('app.env', 'production'); // with default

// Example
$appName = $this->config('app.name');
$debug = $this->config('app.debug', false);
```

### Cache

```php
// Store value in cache
$this->cache('key', 'value', 3600); // TTL in seconds

// Get value from cache
$value = $this->cache('key');
$value = $this->cache('key', 'default'); // with default

// Example
$this->cache('user_data', $userData, 600);
$cachedUser = $this->cache('user_data', []);
```

### Logging

```php
// Log messages
$this->log('info', 'User logged in', ['user_id' => 123]);
$this->log('error', 'Database error', ['query' => $sql]);
$this->log('warning', 'High memory usage');
$this->log('debug', 'Debug info', $data);

// Available levels: emergency, alert, critical, error, warning, notice, info, debug
```

### Authentication

```php
// Get authenticated user
$user = $this->user();

// Get user ID
$userId = $this->userId();

// Check if user is logged in
if ($this->is_logged_in()) {
    // User is authenticated
}

// Example
$user = $this->user();
if ($user) {
    echo "Hello, " . $user->name;
}
```

### Validation

```php
// Validate data
$validator = $this->validate($request->all(), [
    'email' => 'required|email',
    'password' => 'required|min:6',
    'name' => 'required|string|max:255',
]);

// Check if validation fails
if ($validator->fails()) {
    $errors = $validator->errors();
}

// Custom messages
$validator = $this->validate($data, $rules, [
    'email.required' => 'Email harus diisi',
    'password.min' => 'Password minimal 6 karakter',
]);
```

### Request

```php
// Get request data
$email = $this->request('email');
$name = $this->request('name', 'Guest'); // with default

// Get all request data
$data = $this->request();

// Example
$email = $this->request('email');
$password = $this->request('password');
```

### Response

```php
// JSON response
return $this->json(['status' => 'success', 'data' => $data]);
return $this->json(['error' => 'Not found'], 404);
return $this->json(['message' => 'Created'], 201);

// Example
public function api_user($id)
{
    $user = $this->User_model->find($id);
    
    if (!$user) {
        return $this->json(['error' => 'User not found'], 404);
    }
    
    return $this->json(['user' => $user]);
}
```

### Redirect

```php
// Redirect to URL
return $this->redirectTo('/home');
return $this->redirectTo('/dashboard');

// Redirect to named route
return $this->redirectToRoute('dashboard');
return $this->redirectToRoute('user.profile', ['id' => 123]);

// Example
public function logout()
{
    // ... logout logic ...
    return $this->redirectTo('/login');
}
```

### File Storage

```php
// Store file
$path = $this->store_file('uploads/file.pdf', $content);
$path = $this->store_file('uploads/file.pdf', $content, 'public');

// Example
$content = file_get_contents($_FILES['file']['tmp_name']);
$path = $this->store_file('documents/' . $filename, $content);
```

### Flash Messages

```php
// Set flash message
$this->flash('success', 'Data saved successfully!');
$this->flash('error', 'Failed to save data');
$this->flash('warning', 'This action is irreversible');
$this->flash('info', 'Please check your email');

// Get flash message in view (CI3)
$message = $this->session->flashdata('success');
```

---

## 2. Laravel Helper Functions

Functions yang tersedia globally di CI3 (via `laravel_helper.php`):

### config()

```php
// Get config value
$value = config('app.name');
$value = config('app.env', 'production');
$value = config('ci3.modules_path');

// Example
$modulesPath = config('ci3.modules_path', FCPATH . '../Modules/');
```

### cache()

```php
// Get cache instance
$cache = cache();

// Get value
$value = cache('key');
$value = cache('key', 'default');

// Store value
cache(['key' => 'value'], 3600);

// Example
cache(['user_' . $id => $userData], 600);
$userData = cache('user_' . $id);
```

### session()

```php
// Get session instance
$session = session();

// Get value
$value = session('key');
$value = session('key', 'default');

// Example
$userId = session('user_id');
$token = session('api_token', null);
```

### module_path()

```php
// Get modules path
$path = module_path(); // /path/to/Modules/

// Get specific module path
$path = module_path('Contoh'); // /path/to/Modules/Contoh/

// Example
$configPath = module_path('Contoh') . 'Config/config.php';
```

---

## 3. Laravel Facades (L-Prefix)

Class-class facade Laravel dengan prefix "L" untuk menghindari konflik dengan property CI3.

### LSession

```php
// Set value
LSession::put('key', 'value');
LSession::put('user_id', 123);

// Get value
$value = LSession::get('key');
$value = LSession::get('key', 'default');
$userId = LSession::get('user_id');

// Check existence
if (LSession::has('user_id')) {
    // Key exists
}

// Remove value
LSession::forget('key');
LSession::flush(); // Clear all

// Flash data
LSession::flash('message', 'Success!');
$message = LSession::get('message'); // Available once

// Example in controller
public function login()
{
    // ... authentication ...
    LSession::put('user_id', $user->id);
    LSession::put('user_name', $user->name);
    LSession::flash('success', 'Login successful!');
}
```

### LCache

```php
// Store value
LCache::put('key', 'value', 3600); // TTL in seconds
LCache::put('user_' . $id, $userData, 600);

// Get value
$value = LCache::get('key');
$value = LCache::get('key', 'default');

// Check existence
if (LCache::has('key')) {
    // Key exists
}

// Remove value
LCache::forget('key');
LCache::flush(); // Clear all

// Remember (get or store)
$value = LCache::remember('key', 3600, function() {
    return expensive_operation();
});

// Forever (no expiration)
LCache::forever('key', 'value');

// Example in controller
public function getUsers()
{
    $users = LCache::remember('all_users', 600, function() {
        return $this->User_model->get_all();
    });
    
    return $this->json(['users' => $users]);
}
```

### LLog

```php
// Log messages
LLog::info('User logged in', ['user_id' => 123]);
LLog::error('Database error', ['query' => $sql]);
LLog::warning('High memory usage');
LLog::debug('Debug info', $data);

// Available methods
LLog::emergency($message, $context);
LLog::alert($message, $context);
LLog::critical($message, $context);
LLog::error($message, $context);
LLog::warning($message, $context);
LLog::notice($message, $context);
LLog::info($message, $context);
LLog::debug($message, $context);

// Example
LLog::info('Data saved', [
    'model' => 'User',
    'id' => $user->id,
    'action' => 'create'
]);
```

### LConfig

```php
// Get config value
$value = LConfig::get('app.name');
$value = LConfig::get('app.env', 'production');

// Set config value (runtime only)
LConfig::set('app.custom', 'value');

// Example
$appName = LConfig::get('app.name');
$debug = LConfig::get('app.debug', false);
```

### LDB

```php
// Query builder
$users = LDB::table('users')->get();
$user = LDB::table('users')->where('id', 123)->first();

// Raw query
$results = LDB::select('SELECT * FROM users WHERE active = ?', [1]);

// Insert
LDB::table('users')->insert([
    'name' => 'John',
    'email' => 'john@example.com'
]);

// Update
LDB::table('users')->where('id', 123)->update(['name' => 'Jane']);

// Delete
LDB::table('users')->where('id', 123)->delete();

// Example
public function getUsersByRole($role)
{
    return LDB::table('users')
        ->where('role', $role)
        ->where('active', 1)
        ->orderBy('name')
        ->get();
}
```

---

## 4. OpenSID Router Functions

Functions dari `hooks_helper.php`:

### getHooks()

```php
// Get hooks from OpenSID router
$hooks = getHooks();

// Usage in application/config/hooks.php
require_once APPPATH . 'helpers/hooks_helper.php';
$hook = getHooks();
```

### getRoutes()

```php
// Load module routes
getRoutes();

// Usage in application/config/routes.php
require_once APPPATH . 'helpers/hooks_helper.php';
getRoutes();
```

---

## 💡 Usage Examples

### Example 1: Controller with Authentication

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Check authentication
        if (!$this->is_logged_in()) {
            return $this->redirectTo('/login');
        }
    }
    
    public function index()
    {
        // Get user
        $user = $this->user();
        
        // Log access
        $this->log('info', 'User accessed dashboard', [
            'user_id' => $user->id
        ]);
        
        // Load view
        $this->load->view('dashboard', ['user' => $user]);
    }
}
```

### Example 2: API Controller with Validation

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_Users extends MY_Controller
{
    public function create()
    {
        // Get request data
        $data = [
            'name' => $this->request('name'),
            'email' => $this->request('email'),
            'password' => $this->request('password'),
        ];
        
        // Validate
        $validator = $this->validate($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        
        if ($validator->fails()) {
            return $this->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }
        
        // Create user
        $user = $this->User_model->create($data);
        
        // Log
        LLog::info('User created', ['id' => $user->id]);
        
        // Response
        return $this->json([
            'status' => 'success',
            'user' => $user
        ], 201);
    }
}
```

### Example 3: Cached Data

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller
{
    public function monthly()
    {
        $month = $this->request('month', date('Y-m'));
        
        // Cache key
        $cacheKey = 'report_monthly_' . $month;
        
        // Get from cache or generate
        $report = LCache::remember($cacheKey, 3600, function() use ($month) {
            return $this->Report_model->generate_monthly($month);
        });
        
        return $this->json(['report' => $report]);
    }
}
```

### Example 4: Session Management

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function login()
    {
        // ... authentication logic ...
        
        if ($authenticated) {
            // Store in session
            LSession::put('user_id', $user->id);
            LSession::put('user_name', $user->name);
            LSession::put('user_role', $user->role);
            
            // Flash message
            $this->flash('success', 'Login successful!');
            
            // Log
            $this->log('info', 'User logged in', [
                'user_id' => $user->id
            ]);
            
            return $this->redirectTo('/dashboard');
        }
        
        $this->flash('error', 'Invalid credentials');
        return $this->redirectTo('/login');
    }
    
    public function logout()
    {
        $userId = LSession::get('user_id');
        
        // Clear session
        LSession::flush();
        
        // Log
        $this->log('info', 'User logged out', [
            'user_id' => $userId
        ]);
        
        $this->flash('info', 'You have been logged out');
        return $this->redirectTo('/login');
    }
}
```

---

## 🎯 Best Practices

1. **Use LaravelBridge trait methods** for consistency across controllers
2. **Use L-prefix Facades** (LSession, LCache) to avoid property conflicts
3. **Cache expensive operations** using LCache::remember()
4. **Always validate input** using $this->validate()
5. **Log important actions** using LLog or $this->log()
6. **Use flash messages** for user feedback after redirects
7. **Check authentication** in controller constructors
8. **Return JSON** for API endpoints using $this->json()

---

## 📚 See Also

- [CI3_SETUP.md](CI3_SETUP.md) - Complete setup guide
- [README.md](README.md) - Package documentation
- [CHANGELOG.md](CHANGELOG.md) - Version history
