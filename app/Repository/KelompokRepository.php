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

namespace App\Repository;

use App\Models\Kelompok;
use App\Models\KelompokAnggota;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class KelompokRepository
{
    public $tipe = 'kelompok';

    public function detail($slug)
    {
        return QueryBuilder::for(Kelompok::with('pengurus')->tipe($this->tipe)->whereSlug($slug))
            ->allowedFields('*')
            ->first();
    }

    public function anggota($slug)
    {
        return QueryBuilder::for(KelompokAnggota::with('anggota')->anggota()
                ->slugKelompok($slug)
                ->orderByRaw('CAST(no_anggota AS UNSIGNED)'))
            ->allowedFields('*')
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->where(static function ($subQuery) use ($value) {
                        $subQuery->where('no_anggota', 'LIKE', '%' . $value . '%')
                            ->orWhere('sex', 'LIKE', '%' . $value . '%')
                            ->orWhere('alamat_lengkap', 'LIKE', '%' . $value . '%')
                            ->orWhereHas('anggota', static function ($anggotaQuery) use ($value) {
                                $anggotaQuery->where('nama', 'LIKE', '%' . $value . '%');
                            });
                    });
                }),
            ])
            ->allowedSorts(['id', 'no_anggota', 'sex', 'alamat_lengkap', 'nama_penduduk'])
            ->jsonPaginate();
    }
}
