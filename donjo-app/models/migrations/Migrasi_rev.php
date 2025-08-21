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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {
        $this->tambahSettingPbb();
        $this->tabelLogNotifikasiMandiri();
    }

    protected function tabelLogNotifikasiMandiri()
    {
        Schema::table('log_notifikasi_mandiri', static function (Blueprint $table) {
            $table->dropUnique('log_notifikasi_mandiri_device_unique');
        });
    }

     protected function tambahSettingPbb()
     {
        $this->createSetting([
            'judul'      => 'Sinkronisasi PBB',
            'key'        => 'sinkronisasi_pbb',
            'value'      => 0,
            'keterangan' => 'Aktifkan Sinkronisasi PBB',
            'kategori'   => 'pbb',
            'jenis'      => 'boolean',
            'option'     => null,
        ]);

        $this->createSetting([
            'judul'      => 'API Key PBB',
            'key'        => 'api_pbb_key',
            'value'      => null,
            'keterangan' => 'API Key untuk Sinkronisasi Data',
            'kategori'   => 'pbb',
            'jenis'      => 'textarea',
            'option'     => null,
        ]);

        $this->createModul(
            [
                'modul'  => 'PBB',
                'slug'   => 'pbb',
                'ikon'   => 'fa-cogs',
                'level'  => 1,
                'parent' => 0,
            ]
        );
        $this->createModul(
            [
                'modul'       => 'Sinkronisasi PBB',
                'slug'        => 'pbb-sinkronisasi',
                'url'         => 'pbb/sinkronisasi',
                'ikon'        => 'fa-random ',
                'parent_slug' => 'pbb',
                'level'       => 2,
            ]
        );

    }
}
