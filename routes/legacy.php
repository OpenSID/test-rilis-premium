<?php

use App\Legacy\Controllers\Feed;


use App\Legacy\Controllers\Api_informasi_publik;
use App\Legacy\Controllers\Bumindes_arsip;
use App\Legacy\Controllers\First;
use App\Legacy\Controllers\Fmandiri\Bantuan;
use App\Legacy\Controllers\Fmandiri\Beranda;
use App\Legacy\Controllers\Fmandiri\Daftar;
use App\Legacy\Controllers\Fmandiri\Daftar_verifikasi;
use App\Legacy\Controllers\Fmandiri\Dokumen;
use App\Legacy\Controllers\Fmandiri\Kehadiran_perangkat;
use App\Legacy\Controllers\Fmandiri\Lapak as LapakMandiri;
use App\Legacy\Controllers\Fmandiri\Masuk;
use App\Legacy\Controllers\Fmandiri\Masuk_ektp;
use App\Legacy\Controllers\Fmandiri\Pesan;
use App\Legacy\Controllers\Fmandiri\Surat;
use App\Legacy\Controllers\Fmandiri\Verifikasi;
use App\Legacy\Controllers\Fweb\Galeri;
use App\Legacy\Controllers\Fweb\Kelompok;
use App\Legacy\Controllers\Fweb\Lapak;
use App\Legacy\Controllers\Fweb\Pembangunan;
use App\Legacy\Controllers\Fweb\Pengaduan;
use App\Legacy\Controllers\Fweb\Suplemen;
use App\Legacy\Controllers\Fweb\Vaksin;
use App\Legacy\Controllers\Fweb\Verifikasi_surat;
use App\Legacy\Controllers\Koneksi_database;
use App\Legacy\Controllers\Pelanggan;
use App\Legacy\Controllers\Sitemap;
use App\Legacy\Controllers\buku_umum\Bumindes_umum;
use App\Legacy\Controllers\buku_umum\Dokumen_sekretariat;
use Illuminate\Support\Facades\Route;

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

// Route bumindes
Route::match(['get', 'post'], 'bumindes_umum/{alp}/{any}', function ($alp, $any) {
    return redirect("buku_umum/bumindes_umum/{$alp}/{$any}");
})->where(['alp' => $alp, 'any' => $any]);

Route::match(['get', 'post'], 'bumindes_umum/{alp}', function ($alp) {
    return redirect("buku_umum/bumindes_umum/{$alp}");
})->where('alp', $alp);

Route::match(['get', 'post'], 'bumindes_umum', function () {
    return redirect("buku_umum/bumindes_umum");
});

foreach (['ekspedisi', 'lembaran_desa', 'pengurus', 'surat_keluar', 'surat_masuk'] as $menu) {

    Route::match(['get', 'post'], "{$menu}/{alp}/{any1}/{any2}/{any3}", function ($alp, $any1, $any2, $any3) use ($menu) {
        return redirect("buku_umum/{$menu}/{$alp}/{$any1}/{$any2}/{$any3}");
    })->where(['alp' => $alp, 'any1' => $any, 'any2' => $any, 'any3' => $any]);

    Route::match(['get', 'post'], "{$menu}/{alp}/{any1}/{any2}", function ($alp, $any1, $any2) use ($menu) {
        return redirect("buku_umum/{$menu}/{$alp}/{$any1}/{$any2}");
    })->where(['alp' => $alp, 'any1' => $any, 'any2' => $any]);

    Route::match(['get', 'post'], "{$menu}/{alp}/{any1}", function ($alp, $any) use ($menu) {
        return redirect("buku_umum/{$menu}/{$alp}/{$any}");
    })->where(['alp' => $alp, 'any1' => $any]);

    Route::match(['get', 'post'], "{$menu}/{alp}", function ($alp) use ($menu) {
        return redirect("buku_umum/{$menu}/{$alp}");
    })->where(['alp' => $alp]);

    Route::match(['get', 'post'], "{$menu}", function () use ($menu) {
        return redirect("buku_umum/{$menu}");
    });
}

Route::match(['get', 'post'], 'dokumen_sekretariat/{alp}/{any1}/{any2}/{any3}/{any4}', function ($alp, $any1, $any2, $any3, $any4) {
    return redirect("buku_umum/dokumen_sekretariat/{$alp}/{$any1}/{$any2}/{$any3}/{$any4}");
})->where(['alp' => $alp, 'any1' => $any, 'any2' => $any, 'any3' => $any, 'any4' => $any]);

Route::match(['get', 'post'], 'dokumen_sekretariat/{alp}/{any1}/{any2}/{any3}', function ($alp, $any1, $any2, $any3) {
    return redirect("buku_umum/dokumen_sekretariat/{$alp}/{$any1}/{$any2}/{$any3}");
})->where(['alp' => $alp, 'any1' => $any, 'any2' => $any, 'any3' => $any]);

Route::match(['get', 'post'], 'dokumen_sekretariat/{alp}/{any1}/{any2}', function ($alp, $any1, $any2) {
    return redirect("buku_umum/dokumen_sekretariat/{$alp}/{$any1}/{$any2}");
})->where(['alp' => $alp, 'any1' => $any, 'any2' => $any]);

Route::match(['get', 'post'], 'dokumen_sekretariat/{alp}/{any1}', function ($alp, $any) {
    return redirect("buku_umum/dokumen_sekretariat/{$alp}/{$any}");
})->where(['alp' => $alp, 'any1' => $any]);

Route::match(['get', 'post'], 'dokumen_sekretariat/{alp}', function ($alp) {
    return redirect("buku_umum/dokumen_sekretariat/{$alp}");
})->where(['alp' => $alp]);

Route::match(['get', 'post'], 'dokumen_sekretariat', function () {
    return redirect("buku_umum/dokumen_sekretariat");
});

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
    // Auth
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
    Route::match(['get', 'post'], 'pesan-masuk', function () {
        return app(Pesan::class)->index(2);
    });
    Route::match(['get', 'post'], 'pesan-keluar', function () {
        return app(Pesan::class)->index(1);
    });
    Route::match(['get', 'post'], 'pesan/tulis', function () {
        return app(Pesan::class)->tulis(1);
    });
    Route::match(['get', 'post'], 'pesan/balas', function () {
        return app(Pesan::class)->tulis(2);
    });
    Route::match(['get', 'post'], 'pesan/kirim', [Pesan::class, 'kirim']);
    Route::match(['get', 'post'], 'pesan/baca/{num1}/{num2}', [Pesan::class, 'baca'])->where(['num1' => $num, 'num2' => $num]);

    // Surat
    Route::match(['get', 'post'], 'arsip-surat', function () {
        return app(Surat::class)->index(2);
    });
    Route::match(['get', 'post'], 'permohonan-surat', function () {
        return app(Surat::class)->index(1);
    });
    Route::match(['get', 'post'], 'surat/buat', [Surat::class, 'buat']);
    Route::match(['get', 'post'], 'surat/buat/{num}', [Surat::class, 'buat'])->where(['num' => $num]);
    Route::match(['get', 'post'], 'surat/form', [Surat::class, 'form']);
    Route::match(['get', 'post'], 'surat/form/{num}', [Surat::class, 'form'])->where(['num' => $num]);

    // Dokumen
    Route::match(['get', 'post'], 'dokumen', [Dokumen::class, 'index']);
    Route::match(['get', 'post'], 'dokumen/form', [Dokumen::class, 'form']);
    Route::match(['get', 'post'], 'dokumen/form/{num}', [Dokumen::class, 'form'])->where(['num' => $num]);
    Route::match(['get', 'post'], 'dokumen/tambah', [Dokumen::class, 'tambah']);
    Route::match(['get', 'post'], 'dokumen/ubah/{num}', [Dokumen::class, 'ubah'])->where(['num' => $num]);
    Route::match(['get', 'post'], 'dokumen/hapus/{num}', [Dokumen::class, 'hapus'])->where(['num' => $num]);
    Route::match(['get', 'post'], 'dokumen/unduh/{num}', [Dokumen::class, 'unduh'])->where(['num' => $num]);

    // Lapak
    Route::match(['get', 'post'], 'lapak', [LapakMandiri::class, 'index']);
    Route::match(['get', 'post'], 'lapak/{num}', [LapakMandiri::class, 'index'])->where(['num' => $num]);

    // Verifikasi
    Route::match(['get', 'post'], 'verifikasi', [Verifikasi::class, 'index']);
    Route::match(['get', 'post'], 'verifikasi/telegram', [Verifikasi::class, 'telegram']);
    Route::match(['get', 'post'], 'verifikasi/kirim-userid', [Verifikasi::class, 'kirim_otp_telegram']);
    Route::match(['get', 'post'], 'verifikasi/kirim-otp', [Verifikasi::class, 'verifikasi_telegram']);
    Route::match(['get', 'post'], 'verifikasi/email', [Verifikasi::class, 'email']);
    Route::match(['get', 'post'], 'verifikasi/email/kirim-email', [Verifikasi::class, 'kirim_otp_email']);
    Route::match(['get', 'post'], 'verifikasi/email/kirim-otp', [Verifikasi::class, 'verifikasi_email']);

    // Bantuan
    Route::match(['get', 'post'], 'bantuan', [Bantuan::class, 'index']);

    // Kehadiran Perangkat Desa
    Route::match(['get', 'post'], 'kehadiran', [Kehadiran_perangkat::class, 'index']);
    Route::match(['get', 'post'], 'kehadiran/lapor/{num}', [Kehadiran_perangkat::class, 'lapor'])->where(['num' => $num]);
});

// Peringatan
Route::match(['get', 'post'], 'peringatan', [Pelanggan::class, 'peringatan']);

// Koneksi Database
Route::match(['get', 'post'], 'koneksi-database', [Koneksi_database::class, 'index']);