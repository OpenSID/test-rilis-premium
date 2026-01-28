# Cara Register Module Routes

## Overview

Module routes **TIDAK** auto-load. Anda harus **explicitly register** route module yang ingin digunakan di file:
- `application/Routes/web.php`
- `application/Routes/api.php` 
- `application/Routes/console.php`

## Struktur File Routes

```
application/
  Routes/
    web.php      ← Register module WEB routes di sini
    api.php      ← Register module API routes di sini
    console.php  ← Register module CLI routes di sini

Modules/
  Pelanggan/
    Routes/
      web.php
      api.php
```

## Cara Register Module Routes

### 1. Web Routes

**File: `application/Routes/web.php`**

```php
Route::set('translate_uri_dashes', true);

// ============================================================================
// LOAD MODULE ROUTES
// ============================================================================
// Uncomment untuk mengaktifkan routes dari module:

include __DIR__ . '/../../Modules/Pelanggan/Routes/web.php';
include __DIR__ . '/../../Modules/Keuangan/Routes/web.php';
// include __DIR__ . '/../../Modules/Contoh/Routes/web.php';  ← Di-comment = tidak di-load

// ============================================================================
// APPLICATION ROUTES
// ============================================================================
Route::get('home', 'HomeController@index');
```

### 2. API Routes

**File: `application/Routes/api.php`**

```php
// Load module API routes
include __DIR__ . '/../../Modules/Pelanggan/Routes/api.php';
include __DIR__ . '/../../Modules/Keuangan/Routes/api.php';
```

### 3. Console Routes

**File: `application/Routes/console.php`**

```php
// Load module Console routes
include __DIR__ . '/../../Modules/Pelanggan/Routes/console.php';
```

## Keuntungan Explicit Registration

### ✅ Kontrol Penuh
- Hanya module yang **benar-benar diperlukan** yang di-load
- Tidak ada "surprise routes" dari module yang tidak dipakai

### ✅ Performance
- Tidak scan folder `Modules/` setiap request
- Hanya include file yang terdaftar

### ✅ Clear & Maintainable
- Jelas module mana yang aktif
- Mudah enable/disable module (comment/uncomment)
- Mudah debugging

## Pattern Lengkap

```php
// ============================================================================
// application/Routes/web.php
// ============================================================================
<?php

Route::set('default_controller', 'welcome');
Route::set('404_override', function () {
    show_404();
});
Route::set('translate_uri_dashes', true);

// ============================================================================
// MODULE ROUTES - Register module yang ingin digunakan
// ============================================================================

// Module Pelanggan - ACTIVE
include __DIR__ . '/../../Modules/Pelanggan/Routes/web.php';

// Module Keuangan - ACTIVE
include __DIR__ . '/../../Modules/Keuangan/Routes/web.php';

// Module Contoh - DISABLED
// include __DIR__ . '/../../Modules/Contoh/Routes/web.php';

// Module Development - DISABLED (only for dev)
// if (ENVIRONMENT === 'development') {
//     include __DIR__ . '/../../Modules/Debug/Routes/web.php';
// }

// ============================================================================
// APPLICATION ROUTES
// ============================================================================
Route::get('home', 'HomeController@index');
Route::get('about', 'HomeController@about');
```

## Conditional Loading

### Load Berdasarkan Environment

```php
// Load module debug hanya di development
if (ENVIRONMENT === 'development') {
    include __DIR__ . '/../../Modules/Debug/Routes/web.php';
}

// Load module production hanya di production
if (ENVIRONMENT === 'production') {
    include __DIR__ . '/../../Modules/Analytics/Routes/web.php';
}
```

### Load Berdasarkan Config

```php
// Load module berdasarkan config
$enabledModules = config_item('enabled_modules') ?? [];

foreach ($enabledModules as $module) {
    $routeFile = __DIR__ . "/../../Modules/{$module}/Routes/web.php";
    if (file_exists($routeFile)) {
        include $routeFile;
    }
}
```

## Troubleshooting

### Route Module Tidak Muncul

**Check:**
1. ✅ Apakah sudah di-`include` di `application/Routes/web.php`?
2. ✅ Apakah path ke module route file benar?
3. ✅ Apakah file `Modules/NamaModule/Routes/web.php` ada?

**Debug:**
```php
$routeFile = __DIR__ . '/../../Modules/Pelanggan/Routes/web.php';
var_dump(file_exists($routeFile)); // harus true

include $routeFile;
```

### Route Conflict

Jika ada route yang bentrok antara module:

```php
// Module A
Route::get('pelanggan', 'PelangganController@index');

// Module B - ERROR: route 'pelanggan' sudah terdaftar
Route::get('pelanggan', 'CustomerController@index');
```

**Solusi:** Gunakan prefix atau namespace yang berbeda:
```php
// Module A
Route::group('module-a', function() {
    Route::get('pelanggan', 'PelangganController@index'); // /module-a/pelanggan
});

// Module B
Route::group('module-b', function() {
    Route::get('pelanggan', 'CustomerController@index'); // /module-b/pelanggan
});
```

## Best Practices

### ✅ DO

```php
// 1. Group module routes dengan comment jelas
// Module Pelanggan
include __DIR__ . '/../../Modules/Pelanggan/Routes/web.php';

// 2. Disable dengan comment, jangan hapus
// include __DIR__ . '/../../Modules/Contoh/Routes/web.php';  ← Masih ada, tapi disabled

// 3. Gunakan path yang jelas
include __DIR__ . '/../../Modules/Pelanggan/Routes/web.php';  ← Jelas path-nya
```

### ❌ DON'T

```php
// 1. Jangan auto-scan folder Modules
foreach (glob(__DIR__ . '/../../Modules/*/Routes/web.php') as $file) {
    include $file;  ← Buruk! Load semua module
}

// 2. Jangan pakai path relatif tanpa __DIR__
include '../../Modules/Pelanggan/Routes/web.php';  ← Error prone

// 3. Jangan hapus line, tapi comment saja
// Hapus line ini susah track history
```

## Migration dari Auto-Load

Jika sebelumnya pakai auto-load semua module:

**Before:**
```php
// Auto-load semua module (OLD WAY)
foreach (Modules::$locations as $location => $offset) {
    // ... auto scan dan include semua ...
}
```

**After:**
```php
// Explicit registration (NEW WAY)
include __DIR__ . '/../../Modules/Pelanggan/Routes/web.php';
include __DIR__ . '/../../Modules/Keuangan/Routes/web.php';
// Tambah module lain sesuai kebutuhan
```

**Keuntungan:**
- ⚡ Lebih cepat (tidak scan folder)
- 🎯 Lebih jelas (explicit > implicit)
- 🛡️ Lebih aman (hanya load yang diperlukan)

## Summary

| File | Purpose | Example |
|------|---------|---------|
| `application/Routes/web.php` | Register web routes | `include .../Pelanggan/Routes/web.php` |
| `application/Routes/api.php` | Register API routes | `include .../Pelanggan/Routes/api.php` |
| `application/Routes/console.php` | Register CLI routes | `include .../Pelanggan/Routes/console.php` |

**Prinsip:**
> **Explicit is better than implicit** - Daftar module yang mau dipakai, jangan auto-load semua!
