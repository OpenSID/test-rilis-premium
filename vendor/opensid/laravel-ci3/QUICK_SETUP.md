# 🎯 Setup Hanya 1 Perintah

## Instalasi Super Cepat

```bash
composer require opensid/laravel-ci3
php artisan ci3:install
```

**Selesai!** 🚀 

## Apa yang Terjadi Otomatis?

Command `ci3:install` akan otomatis:

1. ✅ **Publish 8 file CI3** ke folder `application/`
   - 4 Helper files (laravel, facades, hooks, module_routes)
   - 2 Config files (hooks.php, modules.php)
   - 1 Core file (MY_Controller.php)
   - 1 Library file (MY_Session.php)

2. ✅ **Konfigurasi .env** dengan auto-detect path
   - CI3_APPPATH
   - CI3_ENVIRONMENT
   - SESSION_DRIVER

3. ✅ **Setup routing** di routes/web.php
   - Route catch-all untuk CI3

4. ✅ **Test instalasi** otomatis
   - 8 test validasi

## 3 Command Utama

### 1. ci3:install
**Setup lengkap otomatis:**
```bash
php artisan ci3:install
```

**Dengan opsi:**
```bash
php artisan ci3:install --force     # Timpa file existing
php artisan ci3:install --no-test   # Tanpa testing
```

### 2. ci3:config
**Kelola konfigurasi:**
```bash
php artisan ci3:config --show       # Lihat config
php artisan ci3:config --detect     # Auto-detect path
php artisan ci3:config              # Interactive config
```

### 3. ci3:test
**Validasi setup:**
```bash
php artisan ci3:test
```

**Output:**
```
🧪 Testing CodeIgniter 3 Integration...

  ✓ CI3 helper files exist
  ✓ CI3 config files exist
  ✓ CI3 core files exist
  ✓ Environment variables configured
  ✓ CI3 routes configured
  ✓ Laravel facades available
  ✓ CI3 folder structure valid
  ✓ CI3 instance can be created

✅ All tests passed! (8/8)
```

## File yang Dipublish

| File | Path | Fungsi |
|------|------|--------|
| **laravel_helper.php** | application/helpers/ | config(), cache(), session() helpers |
| **laravel_facades_helper.php** | application/helpers/ | Session::, Cache::, Log::, DB:: |
| **module_routes_helper.php** | application/helpers/ | Auto-load module routes |
| **hooks_helper.php** | application/helpers/ | OpenSID router integration |
| **hooks.php** | application/config/ | CI3 hooks config |
| **modules.php** | application/config/ | Modules path config |
| **MY_Controller.php** | application/core/ | Base controller + LaravelBridge |
| **MY_Session.php** | application/libraries/ | Laravel session sync |

## Contoh Workflow

### Project Baru
```bash
# 1. Install Laravel
composer create-project laravel/laravel myproject
cd myproject

# 2. Install package
composer require opensid/laravel-ci3

# 3. Setup otomatis (30 detik)
php artisan ci3:install

# 4. Selesai! Test:
php artisan ci3:test
```

### Project CI3 Existing
```bash
# Di folder CI3 existing
composer require opensid/laravel-ci3
php artisan ci3:install

# Verifikasi
php artisan ci3:config --show
php artisan ci3:test
```

## Troubleshooting Cepat

### Error: "Path not found"
```bash
php artisan ci3:config --detect
```

### Error: "Files already exist"
```bash
php artisan ci3:install --force
```

### Cek status instalasi
```bash
php artisan ci3:test
php artisan ci3:config --show
```

## Next Steps

Setelah `ci3:install` berhasil:

1. **Buat controller CI3:**
   ```php
   // application/controllers/Welcome.php
   <?php
   class Welcome extends CI_Controller {
       public function index() {
           // Gunakan Laravel facades langsung
           Session::put('user', 'John');
           Cache::put('data', 'value', 60);
           Log::info('Welcome accessed');
           
           $this->load->view('welcome');
       }
   }
   ```

2. **Akses via browser:**
   ```
   http://localhost/ci3/welcome
   ```

3. **Done!** ✨

## Dokumentasi Lengkap

- 📖 [Panduan Automation Lengkap](AUTOMATION_GUIDE.md)
- 🚀 [Migration Guide](MIGRATION_GUIDE.md)
- 🏗️ [Architecture](ARCHITECTURE.md)
- ❓ [FAQ](FAQ.md)

---

**Setup CI3 + Laravel sekarang hanya 1 perintah!** 🎉
