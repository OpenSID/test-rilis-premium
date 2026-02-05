# ROADMAP MIGRASI LENGKAP: CI3 → Laravel

## STATUS OPENSID SAAT INI

✅ **Sudah selesai (70%):**
- Models → Eloquent ORM
- Views → Blade templates
- View calls → `return view()`
- Database layer → Laravel native

❌ **Masih pending (30%):**
- Controllers → Laravel controllers
- Request handling → Form Requests
- Routing → Laravel routes
- Middleware → Laravel middleware

---

## STRATEGI MIGRASI MULUS (Strangler Pattern)

### **Fase 1: Setup & Infrastructure (2-3 minggu)**

#### 1.1 Persiapan Repository

```bash
# 1. Buat branch untuk migrasi
git checkout -b feat/laravel-migration

# 2. Backup current status
git tag backup/ci3-v1.0.0
```

#### 1.2 Update Routing Layer

```php
// routes/web.php - Tambahkan dual routing

// Route lama (CI3) - backward compatibility
Route::any('{any}', 'CI3Router@route')->where('any', '.*');

// Route baru (Laravel) - prioritas tinggi
Route::middleware(['web', 'auth:admin', 'desa_identity'])->group(function () {
    Route::prefix('sms')->name('sms.')->group(base_path('routes/sms.php'));
    Route::prefix('penduduk')->name('penduduk.')->group(base_path('routes/penduduk.php'));
    // ... tambah modul satu per satu
});
```

#### 1.3 Setup Kernel

```php
// app/Http/Kernel.php - Register middleware

protected $middleware = [
    // ...
    \App\Http\Middleware\CheckDesaIdentity::class,
];

protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\CheckPermission::class,
    ],
];
```

---

### **Fase 2: Migrate Controllers (Priority-based)**

#### Prioritas 1: Controllers Sederhana (minggu 1-2)
Controllers dengan logic minimal, sedikit dependency:

```
- Sms.php              ← DONE (POC)
- Kategori.php
- Komentar.php
- Dokumen.php
- Gallery.php
```

#### Prioritas 2: CRUD Standard (minggu 3-4)
Controllers dengan CRUD operations:

```
- Kelompok.php
- Lembaga.php
- Kelompok_anggota.php
- Daftar_kontak.php
- Area.php
```

#### Prioritas 3: Complex Business Logic (minggu 5-8)
Controllers dengan validasi kompleks:

```
- Penduduk.php         (Core feature)
- Keluarga.php
- Rtm.php
- Inventaris*.php
- Surat*.php
```

#### Prioritas 4: Integration & External (minggu 9-10)
Controllers dengan external API/third-party:

```
- Sinkronisasi.php
- Opendk_pesan.php
- Notif*.php
- Statistik*.php
```

---

### **Fase 3: Controller Migration Template**

#### Step 1: Create New Controller

```bash
# Gunakan artisan (Laravel)
php artisan make:controller SmsController

# Atau copy dari CI3 dan refactor
cp donjo-app/controllers/Sms.php app/Http/Controllers/SmsController.php
```

#### Step 2: Refactor Class

**Checklist untuk setiap method:**

- [ ] Replace `$this->input->post()` → `$request->post()` / `$request->validated()`
- [ ] Replace `$this->input->get()` → `$request->query()` / `$request->get()`
- [ ] Replace `isCan()` → `can()` helper atau middleware
- [ ] Replace `$this->request[field]` → `$request->get()` atau property access
- [ ] Replace `$this->session->set()` → `session()->put()`
- [ ] Replace `show_404()` → `abort(404)`
- [ ] Replace `redirect_with()` → `redirect()->with()`
- [ ] Replace `ci_route()` → `route()`
- [ ] Replace view calls - sudah Blade? ✓
- [ ] Add type hints ke method parameters
- [ ] Add return type declarations
- [ ] Extract logic ke Services (optional tapi recommended)

#### Step 3: Create Form Request (jika ada validation)

```php
// app/Http/Requests/SmsStoreRequest.php
php artisan make:request SmsStoreRequest
```

```php
public function rules(): array {
    return [
        'TextDecoded' => 'required|string|max:1000',
    ];
}
```

#### Step 4: Update Routes

```php
// routes/sms.php
Route::get('/', [SmsController::class, 'index'])->name('index');
Route::get('/datatables', [SmsController::class, 'datatables'])->name('datatables');
// ...
```

#### Step 5: Create Test

```bash
php artisan make:test SmsControllerTest --feature
```

```php
public function test_can_view_inbox() {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user, 'admin')
        ->get(route('sms.index'));
    
    $response->assertOk()->assertViewIs('admin.sms.inbox.index');
}
```

#### Step 6: Deploy

```php
// Option A: Replace sepenuhnya (recommended)
// Hapus: donjo-app/controllers/Sms.php
// Gunakan: routes/sms.php (Laravel)

// Option B: Dual run (untuk transition)
// Tetap jalankan CI3 route
// Redirect traffic ke Laravel via feature flag
```

---

### **Fase 4: Testing & Validation**

#### 4.1 Manual Testing per Controller

| Controller | Test Cases | Status |
|-----------|-----------|--------|
| Sms | List, Create, Update, Delete | ✓ |
| Kategori | CRUD | - |
| Kelompok | CRUD + relations | - |
| Penduduk | Search, Filter, Export | - |

#### 4.2 Automated Testing

```bash
# Run test suites
php artisan test

# Specific test file
php artisan test tests/Feature/SmsControllerTest.php

# With coverage
php artisan test --coverage
```

#### 4.3 Integration Testing

```php
// Test: Old route vs New route (should return same data)
public function test_old_new_route_consistency() {
    $oldRoute = $this->call('GET', '/sms');  // CI3 route
    $newRoute = $this->call('GET', '/sms');  // Laravel route
    
    $this->assertEquals($oldRoute->status(), $newRoute->status());
}
```

---

### **Fase 5: Cleanup & Optimization**

#### 5.1 Remove CI3 Dependencies

```php
// Before: app/Services/Laravel.php mengload CI framework
require BASEPATH.'core/CodeIgniter.php';

// After: Native Laravel only
// no CI3 bootstrap needed
```

#### 5.2 Performance Optimization

```php
// View cache
php artisan view:cache

// Route cache
php artisan route:cache

// Config cache
php artisan config:cache

// Auto-load optimization
composer dump-autoload -o
```

#### 5.3 Remove Legacy Files

```bash
# After all controllers migrated
rm -rf donjo-app/controllers/*
rm -rf donjo-app/core/MY_Controller.php
rm -rf donjo-app/core/Admin_Controller.php

# Keep for reference (optional)
git mv donjo-app/ legacy/donjo-app/
```

---

## COMPLETE CHECKLIST

### Per-Controller Migration

```markdown
### Sms Controller ✓
- [x] New SmsController created
- [x] Routes defined in routes/sms.php
- [x] Form Requests created
- [x] Middleware setup
- [x] Tests written
- [x] Manual testing completed
- [x] Documentation updated
- [x] Deployed to dev/staging

### Kategori Controller
- [ ] New KategoriController created
- [ ] Routes defined
- [ ] Form Requests created
- [ ] Middleware setup
- [ ] Tests written
- [ ] Manual testing completed
- [ ] Documentation updated
- [ ] Deployed to dev/staging

### [Lanjutkan untuk semua controllers...]
```

### Global Migration

```markdown
## Infrastructure Setup
- [ ] Base routing (dual mode)
- [ ] Kernel middleware updated
- [ ] BaseController created
- [ ] Common middleware (CheckDesaIdentity, CheckPermission)
- [ ] Helpers compatibility layer

## Controllers (10 total)
- [ ] 5 simple controllers
- [ ] 3 CRUD controllers
- [ ] 2 complex controllers

## Views
- [ ] All views converted to Blade
- [ ] Asset paths updated
- [ ] JavaScript compatibility checked

## Testing
- [ ] Unit tests passed (80% coverage)
- [ ] Feature tests passed
- [ ] Integration tests passed
- [ ] Performance tests OK

## Deployment
- [ ] Database migrations applied
- [ ] Feature flags configured
- [ ] Staging environment validated
- [ ] Production deployment plan

## Cleanup
- [ ] CI3 dependencies removed
- [ ] Legacy code archived
- [ ] Documentation finalized
- [ ] Team training completed
```

---

## EXPECTED TIMELINE

| Fase | Durasi | Deliverable |
|------|--------|-------------|
| Setup | 2-3 minggu | Dual routing working |
| Controllers (P1) | 2 minggu | 5 simple controllers live |
| Controllers (P2) | 2 minggu | 3 CRUD controllers live |
| Controllers (P3) | 4 minggu | 2 complex controllers live |
| Testing | 2 minggu | 80%+ test coverage |
| Cleanup | 1 minggu | Legacy removed |
| **TOTAL** | **~13 minggu** | **Full Laravel** |

---

## RISK MITIGATION

### Risk: Data Loss
- **Mitigation**: Database backup sebelum setiap deployment
- **Backup strategy**: Auto-backup sebelum migrate

### Risk: Performance Degradation
- **Mitigation**: Performance baseline testing
- **Monitor**: APM tools (New Relic, DataDog)

### Risk: User Confusion
- **Mitigation**: Phased rollout dengan feature flags
- **Training**: Documentation + video tutorial

### Risk: Breaking Changes
- **Mitigation**: Comprehensive test suite
- **Rollback**: Keep CI3 version as fallback

---

## SUCCESS CRITERIA

✅ **Minimum**
- All critical controllers migrated
- 80%+ test coverage
- No data loss
- Performance ≥ CI3 version

✅ **Ideal**
- All controllers migrated
- 95%+ test coverage
- Performance improved
- CI3 completely removed
- Team fully trained

---

## RESOURCES NEEDED

1. **2-3 Developers** (1 lead, 1-2 junior)
2. **QA/Tester** (1 person)
3. **DevOps** (for deployment)
4. **Timeline**: 13 minggu (full-time)
5. **Budget**: Infrastructure, tooling, training

---

## NEXT ACTIONS

1. ✅ **Done**: SmsController as POC
2. **Next**: Create KategoriController (Week 1)
3. **Then**: Batch migrate Prioritas 1 controllers (Weeks 1-2)
4. **Parallel**: Setup CI/CD pipeline (Week 1-2)
5. **Review**: QA testing each batch
6. **Launch**: Staging environment deployment (Week 3)

**Start Date**: [Tentukan tanggal mulai]
**Target Go-Live**: [Tentukan target selesai]

