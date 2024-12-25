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

use App\Models\Widget;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $widget = [
            [
                'isi'          => '<p><iframe src="https://www.google.co.id/maps?f=q&source=s_q&hl=en&geocode=&q=Logandu,+Karanggayam&aq=0&oq=logan&sll=-2.550221,118.015568&sspn=52.267573,80.332031&t=h&ie=UTF8&hq=&hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&ll=-7.55854,109.634173&spn=0.052497,0.078449&z=14&output=embed" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" width="100%"></iframe></p> ',
                'enabled'      => 2,
                'judul'        => 'Peta Desa',
                'jenis_widget' => 3,
                'urut'         => 1,
                'form_admin'   => '',
                'setting'      => '',
            ],
            [
                'isi'          => 'agenda.php',
                'enabled'      => 1,
                'judul'        => 'Agenda',
                'jenis_widget' => 1,
                'urut'         => 6,
                'form_admin'   => 'web/tab/1000',
                'setting'      => '',
            ],
            [
                'isi'          => 'galeri.php',
                'enabled'      => 1,
                'judul'        => 'Galeri',
                'jenis_widget' => 1,
                'urut'         => 8,
                'form_admin'   => 'gallery',
                'setting'      => '',
            ],
            [
                'isi'          => 'statistik.php',
                'enabled'      => 1,
                'judul'        => 'Statistik',
                'jenis_widget' => 1,
                'urut'         => 4,
                'form_admin'   => '',
                'setting'      => '',
            ],
            [
                'isi'          => 'komentar.php',
                'enabled'      => 1,
                'judul'        => 'Komentar',
                'jenis_widget' => 1,
                'urut'         => 10,
                'form_admin'   => 'komentar',
                'setting'      => '',
            ],
            [
                'isi'          => 'media_sosial.php',
                'enabled'      => 1,
                'judul'        => 'Media Sosial',
                'jenis_widget' => 1,
                'urut'         => 11,
                'form_admin'   => 'sosmed',
                'setting'      => '',
            ],
            [
                'isi'          => 'peta_lokasi_kantor.php',
                'enabled'      => 1,
                'judul'        => 'Peta Lokasi Kantor',
                'jenis_widget' => 1,
                'urut'         => 13,
                'form_admin'   => 'identitas_desa/maps/kantor',
                'setting'      => '',
            ],
            [
                'isi'          => 'statistik_pengunjung.php',
                'enabled'      => 1,
                'judul'        => 'Statistik Pengunjung',
                'jenis_widget' => 1,
                'urut'         => 14,
                'form_admin'   => '',
                'setting'      => '',
            ],
            [
                'isi'          => 'arsip_artikel.php',
                'enabled'      => 1,
                'judul'        => 'Arsip Artikel',
                'jenis_widget' => 1,
                'urut'         => 5,
                'form_admin'   => '',
                'setting'      => '',
            ],
            [
                'isi'          => 'aparatur_desa.php',
                'enabled'      => 1,
                'judul'        => 'Aparatur Desa',
                'jenis_widget' => 1,
                'urut'         => 9,
                'form_admin'   => 'web_widget/admin/aparatur_desa',
                'setting'      => '{"overlay":"1"}',
            ],
            [
                'isi'          => 'sinergi_program.php',
                'enabled'      => 1,
                'judul'        => 'Sinergi Program',
                'jenis_widget' => 1,
                'urut'         => 7,
                'form_admin'   => 'web_widget/admin/sinergi_program',
                'setting'      => '[]',
            ],
            [
                'isi'          => 'menu_kategori.php',
                'enabled'      => 1,
                'judul'        => 'Menu Kategori',
                'jenis_widget' => 1,
                'urut'         => 2,
                'form_admin'   => '',
                'setting'      => '',
            ],
            [
                'isi'          => 'peta_wilayah_desa.php',
                'enabled'      => 1,
                'judul'        => 'Peta Wilayah Desa',
                'jenis_widget' => 1,
                'urut'         => 12,
                'form_admin'   => 'identitas_desa/maps/wilayah',
                'setting'      => '',
            ],
            [
                'isi'          => 'keuangan.php',
                'enabled'      => 1,
                'judul'        => 'Keuangan',
                'jenis_widget' => 1,
                'urut'         => 15,
                'form_admin'   => 'keuangan/impor_data',
                'setting'      => '',
            ],
        ];

        foreach ($widget as $data) {
            Widget::create($data);
        }
    }
}
