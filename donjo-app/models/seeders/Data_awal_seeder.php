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

use App\Models\Config;
use Illuminate\Support\Facades\DB;
use Database\Seeders\DatabaseSeeder;
use App\Imports\KlasifikasiSuratImports;

defined('BASEPATH') || exit('No direct script access allowed');

class Data_awal_seeder extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '512M');
        set_time_limit(5400);
    }

    public function run()
    {

        $this->load->helper('directory');
        $directoryTable = 'donjo-app/models/migrations/struktur_tabel';
        $migrations     = directory_map($directoryTable, 1);
        // sort by name
        usort($migrations, static fn ($a, $b) => strcmp($a, $b));

        foreach ($migrations as $migrate) {
            $migrateFile = require $directoryTable . DIRECTORY_SEPARATOR . $migrate;
            $migrateFile->up();
        }

        // Panggil DatabaseSeeder
        (new DatabaseSeeder())->run();
        
        $this->addDataMaster();
    }

    private function addDataMaster()
    {
        

        

        

        

        

        

        

        

        

        

        

        

        
        

        
        

        

        

        

        

        

        // DB::table('tweb_penduduk_umur')->insert(); ikut data awal

        

        

        

        

        

        

        $this->load->model('seeders/dataAwal/Twebaset', 'twebaset');
        $this->load->model('seeders/dataAwal/KeuanganManualRefKegiatan', 'keuanganRefKegiatan');
        $this->load->model('seeders/dataAwal/PendudukSuku', 'pendudukSuku');
        DB::table('tweb_aset')->insert($this->twebaset->getData());
        DB::table('keuangan_manual_ref_kegiatan')->insert($this->keuanganRefKegiatan->getData());
        $this->impor_klasifikasi();
        DB::table('ref_penduduk_suku')->insert($this->pendudukSuku->getData());
        // DB::table('tweb_format_surat')->insert(); ikut data awal
    }

    public function impor_klasifikasi()
    {
        (new KlasifikasiSuratImports())->import();
    }
}
