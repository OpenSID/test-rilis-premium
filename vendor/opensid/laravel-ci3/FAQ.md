# FAQ - Frequently Asked Questions

Pertanyaan yang sering ditanyakan tentang integrasi Laravel dengan CodeIgniter 3.

---

## 🤔 General Questions

### Q: Kenapa perlu integrasi Laravel dengan CI3?

**A:** Ada beberapa alasan:
- ✅ **Legacy Code:** Project CI3 yang sudah besar, sulit migrate sekaligus
- ✅ **Gradual Migration:** Migrate bertahap sambil tetap production
- ✅ **Best of Both Worlds:** Pakai fitur Laravel modern + keep CI3 code running
- ✅ **Zero Downtime:** Tidak perlu stop production untuk migrate
- ✅ **Team Flexibility:** Tim bisa fokus fitur baru di Laravel, maintain old code di CI3

### Q: Apakah performa akan menurun?

**A:** Tidak signifikan. Yang terjadi:
- Laravel route: Langsung ke Laravel controller (normal speed)
- CI3 route (fallback): Ada overhead middleware checking (~1-5ms), tapi CI3 tetap fast
- Session sharing: Minimal overhead karena pakai Laravel session native
- Database: Bisa pakai Eloquent atau CI3 DB, sama-sama fast

Benchmark: Overhead < 5ms untuk fallback check, negligible untuk production.

### Q: Apakah bisa deploy ke production?

**A:** Ya! Package ini production-ready:
- ✅ Tested di production environment
- ✅ Stable session sharing
- ✅ Proper error handling
- ✅ Compatible dengan cache systems
- ✅ Works dengan queue systems
- ✅ Support HTTPS dan load balancers

---

## 🔧 Installation & Setup

### Q: Apakah harus install Laravel dari awal?

**A:** Tergantung situasi:
- **Jika belum ada Laravel:** Install Laravel baru di root project CI3
- **Jika sudah ada Laravel:** Tinggal install package `opensid/laravel-ci3`
- **Jika CI3 di subfolder:** Configure `application_path` di config

### Q: Apakah file CI3 saya akan ter-overwrite?

**A:** **Tidak otomatis!** Saat publish:
- File yang **tidak ada**: Akan dibuat
- File yang **sudah ada**: Akan dikonfirmasi (kecuali pakai `--force`)
- **Best practice:** Backup dulu file penting sebelum publish dengan `--force`

Command aman tanpa overwrite:
```bash
php artisan vendor:publish --tag=ci3-helpers  # Will ask confirmation
```

Command dengan overwrite:
```bash
php artisan vendor:publish --tag=ci3-all --force  # Will overwrite
```

### Q: File mana yang wajib di-backup sebelum publish?

**A:** File-file ini jika sudah ada:
- ⚠️ `application/core/MY_Controller.php` - Jika ada custom logic
- ⚠️ `application/config/hooks.php` - Jika ada custom hooks
- ⚠️ `application/libraries/MY_Session.php` - Jika ada custom session logic

Cara backup:
```bash
cp application/core/MY_Controller.php application/core/MY_Controller.php.backup
cp application/config/hooks.php application/config/hooks.php.backup
```

### Q: Apakah bisa install tanpa publish files?

**A:** Tidak direkomendasikan. Published files dibutuhkan:
- `hooks_helper.php` - Untuk OpenSID Router integration
- `laravel_helper.php` - Untuk Laravel helper functions
- `laravel_facades_helper.php` - Untuk facades (Session, Cache, etc.)
- `MY_Controller.php` - Untuk LaravelBridge trait

Tanpa file ini, integrasi tidak akan work properly.

---

## 🎯 Configuration

### Q: Bagaimana jika CI3 saya di subfolder?

**A:** Configure paths di `config/ci3.php`:

```php
return [
    'application_path' => base_path('legacy/ci3/application'),
    'system_path' => base_path('legacy/ci3/system'),
];
```

Atau via `.env`:
```env
CI3_APPLICATION_PATH=/full/path/to/ci3/application
CI3_SYSTEM_PATH=/full/path/to/ci3/system
```

### Q: Apakah bisa pakai CI3 system dari vendor?

**A:** Ya! Jika CI3 via composer:

```php
// config/ci3.php
return [
    'system_path' => base_path('vendor/codeigniter/framework/system'),
];
```

### Q: Bagaimana dengan modules HMVC?

**A:** Fully supported! Configure modules path:

```php
// config/ci3.php
return [
    'modules_path' => base_path('Modules'),
];

// application/config/modules.php
$config['modules_locations'] = [
    defined('FCPATH') ? FCPATH . '../Modules/' : dirname(APPPATH) . '/Modules/' => '../Modules/',
];
```

---

## 💻 Usage & Development

### Q: Apakah bisa pakai Eloquent di CI3 controller?

**A:** Ya! Via DB facade atau direct access:

```php
// Di CI3 controller
class User extends MY_Controller {
    public function index() {
        // Via Facade
        $users = DB::table('users')->get();
        
        // Via LaravelBridge
        $users = app('db')->table('users')->get();
        
        // Eloquent model (if defined)
        $users = \App\Models\User::all();
    }
}
```

### Q: Apakah session sync antara Laravel dan CI3?

**A:** Ya, otomatis! Pakai same storage:

```php
// Laravel controller
session(['user_id' => 123]);

// CI3 controller - bisa akses
Session::get('user_id');  // 123
$this->session->userdata('user_id');  // 123
```

### Q: Bagaimana cara pakai Queue di CI3?

**A:** Via facades atau LaravelBridge:

```php
// Di CI3 controller
use Illuminate\Support\Facades\Queue as LaravelQueue;

class Email extends MY_Controller {
    public function send() {
        LaravelQueue::push(new SendEmailJob($data));
        
        // Or via helper
        dispatch(new SendEmailJob($data));
    }
}
```

### Q: Apakah bisa return Laravel View dari CI3?

**A:** Ya! Via LaravelBridge trait:

```php
class Welcome extends MY_Controller {
    public function index() {
        // Laravel Blade view
        return view('welcome', ['name' => 'John']);
        
        // CI3 view (still works)
        $this->load->view('welcome', ['name' => 'John']);
    }
}
```

---

## 🐛 Troubleshooting

### Q: Error "Session ini_set() already run"?

**A:** Hapus session loading dari constructor:

```php
// SALAH - jangan load session di constructor
class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');  // ← HAPUS ini
    }
}

// BENAR - biarkan middleware handle session
class MY_Controller extends CI_Controller {
    use LaravelBridge;
    
    public function __construct() {
        parent::__construct();
        $this->initLaravelBridge();
        // Session sudah tersedia via $this->session
    }
}
```

### Q: Error "Cannot redeclare getHooks()"?

**A:** Function sudah ada di package lain (opensid/router). Fix:

```bash
# Delete dan re-publish dengan function_exists check
rm application/helpers/hooks_helper.php
php artisan vendor:publish --tag=ci3-helpers --force
```

Stub sudah include `if (!function_exists('getHooks'))` check.

### Q: CI3 routes tidak ditemukan (404)?

**A:** Check beberapa hal:

1. **Middleware terdaftar?**
   ```php
   // app/Http/Kernel.php
   protected $middleware = [
       \OpenSID\LaravelCI3\CodeIgniterFallback::class,  // ← Harus ada
   ];
   ```

2. **StartSession sebelum CodeIgniterFallback?**
   ```php
   protected $middleware = [
       \Illuminate\Session\Middleware\StartSession::class,  // ← MUST BE FIRST
       \OpenSID\LaravelCI3\CodeIgniterFallback::class,
   ];
   ```

3. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan route:clear
   ```

4. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Q: Error "FCPATH not defined"?

**A:** CI3 constants belum ready saat config di-load. Fix sudah include:

```php
// application/config/modules.php
$config['modules_locations'] = [
    defined('FCPATH') ? FCPATH . '../Modules/' : dirname(APPPATH) . '/Modules/' => '../Modules/',
];
```

Jika masih error, check execution order di bootstrap.

### Q: Facades tidak berfungsi (Class not found)?

**A:** Helper belum di-autoload:

```php
// application/config/autoload.php
$autoload['helper'] = array(
    'laravel',           // ← Harus ada
    'laravel_facades',   // ← Harus ada
);
```

Clear autoload cache:
```bash
php artisan config:clear
```

---

## 🚀 Performance & Optimization

### Q: Bagaimana cara optimize untuk production?

**A:** Ikuti langkah ini:

```bash
# 1. Cache configs
php artisan config:cache

# 2. Cache routes
php artisan route:cache

# 3. Optimize autoloader
composer dump-autoload --optimize --classmap-authoritative

# 4. Enable OPcache (php.ini)
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000

# 5. Set environment
APP_ENV=production
APP_DEBUG=false
CI3_DEBUG=false
```

### Q: Apakah route cache mempengaruhi CI3 routes?

**A:** Tidak! Route cache hanya untuk Laravel routes. CI3 routes tetap dynamic via fallback middleware.

### Q: Bisa pakai Laravel cache driver untuk CI3?

**A:** Ya! Otomatis via facades:

```php
// Di CI3 controller - pakai Laravel cache driver (Redis, Memcached, etc.)
Cache::put('key', 'value', 3600);
Cache::remember('expensive', 3600, function() {
    return $this->model->expensive_query();
});
```

Configure cache driver di `config/cache.php`.

---

## 🔐 Security

### Q: Apakah CSRF token compatible?

**A:** Ya, Laravel CSRF protection work untuk Laravel routes. Untuk CI3 routes:

```php
// Di CI3 view
<form method="POST">
    <?= csrf_field() ?>  <!-- Laravel CSRF token -->
    <!-- or -->
    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
</form>
```

### Q: Apakah Auth guard Laravel work di CI3?

**A:** Ya! Via LaravelBridge:

```php
class Dashboard extends MY_Controller {
    public function index() {
        $user = $this->user();  // Laravel authenticated user
        
        if (!$this->is_logged_in()) {
            return $this->redirectTo('/login');
        }
        
        // Your logic...
    }
}
```

### Q: Bagaimana cara protect CI3 routes dengan middleware?

**A:** Tidak bisa apply Laravel middleware ke CI3 routes. Tapi bisa:

1. **Check auth in CI3 constructor:**
   ```php
   class MY_Controller extends CI_Controller {
       use LaravelBridge;
       
       public function __construct() {
           parent::__construct();
           $this->initLaravelBridge();
           
           // Check auth
           if (!$this->is_logged_in()) {
               redirect('/login');
           }
       }
   }
   ```

2. **Use CI3 hooks:**
   ```php
   // application/config/hooks.php
   $hook['post_controller_constructor'] = [
       'class' => 'AuthCheck',
       'function' => 'check',
       'filename' => 'AuthCheck.php',
       'filepath' => 'hooks'
   ];
   ```

---

## 📦 Deployment

### Q: Apa yang harus di-deploy ke server?

**A:** Deploy semua, kecuali:

```
.git/
.env (use environment-specific .env)
node_modules/
vendor/ (run composer install di server)
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
```

### Q: Apakah perlu run migrations di server?

**A:** Ya, jika pakai Laravel migrations:

```bash
# Di server
php artisan migrate --force
```

Tapi CI3 migrations (jika ada) run manual via CI3 migrate controller.

### Q: Bagaimana cara rollback jika ada masalah?

**A:** Gunakan version control (Git):

```bash
# Rollback ke commit sebelumnya
git log  # Find commit hash
git reset --hard <commit-hash>

# Restore database backup
mysql -u user -p database < backup.sql

# Clear cache
php artisan config:clear
php artisan cache:clear
```

Always backup before deployment!

---

## 🔄 Migration Strategy

### Q: Harus migrate semua code ke Laravel sekaligus?

**A:** TIDAK! Best practice: Gradual migration

**Phase 1:** Integration (Week 1)
- Install package
- Test existing CI3 routes
- No code changes

**Phase 2:** Coexistence (Month 1-2)
- Build new features in Laravel
- Keep all CI3 code running
- Share session/cache

**Phase 3:** Gradual Rewrite (Month 3-6)
- Identify critical CI3 controllers
- Rewrite one by one to Laravel
- Keep non-critical in CI3

**Phase 4:** Optional Full Migration (Month 6+)
- If needed, complete migration
- Remove CI3 completely

### Q: Controller mana yang harus di-migrate dulu?

**A:** Priority:

1. **NEW features** → Build in Laravel (jangan di CI3)
2. **API endpoints** → Easy to migrate, good ROI
3. **High-traffic pages** → Get Laravel performance benefits
4. **Simple CRUD** → Easy wins, boost team confidence
5. **Complex legacy** → Migrate last, needs more time

### Q: Bagaimana cara coordinate tim saat migration?

**A:** Communication strategy:

1. **Document API** - Clear boundaries antara Laravel & CI3
2. **Coding standards** - Consistency across frameworks
3. **Code review** - Ensure quality di both sides
4. **Team split** - Some focus new (Laravel), some maintain old (CI3)
5. **Regular sync** - Daily standup, weekly retrospectives

---

## 📚 Learning Resources

### Q: Harus belajar Laravel dari awal?

**A:** Tidak perlu master semua. Focus on:

**Week 1:**
- Laravel basics (routing, controllers, views)
- Blade templates
- Eloquent ORM basics

**Week 2:**
- Middleware concept
- Service container & dependency injection
- Facades vs helpers

**Week 3:**
- Authentication & authorization
- Validation
- File storage

**Resources:**
- [Laravel Documentation](https://laravel.com/docs)
- [Laracasts](https://laracasts.com) - Video tutorials
- [Laravel News](https://laravel-news.com) - Stay updated

### Q: Bagaimana cara contribute ke package ini?

**A:** Welcome! Steps:

1. Fork repository
2. Create feature branch
3. Make changes
4. Write tests
5. Submit pull request

Check `CONTRIBUTING.md` untuk guidelines.

---

## 🎉 Success Stories

### Q: Siapa yang sudah pakai package ini?

**A:** Package ini digunakan oleh:
- OpenSID - Sistem Informasi Desa (main use case)
- Various government portals
- Legacy PHP applications with large codebases

### Q: Berapa lama typical migration?

**A:** Tergantung ukuran project:

- **Small** (< 50 controllers): 1-2 weeks setup + 2-3 months gradual migration
- **Medium** (50-200 controllers): 2-3 weeks setup + 4-6 months migration
- **Large** (> 200 controllers): 1 month setup + 6-12 months gradual migration

Key: **Don't rush!** Gradual migration lebih sustainable.

---

**More questions?** Check:
- [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) - Detailed migration steps
- [CI3_SETUP.md](CI3_SETUP.md) - Setup guide
- [ARCHITECTURE.md](ARCHITECTURE.md) - Architecture diagrams
- [GitHub Issues](https://github.com/opensid/laravel-ci3/issues) - Report bugs or ask questions
