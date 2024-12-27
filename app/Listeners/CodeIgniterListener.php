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

namespace App\Listeners;

use App\Events\CodeIgniterEvent;
use App\Libraries\TinyMCE;
use App\Models\Config;
use App\Models\SettingAplikasi;
use App\Providers\ViewServiceProvider;
use Illuminate\Container\Container;

class CodeIgniterListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(CodeIgniterEvent $event): void
    {
        $ci = &$event->ci->get_instance();

        $container = Container::getInstance();

        $container->singleton('ci', static fn () => $ci);
        $this->applySettingCI($ci);
        // Set config setelah instance ci
        $container['config']->set('mail.default', $ci?->setting?->email_protocol);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.transport", $ci?->setting?->email_protocol);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.url", $ci?->setting?->email_smtp_url);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.host", $ci?->setting?->email_smtp_host);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.port", $ci?->setting?->email_smtp_port);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.encryption", $ci?->setting?->email_smtp_encryption ?? 'tls');
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.username", $ci?->setting?->email_smtp_user);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.password", $ci?->setting?->email_smtp_pass);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.timeout", $ci?->setting?->email_smtp_timeout);
        $container['config']->set("mail.mailers.{$ci?->setting?->email_protocol}.local_domain", $ci?->setting?->email_smtp_domain);

        $container['config']->set([
            'captcha' => [
                'secret'  => $ci?->setting?->google_recaptcha_secret_key,
                'sitekey' => $ci?->setting?->google_recaptcha_site_key,
                'options' => [],
            ],
            'services' => [
                'telegram-bot-api' => [
                    'token' => $ci?->setting?->telegram_token,
                ],
            ],
        ]);

        $container->register(ViewServiceProvider::class);
    }

    private function applySettingCI($ci): void
    {
        if ($ci->setting) {
            return;
        }
        $ci->listSetting = SettingAplikasi::orderBy('key')->get();
        $ci->setting      = (object) $ci->listSetting->pluck('value', 'key')
            ->map(static fn ($value, $key) => SebutanDesa($value))
            ->toArray();

        //  https://stackoverflow.com/questions/16765158/date-it-is-not-safe-to-rely-on-the-systems-timezone-settings
        date_default_timezone_set($ci->setting?->timezone); // ganti ke timezone lokal

        // Ambil google api key dari desa/config/config.php kalau tidak ada di database
        if (empty($ci->setting?->mapbox_key) && ! empty(config_item('mapbox_key'))) {
            $ci->setting->mapbox_key = config_item('mapbox_key');
        }

        if (empty($ci->setting?->google_api_key) && ! empty(config_item('google_api_key'))) {
            $ci->setting->google_api_key = config_item('google_api_key');
        }

        if (empty($ci->setting?->google_recaptcha_site_key) && ! empty(config_item('google_recaptcha_site_key'))) {
            $ci->setting->google_recaptcha_site_key = config_item('google_recaptcha_site_key');
        }

        if (empty($ci->setting?->google_recaptcha_secret_key) && ! empty(config_item('google_recaptcha_secret_key'))) {
            $ci->setting->google_recaptcha_secret_key = config_item('google_recaptcha_secret_key');
        }

        if (empty($ci->setting?->google_recaptcha) && ! empty(config_item('google_recaptcha'))) {
            $ci->setting->google_recaptcha = config_item('google_recaptcha');
        }

        if (empty($ci->setting?->header_surat)) {
            $ci->setting->header_surat = TinyMCE::HEADER;
        }

        if (empty($ci->setting?->footer_surat)) {
            $ci->setting->footer_surat = TinyMCE::FOOTER;
        }

        if (empty($ci->setting?->footer_surat_tte)) {
            $ci->setting->footer_surat_tte = TinyMCE::FOOTER_TTE;
        }

        // Ganti token_layanan sesuai config untuk mempermudah development
        if ((ENVIRONMENT == 'development') || config_item('token_layanan')) {
            $ci->setting->layanan_opendesa_token = config_item('token_layanan');
        }

        $ci->setting->user_admin = config_item('user_admin');

        // Kalau folder tema ubahan tidak ditemukan, ganti dengan tema default
        $pos = strpos($ci->setting?->web_theme, 'desa/');
        if ($pos !== false) {
            $folder = FCPATH . '/desa/themes/' . substr($ci->setting?->web_theme, $pos + strlen('desa/'));
            if (! file_exists($folder)) {
                $ci->setting->web_theme = 'esensi';
            }
        }

        // Sebutan kepala desa diambil dari tabel ref_jabatan dengan jenis = 1
        // Diperlukan karena masih banyak yang menggunakan variabel ini, hapus jika tidak digunakan lagi
        $ci->setting->sebutan_kepala_desa = kades()->nama;

        // Sebutan sekretaris desa diambil dari tabel ref_jabatan dengan jenis = 2
        $ci->setting->sebutan_sekretaris_desa = sekdes()->nama;

        // Setting Multi Database untuk OpenKab
        $ci->setting->multi_desa = Config::count() > 1;

        // Feeds
        if (empty($ci->setting?->link_feed)) {
            $ci->setting->link_feed = 'https://www.covid19.go.id/feed/';
        }

        if (empty($ci->setting?->anjungan_layar)) {
            $ci->setting->anjungan_layar = 1;
        }

        if (empty($ci->setting?->sebutan_anjungan_mandiri)) {
            $ci->setting->sebutan_anjungan_mandiri = SebutanDesa('Anjungan [desa] Mandiri');
        }

        // Konversi nilai margin global dari cm ke mm
        $margins                              = json_decode($ci->setting?->surat_margin, true);
        $ci->setting->surat_margin_cm_to_mm = [
            $margins['kiri'] * 10,
            $margins['atas'] * 10,
            $margins['kanan'] * 10,
            $margins['bawah'] * 10,
        ];

        // Konversi nilai margin surat dinas global dari cm ke mm
        $margins                                    = json_decode($ci->setting?->surat_dinas_margin, true);
        $ci->setting->surat_dinas_margin_cm_to_mm = [
            $margins['kiri'] * 10,
            $margins['atas'] * 10,
            $margins['kanan'] * 10,
            $margins['bawah'] * 10,
        ];                
    }
}
