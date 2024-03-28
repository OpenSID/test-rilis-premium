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

namespace App\Traits;

use App\Models\UserGrup;
use App\Enums\StatusEnum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait Migrasi
{
    protected function tambahModul($modul)
    {
        $parentId            = DB::table('setting_modul')->where('config_id', $modul['config_id'])->where('slug', $modul['slug_parent'])->value('id');
        $urutTerakhir        = DB::table('setting_modul')->where('config_id', $modul['config_id'])->where('parent', $parentId)->max('urut') + 1;
        $modul['slug']       = $modul['slug']       ?: Str::slug($modul['modul']);
        $modul['url']        = $modul['url']        ?: Str::slug($modul['modul']);
        $modul['aktif']      = $modul['aktif']      ?: StatusEnum::YA;
        $modul['level']      = $modul['level']      ?: 1;
        $modul['hidden']     = $modul['hidden']     ?: StatusEnum::TIDAK;
        $modul['ikon_kecil'] = $modul['ikon_kecil'] ?: $modul['ikon'];
        $modul['urut']       = $modul['urut']       ?: $urutTerakhir;
        $modul['parent']     = $modul['parent']     ?: $parentId;

        unset($modul['slug_parent']);

        $cekModul = DB::table('setting_modul')->where('config_id', $modul['config_id'])->where('slug', $modul['slug'])->exists();
        if ($cekModul) {
            if (isset($modul['urut'])) {
                unset($modul['urut']);
            }
            unset($modul['modul']);

            DB::table('setting_modul')->where('config_id', $modul['config_id'])->where('slug', $modul['slug'])->update($modul);
            $result = true;
        } else {
            $result = DB::table('setting_modul')->insert($modul);

            $modulId = DB::table('setting_modul')->where('config_id', $modul['config_id'])->where('slug', $modul['slug'])->value('id');
            $grupId  = DB::table('user_grup')->where('config_id', $modul['config_id'])->where('slug', UserGrup::OPERATOR)->value('id');
            $this->hakAkses($modul['config_id'], $grupId, $modulId, 3);
        }

        return $result;
    }

    protected function hakAkses($configId, $idGrup, $idModul, $akses)
    {
        $hakAkses = [
            'config_id' => $configId,
            'id_grup'  => $idGrup,
            'id_modul' => $idModul,
        ];

        return DB::table('grup_akses')->updateOrInsert(
            $hakAkses,
            array_merge($hakAkses, ['akses' => $akses])
        );
    }

    public function ubahModul($where, array $modul)
    {
        if (is_array($where)) {
            DB::table('setting_modul')->where($where)->update($modul);
        } else {
            DB::table('setting_modul')->where('id', $where)->update($modul);
        }

        return true;
    }

    public function tambahSetting(array $setting)
    {
        $setting['key']       = $setting['key'] ?: Str::slug($setting['judul'], '_');
        $setting['option']    = $setting['option'] ? json_encode($setting['option']) : null;
        $setting['attribute'] = $setting['attribute'] ?: null;
        $setting['value']     = is_array($setting['value']) ? json_encode($setting['value']) : $setting['value'];

        $cekSetting = DB::table('setting')->where('key', $setting['key'])->exists();
        if ($cekSetting) {
            unset($setting['value']);
            DB::table('setting')->where('key', $setting['key'])->update($setting);
            $result = true;
        } else {
            $result = DB::table('setting')->insert($setting);
        }

        return $result;
    }
}
