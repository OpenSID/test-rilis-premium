# Module Routes - Pattern & Best Practices

## ✅ Cara yang Benar

### Pattern untuk Module Routes

```php
<?php

// PENTING: Selalu gunakan Route::group dengan namespace!
Route::group('prefix', ['namespace' => 'NamaModule'], function () {
    Route::get('/', 'Controller@method');
});
```

### Contoh Lengkap

```php
<?php

/**
 * Module Routes - Contoh
 */

// Namespace 'Contoh' akan resolve ke:
// Modules/Contoh/Http/Controllers/
Route::group('sore', ['namespace' => 'Contoh'], function () {
    Route::get('/', 'ContohController@index');
    Route::get('/show/(:any)', 'ContohController@show/$1');
    Route::post('/store', 'ContohController@store');
});

// Dengan subfolder di Controllers
// Namespace 'Contoh/Admin' akan resolve ke:
// Modules/Contoh/Http/Controllers/Admin/
Route::group('admin', ['namespace' => 'Contoh/Admin'], function () {
    Route::get('/', 'AdminController@index');
    Route::get('/users', 'AdminController@users');
});
```

## ❌ Kesalahan Umum

### 1. Tanpa Namespace (SALAH!)

```php
// ❌ SALAH - Controller tidak akan ditemukan
Route::get('sore', 'ContohController@index');
```

**Error yang muncul:**
```
Path dobel: OpenSID\Modules\Contoh\Http\ControllersOpenSID\Modules\Contoh\Http\Controllers
```

### 2. Namespace Full Path (SALAH!)

```php
// ❌ SALAH - Terlalu panjang, akan dobel
Route::group('sore', ['namespace' => 'OpenSID\Modules\Contoh\Http\Controllers'], function () {
    Route::get('/', 'ContohController@index');
});
```

### 3. Tanpa Route Group (SALAH!)

```php
// ❌ SALAH - Namespace tidak bisa di-set tanpa group
Route::get('sore', 'ContohController@index')->namespace('Contoh');
```

## ✅ Pattern yang Benar

### 1. Dengan Prefix

```php
Route::group('contoh', ['namespace' => 'Contoh'], function () {
    // URL: /contoh
    Route::get('/', 'ContohController@index');
    
    // URL: /contoh/detail/123
    Route::get('/detail/(:any)', 'ContohController@detail/$1');
});
```

### 2. Tanpa Prefix (Root Level)

```php
Route::group('', ['namespace' => 'Contoh'], function () {
    // URL: /welcome
    Route::get('welcome', 'ContohController@index');
    
    // URL: /about
    Route::get('about', 'ContohController@about');
});
```

### 3. Dengan Subfolder Controller

```php
// Controllers di: Modules/Contoh/Http/Controllers/Api/
Route::group('api/contoh', ['namespace' => 'Contoh/Api'], function () {
    Route::get('/', 'ApiController@index');
    Route::get('/data', 'ApiController@data');
});
```

## Namespace Mapping

| Namespace | Controller Path |
|-----------|----------------|
| `'Contoh'` | `Modules/Contoh/Http/Controllers/` |
| `'Contoh/Admin'` | `Modules/Contoh/Http/Controllers/Admin/` |
| `'Contoh/Api/V1'` | `Modules/Contoh/Http/Controllers/Api/V1/` |
| `'Pelanggan'` | `Modules/Pelanggan/Http/Controllers/` |
| `'Pelanggan/BackEnd'` | `Modules/Pelanggan/Http/Controllers/BackEnd/` |

## Formula

```
Namespace Pattern:
'ModuleName/SubFolder/SubFolder2'
    ↓
Modules/{ModuleName}/Http/Controllers/{SubFolder}/{SubFolder2}/
```

## Template Module Routes

Salin template ini untuk module baru:

```php
<?php

/**
 * Module Routes - [NAMA_MODULE]
 * 
 * Routes untuk module [NAMA_MODULE]
 */

// Routes dengan prefix
Route::group('[prefix]', ['namespace' => '[NamaModule]'], function () {
    Route::get('/', '[Controller]@index');
    Route::get('/show/(:any)', '[Controller]@show/$1');
    Route::post('/store', '[Controller]@store');
});
```

**Replace:**
- `[NAMA_MODULE]` - Nama module Anda
- `[prefix]` - URL prefix (contoh: 'admin', 'api/v1', dll)
- `[NamaModule]` - Nama folder module (sama dengan folder di Modules/)
- `[Controller]` - Nama controller class

## Troubleshooting

### Error: "Controller not found" atau path dobel

**Penyebab:** Route tanpa namespace atau namespace salah

**Solusi:**
```php
// ✅ BENAR
Route::group('prefix', ['namespace' => 'NamaModule'], function () {
    Route::get('/', 'Controller@method');
});
```

### Error: "Class not found"

**Check:**
1. Namespace sudah benar? (hanya nama module, bukan full path)
2. Controller file ada di `Modules/{Module}/Http/Controllers/`?
3. Class name sesuai dengan file name?

## Best Practices

1. ✅ **Selalu gunakan Route::group**
2. ✅ **Namespace hanya nama module** (bukan full path)
3. ✅ **Untuk subfolder, gunakan slash**: `'Module/SubFolder'`
4. ✅ **Konsisten dengan penamaan**
5. ✅ **Group route yang related**

## Contoh Real World

```php
<?php

/**
 * Module Routes - Pelanggan
 */

// Public routes
Route::group('pelanggan', ['namespace' => 'Pelanggan'], function () {
    Route::get('/', 'PelangganController@index');
    Route::get('/detail/(:num)', 'PelangganController@detail/$1');
});

// Admin routes (subfolder)
Route::group('admin/pelanggan', ['namespace' => 'Pelanggan/Admin'], function () {
    Route::get('/', 'PelangganAdminController@index');
    Route::post('/store', 'PelangganAdminController@store');
    Route::put('/update/(:num)', 'PelangganAdminController@update/$1');
});

// API routes (subfolder)
Route::group('api/pelanggan', ['namespace' => 'Pelanggan/Api'], function () {
    Route::get('/', 'PelangganApiController@list');
    Route::get('/(:num)', 'PelangganApiController@show/$1');
});
```
