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

use App\Models\UserGrup;
use Fcm\DeviceGroup\Create;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserGrupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $userGrup = [
            [
                'nama'       => 'Administrator',
                'slug'       => 'administrator',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'nama'       => 'Operator',
                'slug'       => 'operator',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'nama'       => 'Redaksi',
                'slug'       => 'redaksi',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'nama'       => 'Kontributor',
                'slug'       => 'kontributor',
                'jenis'      => 1,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'nama'       => 'Satgas Covid-19',
                'slug'       => 'satgas-covid-19',
                'jenis'      => 2,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
        ];

        foreach ($userGrup as $value) {
            UserGrup::create($value);
        }
    }
}
