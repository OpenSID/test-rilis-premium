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

// Internal API
Route::group('internal_api', ['namespace' => 'internal_api'], static function (): void {
    // Wilayah
    Route::get('wilayah/get_rw', 'Wilayah@get_rw');
    Route::get('wilayah/get_rt', 'Wilayah@get_rt');
    Route::get('apipenduduksuplemen', 'Suplemen@apipenduduksuplemen');
    Route::get('pengaduan', 'Pengaduan@index');    
    Route::get('pembangunan', 'Pembangunan@index')->name('api.pembangunan');
    Route::get('arsip', 'Artikel@index');
    Route::get('galeri', 'Galeri@index');
    Route::get('galeri/{parent}', 'Galeri@detail');

    // Status Desa
    Route::get('sdgs', 'Sdgs@index')->name('api.sdgs');
    Route::get('idm/{tahun}', 'Idm@index')->name('api.idm');
    Route::get('stunting', 'Stunting@index')->name('api.stunting');

    // Lapak
    Route::group('lapak', static function (): void {
        Route::get('produk', 'Lapak@produk')->name('api.lapak.produk');
        Route::get('kategori', 'Lapak@kategori')->name('api.lapak.kategori');
        Route::get('pelapak', 'Lapak@pelapak')->name('api.lapak.pelapak');
    });

    // Informasi Publik
    Route::get('informasi-publik', 'InformasiPublik@index')->name('api.informasi-publik');

    // Produk Hukum
    Route::group('produk-hukum', static function (): void {
        Route::get('/', 'ProdukHukum@index')->name('api.produk-hukum');
        Route::get('tahun', 'ProdukHukum@tahun')->name('api.tahun-produk-hukum');
        Route::get('kategori', 'ProdukHukum@kategori')->name('api.kategori-produk-hukum');
    });

    // Peta
    Route::get('peta', 'Peta@index')->name('api.peta');

    // Statistik
    Route::get('statistik/{key}', 'Statistik@index');

    // Pemerintah
    Route::get('pemerintah', 'Pemerintah@index')->name('api.pemerintah');
});

// Eksternal API
Route::group('external_api', ['namespace' => 'external_api'], static function (): void {
    // Sign
    Route::get('sign/pdf', 'Sign@pdf');
    // Surat Kecamatan
    Route::group('surat_kecamatan', static function (): void {
        Route::post('/kirim', 'Surat_kecamatan@kirim');
        Route::get('/download/{jenis}/{nomor}/{desa}/{bulan}/{tahun}', 'Surat_kecamatan@download');
    });

    // TTE
    Route::group('tte', static function (): void {
        Route::get('/periksa_status/{nik?}', 'Tte@periksa_status');
        Route::post('/sign_invisible', 'Tte@sign_invisible');
        Route::post('/sign_visible', 'Tte@sign_visible');
    });
});

// API Publik
Route::group('', ['namespace' => 'fweb'], static function (): void {
    Route::group('api/v1', static function (): void {
        Route::get('sdgs', 'Sdgs@api_sdgs');
    });
});
