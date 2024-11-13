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

    Route::group('galeri', static function (): void {        
        Route::get('', 'Galeri@index')->name('fweb.galeri.index');        
        Route::get('{parent}', 'Galeri@detail')->name('fweb.galeri.detail');
    });

    Route::get('/status-idm/{tahun?}', 'Idm@index');
    Route::get('/status-sdgs', 'Sdgs@index');
});

