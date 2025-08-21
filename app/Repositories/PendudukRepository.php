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

namespace App\Repositories;

use App\Models\PendudukSaja;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PendudukRepository
{
    public function listPenduduk()
    {

        $penduduk = QueryBuilder::for(PendudukSaja::query())
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('sex'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('pendidikan_kk_id'),
                AllowedFilter::exact('pendidikan_sedang_id'),
                AllowedFilter::exact('pekerjaan_id'),
                AllowedFilter::exact('pekerja_migran'),
                AllowedFilter::exact('status_kawin'),
                AllowedFilter::exact('agama_id'),
                AllowedFilter::exact('cara_kb_id'),
                AllowedFilter::exact('id_asuransi'),
                AllowedFilter::exact('id_rtm'),
                AllowedFilter::exact('hamil'),
                AllowedFilter::exact('suku'),
                AllowedFilter::exact('golongan_darah_id'),
                AllowedFilter::exact('cacat_id'),
                AllowedFilter::exact('sakit_menahun_id'),
                AllowedFilter::exact('kk_level'),
                AllowedFilter::exact('warganegara_id'),
                AllowedFilter::exact('config_id'),
                AllowedFilter::callback('nama', static function ($query, $value) {
                    $query->where('nama', 'like', "%{$value}%");
                }),
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->where(static function ($query) use ($value) {
                        $query->where('nama', 'like', "%{$value}%")
                            ->orWhere('nik', 'like', "%{$value}%")
                            ->orWhere('tag_id_card', 'like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts([
                'nik',
                'nama',
                'umur',
                'created_at',
                'tag_id_card',
            ]);

            return $penduduk->jsonPaginate();

    }
}
