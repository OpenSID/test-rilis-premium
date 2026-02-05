# MIGRATION GUIDE: CodeIgniter 3 → Laravel

## Perubahan Utama dalam SmsController

### 1. **Class Declaration & Namespace**

**SEBELUM (CI3):**
```php
class Sms extends Admin_Controller
{
    public $modul_ini = 'hubung-warga';
}
```

**SESUDAH (Laravel):**
```php
namespace App\Http\Controllers;

class SmsController extends BaseController
{
    protected string $modulIni = 'hubung-warga';
}
```

---

## 2. **Input Handling**

### Request Data (GET/POST/JSON)

**SEBELUM (CI3):**
```php
$this->input->post()          // $_POST
$this->input->get()           // $_GET
$this->input->is_ajax_request()
$this->request['field']       // CI3 property
```

**SESUDAH (Laravel):**
```php
$request->post()              // $_POST
$request->query()             // $_GET
$request->expectsJson()
$request->validated()         // Setelah validation
```

**Contoh Lengkap:**
```php
// SEBELUM
public function insert($tipe = '', $id = ''): void {
    $post = $this->input->post();
    $isi_pesan = htmlentities((string) $post['TextDecoded']);
}

// SESUDAH
public function insert(Request $request): RedirectResponse {
    $validated = $request->validate([
        'TextDecoded' => 'required|string',
    ]);
    $isiPesan = htmlentities($validated['TextDecoded']);
}
```

---

## 3. **Authorization & Permission**

**SEBELUM (CI3):**
```php
public function __construct() {
    parent::__construct();
    isCan('b');  // CI3 function
}

if (can('h')) {  // Direct call
    // ...
}
```

**SESUDAH (Laravel):**
```php
public function __construct() {
    parent::__construct();
    $this->authorize('b');  // BaseController method
}

if (can('h')) {  // Built-in helper (tetap sama)
    // ...
}

// Atau gunakan policy/middleware
public function delete(Request $request): RedirectResponse {
    if (!can('h')) {
        abort(403, 'Unauthorized');
    }
}
```

---

## 4. **View Rendering**

**SEBELUM (CI3):**
```php
return view('admin.sms.inbox.index', [
    'navigasi' => 'inbox',
]);

// Atau explicit
$this->load->view('admin.sms.index', $data);
```

**SESUDAH (Laravel):**
```php
// Sama seperti sekarang
return view('admin.sms.inbox.index', [
    'navigasi' => 'inbox',
]);

// Atau render untuk AJAX
return view('admin.sms.form', $data)->render();
```

---

## 5. **Session Management**

**SEBELUM (CI3):**
```php
$this->session->intended = url;
set_session('success', 'Berhasil');
```

**SESUDAH (Laravel):**
```php
session()->put('intended', url);
// atau built-in redirect
return redirect()->route('sms')->with('success', 'Berhasil');
```

---

## 6. **Redirect dengan Flash Message**

**SEBELUM (CI3):**
```php
redirect_with('success', 'Data berhasil disimpan', ci_route('sms.outbox'));
```

**SESUDAH (Laravel):**
```php
// Option 1: Direct
return redirect()->route('sms.outbox')
    ->with('success', 'Data berhasil disimpan');

// Option 2: Helper dari BaseController
return $this->redirectWithSuccess('sms.outbox', 'Data berhasil disimpan');
```

---

## 7. **Database Operations (ORM)**

✅ **TIDAK BERUBAH** - Sudah menggunakan Eloquent:

```php
// SEBELUM & SESUDAH (identik)
Inbox::with(['penduduk', 'kontak'])->get();
Outbox::create(['field' => 'value']);
$model->update($data);
Outbox::destroy($ids);
```

---

## 8. **DataTables Integration**

**SEBELUM (CI3):**
```php
if ($this->input->is_ajax_request()) {
    return datatables()->of(Inbox::with(['penduduk']))
        ->addColumn('ceklist', function($row) { ... })
        ->make();
}
return show_404();
```

**SESUDAH (Laravel):**
```php
public function datatables(): JsonResponse {
    return DataTables::of(Inbox::with(['penduduk']))
        ->addColumn('ceklist', function($row) { ... })
        ->make(true);  // true = auto-detect AJAX
}
```

---

## 9. **Error Handling**

**SEBELUM (CI3):**
```php
if (empty($id)) {
    show_404();
}

SentItem::findOrFail($id);  // Throws ModelNotFoundException
```

**SESUDAH (Laravel):**
```php
if (empty($id)) {
    abort(404);
}

// findOrFail() sudah throw ModelNotFoundException
SentItem::findOrFail($id);
```

---

## 10. **Route URL Generation**

**SEBELUM (CI3):**
```php
ci_route('sms.form.1', $id)
ci_route('sms.delete.1', $id)
```

**SESUDAH (Laravel):**
```php
route('sms.form', ['tipe' => 1, 'id' => $id])
route('sms.delete', ['tipe' => 1, 'id' => $id])
```

**Di views Blade:**
```blade
<a href="{{ route('sms.form', ['tipe' => 1, 'id' => $id]) }}">Link</a>
```

---

## 11. **Dependency Injection**

**SEBELUM (CI3):**
```php
public function __construct() {
    parent::__construct();
    $this->otp = new OtpManager();
}
```

**SESUDAH (Laravel):**
```php
public function __construct(OtpManager $otp) {
    parent::__construct();
    $this->otp = $otp;  // Auto-resolved from container
}
```

---

## 12. **Configuration & Settings**

**SEBELUM (CI3):**
```php
if (empty(setting('aktifkan_sms'))) { }
```

**SESUDAH (Laravel):**
```php
// Sama (helper tetap berlaku)
if (empty(setting('aktifkan_sms'))) { }

// Atau config
config('app.sms_enabled')
```

---

## 13. **Logging**

**SEBELUM (CI3):**
```php
log_message('error', $e);
```

**SESUDAH (Laravel):**
```php
// Option 1: Helper
log_message('error', $e->getMessage());

// Option 2: Facade
use Illuminate\Support\Facades\Log;
Log::error($e->getMessage());
```

---

## 14. **Exception Handling**

**SEBELUM (CI3):**
```php
try {
    $kirim = $this->otp->kirimPesan($data);
} catch (Exception $e) {
    log_message('error', $e);
}
```

**SESUDAH (Laravel):**
```php
try {
    $kirim = $this->otp->kirimPesan($data);
} catch (Exception $e) {
    Log::error($e->getMessage());
    // atau
    report($e);  // Report ke exception handler
}
```

---

## Checklist Migrasi SmsController

- [x] Convert class declaration & namespace
- [x] Replace `$this->input` dengan `Request`
- [x] Replace `isCan()` dengan `abort()` atau middleware
- [x] Update view rendering
- [x] Update redirect patterns
- [x] Update route URL generation
- [x] Replace `show_404()` dengan `abort(404)`
- [x] Implement dependency injection
- [x] Convert DataTables logic
- [x] Update exception handling
- [x] Add form validation dengan Request
- [x] Create routes file

---

## Testing SmsController (Feature Test)

```php
// tests/Feature/SmsControllerTest.php

class SmsControllerTest extends TestCase {
    use RefreshDatabase;

    public function test_user_can_view_sms_inbox() {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'admin')
            ->get(route('sms.index'));
        
        $response->assertStatus(200)
            ->assertViewIs('admin.sms.inbox.index');
    }

    public function test_unauthorized_user_cannot_access() {
        $response = $this->get(route('sms.index'));
        
        $response->assertRedirect(route('login'));
    }
}
```

---

## Next Steps

1. **Migrate routing**: Masukkan routes/sms.php ke routes/web.php
2. **Create middleware** untuk common checks (jika diperlukan)
3. **Test features** sebelum push ke production
4. **Update CI3 routes** untuk backward compatibility
5. **Deprecate donjo-app/controllers/Sms.php** (keep for reference)
