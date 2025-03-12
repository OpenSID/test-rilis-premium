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

namespace App\Models;

use App\Traits\Author;
use App\Traits\ConfigId;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class DokumenPenduduk extends BaseModel
{
    use ConfigId;
    use Author;
    use Uuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokumen_penduduk';
    protected $primaryKey = 'uuid';

    public function getKeyName()
    {
        return 'uuid'; // Setiap query 'id' otomatis menjadi 'uuid'
    }

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = ['uuid'];

    /**
     * The casts with the model.
     *
     * @var array
     */
    protected $casts = [
    ];

    protected $appends = [
        'tgl_upload'
    ];

    protected function getTglUploadAttribute()
    {
        return $this->created_at;
    }

    public function scopeHidup($query)
    {
        return $query->where('deleted', '!=', 1)->orWhereNull('deleted');
    }

    /**
     * Scope daftar arsip fisik kependudukan.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArsipFisikKependudukan(mixed $query)
    {
        return $query
            ->select([
                DB::raw('dokumen_penduduk.uuid'),
                DB::raw("'' as nomor_dokumen"),
                DB::raw('DATE(dokumen_penduduk.updated_at) as tanggal_dokumen'),
                DB::raw('tweb_penduduk.nama as nama_dokumen'),
                DB::raw("CONCAT('4-', ref_syarat_surat.ref_syarat_id) as jenis"),
                DB::raw('ref_syarat_surat.ref_syarat_nama as nama_jenis'),
                DB::raw('dokumen_penduduk.lokasi_arsip'),
                DB::raw("CONCAT('penduduk/dokumen/', dokumen_penduduk.id_pend) as modul_asli"),
                DB::raw('EXTRACT(YEAR FROM dokumen_penduduk.updated_at) as tahun'),
                DB::raw("'kependudukan' as kategori"),
                DB::raw('NULL as lampiran'),
            ])
            ->join('tweb_penduduk', 'dokumen_penduduk.id_pend', '=', 'tweb_penduduk.id')
            ->leftJoin('ref_syarat_surat', 'dokumen_penduduk.id_syarat', '=', 'ref_syarat_surat.ref_syarat_id')
            ->whereNotNull('dokumen_penduduk.id_pend')
            ->whereNotNull('dokumen_penduduk.file');
    }

    public static function listDokumen($idPenduduk)
    {
        $data    = self::where('id_pend', $idPenduduk)->where('deleted', 0)->get()->toArray();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']     = $i + 1;
            $data[$i]['hidden'] = false;

            // jika dokumen berelasi dengan dokumen kepala kk
            if (isset($data[$i]['id_parent'])) {
                $data[$i]['hidden'] = true;
            }
        }

        return $data;
    }

    /**
     * Get all of the children for the Dokumen
     */
    public function children(): HasMany
    {
        return $this->hasMany(DokumenPenduduk::class, 'parent_uuid', 'uuid');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @return BelongsTo
     */
    public function jenisDokumen()
    {
        return $this->belongsTo(SyaratSurat::class, 'id_syarat');
    }

    public static function validasi(array $post): array
    {
        $ci                           = &get_instance();
        $data                         = [];
        $data['nama']                 = nomor_surat_keputusan($post['nama']);
        $data['kategori']             = (int) $post['kategori'] ?: 1;
        $data['kategori_info_publik'] = (int) $post['kategori_info_publik'] ?: null;
        $data['id_syarat']            = (int) $post['id_syarat'] ?: null;
        $data['id_pend']              = (int) $post['id_pend'] ?: null;
        $data['tipe']                 = (int) $post['tipe'];
        $data['url']                  = $ci->security->xss_clean($post['url']) ?: null;
        $data['anggota_kk']           = (array) $post['anggota_kk'] ?? [];
        $data['dok_warga']            = (int) $post['dok_warga'] ?? 0;

        if ($data['tipe'] == 1) {
            $data['url'] = null;
        }

        switch ($data['kategori']) {
            case 1: //Informsi Publik
                $data['tahun'] = $post['tahun'];
                break;

            case 2: //SK Kades
                $data['tahun']                 = date('Y', strtotime((string) $post['attr']['tgl_kep_kades']));
                $data['kategori_info_publik']  = '3';
                $data['attr']['tgl_kep_kades'] = $post['attr']['tgl_kep_kades'];
                $data['attr']['uraian']        = $ci->security->xss_clean($post['attr']['uraian']);
                $data['attr']['no_kep_kades']  = nomor_surat_keputusan($post['attr']['no_kep_kades']);
                $data['attr']['no_lapor']      = nomor_surat_keputusan($post['attr']['no_lapor']);
                $data['attr']['tgl_lapor']     = $post['attr']['tgl_lapor'];
                $data['attr']['keterangan']    = $ci->security->xss_clean($post['attr']['keterangan']);
                break;

            case 3: //Perdes
                $data['tahun']                     = date('Y', strtotime((string) $post['attr']['tgl_ditetapkan']));
                $data['kategori_info_publik']      = '3';
                $data['attr']['tgl_ditetapkan']    = $post['attr']['tgl_ditetapkan'];
                $data['attr']['tgl_lapor']         = $post['attr']['tgl_lapor'];
                $data['attr']['tgl_kesepakatan']   = $post['attr']['tgl_kesepakatan'];
                $data['attr']['uraian']            = $ci->security->xss_clean($post['attr']['uraian']);
                $data['attr']['jenis_peraturan']   = htmlentities((string) $post['attr']['jenis_peraturan']);
                $data['attr']['no_ditetapkan']     = nomor_surat_keputusan($post['attr']['no_ditetapkan']);
                $data['attr']['no_lapor']          = nomor_surat_keputusan($post['attr']['no_lapor']);
                $data['attr']['no_lembaran_desa']  = nomor_surat_keputusan($post['attr']['no_lembaran_desa']);
                $data['attr']['no_berita_desa']    = nomor_surat_keputusan($post['attr']['no_berita_desa']);
                $data['attr']['tgl_lembaran_desa'] = $post['attr']['tgl_lembaran_desa'];
                $data['attr']['tgl_berita_desa']   = $post['attr']['tgl_berita_desa'];
                $data['attr']['keterangan']        = htmlentities((string) $post['attr']['keterangan']);
                break;

            default:
                $data['tahun'] = date('Y');
                break;
        }

        return $data;
    }

    public static function boot(): void
    {
        parent::boot();

        static::updating(static function ($model): void {
            if ($model->parent_uuid != null) {
                return;
            }
            static::deleteFile($model, 'file');
        });

        static::deleting(static function ($model): void {
            if ($model->parent_uuid == null) {
                static::deleteFile($model, 'file', true);
            }
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $logo = LOKASI_DOKUMEN . $model->getOriginal($file);
            if (file_exists($logo)) {
                unlink($logo);
            }
        }
    }
}
