# 🚀 Instalasi Otomatis (1 Perintah)

Sekarang Anda dapat menginstal dan mengkonfigurasi integrasi CodeIgniter 3 dengan **satu perintah**!

## Instalasi Cepat

```bash
php artisan ci3:install
```

Perintah ini akan **otomatis**:
1. ✅ Publish semua file CI3 yang diperlukan
2. ✅ Deteksi dan konfigurasi path CI3 di `.env`
3. ✅ Setup routing CI3 di `routes/web.php`
4. ✅ Konfigurasi session sharing
5. ✅ Test instalasi untuk memastikan semuanya berjalan

## Command yang Tersedia

### 1. ci3:install - Instalasi Otomatis

Install dan konfigurasi lengkap:
```bash
php artisan ci3:install
```

**Opsi:**
- `--force` : Timpa file yang sudah ada
- `--no-test` : Lewati testing setelah instalasi

**Contoh:**
```bash
# Install dengan timpa file existing
php artisan ci3:install --force

# Install tanpa testing
php artisan ci3:install --no-test
```

### 2. ci3:config - Konfigurasi

Kelola konfigurasi CI3:

**Lihat konfigurasi saat ini:**
```bash
php artisan ci3:config --show
```

**Auto-detect path CI3:**
```bash
php artisan ci3:config --detect
```

**Konfigurasi interaktif:**
```bash
php artisan ci3:config
```

Output contoh:
```
📋 Current CI3 Configuration:

  CI3_APPPATH: D:/PROJECT/application/
  CI3_ENVIRONMENT: development
  SESSION_DRIVER: file

📁 Detected Paths:

  ✓ Application: D:/PROJECT/application
  ✓ System: D:/PROJECT/vendor/codeigniter/framework/system
  ✓ Modules: D:/PROJECT/Modules
  ✓ Web Root: D:/PROJECT/public
```

### 3. ci3:test - Validasi Setup

Test apakah integrasi CI3 sudah benar:
```bash
php artisan ci3:test
```

**Test apa saja yang dilakukan:**
- ✓ CI3 helper files exist
- ✓ CI3 config files exist
- ✓ CI3 core files exist
- ✓ Environment variables configured
- ✓ CI3 routes configured
- ✓ Laravel facades available
- ✓ CI3 folder structure valid
- ✓ CI3 instance can be created

**Output contoh:**
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

Your CodeIgniter 3 integration is ready to use!
```

**Opsi verbose:**
```bash
php artisan ci3:test --verbose
```

## Alur Instalasi Otomatis

### Untuk Project Baru Laravel + CI3

```bash
# 1. Install Laravel
composer create-project laravel/laravel myproject

cd myproject

# 2. Install package
composer require opensid/laravel-ci3

# 3. Copy folder CI3 yang sudah ada (application, system, Modules)
# Atau buat baru jika belum ada

# 4. Install otomatis (1 perintah!)
php artisan ci3:install

# 5. Selesai! Akses CI3:
# http://localhost/ci3/welcome
```

### Untuk Project CI3 yang Sudah Ada

```bash
# 1. Di folder CI3 yang sudah ada, install Laravel
composer create-project laravel/laravel temp
mv temp/* ./
rm -rf temp

# 2. Install package
composer require opensid/laravel-ci3

# 3. Install otomatis
php artisan ci3:install

# 4. Verifikasi
php artisan ci3:test

# 5. Selesai!
```

## Troubleshooting

### Error: "CI3 application folder not found"

**Solusi:**
```bash
# Deteksi ulang path
php artisan ci3:config --detect

# Atau set manual
php artisan ci3:config
```

### Error: "Some tests failed"

**Solusi:**
```bash
# Lihat detail error
php artisan ci3:test --verbose

# Install ulang dengan force
php artisan ci3:install --force
```

### Error: "Cannot declare class Session"

**Solusi:** File sudah dipublish sebelumnya. Gunakan `--force`:
```bash
php artisan ci3:install --force
```

## Manual Setup (Jika Diperlukan)

Jika ingin setup manual tanpa automation:

### 1. Publish file-file
```bash
# Publish semua
php artisan vendor:publish --tag=ci3-all

# Atau publish satu per satu
php artisan vendor:publish --tag=ci3-helpers
php artisan vendor:publish --tag=ci3-config-files
php artisan vendor:publish --tag=ci3-core
php artisan vendor:publish --tag=ci3-libraries
```

### 2. Konfigurasi `.env`
```env
CI3_APPPATH="D:/PROJECT/application/"
CI3_ENVIRONMENT=development
SESSION_DRIVER=file
```

### 3. Tambahkan route di `routes/web.php`
```php
Route::any('ci3/{any}', function () {
    return app('ci3');
})->where('any', '.*');
```

### 4. Test
```bash
php artisan ci3:test
```

## Perbandingan: Manual vs Otomatis

| Aspek | Manual | Otomatis (`ci3:install`) |
|-------|--------|--------------------------|
| **Waktu Setup** | 10-15 menit | 30 detik |
| **Error-prone** | Tinggi | Rendah |
| **Path Detection** | Manual | Otomatis |
| **Validation** | Manual | Built-in |
| **Update `.env`** | Manual edit | Otomatis |
| **Setup Routes** | Manual copy-paste | Otomatis |

## Keuntungan Automation

✅ **Cepat**: Setup dalam 30 detik  
✅ **Aman**: Auto-validation mencegah error  
✅ **Konsisten**: Semua developer setup sama  
✅ **Smart**: Auto-detect path CI3  
✅ **Reliable**: Built-in testing  

## Next Steps

Setelah instalasi otomatis:

1. **Buat controller CI3** di `application/controllers/`:
   ```php
   <?php
   defined('BASEPATH') OR exit('No direct script access allowed');
   
   class Welcome extends CI_Controller {
       public function index() {
           // Gunakan facades Laravel langsung!
           Session::put('user', 'John Doe');
           Cache::put('data', 'value', 60);
           Log::info('Welcome page accessed');
           
           $this->load->view('welcome');
       }
   }
   ```

2. **Akses via browser:**
   ```
   http://localhost/ci3/welcome
   ```

3. **Test facades:**
   ```bash
   php artisan tinker
   >>> Session::put('test', 'value');
   >>> Session::get('test');
   ```

## Tips & Trik

### Re-install Bersih
```bash
# Hapus semua config CI3 dari .env
# Lalu install ulang
php artisan ci3:install --force
```

### Cek Apakah Sudah Siap
```bash
php artisan ci3:test
```

### Lihat Konfigurasi
```bash
php artisan ci3:config --show
```

### Auto-detect Ulang
```bash
php artisan ci3:config --detect
```

## FAQ Automation

**Q: Apakah ci3:install aman untuk production?**  
A: Ya, tapi gunakan `--no-test` untuk production deployment.

**Q: Bisa dijalankan ulang?**  
A: Ya, gunakan `--force` untuk timpa file existing.

**Q: Bagaimana jika path CI3 tidak standar?**  
A: Gunakan `php artisan ci3:config` untuk set manual.

**Q: Apakah bisa rollback?**  
A: Ya, backup dulu atau gunakan git untuk rollback.

## Support

Jika ada masalah dengan automation tools:

1. Check logs: `php artisan ci3:test --verbose`
2. Show config: `php artisan ci3:config --show`
3. Re-install: `php artisan ci3:install --force`

---

**Sekarang setup CI3 + Laravel hanya butuh 1 perintah!** 🚀
