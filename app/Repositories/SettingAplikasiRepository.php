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

namespace App\Repositories;

use App\Models\Notifikasi;
use App\Models\SettingAplikasi;
use App\Models\Theme;
use App\Traits\Upload;

class SettingAplikasiRepository
{
    use Upload;
    protected $setting;

    public function __construct()
    {
        $this->setting = new SettingAplikasi();
    }

    /**
     * Mengambil semua data pengaturan.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return $this->setting->all();
    }

    /**
     * Mengambil data pengaturan berdasarkan kategori.
     *
     * @param string $kategori
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByKategori($kategori)
    {
        return $this->setting->where('kategori', $kategori)->get();
    }

    /**
     * Mengambil pengaturan pertama berdasarkan key.
     *
     * @param string $key
     *
     * @return SettingAplikasi|null
     */
    public function firstByKey($key)
    {
        return $this->setting->where('key', $key)->first();
    }

    /**
     * Memperbarui pengaturan berdasarkan key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function updateWithKey($key, $value)
    {
        return $this->setting->where('key', $key)->update(['value' => $value]) > 0;
    }

    /**
     * Membersihkan cache query.
     *
     * @return void
     */
    public function flushCache()
    {
        $this->setting->flushQueryCache();
    }

    public function updateSetting($data)
    {
        $hasil = true;            

        foreach ($data as $key => $value) {
            // Update setting yang diubah
            if (setting($key) != $value) {
                if (in_array($key, ['current_version', 'warna_tema', 'lock_theme'])) {
                    continue;
                }

                $value = is_array($value) ? $value : strip_tags($value);
                // update password jika terisi saja
                if ($key == 'email_smtp_pass' && $value === '') {
                    continue;
                }

                if ($key == 'tampilkan_pendaftaran' && $value == 1) {
                    if (setting('email_notifikasi') == 0 || setting('telegram_notifikasi') == 0) {
                        $value = 0;
                        $hasil = false;
                        set_session('flash_error_msg', 'Untuk menampilkan pendaftaran, notifikasi harus mengaktifkan pengaturan notifikasi email dan telegram');
                    }
                }

                if ($key == 'ip_adress_kehadiran' || $key == 'mac_adress_kehadiran') {
                    $value = trim($value);
                }

                if ($key == 'id_pengunjung_kehadiran') {
                    $value = alfanumerik(trim($value));
                }

                // update password jika terisi saja
                if ($key == 'api_opendk_password' && $value === '') {
                    continue;
                }

                if ($key == 'api_opendk_key' && (empty(setting('api_opendk_server')) || empty(setting('api_opendk_user')) || empty(setting('api_opendk_password')))) {
                    $value = null;
                }

                if (is_array($post = request()->get($key))) {
                    if (in_array('-', $post)) {
                        unset($post[0]);
                    }
                    $value = json_encode($post, JSON_THROW_ON_ERROR);
                }

                $hasil                 = $hasil && $this->updateWithKey($key, $value);
                if ($key == 'tte' && $value == 1) {
                    $this->updateWithKey('verifikasi_kades', $value); // jika tte aktif, aktifkan juga verifikasi kades
                }
                // $this->setting->{$key} = $value;
                if ($key == 'enable_track') {
                    $hasil = $hasil && $this->notifikasiTracker($value);
                }
            }
        }
        // model seperti diatas tidak bisa otomatis invalidated cache, jadi harus dihapus manual
        $this->flushCache();        

        return $hasil;
    }

    private function notifikasiTracker($value): bool
    {
        if ($value == 0) {
            // Notifikasi tracker dimatikan
            $notif = [
                'updated_at'     => date('Y-m-d H:i:s'),
                'tgl_berikutnya' => date('Y-m-d H:i:s'),
                'aktif'          => 1,
            ];
        } else {
            // Matikan notifikasi tracker yg sdh aktif
            $notif = [
                'updated_at' => date('Y-m-d H:i:s'),
                'aktif'      => 0,
            ];
        }
        Notifikasi::where('kode', 'tracking_off')->update($notif);
        return true;
    }    
}