# Migration Guide: Existing CI3 Project to Laravel

Panduan lengkap untuk mengintegrasikan project CodeIgniter 3 yang sudah ada ke dalam Laravel menggunakan package `opensid/laravel-ci3`.

## 📋 Prerequisites

Pastikan Anda memiliki:
- ✅ Project CI3 yang sudah berjalan
- ✅ PHP 8.1 atau lebih tinggi
- ✅ Composer installed
- ✅ Database yang sudah dikonfigurasi

---

## 🎯 Scenario: Anda Sudah Punya Project CI3

Misalnya struktur project Anda saat ini:

```
my-project/
├── application/          # CI3 application folder
│   ├── controllers/
│   ├── models/
│   ├── views/
│   ├── config/
│   └── ...
├── system/              # CI3 system folder (atau via vendor)
├── public/              # atau index.php di root
│   └── index.php
├── Modules/             # (optional) jika pakai HMVC
└── composer.json        # (optional) jika sudah pakai composer
```

---

## 🚀 Langkah-langkah Migrasi

### Step 1: Install Laravel di Project Root

**Option A: Laravel Baru di Project Root**

Jika belum ada Laravel, install Laravel di folder yang sama dengan CI3:

```bash
# Buat Laravel project baru
composer create-project laravel/laravel temp-laravel
```

Kemudian pindahkan file-file Laravel ke root project:

```bash
# Windows PowerShell
Move-Item temp-laravel/* . -Force
Remove-Item temp-laravel -Recurse -Force

# Linux/Mac
mv temp-laravel/* .
rm -rf temp-laravel
```

**Option B: Sudah Ada Laravel**

Skip step ini jika Laravel sudah terinstall.

---

### Step 2: Install Package opensid/laravel-ci3

Tambahkan package ke `composer.json`:

```json
{
    "require": {
        "opensid/laravel-ci3": "^1.0"
    }
}
```

Install via composer:

```bash
composer require opensid/laravel-ci3
```

---

### Step 3: Publish Config Laravel

Publish config package untuk customize paths:

```bash
php artisan vendor:publish --tag=ci3-config
```

Edit `config/ci3.php` sesuai struktur project CI3 Anda:

```php
return [
    // Path ke system folder CI3
    'system_path' => env('CI3_SYSTEM_PATH', base_path('system')),
    
    // Path ke application folder CI3
    'application_path' => env('CI3_APPLICATION_PATH', base_path('application')),
    
    // Path ke Modules folder (jika pakai HMVC)
    'modules_path' => env('CI3_MODULES_PATH', base_path('Modules')),
    
    // Environment mapping Laravel -> CI3
    'environment_map' => [
        'local' => 'development',
        'staging' => 'testing',
        'production' => 'production',
    ],
    
    // Auto-load helpers
    'auto_load_helpers' => [
        'laravel',
        'laravel_facades',
    ],
    
    // Debug mode
    'debug' => env('CI3_DEBUG', false),
];
```

**Atau gunakan environment variables** di `.env`:

```env
CI3_SYSTEM_PATH=/path/to/ci3/system
CI3_APPLICATION_PATH=/path/to/ci3/application
CI3_MODULES_PATH=/path/to/modules
CI3_DEBUG=true
```

---

### Step 4: Publish CI3 Integration Files

Publish file-file helper, config, dan core classes:

```bash
php artisan vendor:publish --tag=ci3-all
```

Ini akan membuat file-file berikut di folder `application/`:

- ✅ `application/helpers/hooks_helper.php`
- ✅ `application/helpers/laravel_helper.php`
- ✅ `application/helpers/laravel_facades_helper.php`
- ✅ `application/config/hooks.php` ⚠️ **BACKUP dulu jika sudah ada!**
- ✅ `application/config/modules.php`
- ✅ `application/core/MY_Controller.php` ⚠️ **BACKUP dulu jika sudah ada!**
- ✅ `application/libraries/MY_Session.php` ⚠️ **BACKUP dulu jika sudah ada!**

**⚠️ PENTING:** Jika file-file ini sudah ada di project CI3 Anda:

```bash
# Backup file-file existing
cp application/config/hooks.php application/config/hooks.php.backup
cp application/core/MY_Controller.php application/core/MY_Controller.php.backup
cp application/libraries/MY_Session.php application/libraries/MY_Session.php.backup

# Kemudian publish dengan force
php artisan vendor:publish --tag=ci3-all --force
```

---

### Step 5: Update MY_Controller (Manual Merge)

Jika Anda sudah punya `MY_Controller.php` dengan custom logic, **jangan overwrite**! 

Merge manual dengan menambahkan `LaravelBridge` trait:

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use OpenSID\LaravelCI3\Traits\LaravelBridge;

class MY_Controller extends CI_Controller 
{
    use LaravelBridge;  // ← Tambahkan ini

    public function __construct()
    {
        parent::__construct();
        
        // Initialize Laravel Bridge
        $this->initLaravelBridge();  // ← Tambahkan ini
        
        // Your existing constructor code here...
        // $this->load->library('session');  ← HAPUS ini jika ada
        // $this->load->model('user_model');
        // etc...
    }
    
    // Your existing methods...
}
```

**⚠️ PENTING:** Hapus `$this->load->library('session')` dari constructor karena session sudah di-handle otomatis.

---

### Step 6: Update CI3 Autoload Helper

Edit `application/config/autoload.php`, tambahkan helper Laravel:

```php
$autoload['helper'] = array(
    'laravel',           // ← Tambahkan
    'laravel_facades',   // ← Tambahkan
    // ... existing helpers ...
    'url',
    'form',
    // etc...
);
```

---

### Step 7: Register Service Provider

Edit `config/app.php`, tambahkan provider:

```php
'providers' => [
    // ... existing providers ...
    
    /*
     * Package Service Providers...
     */
    OpenSID\LaravelCI3\Providers\CodeIgniterServiceProvider::class,  // ← Tambahkan
],
```

---

### Step 8: Register Middleware

Edit `app/Http/Kernel.php`, tambahkan middleware:

```php
protected $middleware = [
    // \App\Http\Middleware\TrustHosts::class,
    \App\Http\Middleware\TrustProxies::class,
    \Illuminate\Http\Middleware\HandleCors::class,
    \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
    \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
    \App\Http\Middleware\TrimStrings::class,
    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    
    // ← PENTING: StartSession HARUS sebelum CodeIgniterFallback
    \Illuminate\Session\Middleware\StartSession::class,
    
    // ← Tambahkan ini di paling bawah
    \OpenSID\LaravelCI3\CodeIgniterFallback::class,
];
```

**⚠️ CRITICAL:** `StartSession` middleware HARUS ada SEBELUM `CodeIgniterFallback`!

---

### Step 9: Update Public Index (Optional)

Jika CI3 `index.php` Anda ada di `public/` folder dan ingin tetap akses via CI3 native:

**Option A: Biarkan Laravel Handle Semua**

Hapus atau rename `public/index.php` CI3:

```bash
# Backup CI3 index
mv public/index.php public/index.ci3.php
```

Laravel akan handle semua request, dan fallback ke CI3 via middleware.

**Option B: Dual Entry Point**

Buat `public/ci3.php` untuk direct CI3 access:

```bash
cp public/index.ci3.php public/ci3.php
```

Akses:
- Laravel: `http://localhost/`
- CI3 Direct: `http://localhost/ci3.php/controller`

---

### Step 10: Clear Cache & Test

```bash
# Clear Laravel cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Test Laravel
php artisan inspire

# Start server
php artisan serve
```

---

## 🧪 Testing

### Test 1: Akses Route CI3

Buka browser: `http://localhost:8000/welcome`

Jika controller CI3 `Welcome.php` ada, harus bisa diakses.

### Test 2: Test Laravel Facades di CI3

Buat test controller `application/controllers/Test.php`:

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller
{
    public function index()
    {
        // Test Session facade
        Session::put('test', 'Hello from CI3!');
        echo "Session: " . Session::get('test') . "<br>";
        
        // Test Cache facade
        Cache::put('test', 'Cached value', 60);
        echo "Cache: " . Cache::get('test') . "<br>";
        
        // Test Log facade
        Log::info('Test from CI3 controller');
        echo "Log: Written to Laravel logs<br>";
        
        // Test Config facade
        echo "Config: " . Config::get('app.name') . "<br>";
        
        echo "<hr><strong>✅ All facades working!</strong>";
    }
}
```

Akses: `http://localhost:8000/test`

### Test 3: Test LaravelBridge Trait

Di controller CI3:

```php
<?php
class Dashboard extends MY_Controller
{
    public function index()
    {
        // LaravelBridge trait methods
        $user = $this->user();           // Get authenticated user
        $config = $this->config('app.name');
        $cached = $this->cache('key');
        
        $this->log('info', 'Dashboard accessed');
        
        return $this->json(['status' => 'success']);
    }
}
```

---

## 🔧 Common Issues & Solutions

### Issue 1: Session Error "ini_set() already run"

**Cause:** Session loaded di controller constructor

**Solution:** Hapus `$this->load->library('session')` dari `MY_Controller::__construct()`

### Issue 2: CI3 Routes Tidak Ditemukan

**Cause:** Middleware tidak terdaftar atau order salah

**Solution:**
1. Check `CodeIgniterFallback` ada di `app/Http/Kernel.php`
2. Check `StartSession` ada SEBELUM `CodeIgniterFallback`
3. Clear cache: `php artisan config:clear`

### Issue 3: Module Routes Tidak Bekerja

**Cause:** Path modules salah di config

**Solution:**
1. Check `config/ci3.php` - `modules_path` benar
2. Check `application/config/modules.php` - `modules_locations` benar
3. Check helper `hooks_helper.php` ter-load di autoload

### Issue 4: Cannot Redeclare getHooks()

**Cause:** Function `getHooks()` sudah ada di package lain (opensid/router)

**Solution:** File `hooks_helper.php` sudah pakai `function_exists()` check. Delete dan re-publish:

```bash
rm application/helpers/hooks_helper.php
php artisan vendor:publish --tag=ci3-helpers --force
```

### Issue 5: FCPATH Not Defined

**Cause:** Config modules.php di-load sebelum CI3 constants ready

**Solution:** Sudah di-handle di stub dengan fallback:

```php
$config['modules_locations'] = [
    defined('FCPATH') ? FCPATH . '../Modules/' : dirname(APPPATH) . '/Modules/' => '../Modules/',
];
```

---

## 📝 Checklist Migrasi

### Pre-Migration

- [ ] Backup database
- [ ] Backup semua file project CI3
- [ ] Test CI3 project masih berjalan normal
- [ ] Catat semua custom configurations

### Installation

- [ ] Install Laravel (jika belum ada)
- [ ] Install package `opensid/laravel-ci3`
- [ ] Publish config: `php artisan vendor:publish --tag=ci3-config`
- [ ] Configure paths di `config/ci3.php` atau `.env`

### CI3 Files

- [ ] Backup `application/config/hooks.php` (jika ada)
- [ ] Backup `application/core/MY_Controller.php` (jika ada)
- [ ] Backup `application/libraries/MY_Session.php` (jika ada)
- [ ] Publish: `php artisan vendor:publish --tag=ci3-all`
- [ ] Merge manual MY_Controller jika ada custom logic
- [ ] Update `application/config/autoload.php` - add helpers

### Laravel Configuration

- [ ] Register `CodeIgniterServiceProvider` di `config/app.php`
- [ ] Register `CodeIgniterFallback` middleware di `app/Http/Kernel.php`
- [ ] Verify `StartSession` BEFORE `CodeIgniterFallback`
- [ ] Configure database di `.env`

### Testing

- [ ] Clear cache: `php artisan config:clear`
- [ ] Test Laravel: `php artisan inspire`
- [ ] Test CI3 route: `/welcome` atau existing controller
- [ ] Test facades: Create test controller
- [ ] Test LaravelBridge trait methods
- [ ] Check logs: `storage/logs/laravel.log`

### Production

- [ ] Set `APP_ENV=production` di `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `CI3_DEBUG=false`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Setup queue workers (if needed)
- [ ] Setup cron jobs (if needed)

---

## 🎯 Best Practices

### 1. Gradual Migration

Tidak perlu migrate semua sekaligus:

- **Phase 1:** Setup integration, test existing CI3 routes
- **Phase 2:** Add new features in Laravel
- **Phase 3:** Gradually rewrite CI3 controllers to Laravel
- **Phase 4:** Full Laravel when ready

### 2. Keep CI3 Controllers Clean

```php
// Good: Use LaravelBridge methods
class User extends MY_Controller
{
    public function profile()
    {
        $user = $this->user();  // LaravelBridge
        $this->cache('user_' . $user->id, $user, 600);
        return $this->json(['user' => $user]);
    }
}

// Avoid: Direct CI3 methods for new code
class User extends MY_Controller
{
    public function profile()
    {
        $user = $this->session->userdata('user');  // Old way
        // ...
    }
}
```

### 3. Use Laravel Services

Leverage Laravel's powerful features:

```php
// In CI3 controller - use Laravel DB
$users = DB::table('users')
    ->where('active', 1)
    ->orderBy('name')
    ->get();

// Use Laravel Cache
Cache::remember('popular_posts', 3600, function() {
    return DB::table('posts')
        ->where('views', '>', 1000)
        ->get();
});

// Use Laravel Log
Log::info('User logged in', ['user_id' => $userId]);
```

### 4. Environment-Specific Config

Use `.env` for different environments:

```env
# Development
APP_ENV=local
CI3_DEBUG=true
CI3_SYSTEM_PATH=/path/to/dev/system

# Production
APP_ENV=production
CI3_DEBUG=false
CI3_SYSTEM_PATH=/path/to/prod/system
```

---

## 📚 Next Steps

Setelah migrasi berhasil:

1. **Read Documentation:**
   - [README.md](README.md) - Package overview
   - [CI3_SETUP.md](CI3_SETUP.md) - Detailed setup guide
   - [CI3_HELPERS_REFERENCE.md](CI3_HELPERS_REFERENCE.md) - Helpers reference

2. **Explore Features:**
   - LaravelBridge trait methods
   - Laravel Facades (Session, Cache, Log, DB)
   - Module routing (if using HMVC)

3. **Start Building:**
   - Keep existing CI3 code running
   - Build new features in Laravel
   - Gradually migrate old code

---

## 💡 Tips

1. **Start Small:** Test dengan 1 controller dulu, jangan langsung semua
2. **Keep Backup:** Selalu backup sebelum major changes
3. **Test Thoroughly:** Test semua critical paths setelah integration
4. **Monitor Logs:** Check `storage/logs/laravel.log` untuk errors
5. **Use Git:** Commit setiap step, easy rollback jika ada masalah

---

## 🆘 Need Help?

- Check [Troubleshooting](CI3_SETUP.md#troubleshooting) section
- Review [Common Issues](#common-issues--solutions) di atas
- Check Laravel logs: `storage/logs/laravel.log`
- Check CI3 logs: `application/logs/`

---

## ✅ Success Criteria

Your migration is successful when:

- ✅ Laravel artisan commands work: `php artisan inspire`
- ✅ Existing CI3 routes accessible: `/welcome`, `/your-controller`
- ✅ New Laravel routes work: `/`, `/api/*`
- ✅ Session shared between Laravel & CI3
- ✅ Facades work in CI3 controllers: `Session::`, `Cache::`, etc.
- ✅ LaravelBridge trait methods work: `$this->config()`, `$this->cache()`, etc.
- ✅ No errors in logs

**Congratulations! Your CI3 project is now integrated with Laravel! 🎉**
