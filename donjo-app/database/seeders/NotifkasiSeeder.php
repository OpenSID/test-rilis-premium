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

use Mockery\Matcher\Not;
use App\Models\Notifikasi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $nofitikasi = [
            [
                'kode'           => 'persetujuan_penggunaan',
                'judul'          => '<i class="fa fa-file-text-o text-black"></i> &nbsp;Persetujuan Penggunaan OpenSID',
                'jenis'          => 'persetujuan',
                'isi'            => '<p><b>Untuk menggunakan OpenSID, anda dan desa anda perlu menyetujui ketentuan berikut:</b>\n                    <ol>\n                      <li>Pengguna telah membaca dan menyetujui <a href="https://www.gnu.org/licenses/gpl-3.0.en.html" target="_blank">Lisensi GPL V3</a>.</li>\n                     <li>OpenSID gratis dan disediakan "SEBAGAIMANA ADANYA", di mana segala tanggung jawab termasuk keamanan data desa ada pada pengguna.</li>\n                       <li>Pengguna paham bahwa setiap ubahan OpenSID juga berlisensi GPL V3 yang tidak dapat dimusnahkan, dan aplikasi ubahan itu juga sumber terbuka yang bebas disebarkan oleh pihak yang menerima.</li>\n                      <li>Pengguna mengetahui, paham dan menyetujui bahwa OpenSID akan mengirim data penggunaan ke server OpenDesa secara berkala untuk tujuan menyempurnakan OpenSID, dengan pengertian bahwa data yang dikirim sama sekali tidak berisi data identitas penduduk atau data sensitif desa lainnya.</li>\n                 </ol></p>\n                 <b>Apakah anda dan desa anda setuju dengan ketentuan di atas?</b>',
                'server'         => 'client',
                'tgl_berikutnya' => '2022-03-01 04:16:23',
                'updated_at'     => '2021-12-01 04:16:23',
                'updated_by'     => 1,
                'frekuensi'      => 90,
                'aksi'           => 'notif/update_pengumuman,siteman/logout',
                'aktif'          => 1,
            ],
            [
                'kode'           => 'tracking_off',
                'judul'          => '<i class="fa fa-exclamation-triangle text-red"></i> &nbsp;Peringatan Tracking Off',
                'jenis'          => 'peringatan',
                'isi'            => '<p>Kami mendeteksi bahwa anda telah mematikan fitur tracking. Bila dimatikan, penggunaan website desa anda tidak akan tercatat di server OpenDesa dan tidak akan menerima informasi penting yang sesekali dikirim OpenDesa.</p>\n                   <br><b>Hidupkan kembali tracking untuk mendapatkan informasi dari OpenDesa?</b>',
                'server'         => 'client',
                'tgl_berikutnya' => '2020-07-30 03:37:42',
                'updated_at'     => '2020-07-30 10:37:03',
                'updated_by'     => 1,
                'frekuensi'      => 90,
                'aksi'           => 'setting/aktifkan_tracking,notif/update_pengumuman',
                'aktif'          => 0,
            ],
        ];

        foreach ($nofitikasi as $notif) {
            Notifikasi::create($notif);
        }
    }
}
