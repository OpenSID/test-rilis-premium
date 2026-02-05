# OPENSID LARAVEL MIGRATION - QUICK START GUIDE

## 📋 Overview

Dokumentasi ini menjelaskan rencana migrasi OpenSID dari CodeIgniter 3 ke Laravel native. OpenSID sudah 70% siap karena:

- ✅ Models menggunakan Eloquent ORM
- ✅ Views sudah format Blade
- ✅ Database layer sudah Laravel
- ❌ Controllers masih CI3
- ❌ Routing masih CI3

---

## 🚀 Quick Start (5 menit)

### 1. Understand the POC

```bash
# Lihat contoh implementasi SmsController yang sudah di-convert
cat app/Http/Controllers/SmsController.php

# Lihat base controller baru
cat app/Http/Controllers/BaseController.php

# Lihat migration guide
cat MIGRATION_GUIDE.md
```

### 2. Read Key Documents

| File | Isi |
|------|-----|
| `MIGRATION_GUIDE.md` | Detailed perubahan kode (CI3 → Laravel) |
| `MIGRATION_ROADMAP.md` | Timeline, prioritas, dan checklist lengkap |
| `app/Http/Controllers/SmsController.php` | Proof of Concept - lihat bagaimana convert |
| `app/Http/Controllers/BaseController.php` | Menggantikan Admin_Controller |

### 3. Run Migration Script

```bash
# Untuk migrate controller baru
bash migrate_controller.sh KategoriController

# Script akan membuat checklist dan show next steps
```

---

## 📝 Refactor Checklist (Per Controller)

Copy-paste untuk setiap controller yang di-migrate:

```markdown
### [ControllerName]
- [ ] Class declaration & namespace updated
- [ ] $this->input → $request
- [ ] $this->session → session()
- [ ] isCan() → can() + middleware
- [ ] redirect_with() → redirect()->with()
- [ ] ci_route() → route()
- [ ] show_404() → abort(404)
- [ ] Form Requests created
- [ ] Routes file created
- [ ] Feature tests written
- [ ] Manual testing passed
- [ ] Code review approved
- [ ] Deployed to staging
```

---

## 🔄 Key Changes at a Glance

### Input Handling
```php
// OLD
$this->input->post('field')
$this->input->get('field')

// NEW
$request->post('field')
$request->query('field')
// atau lebih baik gunakan validated():
$validated = $request->validate([...]);
```

### Redirects
```php
// OLD
redirect_with('success', 'Message', ci_route('path'));

// NEW
return redirect()->route('path')->with('success', 'Message');
```

### Routes
```php
// OLD (CI3)
ci_route('sms.form.1', $id)

// NEW (Laravel)
route('sms.form', ['tipe' => 1, 'id' => $id])
```

### Views
```blade
{{-- OLD (CI3) --}}
<?php echo ci_route('path'); ?>

{{-- NEW (Blade) --}}
{{ route('path') }}
```

---

## 📊 Progress Tracking

### Status by Controller Category

**Prioritas 1 - Simple (Target: Week 1)**
- [x] Sms
- [ ] Kategori
- [ ] Komentar
- [ ] Dokumen
- [ ] Gallery

**Prioritas 2 - CRUD (Target: Week 3)**
- [ ] Kelompok
- [ ] Lembaga
- [ ] Daftar_kontak
- [ ] Area

**Prioritas 3 - Complex (Target: Week 8)**
- [ ] Penduduk
- [ ] Keluarga
- [ ] Surat*

---

## 🛠 Common Tasks

### Create New Laravel Controller
```bash
php artisan make:controller SmsController
```

### Create Form Request
```bash
php artisan make:request StoreSmsRequest
```

### Create Feature Test
```bash
php artisan make:test SmsControllerTest --feature
```

### Register Middleware
Edit `app/Http/Kernel.php`:
```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\CheckDesaIdentity::class,
    ],
];
```

### Add Routes
Create `routes/sms.php`:
```php
Route::get('/', [SmsController::class, 'index'])->name('index');
```

Register in `routes/web.php`:
```php
require base_path('routes/sms.php');
```

---

## 🧪 Testing

### Run Tests
```bash
php artisan test

# Specific file
php artisan test tests/Feature/SmsControllerTest.php

# With coverage
php artisan test --coverage
```

### Test Template
```php
class SmsControllerTest extends TestCase {
    use RefreshDatabase;

    public function test_can_view_inbox() {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'admin')
            ->get(route('sms.index'));
        
        $response->assertOk()
            ->assertViewIs('admin.sms.inbox.index');
    }
}
```

---

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [OpenSID Repository](https://github.com/OpenSID/OpenSID)
- [CodeIgniter 3 Docs](https://codeigniter.com/user_guide/)

---

## ❓ FAQ

**Q: Apakah perlu migrate semua controllers sekaligus?**
A: Tidak! Gunakan strategi batch dengan Strangler Pattern. Migrate satu-satu atau grup kecil.

**Q: Bisakah CI3 dan Laravel routes berjalan bersamaan?**
A: Ya! Dual routing bisa diatur di `routes/web.php` dengan order yang tepat.

**Q: Bagaimana kalau ada CI3 helpers yang belum ter-convert?**
A: Buat wrapper di `app/Helpers/` atau pastikan semua helpers sudah ada.

**Q: Kapan semua harus selesai?**
A: Target: 13 minggu (tergantung jumlah controllers dan developer available).

---

## 👥 Team Responsibilities

| Role | Task |
|------|------|
| Lead Dev | Code review, architecture decisions |
| Dev 1-2 | Refactor controllers |
| QA | Manual testing, validation |
| DevOps | Deployment, monitoring |

---

## 🎯 Success Metrics

✅ Semua critical controllers live di Laravel
✅ 80%+ automated test coverage
✅ Zero data loss
✅ Performance maintained or improved
✅ Team trained on Laravel patterns
✅ Documentation complete

---

**Last Updated**: 2026-02-05
**Version**: 1.0 (POC Complete)
**Next Review**: After first batch migrated
