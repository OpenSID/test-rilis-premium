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

defined('BASEPATH') || exit('No direct script access allowed');

class PendudukSaja extends Penduduk
{
   protected $appends = [];
   protected $with    = [];
   
   /** Tidak boleh menghapus data penduduk jika:
     * dalam demo_mode, atau
     * status penduduk sudah lengkap
     * tidak ada lagi data tweb_penduduk contoh awal (created_by = -1)
     */
    public static function bolehHapusPenduduk()
    {
        $data_awal = self::where('created_by', '<=', 0)->count();            
        if (config_item('demo_mode') || $data_awal == 0) {
            return false;
        }

        return ! setting('tgl_data_lengkap_aktif');
    }

    public static function cekTagIdCard($cek = null, $kecuali = null)
    {        
        $tagIdCard = self::select('tag_id_card')->when($kecuali, static fn($q) => $q->where('id', '!=', $kecuali))->whereNotNull('tag_id_card')->pluck('tag_id_card','tag_id_card')->toArray();
        return in_array($cek, $tagIdCard);
    }
}
