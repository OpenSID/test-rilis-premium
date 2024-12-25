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

class RefPendudukKursusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('ref_penduduk_kursus')->insert([
            ['id' => 1, 'nama' => 'Kursus Komputer'],
            ['id' => 2, 'nama' => 'Kursus Menjahit'],
            ['id' => 3, 'nama' => 'Pelatihan Kelistrikan'],
            ['id' => 4, 'nama' => 'Kursus Mekanik Motor'],
            ['id' => 5, 'nama' => 'Pelatihan Security'],
            ['id' => 6, 'nama' => 'Kursus Otomotif'],
            ['id' => 7, 'nama' => 'Kursus Bahasa Inggris'],
            ['id' => 8, 'nama' => 'Kursus Tata Kecantikan Kulit'],
            ['id' => 9, 'nama' => 'Kursus Megemudi'],
            ['id' => 10, 'nama' => 'Kursus Tata Boga'],
            ['id' => 11, 'nama' => 'Kursus Meubeler'],
            ['id' => 12, 'nama' => 'Kursus Las'],
            ['id' => 13, 'nama' => 'Kursus Sablon'],
            ['id' => 14, 'nama' => 'Kursus Penerbangan'],
            ['id' => 15, 'nama' => 'Kursus Desain Interior'],
            ['id' => 16, 'nama' => 'Kursus Teknisi HP'],
            ['id' => 17, 'nama' => 'Kursus Garment'],
            ['id' => 18, 'nama' => 'Kursus Akupuntur'],
            ['id' => 19, 'nama' => 'Kursus Senam'],
            ['id' => 20, 'nama' => 'Kursus Pendidik PAUD'],
            ['id' => 21, 'nama' => 'Kursus Baby Sitter'],
            ['id' => 22, 'nama' => 'Kursus Desain Grafis'],
            ['id' => 23, 'nama' => 'Kursus Bahasa Indonesia'],
            ['id' => 24, 'nama' => 'Kursus Photografi'],
            ['id' => 25, 'nama' => 'Kursus Expor Impor'],
            ['id' => 26, 'nama' => 'Kursus Jurnalistik'],
            ['id' => 27, 'nama' => 'Kursus Bahasa Arab'],
            ['id' => 28, 'nama' => 'Kursus Bahasa Jepang'],
            ['id' => 29, 'nama' => 'Kursus Anak Buah Kapal'],
            ['id' => 30, 'nama' => 'Kursus Refleksi'],
            ['id' => 31, 'nama' => 'Kursus Akupuntur'],
            ['id' => 32, 'nama' => 'Kursus Perhotelan'],
            ['id' => 33, 'nama' => 'Kursus Tata Rias'],
            ['id' => 34, 'nama' => 'Kursus Administrasi Perkantoran'],
            ['id' => 35, 'nama' => 'Kursus Broadcasting'],
            ['id' => 36, 'nama' => 'Kursus Kerajinan Tangan'],
            ['id' => 37, 'nama' => 'Kursus Sosial Media Marketing'],
            ['id' => 38, 'nama' => 'Kursus Internet Marketing'],
            ['id' => 39, 'nama' => 'Kursus Sekretaris'],
            ['id' => 40, 'nama' => 'Kursus Perpajakan'],
            ['id' => 41, 'nama' => 'Kursus Publik Speaking'],
            ['id' => 42, 'nama' => 'Kursus Publik Relation'],
            ['id' => 43, 'nama' => 'Kursus Batik'],
            ['id' => 44, 'nama' => 'Kursus Pengobatan Tradisional'],
        ]);
    }
}

