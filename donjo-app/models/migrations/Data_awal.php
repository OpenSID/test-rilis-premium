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

use Carbon\Carbon;
use App\Models\Modul;
use App\Models\Config;
use App\Models\UserGrup;
use App\Traits\Migrator;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Data_awal extends MY_Model
{
    use Migrator;
    
    public function up()
    {
        $hasil = true;

        cache()->forget('identitas_desa');

        // Tambah Modul
        $hasil = $hasil && $this->tambah_modul($hasil);

        // Keuangan Manual
        return $hasil && $this->keuangan_manual($hasil);
    }

    // Tambah syarat surat pada tabel surat
    public function tambah_modul($hasil): bool
    {
        $this->load->model('seeders/dataAwal/SettingModul', 'settingModul');
        $data   = $this->settingModul->getData();
        $parent = [
            '2'   => 'kependudukan',
            '3'   => 'statistik',
            '4'   => 'layanan-surat',
            '5'   => 'analisis',
            '6'   => 'bantuan',
            '7'   => 'pertanahan',
            '9'   => 'pemetaan',
            '10'  => 'hubung-warga',
            '11'  => 'pengaturan',
            '13'  => 'admin-web',
            '14'  => 'layanan-mandiri',
            '15'  => 'sekretariat',
            '200' => 'info-desa',
            '201' => 'keuangan',
            '206' => 'kesehatan',
            '220' => 'pembangunan',
            '301' => 'buku-administrasi-desa',
            '312' => 'anjungan',
            '324' => 'lapak',
            '334' => 'pengaduan',
            '337' => 'kehadiran',
            '343' => 'opendk',
            '352' => 'satu-data',
            '354' => 'buku-tamu',
        ];
        // jika parent belum ada maka tambahkan dulu
        $cekParent = DB::table('setting_modul')->where(['slug' => 'kependudukan', 'config_id' => $this->config_id])->count();
        if (! $cekParent) {
            $slugParent = implode("','", $parent);
            DB::statement("
                insert into setting_modul (config_id, modul, slug, url, aktif, ikon, urut, `level`, hidden , ikon_kecil , parent)
                select {$this->config_id}, modul, slug, url, aktif, ikon, urut, `level`, hidden , ikon_kecil , parent  from setting_modul where config_id = 1 and slug in ('{$slugParent}')
            ");
        }
        $hasil = $hasil && $this->data_awal('setting_modul', $data);

        foreach ($parent as $key => $value) {
            DB::table('setting_modul')->where('id', $key)->update(['slug' => $value]);

            // Cari parent_id
            $parent_id = DB::table('setting_modul')->where('config_id', $this->config_id)->where('slug', $value)->value('id');

            // Update parent submodul
            DB::table('setting_modul')->where('config_id', $this->config_id)->where('parent', $key)->update(['parent' => $parent_id]);
        }

        return $hasil;
    }

    // Keuangan Manual
    protected function keuangan_manual($hasil)
    {
        

        return true;
    }
}
