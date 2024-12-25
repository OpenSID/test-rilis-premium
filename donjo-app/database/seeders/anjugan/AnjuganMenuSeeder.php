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

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Anjungan\Models\AnjunganMenu;

class AnjuganMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $menuAnjungan = [
            [
                'nama'      => 'Peta Desa',
                'icon'      => 'peta.svg',
                'link'      => 'peta',
                'link_tipe' => 5,
                'urut'      => 1,
                'status'    => 1,
            ],
            [
                'nama'      => 'Informasi Pubik',
                'icon'      => 'protected.svg',
                'link'      => 'informasi_publik',
                'link_tipe' => 5,
                'urut'      => 2,
                'status'    => 1,
            ],
            [
                'nama'      => 'Data Pekerjaan',
                'icon'      => 'statistik.svg',
                'link'      => 'statistik/1',
                'link_tipe' => 2,
                'urut'      => 3,
                'status'    => 1,
            ],
            [
                'nama'      => 'Layanan Mandiri',
                'icon'      => 'mandiri.svg',
                'link'      => 'layanan-mandiri/beranda',
                'link_tipe' => 5,
                'urut'      => 4,
                'status'    => 1,
            ],
            [
                'nama'      => 'Lapak',
                'icon'      => 'lapak.svg',
                'link'      => 'lapak',
                'link_tipe' => 5,
                'urut'      => 5,
                'status'    => 1,
            ],
            [
                'nama'      => 'Keuangan',
                'icon'      => 'keuangan.svg',
                'link'      => 'artikel/100',
                'link_tipe' => 6,
                'urut'      => 6,
                'status'    => 1,
            ],
            [
                'nama'      => 'IDM 2020',
                'icon'      => 'idm.svg',
                'link'      => 'status-idm/2022',
                'link_tipe' => 10,
                'urut'      => 7,
                'status'    => 1,
            ],
        ];

        foreach ($menuAnjungan as $menu) {
            AnjunganMenu::create($menu);
        }

        $from  = FCPATH . LOKASI_ICON_MENU_ANJUNGAN_DEFAULT . 'contoh/';
        $to    = FCPATH . LOKASI_ICON_MENU_ANJUNGAN;
        $files = array_filter(glob("{$from}*"), 'is_file');

        foreach ($files as $file) {
            copy($file, $to . basename($file));
        }
    }
}
