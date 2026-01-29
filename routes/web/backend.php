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
Route::redirect('wilayah/clear', 'wilayah');
Route::redirect('penduduk/clear', 'penduduk');
Route::redirect('keluarga/clear', 'keluarga');
Route::redirect('rtm/clear', 'rtm');
Route::redirect('suplemen/clear', 'suplemen');
Route::redirect('dokumen/clear', 'dokumen');
Route::redirect('bumindes_umum', 'dokumen_sekretariat/peraturan');
Route::redirect('ekspedisi/clear', 'ekspedisi');
Route::redirect('bumindes_tanah_kas_desa/clear', 'inventaris_kekayaan');
Route::redirect('dpt/clear', 'dpt');
Route::redirect('statistik/clear', 'statistik');
Route::redirect('klasifikasi/clear', 'klasifikasi');
Route::redirect('bumindes_tanah_desa', 'inventaris_kekayaan');
Route::redirect('bumindes_inventaris_kekayaan', 'inventaris_kekayaan');
Route::redirect('bumindes_penduduk_induk', 'statistik_penduduk');
Route::redirect('bumindes_penduduk_rekapitulasi', 'statistik_penduduk');
Route::redirect('bumindes_penduduk_ktpkk', 'statistik_penduduk');
Route::redirect('modul/clear', 'modul');
Route::redirect('qr_code', 'pengaturan_qrcode');
Route::redirect('qrcode', 'pengaturan_qrcode');
Route::redirect('qrcode/clear', 'pengaturan_qrcode');
Route::redirect('web/clear', 'web');
Route::redirect('slider', 'slider');
Route::redirect('slider/clear', 'slider');
Route::redirect('web_widget/clear', 'web_widget');
Route::redirect('pengunjung/clear', 'pengunjung');