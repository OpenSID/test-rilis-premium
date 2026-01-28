# Dokumentasi Package OpenSID

Ini adalah daftar package yang telah ditambahkan ke project untuk mendukung fungsionalitas OpenSID.

---

## 📦 Package Dokumentasi & Penggunaan

### 1. **cviebrock/eloquent-sluggable** ^10.0
**Fungsi:** Otomatis membuat URL-friendly slug dari field tertentu
```php
// Model
use Cviebrock\Sluggable\Sluggable;

class Penduduk extends Model {
    use Sluggable;
    
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'nama' // generate slug dari field 'nama'
            ]
        ];
    }
}

// Penggunaan
$penduduk = Penduduk::where('slug', 'john-doe')->first();
```
**Docs:** https://github.com/cviebrock/eloquent-sluggable

---

### 2. **dg/mysql-dump** ^1.6
**Fungsi:** Backup database MySQL/MariaDB
```php
use Drago\Database\MySqlDump;

$dump = new MySqlDump([
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_pass' => 'password',
    'db_name' => 'opensid'
]);

$dump->start('backup-' . date('Y-m-d-H-i-s') . '.sql');
```
**Docs:** https://github.com/dg/mysql-dump

---

### 3. **doctrine/dbal** 3.7.0
**Fungsi:** Database Abstraction Layer untuk query database yang kompleks
```php
use Doctrine\DBAL\DriverManager;

$connection = DriverManager::getConnection(['url' => 'mysql://user:pass@localhost/opensid']);
$result = $connection->fetchOne('SELECT * FROM penduduk WHERE id = ?', [1]);
```
**Docs:** https://www.doctrine-project.org/projects/dbal.html

---

### 4. **dragonmantank/cron-expression** ^3.5
**Fungsi:** Parse dan evaluate cron expressions
```php
use Cron\CronExpression;

$cron = CronExpression::factory('0 0 * * *'); // Daily at midnight
if ($cron->isDue()) {
    // Jalankan task harian
}
```
**Docs:** https://github.com/dragonmantank/cron-expression

---

### 5. **edwinhoksberg/php-fcm** ^1.2
**Fungsi:** Firebase Cloud Messaging untuk push notifications
```php
use Edwinhoksberg\LaravelFcm\Facades\Fcm;

Fcm::sendMessage(
    [
        'notification' => [
            'title' => 'Notifikasi Penting',
            'body' => 'Ada pengumuman baru dari desa'
        ],
        'data' => ['id' => 123]
    ],
    $device_token
);
```
**Docs:** https://github.com/edwinhoksberg/php-fcm

---

### 6. **erusev/parsedown** ^1.7
**Fungsi:** Parse Markdown ke HTML
```php
use Parsedown;

$Parsedown = new Parsedown();
$html = $Parsedown->text('# Heading\n\nParagraf dengan **bold**');
echo $html;
```
**Docs:** https://parsedown.org/

---

### 7. **f9webltd/laravel-api-response-helpers** ^2.0
**Fungsi:** Helper untuk response API yang konsisten
```php
use F9Web\ApiResponseHelpers;

trait uses ApiResponseHelpers;

public function show($id) {
    $data = Penduduk::find($id);
    return $this->respondWithSuccess($data);
}

// Error response
return $this->respondWithError('Tidak ditemukan', 404);
```
**Docs:** https://github.com/9develop/laravel-api-response-helpers

---

### 8. **google/apiclient** ^2.11
**Fungsi:** Akses Google APIs (Drive, Sheets, Calendar, dll)
```php
use Google\Client;

$client = new Client();
$client->setAuthConfig('service-account-key.json');
$client->addScope('https://www.googleapis.com/auth/drive');

$service = new Google\Service\Drive($client);
$files = $service->files->listFiles();
```
**Docs:** https://github.com/googleapis/google-api-php-client

---

### 9. **laravel/helpers** ^1.7
**Fungsi:** Helper functions Laravel tambahan
```php
// Sudah tersedia berbagai helper
route('penduduk.show', $id);
asset('css/app.css');
```
**Docs:** https://laravel.com/docs/helpers

---

### 10. **league/flysystem** ^3.8.0
**Fungsi:** Abstraksi file storage (Local, S3, FTP, dll)
```php
use Illuminate\Support\Facades\Storage;

// Local
Storage::disk('local')->put('file.txt', $content);

// S3
Storage::disk('s3')->put('file.txt', $content);

// Read
$content = Storage::disk('local')->get('file.txt');
```
**Docs:** https://flysystem.thephpleague.com/

---

### 11. **mike42/escpos-php** ^3.0
**Fungsi:** Print ke thermal printer (POS/Kasir)
```php
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

$printer = new Printer("tcp://192.168.1.100:9100");
$printer->text("Surat Keterangan\n");
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("Desa Contoh\n");
$printer->cut();
$printer->close();
```
**Docs:** https://github.com/mike42/escpos-php

---

### 12. **openspout/openspout** ^4.25
**Fungsi:** Baca/tulis Excel & CSV dengan efisien
```php
use OpenSpout\Reader\Common\Creator\ReaderEntityFactory;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

// Read
$reader = ReaderEntityFactory::createReaderFromFile('data.xlsx');
$reader->open('data.xlsx');
foreach ($reader->getSheetIterator() as $sheet) {
    foreach ($sheet->getRowIterator() as $row) {
        print_r($row->getCells());
    }
}
$reader->close();

// Write
$writer = WriterEntityFactory::createXLSXWriter();
$writer->openToFile('output.xlsx');
$writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['Name', 'Email']));
$writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['John', 'john@example.com']));
$writer->close();
```
**Docs:** https://github.com/openspout/openspout

---

### 13. **ramsey/uuid** ^4.9
**Fungsi:** Generate UUID (Universally Unique Identifier)
```php
use Ramsey\Uuid\Uuid;

$uuid1 = Uuid::uuid1(); // Time-based
$uuid4 = Uuid::uuid4(); // Random
$uuid7 = Uuid::uuid7(); // Unix timestamp based

echo $uuid4->toString(); // "f47ac10b-58cc-4372-a567-0e02b2c3d479"
```
**Docs:** https://uuid.ramsey.dev/

---

### 14. **rap2hpoutre/fast-excel** ^5.6
**Fungsi:** Export/import Excel besar dengan cepat
```php
use Rap2hpoutre\FastExcel\FastExcel;

// Export
(new FastExcel(Penduduk::all()))->download('penduduk.xlsx');

// Import
(new FastExcel())->import(request()->file('file'), function ($line) {
    Penduduk::create($line);
});
```
**Docs:** https://github.com/rap2hpoutre/FastExcel

---

### 15. **rennokki/laravel-eloquent-query-cache** ^3.4
**Fungsi:** Cache query Eloquent otomatis
```php
use Rennokki\QueryCache\Traits\QueryCacheable;

class Penduduk extends Model {
    use QueryCacheable;
    
    protected $cacheFor = 3600; // Cache 1 jam
}

// Automatically cached
$penduduk = Penduduk::with('keluarga')->get();
```
**Docs:** https://github.com/rennokki/laravel-eloquent-query-cache

---

### 16. **slowprog/composer-copy-file** ^0.3.3
**Fungsi:** Copy file dari vendor saat install/update composer
**Config di composer.json:**
```json
"copy-file": {
    "vendor/tinymce/tinymce": "assets/js/tinymce-72"
}
```

---

### 17. **spatie/eloquent-sortable** ^4.3
**Fungsi:** Sort model dengan drag-drop (orderBy)
```php
use Spatie\EloquentSortable\Sortable;

class Menu extends Model implements Sortable {
    use \Spatie\EloquentSortable\SortableTrait;
    
    public $sortable = ['order_column'];
}

// Sortable query
Menu::ordered()->get();
Menu::where('kategori', 'main')->ordered()->get();
```
**Docs:** https://github.com/spatie/eloquent-sortable

---

### 18. **spatie/image** ^2.2
**Fungsi:** Manipulasi image (resize, crop, filter)
```php
use Spatie\Image\Image;

Image::load('input.jpg')
    ->width(300)
    ->height(200)
    ->optimize()
    ->save('output.jpg');
```
**Docs:** https://github.com/spatie/image

---

### 19. **spatie/laravel-activitylog** ^4.10
**Fungsi:** Log semua aktivitas/perubahan data
```php
use Spatie\Activitylog\Traits\LogsActivity;

class Penduduk extends Model {
    use LogsActivity;
    
    protected static $logFillable = true;
    protected static $logName = 'penduduk';
}

// Query logs
activity()->all();
activity()->where('subject_type', Penduduk::class)->get();
```
**Docs:** https://github.com/spatie/laravel-activitylog

---

### 20. **spatie/laravel-fractal** ^6.2
**Fungsi:** Transform data dengan Fractal (API Transformer)
```php
use League\Fractal\TransformerAbstract;

class PendudukTransformer extends TransformerAbstract {
    public function transform(Penduduk $penduduk) {
        return [
            'id' => $penduduk->id,
            'nama' => $penduduk->nama,
            'nik' => $penduduk->nik,
        ];
    }
}

// Dalam controller
return fractal($penduduk, new PendudukTransformer());
```
**Docs:** https://github.com/spatie/laravel-fractal

---

### 21. **spatie/laravel-json-api-paginate** ^1.15
**Fungsi:** JSON API pagination standard
```php
use Spatie\JsonApiPaginate\JsonApiPaginationProvider;

// auto paginate dengan JSON API format
Penduduk::jsonPaginate();
```
**Docs:** https://github.com/spatie/laravel-json-api-paginate

---

### 22. **spatie/laravel-one-time-passwords** ^1.7
**Fungsi:** OTP (One Time Password) untuk 2FA
```php
use Spatie\LaravelOneTimePasswords\Models\OneTimePassword;

// Generate
$otp = OneTimePassword::createForModel($user);

// Verify
if ($otp->used_at) {
    // Sudah digunakan
}
```
**Docs:** https://github.com/spatie/laravel-one-time-passwords

---

### 23. **spatie/laravel-query-builder** ^5.7
**Fungsi:** Build query API dengan filter, sort, include relations
```php
use Spatie\QueryBuilder\QueryBuilder;

$penduduk = QueryBuilder::for(Penduduk::class)
    ->allowedFilters(['nama', 'nik'])
    ->allowedSorts(['nama', 'nik'])
    ->allowedIncludes(['keluarga', 'pendidikan'])
    ->get();

// Usage: /api/penduduk?filter[nama]=John&sort=-nik&include=keluarga
```
**Docs:** https://github.com/spatie/laravel-query-builder

---

### 24. **spipu/html2pdf** ^5.3
**Fungsi:** Convert HTML ke PDF
```php
use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML('<h1>Surat Keterangan</h1><p>Desa Contoh</p>');
$html2pdf->output('surat.pdf');
```
**Docs:** https://github.com/spipu/html2pdf

---

### 25. **stechstudio/laravel-zipstream** ^5.7
**Fungsi:** Download files sebagai ZIP stream (efisien)
```php
use Stechstudio\LaravelZipstream\ZipStream;

return ZipStream::make('export.zip', function (ZipStream $zip) {
    $zip->addFile('file1.txt', 'Content 1');
    $zip->addFile('file2.pdf', Storage::disk('local')->get('reports/2024.pdf'));
    foreach (Penduduk::all() as $penduduk) {
        $zip->addFile("penduduk/{$penduduk->id}.csv", $penduduk->toCSV());
    }
});
```
**Docs:** https://github.com/stechstudio/laravel-zipstream

---

### 26. **tinymce/tinymce** ^7.2
**Fungsi:** Rich text editor untuk form
```html
<!-- Dalam Blade template -->
<textarea name="deskripsi" id="editor"></textarea>

<script src="{{ asset('assets/js/tinymce-72/tinymce.min.js') }}"></script>
<script>
tinymce.init({
    selector: '#editor',
    plugins: 'image link media',
    toolbar: 'undo redo | bold italic | image link media'
});
</script>
```
**Docs:** https://www.tiny.cloud/

---

### 27. **voku/anti-xss** ^4.1
**Fungsi:** Sanitasi input untuk mencegah XSS
```php
use voku\helper\AntiXSS;

$antiXss = new AntiXSS();
$clean = $antiXss->clean('<script>alert("xss")</script>');
echo $clean; // Output aman
```
**Docs:** https://github.com/voku/anti-xss

---

### 28. **yajra/laravel-datatables-oracle** ^10.11
**Fungsi:** Server-side datatable untuk tabel besar
```php
use Yajra\DataTables\Facades\DataTables;

// Controller
public function index() {
    return DataTables::of(Penduduk::query())->make(true);
}

// Blade
<table id="table" class="table">
    <thead>
        <tr><th>Nama</th><th>NIK</th></tr>
    </thead>
</table>

<script>
$('#table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/api/penduduk',
    columns: [
        {data: 'nama'},
        {data: 'nik'}
    ]
});
</script>
```
**Docs:** https://github.com/yajra/laravel-datatables

---

### 29. **laravel-notification-channels/telegram** ^5.0
**Fungsi:** Kirim notifikasi via Telegram Bot
```php
use NotificationChannels\Telegram\TelegramChannel;

class PengumumanNotification extends Notification {
    public function via($notifiable) {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable) {
        return TelegramMessage::create()
            ->content('Ada pengumuman baru dari desa');
    }
}
```
**Docs:** https://github.com/laravel-notification-channels/telegram

---

### 30. **karriere/pdf-merge** ^2.1
**Fungsi:** Merge multiple PDF files
```php
use Clegginabox\PDFMerger\PDFMerger;

$merger = new PDFMerger;
$merger->addPDF('file1.pdf', 'all')
       ->addPDF('file2.pdf', '1,2')
       ->merge()
       ->output(PDFMerger::OUTPUT_DOWNLOAD, 'merged.pdf');
```
**Docs:** https://github.com/Clegginabox/pdf-merge

---

## 🔧 Instalasi

```bash
composer install
# atau jika sudah ada
composer update
```

---

## 📋 Dev Dependencies Tambahan

### 1. **ergebnis/composer-normalize** ^2.42
Untuk normalize composer.json
```bash
composer normalize
```

### 2. **friendsofphp/php-cs-fixer** ^3.49
Untuk auto-fix code style
```bash
composer fixer
```

### 3. **mikey179/vfsstream** ~1.1.0
Virtual file system untuk testing

### 4. **rector/rector** ^1.0
Untuk automatic code refactoring
```bash
composer rector
```

---

## 🚀 Tips Penggunaan

1. **Jangan import semua, gunakan yang diperlukan**
   ```php
   // Hanya gunakan apa yang dibutuhkan
   use Ramsey\Uuid\Uuid;
   use Spatie\Image\Image;
   ```

2. **Cache query untuk performa**
   ```php
   $penduduk = Penduduk::cache(3600)->get();
   ```

3. **Gunakan laravel/helpers untuk fungsi global**
   ```php
   route('home')
   asset('css/app.css')
   trans('messages.welcome')
   ```

4. **Aktivitas log otomatis dengan spatie/activitylog**
   ```php
   activity('update penduduk')->performedOn($penduduk)->log('Diubah oleh admin');
   ```

---

## 📚 Referensi

- [Laravel Documentation](https://laravel.com/docs)
- [Spatie Packages](https://spatie.be/opensource)
- [PHP Packages](https://packagist.org/)
