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

namespace App\Models;

use App\Enums\StatusEnum;
use App\Models\Galery as Galeri;
use App\Traits\ConfigId;
use Rennokki\QueryCache\Traits\QueryCacheable;

defined('BASEPATH') || exit('No direct script access allowed');

class SettingAplikasi extends BaseModel
{
    use ConfigId;
    use QueryCacheable;

    public const WARNA_TEMA              = '#eab308';
    public const RENTANG_WAKTU_KEHADIRAN = 10;

    /**
     * Invalidate the cache automatically
     * upon update in the database.
     *
     * @var bool
     */
    protected static $flushCacheOnUpdate = true;

    // forever cache
    public $cacheFor = -1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_aplikasi';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The fillable with the model.
     *
     * @var array
     */
    protected $fillable = [
        'config_id',
        'key',
        'value',
    ];

    /**
     * The hidden with the model.
     *
     * @var array
     */
    protected $hidden = [
        'config_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'option' => 'json',
    ];

    // public function getValueAttribute()
    // {
    //     if ($this->attributes['key'] == 'web_theme') {
    //         return config_item('web_theme');
    //     }

    //     return $this->attributes['value'];
    // }

    public function getOptionAttribute()
    {
        if ($this->attributes['jenis'] == 'option' && $this->attributes['key'] == 'tampilan_anjungan_slider') {
            return Galeri::whereParrent(Galeri::PARRENT)->whereEnabled(StatusEnum::YA)->pluck('nama', 'id');
        }
        if ($this->attributes['jenis'] == 'boolean') {
            return [
                1 => 'Ya',
                0 => 'Tidak',
            ];
        }

        return json_decode($this->attributes['option'], true);
    }

    public function getValueAttribute()
    {
        if ($this->attributes['jenis'] == 'select-simbol') {
            return base_url(LOKASI_SIMBOL_LOKASI . $this->attributes['value']);
        } elseif ($this->attributes['key'] == 'sebutan_kepala_desa') {
            return kades()->nama;
        } elseif ($this->attributes['key'] == 'sebutan_sekretaris_desa') {
            return sekdes()->nama;
        } elseif ($this->attributes['key'] == 'multi_desa') {
            return Config::count() > 1;
        } elseif ($this->attributes['key'] == 'surat_margin_cm_to_mm') {
            $margins = json_decode(setting('surat_margin'), true);
            return [
                $margins['kiri'] * 10,
                $margins['atas'] * 10,
                $margins['kanan'] * 10,
                $margins['bawah'] * 10,
            ]; 
        } elseif (in_array($this->attributes['key'], ['mapbox_key', 'google_api_key', 'google_recaptcha_site_key', 'google_recaptcha_secret_key', 'google_recaptcha']) && empty($this->attributes['value'])) {
            return config_item($this->attributes['key']);
        } else if (($this->attributes['key'] == 'layanan_opendesa_token') && ((ENVIRONMENT == 'development') || config_item('token_layanan'))) {
            return config_item('token_layanan');
        }

        return $this->attributes['value'];
    }
}
