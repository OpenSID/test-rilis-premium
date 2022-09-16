<?php

namespace App\Legacy\Core;

use DateTime;
use Exception;
use App\Legacy\Core\MY_Controller;

class Premium extends MY_Controller
{
    // Hanya domain terdaftar yg bisa melewati validasi premium dengan mode demo
    protected $domain = [
        'beta.opendesa.id',
        'beta2.opensid.or.id',
        'berputar.opendesa.id',
    ];
    protected $versi_setara;

    /**
     * TODO :
     * Controller main exted ke CI_Controller bukan ke Admin_controller tp masih berpengaruh pada validasi pengguna premium
     * Sehingga akan error saat login di awal, namun setelah di refresh akan kembali normal
     */
    protected $kecuali = [
        'hom_sid', 'identitas_desa', 'pelanggan', 'pendaftaran_kerjasama', 'setting', 'notif', 'user_setting', 'main', 'info_sistem',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('header_model');
        $this->header = $this->header_model->get_data();
    }

    /**
     * Validasi akses.
     *
     * @return mixed
     */
    public function validasi()
    {
        // Jangan jalankan validasi akses untuk spesifik controller.
        if (in_array($this->router->class, $this->kecuali) || (config_item('demo_mode') && (in_array(get_domain(APP_URL), $this->domain)))) {
            return;
        }

        // Validasi akses
        if (! $this->validasi_akses()) {
            ci_redirect('peringatan');
        }

        $this->session->unset_userdata(['error_premium', 'error_premium_pesan']);
    }

    /**
     * Validasi akses fitur.
     *
     * @return bool
     */
    protected function validasi_akses()
    {
        $this->session->unset_userdata('error_premium');

        if (empty($this->header['desa']['kode_desa'])) {
            $this->session->set_userdata('error_premium', 'Kode desa diperlukan.');

            return false;
        }

        if (empty($token = $this->setting->layanan_opendesa_token)) {
            $this->session->set_userdata('error_premium', 'Token pelanggan kosong / tidak valid.');

            return false;
        }

        $tokenParts   = explode('.', $token);
        $tokenPayload = base64_decode($tokenParts[1], true);
        $jwtPayload   = json_decode($tokenPayload);
        $date         = new DateTime('20' . str_replace('.', '-', currentVersion()) . '-01');
        $version      = $date->format('Y-m-d');

        if (version_compare($jwtPayload->desa_id, kode_wilayah($this->header['desa']['kode_desa']), '!=')) {
            $this->session->set_userdata('error_premium', ucwords($this->setting->sebutan_desa . ' ' . $this->header['desa']['nama_desa']) . ' tidak terdaftar di ' . config_item('server_layanan'));

            $this->daftarHitam();

            return false;
        }

        $berakhir   = $jwtPayload->tanggal_berlangganan->akhir;
        $disarankan = 'v' . str_replace('-', '.', substr($berakhir, 2, 5)) . '-premium';

        if ($version > $berakhir) {
            // Versi premium setara dengan umum adalah 6 bulan setelahnya + 1 bulan untuk versi pembaharuan
            // Misalnya 21.05-premium setara dengan 21.12-umum, notifikasi tampil jika ada umum di atas 21.12-umum
            $this->versi_setara = date('Y-m-d', strtotime('+7 month', strtotime($berakhir)));
            $this->versi_setara = str_replace('-', '.', substr($this->versi_setara, 2, 5));
            $this->session->set_userdata('error_premium', 'Masa aktif berlangganan fitur premium sudah berakhir.');
            $this->session->set_userdata('error_premium_pesan', "Hanya diperbolehkan menggunakan {$disarankan} (maupun versi revisinya) atau menggunakan versi rilis {$this->versi_setara} umum.");

            return false;
        }

        if (isLocalIPAddress($_SERVER['REMOTE_ADDR'])) {
            return true;
        }

        if (get_domain($jwtPayload->domain) != get_domain(APP_URL)) {
            $this->session->set_userdata('error_premium', 'Domain ' . get_domain(APP_URL) . ' tidak terdaftar di ' . config_item('server_layanan'));

            $this->daftarHitam();

            return false;
        }

        return true;
    }

    private function daftarHitam()
    {
        $this->load->library('user_agent');
        if ($this->agent->is_browser()) {
            $browser = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $browser = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $browser = $this->agent->mobile();
        } else {
            $browser = 'Unidentified User Agent';
        }

        $os = $this->agent->platform();

        try {
            $client = new \GuzzleHttp\Client();
            $client->post(config_item('server_layanan') . '/api/v1/pelanggan/daftarhitam', [
                'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
                'form_params' => [
                    'kode_desa'  => kode_wilayah($this->header['desa']['kode_desa']),
                    'ip_address' => $this->input->ip_address(),
                    'token'      => $this->setting->layanan_opendesa_token,
                    'waktu'      => date('Y-m-d h:i:sa'),
                    'browser'    => $browser,
                    'os'         => $os,
                    'domain'     => get_domain(APP_URL),
                ],
            ])->getBody();
        } catch (Exception $e) {
            log_message('error', $e);
        }
    }
}