<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Legacy\Api_informasi_publik;
use App\Http\Controllers\Legacy\buku_umum\Bumindes_umum;
use App\Http\Controllers\Legacy\buku_umum\Dokumen_sekretariat;
use App\Http\Controllers\Legacy\Bumindes_arsip;
use App\Http\Controllers\Legacy\Feed;
use App\Http\Controllers\Legacy\First;
use App\Http\Controllers\Legacy\Fmandiri\Beranda;
use App\Http\Controllers\Legacy\Fmandiri\Daftar;
use App\Http\Controllers\Legacy\Fmandiri\Daftar_verifikasi;
use App\Http\Controllers\Legacy\Fmandiri\Masuk;
use App\Http\Controllers\Legacy\Fmandiri\Masuk_ektp;
use App\Http\Controllers\Legacy\Fmandiri\Pesan;
use App\Http\Controllers\Legacy\Fweb\Galeri;
use App\Http\Controllers\Legacy\Fweb\Kelompok;
use App\Http\Controllers\Legacy\Fweb\Lapak;
use App\Http\Controllers\Legacy\Fweb\Pembangunan;
use App\Http\Controllers\Legacy\Fweb\Pengaduan;
use App\Http\Controllers\Legacy\Fweb\Suplemen;
use App\Http\Controllers\Legacy\Fweb\Vaksin;
use App\Http\Controllers\Legacy\Fweb\Verifikasi_surat;
use App\Http\Controllers\Legacy\Sitemap;

// Regex
$alp = '[a-z_]+';
$num = '[0-9]+';
$any = '[^/]+';

// index of route
Route::get('/', [First::class, 'index']);

Route::get('sitemap.xml', [Sitemap::class, 'index']);
Route::get('feed.xml', [Feed::class, 'index']);
Route::get('ppid', [Api_informasi_publik::class, 'ppid']);

// Artikel
Route::get('artikel/{num}', [First::class, 'artikel'])->where('num', $num);                     // Contoh : artikel/1
Route::get('artikel/{num1}/{num2}/{num3}/{any}/', function ($num1, $num2, $num3, $any) {
    return app(First::class)->artikel($any);
})->where(['num1' => $num, 'num2' => $num, 'num3' => $num, 'any' => $any]);                     // Contoh : artikel/2020/5/15/contoh-artikel

// Artikel lama (Agar url lama masih dpt di akases)
Route::get('first/artikel/{num}', [First::class, 'artikel'])->where('num', $num);               // Contoh : first/artikel/1
Route::get('first/artikel/{num1}/{num2}/{num3}/{any}/', function ($num1, $num2, $num3, $any) {
    return app(First::class)->artikel($any);
})->where(['num1' => $num, 'num2' => $num, 'num3' => $num, 'any' => $any]);                     // Contoh : first/artikel/2020/5/15/contoh-artikel

Route::get('bumindes_umum/{alp}/{any}', [Bumindes_umum::class, 'index'])->where(['alp' => $alp, 'any' => $any]);
Route::get('bumindes_umum/{alp}', [Bumindes_umum::class, 'index'])->where('alp', $alp);
Route::get('bumindes_umum', [Bumindes_umum::class, 'index']);

Route::get('bumindes_arsip', [Bumindes_arsip::class, 'index']);
Route::get('bumindes_arsip/{num}', [Bumindes_arsip::class, 'index'])->where('num', $num);
Route::get('bumindes_arsip/{num1}/{num2}', [Bumindes_arsip::class, 'index'])->where(['num1' => $num, 'num2' => $num]);

$buku_umum = ['ekspedisi', 'lembaran_desa', 'pengurus', 'surat_keluar', 'surat_masuk'];

foreach ($buku_umum as $menu) {
    $controller = sprintf("App\Http\Controllers\Legacy\buku_umum\%s@index", ucfirst($menu));

    Route::get("{$menu}/{alp}/{any1}/{any2}/{any3}", $controller)->where(['alp' => $alp, 'any1' => $any, 'any2' => $any, 'any3' => $any]);
    Route::get("{$menu}/{alp}/{any1}/{any2}", $controller)->where(['alp' => $alp, 'any1' => $any, 'any2' => $any]);
    Route::get("{$menu}/{alp}/{any1}", $controller)->where(['alp' => $alp, 'any1' => $any]);
    Route::get("{$menu}/{alp}", $controller)->where(['alp' => $alp]);
    Route::get("{$menu}", $controller);
}

Route::get('dokumen_sekretariat/{alp}/{any1}/{any2}/{any3}/{any4}', [Dokumen_sekretariat::class, 'index'])->where(['alp' => $alp, 'any1' => $any, 'any2' => $any, 'any3' => $any, 'any4' => $any]);
Route::get('dokumen_sekretariat/{alp}/{any1}/{any2}/{any3}', [Dokumen_sekretariat::class, 'index'])->where(['alp' => $alp, 'any1' => $any, 'any2' => $any, 'any3' => $any]);
Route::get('dokumen_sekretariat/{alp}/{any1}/{any2}', [Dokumen_sekretariat::class, 'index'])->where(['alp' => $alp, 'any1' => $any, 'any2' => $any]);
Route::get('dokumen_sekretariat/{alp}/{any1}', [Dokumen_sekretariat::class, 'index'])->where(['alp' => $alp, 'any1' => $any]);
Route::get('dokumen_sekretariat/{alp}', [Dokumen_sekretariat::class, 'index'])->where(['alp' => $alp]);
Route::get('dokumen_sekretariat', [Dokumen_sekretariat::class, 'index']);

// Route untuk menghilangkan 'first' dari URL web
// Kategori artikel
Route::get('artikel/kategori/{any}', [First::class, 'kategori'])->where('any', $any);
Route::get('artikel/kategori/{any}/{num}', [First::class, 'kategori'])->where(['any' => $any, 'num' => $num]);

Route::get('index/{num}', [First::class, 'index'])->where('num', $num);
Route::get('{num}', [First::class, 'index'])->where('num', $num);
Route::get('arsip', [First::class, 'arsip']);
Route::get('arsip/{num}', [First::class, 'arsip'])->where('num', $num);
Route::get('add_comment/{any}', [First::class, 'indadd_commentex'])->where('any', $any);
Route::get('ambil_data_covid', [First::class, 'ambil_data_covid']);
Route::get('load_apbdes', [First::class, 'load_apbdes']);
Route::get('logout', [First::class, 'logout']);
Route::get('ganti', [First::class, 'ganti']);
Route::get('auth', [First::class, 'auth']);

// Halaman statis
Route::get('data-wilayah', [First::class, 'data-wilayah']);
Route::get('data-kelompok/{num}', [First::class, 'data-kelompok'])->where('num', $num);
Route::get('informasi_publik', [First::class, 'informasi_publik']);
Route::get('peraturan_desa', [First::class, 'peraturan_desa']);
Route::get('data_analisis', [First::class, 'data_analisis']);
Route::get('data_analisis/{any}', [First::class, 'auth'])->where('any', $any);
Route::get('jawaban_analisis/{any}', [First::class, 'jawaban_analisis'])->where('any', $any);
Route::get('load_aparatur_desa', [First::class, 'load_aparatur_desa']);
Route::get('load_aparatur_wilayah/{any}', [First::class, 'auth'])->where('any', $any);

// WEB
// Pembangunan
Route::get('pembangunan', [Pembangunan::class, 'index']);
Route::get('pembangunan/index/{num}', [Pembangunan::class, 'index'])->where('num', $num);
Route::get('pembangunan/{any}', [Pembangunan::class, 'detail'])->where('any', $any);

// Lapak
Route::get('lapak', [Lapak::class, 'index']);
Route::get('lapak/{num}', [Lapak::class, 'index'])->where('num', $num);

// Pengaduan
Route::get('pengaduan', [Pengaduan::class, 'index']);
Route::get('pengaduan/{num}', [Pengaduan::class, 'index'])->where('num', $num);
Route::get('pengaduan/kirim', [Pengaduan::class, 'kirim']);

// Surat
Route::get('v/{any}', [Verifikasi_surat::class, 'cek'])->where('any', $any);
Route::get('c1/{any}', [Verifikasi_surat::class, 'encode'])->where('any', $any);
Route::get('verifikasi-surat/{any}', [Verifikasi_surat::class, 'decode'])->where('any', $any);

// Galeri
Route::get('galeri/{num1}/index/{num2}', [Galeri::class, 'detail'])->where(['num1' => $num, 'num2' => $num]);
Route::get('galeri/{num}', [Galeri::class, 'detail'])->where(['num' => $num]);
Route::get('galeri/index/{num}', [Galeri::class, 'index'])->where(['num' => $num]);
Route::get('galeri', [Galeri::class, 'index']);

// Suplemen
Route::get('data-suplemen/{any}', [Suplemen::class, 'detail'])->where('any', $any);

// Kelompok
Route::get('data-kelompok/{any}', [Kelompok::class, 'detail'])->where('any', $any);

// Vaksin
Route::get('data-vaksinasi', [Vaksin::class, 'index']);

// Halaman Layanan Mandiri
Route::prefix('layanan-mandiri')->group(function () use ($num) {
    // Front
    Route::match(['get', 'post'], 'masuk', [Masuk::class, 'index']);
    Route::match(['get', 'post'], 'cek', [Masuk::class, 'cek']);
    Route::match(['get', 'post'], 'masuk-ektp', [Masuk_ektp::class, 'index']);
    Route::match(['get', 'post'], 'cek-ektp', [Masuk_ektp::class, 'cek_ektp']);
    Route::match(['get', 'post'], 'daftar', [Daftar::class, 'index']);
    Route::match(['get', 'post'], 'proses-daftar', [Daftar::class, 'proses_daftar']);
    Route::match(['get', 'post'], 'daftar/verifikasi', [Daftar_verifikasi::class, 'index']);
    Route::match(['get', 'post'], 'daftar/verifikasi/telegram', [Daftar_verifikasi::class, 'telegram']);
    Route::match(['get', 'post'], 'daftar/verifikasi/telegram/kirim-userid', [Daftar_verifikasi::class, 'kirim_otp_telegram']);
    Route::match(['get', 'post'], 'daftar/verifikasi/telegram/kirim-otp', [Daftar_verifikasi::class, 'verifikasi_telegram']);
    Route::match(['get', 'post'], 'daftar/verifikasi/email', [Daftar_verifikasi::class, 'email']);
    Route::match(['get', 'post'], 'daftar/verifikasi/email/kirim-email', [Daftar_verifikasi::class, 'kirim_otp_email']);
    Route::match(['get', 'post'], 'daftar/verifikasi/email/kirim-otp', [Daftar_verifikasi::class, 'verifikasi_email']);
    Route::match(['get', 'post'], 'lupa-pin', [Masuk::class, 'lupa_pin']);
    Route::match(['get', 'post'], 'cek-pin', [Masuk::class, 'cek_pin']);
    // Beranda
    Route::get('/', [Beranda::class, 'index']);
    Route::get('pendapat/{num}', [Beranda::class, 'pendapat'])->where(['num' => $num]);
    // Profil
    Route::match(['get', 'post'], 'profil', [Beranda::class, 'profil']);
    Route::match(['get', 'post'], 'cetak-biodata', [Beranda::class, 'cetak_biodata']);
    Route::match(['get', 'post'], 'ganti-pin', [Beranda::class, 'ganti_pin']);
    Route::match(['get', 'post'], 'proses-ganti-pin', [Beranda::class, 'proses_ganti_pin']);
    Route::match(['get', 'post'], 'cetak-kk', [Beranda::class, 'cetak_kk']);
    Route::match(['get', 'post'], 'keluar', [Beranda::class, 'keluar']);
    // Pesan
});