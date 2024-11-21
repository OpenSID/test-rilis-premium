<?php

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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

Route::group('', ['namespace' => 'fweb'], static function (): void {
    Route::get('/', 'Utama@index');
    Route::get('/index/{p?}', 'Utama@index');

    // Rute untuk Artikel Lama
    Route::group('/first/artikel', static function (): void {
        Route::get('/', 'Artikel@utama');
        Route::get('/{id}', 'Artikel@index');
        Route::get('/{thn}/{bln}/{tgl}/{slug}', 'Artikel@index');
    });

    // Rute untuk Artikel Baru
    Route::group('/artikel', static function (): void {
        Route::get('/kategori/{id}/{p?}', 'Artikel@kategori');
        Route::get('datatables_peserta_bantuan/{lap}', 'Artikel@datatables_peserta_bantuan');
        Route::get('{id}', 'Artikel@index');
        Route::get('{thn}/{bln}/{tgl}/{slug}', 'Artikel@index');
    });

    // Arsip Artikel
    Route::get('arsip', 'Arsip@index');    
    Route::get('data-kesehatan/cetak/{aksi?}', 'Kesehatan@cetak')->name('fweb.kesehatan.cetak');
    Route::post('data-kesehatan/scorecard', 'Kesehatan@scorecard')->name('fweb.kesehatan.scorecard');
    Route::get('data-kesehatan/{slug?}', 'Kesehatan@detail')->name('fweb.kesehatan.detail');

    // Status Desa
    Route::get('/status-idm/{tahun?}', 'Idm@index');
    Route::get('/status-sdgs', 'Sdgs@index');

    // Galeri
    Route::group('galeri', static function (): void {
        Route::get('', 'Galeri@index')->name('web.galeri.index');
        Route::get('{parent}', 'Galeri@detail')->name('web.galeri.detail');
    });
    
    Route::group('inventaris', static function (): void {
        Route::get('', 'Inventaris@index')->name('fweb.inventaris.index');
        Route::get('{slug}', 'Inventaris@detail')->name('fweb.inventaris.detail');
    });

    Route::group('pengaduan', static function (): void {
        Route::post('/kirim', 'Pengaduan@kirim')->name('fweb.pengaduan.kirim');
        Route::get('/{p?}', 'Pengaduan@index')->name('fweb.pengaduan.index');
    });

    // Statistik
    Route::get('first/statistik/{stat?}/{tipe?}', 'Statistik@index')->name('first.statistik');
    Route::get('data-statistik/{slug}/cetak/{aksi}', 'Statistik@cetak')->name('fweb.statistik.cetak');
    Route::get('data-statistik/{slug?}', 'Statistik@index')->name('fweb.statistik.index');
    
    Route::get('peraturan-desa', 'Peraturan@index')->name('web.peraturan.index');

    // Pembangunan
    Route::group('pembangunan', static function (): void {
        Route::get('/', 'Pembangunan@index')->name('web.pembangunan.index');
        Route::get('/index', 'Pembangunan@index')->name('web.pembangunan.index-page');
        Route::get('/{slug}', 'Pembangunan@detail')->name('web.pembangunan.detail');
    });

    // Peta
    Route::get('peta', 'Peta@index')->name('web.peta.index');
});