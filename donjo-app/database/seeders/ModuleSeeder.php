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

namespace Database\Seeders;

use App\Traits\Migrator;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ModuleSeeder extends Seeder
{
    use Migrator;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Create Module Parent
        $this->createModuls([
            [
                'modul'      => 'Kependudukan',
                'slug'       => 'kependudukan',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-users',
                'urut'       => 30,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Statistik',
                'slug'       => 'statistik',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-line-chart',
                'urut'       => 40,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Layanan Surat',
                'slug'       => 'layanan-surat',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-book',
                'urut'       => 50,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Analisis',
                'slug'       => 'analisis',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => ' fa-check-square-o',
                'urut'       => 90,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Bantuan',
                'slug'       => 'bantuan',
                'url'        => 'program_bantuan/clear',
                'aktif'      => 1,
                'ikon'       => 'fa-heart',
                'urut'       => 100,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Pertanahan',
                'slug'       => 'pertanahan',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-map-signs',
                'urut'       => 110,
                'level'      => 2,
                'hidden'     => 0,
                'ikon_kecil' => 'fa fa-map-signs',
                'parent'     => 0,
            ],
            [
                'modul'      => 'Pemetaan',
                'slug'       => 'pemetaan',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-globe',
                'urut'       => 130,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Hubung Warga',
                'slug'       => 'hubung-warga',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-envelope',
                'urut'       => 140,
                'level'      => 2,
                'hidden'     => 0,
                'ikon_kecil' => 'fa fa-envelope',
                'parent'     => 0,
            ],
            [
                'modul'      => 'Pengaturan',
                'slug'       => 'pengaturan',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-users',
                'urut'       => 150,
                'level'      => 1,
                'hidden'     => 1,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Admin Web',
                'slug'       => 'admin-web',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-desktop',
                'urut'       => 160,
                'level'      => 4,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Layanan Mandiri',
                'slug'       => 'layanan-mandiri',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-inbox',
                'urut'       => 170,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Sekretariat',
                'slug'       => 'sekretariat',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-archive',
                'urut'       => 60,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Informasi Publik',
                'slug'       => 'informasi-publik',
                'url'        => 'dokumen/clear',
                'aktif'      => 1,
                'ikon'       => 'fa-file-text',
                'urut'       => 4,
                'level'      => 4,
                'hidden'     => 0,
                'parent'     => 15,
            ],
            [
                'modul'      => 'Info [Desa]',
                'slug'       => 'info-desa',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-dashboard',
                'urut'       => 20,
                'level'      => 2,
                'hidden'     => 1,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Keuangan',
                'slug'       => 'keuangan',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-balance-scale',
                'urut'       => 80,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'id'         => 206,
                'config_id'  => 1,
                'modul'      => 'Kesehatan',
                'slug'       => 'kesehatan',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-heartbeat',
                'urut'       => 41,
                'level'      => 2,
                'hidden'     => 0,
                'ikon_kecil' => 'fa fa-heartbeat',
                'parent'     => 0,
            ],
            [
                'modul'      => 'Buku Administrasi [Desa]',
                'slug'       => 'buku-administrasi-desa',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-paste',
                'urut'       => 70,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Anjungan',
                'slug'       => 'anjungan',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-desktop',
                'urut'       => 180,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Kehadiran',
                'slug'       => 'kehadiran',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-calendar-check-o',
                'urut'       => 41,
                'level'      => 0,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'OpenDK',
                'slug'       => 'opendk',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-university',
                'urut'       => 124,
                'level'      => 2,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Satu Data',
                'slug'       => 'satu-data',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-globe',
                'urut'       => 180,
                'level'      => 1,
                'hidden'     => 0,
                'parent'     => 0,
            ],
            [
                'modul'      => 'Buku Tamu',
                'slug'       => 'buku-tamu',
                'url'        => '',
                'aktif'      => 1,
                'ikon'       => 'fa-book',
                'urut'       => 180,
                'level'      => 2,
                'hidden'     => 0,
                'ikon_kecil' => 'fa-book',
                'parent'     => 0,
            ],
        ]);

        // Create Module Child
        
    }
}
