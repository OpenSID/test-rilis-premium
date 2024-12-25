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

class RefPendudukPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('tweb_penduduk_pendidikan')->insert([
            ['id' => 1, 'nama' => 'BELUM MASUK TK/KELOMPOK BERMAIN'],
            ['id' => 2, 'nama' => 'SEDANG TK/KELOMPOK BERMAIN'],
            ['id' => 3, 'nama' => 'TIDAK PERNAH SEKOLAH'],
            ['id' => 4, 'nama' => 'SEDANG SD/SEDERAJAT'],
            ['id' => 5, 'nama' => 'TIDAK TAMAT SD/SEDERAJAT'],
            ['id' => 6, 'nama' => 'SEDANG SLTP/SEDERAJAT'],
            ['id' => 7, 'nama' => 'SEDANG SLTA/SEDERAJAT'],
            ['id' => 8, 'nama' => 'SEDANG  D-1/SEDERAJAT'],
            ['id' => 9, 'nama' => 'SEDANG D-2/SEDERAJAT'],
            ['id' => 10, 'nama' => 'SEDANG D-3/SEDERAJAT'],
            ['id' => 11, 'nama' => 'SEDANG  S-1/SEDERAJAT'],
            ['id' => 12, 'nama' => 'SEDANG S-2/SEDERAJAT'],
            ['id' => 13, 'nama' => 'SEDANG S-3/SEDERAJAT'],
            ['id' => 14, 'nama' => 'SEDANG SLB A/SEDERAJAT'],
            ['id' => 15, 'nama' => 'SEDANG SLB B/SEDERAJAT'],
            ['id' => 16, 'nama' => 'SEDANG SLB C/SEDERAJAT'],
            [
                'id'   => 17,
                'nama' => 'TIDAK DAPAT MEMBACA DAN MENULIS HURUF LATIN/ARAB',
            ],
            ['id' => 18, 'nama' => 'TIDAK SEDANG SEKOLAH'],
        ]);
    }
}

