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

use App\Models\UserGrup;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_dev extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil && true;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024051651($hasil, $id);
        }

        return $hasil && true;
    }

    public function migrasi_2024051651($hasil, $id)
    {
        // tambahkan setting yang belum ada di tabel setting_aplikasi, karena ditambahkan manual

        // Sebutan Kepala Desa
        $hasil && $this->tambah_setting([
            'judul'      => 'Sebutan Kepala Desa',
            'key'        => 'sebutan_kepala_desa',
            'value'      => null, // terisi otomatis dari query kades()->nama
            'keterangan' => null,
            'jenis'      => null,
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'hidden',
        ], $id);

        // Sebutan Sekretaris Desa
        $hasil && $this->tambah_setting([
            'judul'      => 'Sebutan Sekretaris Desa',
            'key'        => 'sebutan_sekretaris_desa',
            'value'      => null, // terisi otomatis dari query sekdes()->nama
            'keterangan' => null,
            'jenis'      => null,
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'hidden',
        ], $id);

        // multi_desa
        $hasil && $this->tambah_setting([
            'judul'      => 'Multi Desa',
            'key'        => 'multi_desa',
            'value'      => null, // terisi otomatis dari query Config::count() > 1
            'keterangan' => null,
            'jenis'      => null,
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'hidden',
        ], $id);

        // surat_margin_cm_to_mm
        $hasil && $this->tambah_setting([
            'judul'      => 'Margin Surat (cm)',
            'key'        => 'surat_margin_cm_to_mm',
            'value'      => null,
            // terisi otomatis dari Konversi nilai margin global dari cm ke mm
            // $margins                              = json_decode($this->setting->surat_margin, true);
            // $this->setting->surat_margin_cm_to_mm = [
            //     $margins['kiri'] * 10,
            //     $margins['atas'] * 10,
            //     $margins['kanan'] * 10,
            //     $margins['bawah'] * 10,
            // ];
            'keterangan' => null,
            'jenis'      => null,
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'hidden',
        ], $id);

        return $hasil;
    }
}
