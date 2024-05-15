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

defined('BASEPATH') || exit('No direct script access allowed');

define('EKSTENSI_WAJIB', serialize([
    'curl',
    'fileinfo',
    'gd',
    'iconv',
    'json',
    'mbstring',
    'mysqli',
    'mysqlnd',
    'tidy',
    'zip',
    'exif',
]));
define('minPhpVersion', '7.4.0');
define('maxPhpVersion', '8.2.0');
define('minMySqlVersion', '5.6.0');
define('maxMySqlVersion', '8.0.0');
define('minMariaDBVersion', '10.3.0');

class Setting_model extends MY_Model
{
    public function init(): void
    {
        $CI = &get_instance();

        if ($this->setting) {
            return;
        }

        $CI->list_setting = SettingAplikasi::orderBy('key')->get();
        $CI->setting      = (object) $CI->list_setting->pluck('value', 'key')
            ->map(static fn ($value, $key) => SebutanDesa($value))
            ->toArray();

        date_default_timezone_set(setting('timezone'));

        $this->load->model('database_model');
        $this->database_model->cek_migrasi();
    }

    public function update_setting($data)
    {
        $hasil = true;
        $this->load->model('theme_model');

        // TODO : Jika sudah dipisahkan, buat agar upload gambar dinamis/bisa menyesuaikan dengan kebutuhan tema (u/ Modul Pengaturan Tema)
        if ($data['latar_website'] != '') {
            $hasil = $hasil && $this->upload_img('latar_website', $this->theme_model->lokasi_latar_website(str_replace('desa/', '', setting('web_theme'))), setting('latar_website'));
        }

        if ($data['latar_login'] != '') {
            $hasil = $hasil && $this->upload_img('latar_login', LATAR_LOGIN, setting('latar_login'));
        }

        if ($data['latar_login_mandiri'] != '') {
            $hasil = $hasil && $this->upload_img('latar_login_mandiri', LATAR_LOGIN, setting('latar_login_mandiri'));
        }

        if (setting('latar_website')) {
            $data['latar_website'] = setting('latar_website');
        }

        if (setting('latar_login')) {
            $data['latar_login'] = setting('latar_login');
        }

        if (setting('latar_login_mandiri')) {
            $data['latar_login_mandiri'] = setting('latar_login_mandiri');
        }

        foreach ($data as $key => $value) {
            // Update setting yang diubah
            if ($this->setting->{$key} != $value) {
                if (in_array($key, ['current_version', 'warna_tema', 'lock_theme'])) {
                    continue;
                }

                $value = is_array($value) ? $value : strip_tags($value);
                // update password jika terisi saja
                if ($key == 'email_smtp_pass' && $value === '') {
                    continue;
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

                if (is_array($post = $this->input->post($key))) {
                    if (in_array('-', $post)) {
                        unset($post[0]);
                    }
                    $value = json_encode($post, JSON_THROW_ON_ERROR);
                }

                $hasil                 = $hasil && $this->update($key, $value);
                $this->setting->{$key} = $value;
                if ($key == 'enable_track') {
                    $hasil = $hasil && $this->notifikasi_tracker();
                }
            }
        }
        // model seperti diatas tidak bisa otomatis invalidated cache, jadi harus dihapus manual
        (new SettingAplikasi())->flushQueryCache();

        return $hasil;
    }

    public function upload_img($key = '', $lokasi = '', $latar_old = '')
    {
        $this->load->library('MY_Upload', null, 'upload');

        $config['upload_path']   = $lokasi;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = time() . $key . '.jpg';
        $data['value']           = $config['file_name'];

        $this->upload->initialize($config);

        if ($this->upload->do_upload($key)) {
            $this->upload->data();

            if ($latar_old) {
                unlink($lokasi . $latar_old); // hapus file yang sebelumya
            }

            if ($key . '.jpg' !== '') {
                unlink($lokasi . $key . '.jpg'); // hapus file yang sebelumya
            }

            (SettingAplikasi::where('key', $key)->first())->update($data); // simpan ke database

            return $lokasi . $config['file_name']; // simpan ke path
        }

        set_session('flash_error_msg', $this->upload->display_errors(null, null));

        return false;
    }

    private function notifikasi_tracker(): bool
    {
        if (setting('enable_track') == 0) {
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
        $this->config_id()->where('kode', 'tracking_off')->update('notifikasi', $notif);

        return true;
    }

    public function update($key = 'enable_track', $value = 1)
    {
        if ($key == 'latar_kehadiran') {
            $value = $this->upload_img('latar_kehadiran', LATAR_LOGIN, null);
        }

        if ($key == 'tte' && $value == 1) {
            SettingAplikasi::where('key', 'verifikasi_kades')->update(['value' => 1]); // jika tte aktif, aktifkan juga verifikasi kades
        }

        $outp = SettingAplikasi::where('key', $key)->update(['value' => $value]);

        // Hapus Cache
        // $this->cache->hapus_cache_untuk_semua('status_langganan');
        // cache()->flush();
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        status_sukses($outp);

        return true;
    }

    public function aktifkan_tracking(): void
    {
        // ini bisa otomatis invalidate cache
        (SettingAplikasi::where('key', 'enable_track')->first())->update(['value' => 1]);
        status_sukses(1);
    }

    public function update_slider(): void
    {
        $_SESSION['success']                 = 1;
        SettingAplikasi::where('key', 'sumber_gambar_slider')->update(['value' => $this->input->post('pilihan_sumber')]);
        SettingAplikasi::where('key', 'jumlah_gambar_slider')->update(['value' => $this->input->post('jumlah_gambar_slider')]);
        (new SettingAplikasi())->flushQueryCache();
        $outp = 1;
        if (! $outp) {
            $_SESSION['success'] = -1;
        }
    }

    /*
        Input post:
        - jenis_server dan server_mana menentukan setting penggunaan_server
        - offline_mode dan offline_mode_saja menentukan setting offline_mode
    */
    public function update_penggunaan_server(): void
    {
        $_SESSION['success']         = 1;
        $mode                        = $this->input->post('offline_mode_saja');
        $penggunaan_server                = $this->input->post('server_mana') ?: $this->input->post('jenis_server');
        (SettingAplikasi::where('key', 'offline_mode')->first())->update(['value' => ($mode === '0' || $mode) ? $mode : $this->input->post('offline_mode')]);
        (SettingAplikasi::where('key', 'penggunaan_server')->first())->update(['value' => $penggunaan_server]);
    }
}
