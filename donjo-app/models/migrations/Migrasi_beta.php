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

use App\Models\SettingAplikasi;
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

        $hasil = $hasil && $this->migrasi_2024040271($hasil);

        return $hasil && true;
    }

    protected function migrasi_2024040271($hasil)
    {
        $penduduk_luar = SettingAplikasi::withoutGlobalScope(App\Scopes\ConfigIdScope::class)->where('key', '=', 'form_penduduk_luar')->first();
        if ($penduduk_luar) {
            $value             = json_decode($penduduk_luar->value, true);
            $value[3]['input'] = 'nama,no_ktp,tempat_lahir,tanggal_lahir,jenis_kelamin,agama,pendidikan_kk,pekerjaan,warga_negara,alamat,golongan_darah,status_perkawinan,tanggal_perkawinan,shdk,no_paspor,no_kitas,nama_ayah,nama_ibu,no_kk,kepala_kk';
            $penduduk_luar->update(['value' => json_encode($value)]);
        }

        return $hasil;
    }

    public function migrasi_2024070971($hasil, $id)
    {
        if (! Schema::hasTable('log_ttd')) {
            Schema::create('log_ttd', static function (Blueprint $table) {
                $table->uuid()->primary();
                $table->integer('config_id');
                $table->integer('log_surat_id');
                $table->text('alasan')->nullable();
                $table->timestamps();
                $table->integer('created_by');
                $table->integer('updated_by');

                $table->foreign('config_id')->references('id')->on('config')->onDelete('cascade');
                $table->foreign('log_surat_id')->references('id')->on('log_surat')->onDelete('cascade');
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

        return $hasil && $this->tambah_setting([
            'judul'      => 'Lebar Visual Tanda Tangan [Pemerintah Desa]',
            'key'        => 'visual_ttd_width',
            'value'      => 100,
            'keterangan' => 'Lebar Visual Tanda Tangan [Pemerintah Desa]',
            'jenis'      => null,
            'attribute'  => null,
            'kategori'   => 'surat_master',
        ], $id);
    }
}
