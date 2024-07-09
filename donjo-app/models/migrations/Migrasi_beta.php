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

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_beta extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024070971($hasil, $id);
        }

        return $hasil && true;
    }

    public function migrasi_2024070971($hasil, $id)
    {
        if (! Schema::hasTable('log_ttd')) {
            Schema::create('log_ttd', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->integer('config_id');
                $table->integer('log_surat_id');
                $table->text('alasan')->nullable();
                $table->timestamps();
                $table->integer('created_by');
                $table->integer('updated_by');
            });
        }

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Tanda Tangan [Pemerintah Desa]',
            'key'        => 'ttd_scan',
            'value'      => 0,
            'keterangan' => 'Tanda Tangan [Pemerintah Desa]',
            'jenis'      => 'boolean',
            'attribute'  => null,
            'kategori'   => 'surat_master',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Visual Tanda Tangan [Pemerintah Desa]',
            'key'        => 'visual_ttd_scan',
            'value'      => null,
            'keterangan' => 'Visual Tanda Tangan [Pemerintah Desa]',
            'jenis'      => null,
            'attribute'  => null,
            'kategori'   => 'surat_master',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Tinggi Visual Tanda Tangan [Pemerintah Desa]',
            'key'        => 'visual_ttd_height',
            'value'      => 100,
            'keterangan' => 'Tinggi Visual Tanda Tangan [Pemerintah Desa]',
            'jenis'      => null,
            'attribute'  => null,
            'kategori'   => 'surat_master',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Lebar Visual Tanda Tangan [Pemerintah Desa]',
            'key'        => 'visual_ttd_width',
            'value'      => 100,
            'keterangan' => 'Lebar Visual Tanda Tangan [Pemerintah Desa]',
            'jenis'      => null,
            'attribute'  => null,
            'kategori'   => 'surat_master',
        ], $id);

        return $hasil;
    }
}
