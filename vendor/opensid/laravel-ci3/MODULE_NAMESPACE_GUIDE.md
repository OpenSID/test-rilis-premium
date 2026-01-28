# Module Routing dengan Namespace

## Struktur Folder Module

```
Modules/
  Pelanggan/
    Http/
      Controllers/
        PelangganController.php
        BackEnd/
          AdminController.php
    Routes/
      web.php
```

## Cara Kerja Namespace

### 1. Namespace Sederhana

**Routes/web.php:**
```php
Route::group('pelanggan', ['namespace' => 'Pelanggan'], function () {
    Route::get('/', 'PelangganController@index');
});
```

**Controller Path:**
```
Modules/Pelanggan/Http/Controllers/PelangganController.php
```

### 2. Namespace dengan Subfolder

**Routes/web.php:**
```php
Route::group('admin', ['namespace' => 'Pelanggan/BackEnd'], function () {
    Route::get('/', 'AdminController@index');
});
```

**Controller Path:**
```
Modules/Pelanggan/Http/Controllers/BackEnd/AdminController.php
```

## Logic Namespace to Path

```
namespace: 'Pelanggan'
→ Modules/Pelanggan/Http/Controllers/

namespace: 'Pelanggan/BackEnd'  
→ Modules/Pelanggan/Http/Controllers/BackEnd/
```

### Pattern

1. **Ambil bagian pertama namespace** = Module Name
   - `'Pelanggan'` → Module = `Pelanggan`
   - `'Pelanggan/BackEnd'` → Module = `Pelanggan`

2. **Sisanya adalah subfolder di Controllers**
   - `'Pelanggan'` → subfolder = `` (kosong)
   - `'Pelanggan/BackEnd'` → subfolder = `BackEnd/`

3. **Path lengkap:**
   ```
   Modules/{Module}/Http/Controllers/{subfolder}{Controller}.php
   ```

## Implementasi di Code

### Helper Function

```php
function resolve_module_controller($namespace, $controller)
{
    $parts = explode('/', $namespace);
    $moduleName = $parts[0];
    $subfolder = count($parts) > 1 ? implode('/', array_slice($parts, 1)) . '/' : '';
    
    $controllerPath = FCPATH . "../Modules/{$moduleName}/Http/Controllers/{$subfolder}{$controller}.php";
    
    return [
        'module' => $moduleName,
        'subfolder' => $subfolder,
        'path' => $controllerPath,
        'exists' => file_exists($controllerPath)
    ];
}
```

### Contoh Usage

```php
// namespace: 'Pelanggan'
$info = resolve_module_controller('Pelanggan', 'PelangganController');
// Result:
// [
//     'module' => 'Pelanggan',
//     'subfolder' => '',
//     'path' => 'Modules/Pelanggan/Http/Controllers/PelangganController.php',
//     'exists' => true
// ]

// namespace: 'Pelanggan/BackEnd'
$info = resolve_module_controller('Pelanggan/BackEnd', 'AdminController');
// Result:
// [
//     'module' => 'Pelanggan',
//     'subfolder' => 'BackEnd/',
//     'path' => 'Modules/Pelanggan/Http/Controllers/BackEnd/AdminController.php',
//     'exists' => true
// ]
```

## OpenSID Router Integration

OpenSID Router sudah handle ini secara otomatis:

1. **Route::group** menyimpan namespace
2. **Hook.php** resolve controller path dari namespace
3. **Pattern:** `APPPATH . 'controllers/' . $namespace . '/' . $controller . '.php'`

Untuk Module, path menjadi:
```php
FCPATH . '../Modules/' . $moduleName . '/Http/Controllers/' . $subfolder . $controller . '.php'
```

## Best Practices

### ✅ DO

```php
// Namespace match dengan struktur folder
Route::group('pelanggan', ['namespace' => 'Pelanggan'], function () {
    // Controller: Modules/Pelanggan/Http/Controllers/
});

Route::group('admin', ['namespace' => 'Pelanggan/BackEnd'], function () {
    // Controller: Modules/Pelanggan/Http/Controllers/BackEnd/
});
```

### ❌ DON'T

```php
// Namespace tidak match struktur
Route::group('pelanggan', ['namespace' => 'WrongName'], function () {
    // Error: Module tidak ditemukan
});
```

## Troubleshooting

### Error: "Controller not found"

**Check:**
1. Namespace match dengan nama module
2. Struktur folder benar: `Modules/{Module}/Http/Controllers/`
3. File controller ada di lokasi yang benar

**Debug:**
```php
$namespace = 'Pelanggan/BackEnd';
$controller = 'AdminController';
$info = resolve_module_controller($namespace, $controller);
var_dump($info);
```

## Auto-load Module Routes

Helper `load_module_routes()` akan auto-scan semua module dan load routes mereka:

```php
// application/helpers/module_routes_helper.php
load_module_routes();
```

Ini akan:
1. Scan folder `Modules/`
2. Cari file `Routes/web.php` di setiap module
3. Include dan register semua routes

## Summary

| Namespace | Module | Subfolder | Controller Path |
|-----------|--------|-----------|-----------------|
| `Pelanggan` | Pelanggan | - | `Modules/Pelanggan/Http/Controllers/` |
| `Pelanggan/BackEnd` | Pelanggan | `BackEnd/` | `Modules/Pelanggan/Http/Controllers/BackEnd/` |
| `Pelanggan/Api/V1` | Pelanggan | `Api/V1/` | `Modules/Pelanggan/Http/Controllers/Api/V1/` |

**Formula:**
```
Modules/{FirstPart}/Http/Controllers/{RestParts}/{Controller}.php
```
