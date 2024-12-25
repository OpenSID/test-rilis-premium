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
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RefPendudukBidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('ref_penduduk_bidang')->insert([
            ['id' => 1, 'nama' => 'Service Komputer'],
            ['id' => 2, 'nama' => 'Operator Buldoser'],
            ['id' => 3, 'nama' => 'Operator Komputer'],
            ['id' => 4, 'nama' => 'Operator Genset'],
            ['id' => 5, 'nama' => 'Service HP'],
            ['id' => 6, 'nama' => 'Rias Pengantin'],
            ['id' => 7, 'nama' => 'Design Grafis'],
            ['id' => 8, 'nama' => 'Menjahit'],
            ['id' => 9, 'nama' => 'Menulis'],
            ['id' => 10, 'nama' => 'Reporter'],
            ['id' => 11, 'nama' => 'Sosial Media Manajer'],
            ['id' => 12, 'nama' => 'Manajemen Trainee'],
            ['id' => 13, 'nama' => 'Kasir'],
            ['id' => 14, 'nama' => 'HRD'],
            ['id' => 15, 'nama' => 'Guru'],
            ['id' => 16, 'nama' => 'Digital Marketing'],
            ['id' => 17, 'nama' => 'Customer Services'],
            ['id' => 18, 'nama' => 'Welder'],
            ['id' => 19, 'nama' => 'Mekanik Alat Berat'],
            ['id' => 20, 'nama' => 'Teknisi Listrik'],
            ['id' => 21, 'nama' => 'Internet Marketing'],
        ]);
    }
}

