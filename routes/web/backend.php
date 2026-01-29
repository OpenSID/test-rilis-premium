<?php

use Illuminate\Support\Facades\Route;

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

// TODO:: Lebih baik buat migrasi untuk menghapus route yg lama
Route::get('wilayah/clear', function () {
    redirect('wilayah');
});
Route::get('penduduk/clear', function () {
    redirect('penduduk');
});
Route::get('keluarga/clear', function () {
    redirect('keluarga');
});
Route::get('rtm/clear', function () {
    redirect('rtm');
});
Route::get('suplemen/clear', function () {
    redirect('suplemen');
});
Route::get('dokumen/clear', function () {
    redirect('dokumen');
});
Route::get('bumindes_umum', function () {
    redirect('dokumen_sekretariat/peraturan');
});
// Route::redirect('ekspedisi/clear', 'ekspedisi'); // Ini aneh, jadi double
Route::get('ekspedisi/clear', function () {
    redirect('ekspedisi');
});
Route::get('bumindes_tanah_kas_desa/clear', function () {
    redirect('inventaris_kekayaan');
});
Route::get('dpt/clear', function () {
    redirect('dpt');
});
// Route::redirect('statistik/clear', 'statistik'); // Ini aneh, jadi double
Route::get('statistik/clear', function () {
    redirect('statistik');
});
Route::get('klasifikasi/clear', function () {
    redirect('klasifikasi');
});
Route::get('bumindes_tanah_desa/clear', function () {
    redirect('bumindes_tanah_desa');
});
Route::get('bumindes_inventaris_kekayaan/clear', function () {
    redirect('bumindes_inventaris_kekayaan');
});
Route::get('bumindes_penduduk_induk/clear', function () {
    redirect('bumindes_penduduk_induk');
});
Route::get('bumindes_penduduk_rekapitulasi/clear', function () {
    redirect('bumindes_penduduk_rekapitulasi');
});
Route::get('bumindes_penduduk_ktpkk/clear', function () {
    redirect('bumindes_penduduk_ktpkk');
});
Route::get('modul/clear', function () {
    redirect('modul');
});
Route::get('qr_code', function () {
    redirect('qrcode');
});
Route::get('qrcode/clear', function () {
    redirect('qrcode/clear');
});
Route::get('web/clear', function () {
    redirect('web');
});
Route::get('slider/clear', function () {
    redirect('slider');
});
Route::get('web_widget/clear', function () {
    redirect('web_widget');
});
Route::get('pengunjung/clear', function () {
    redirect('pengunjung');
});