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

namespace Database\Seeders\Pertanahan;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RefPersilKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('ref_persil_kelas')->insert([
            [
                'id'    => 1,
                'tipe'  => 'BASAH',
                'kode'  => 'S-I',
                'ndesc' => 'Persawahan Dekat dengan Pemukiman',
            ],
            [
                'id'    => 2,
                'tipe'  => 'BASAH',
                'kode'  => 'S-II',
                'ndesc' => 'Persawahan Agak Dekat dengan Pemukiman',
            ],
            [
                'id'    => 3,
                'tipe'  => 'BASAH',
                'kode'  => 'S-III',
                'ndesc' => 'Persawahan Jauh dengan Pemukiman',
            ],
            [
                'id'    => 4,
                'tipe'  => 'BASAH',
                'kode'  => 'S-IV',
                'ndesc' => 'Persawahan Sangat Jauh dengan Pemukiman',
            ],
            [
                'id'    => 5,
                'tipe'  => 'KERING',
                'kode'  => 'D-I',
                'ndesc' => 'Lahan Kering Dekat dengan Pemukiman',
            ],
            [
                'id'    => 6,
                'tipe'  => 'KERING',
                'kode'  => 'D-II',
                'ndesc' => 'Lahan Kering Agak Dekat dengan Pemukiman',
            ],
            [
                'id'    => 7,
                'tipe'  => 'KERING',
                'kode'  => 'D-III',
                'ndesc' => 'Lahan Kering Jauh dengan Pemukiman',
            ],
            [
                'id'    => 8,
                'tipe'  => 'KERING',
                'kode'  => 'D-IV',
                'ndesc' => 'Lahan Kering Sanga Jauh dengan Pemukiman',
            ],
        ]);
    }
}

