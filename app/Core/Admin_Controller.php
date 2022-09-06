<?php

namespace App\Core;

use App\Models\Pesan;
use App\Models\Pamong;
use App\Models\LogSurat;
use Illuminate\Support\Str;

class Admin_Controller extends Premium
{
    public $grup;
    public $CI;
    public $pengumuman;

    public function __construct()
    {
        parent::__construct();
        $this->validasi();
        $this->CI = CI_Controller::get_instance();
        $this->load->model(['user_model', 'notif_model', 'referensi_model']);

        // Kalau sehabis periksa data, paksa harus login lagi
        if ($this->session->periksa_data == 1) {
            $this->user_model->logout();
        }

        $this->grup = $this->user_model->sesi_grup($_SESSION['sesi']);
        $this->load->model('modul_model');

        // TODO: Fix compablity legacy route
        if (Str::contains(request()->getUri(), ['bumindes_umum', 'dokumen_sekretariat', 'ekspedisi', 'lembaran_desa', 'pengurus', 'surat_keluar', 'surat_masuk'])) {
            $this->controller = request()->segment(2);
        }

        if (! $this->modul_model->modul_aktif($this->controller)) {
            session_error('Fitur ini tidak aktif');
            ci_redirect($_SERVER['HTTP_REFERER']);
        }

        if (! $this->user_model->hak_akses($this->grup, $this->controller, 'b')) {
            if (empty($this->grup)) {
                $_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
                ci_redirect('siteman');
            } else {
                session_error('Anda tidak mempunyai akses pada fitur itu');
                unset($_SESSION['request_uri']);
                ci_redirect('main');
            }
        }
        $cek_kotak_pesan                        = $this->db->table_exists('pesan') && $this->db->table_exists('pesan_detail');
        $this->header['notif_permohonan_surat'] = $this->notif_model->permohonan_surat_baru();
        $this->header['notif_inbox']            = $this->notif_model->inbox_baru();
        $this->header['notif_komentar']         = $this->notif_model->komentar_baru();
        $this->header['notif_langganan']        = $this->notif_model->status_langganan();
        $this->header['notif_pesan_opendk']     = $cek_kotak_pesan ? Pesan::where('sudah_dibaca', '=', 0)->where('diarsipkan', '=', 0)->count() : 0;
        $this->header['notif_pengumuman']       = $this->cek_pengumuman();
        $isAdmin                                = $this->session->isAdmin->pamong;
        $this->header['notif_permohonan']       = 0;
        if ($this->db->field_exists('verifikasi_operator', 'log_surat')) {
            $this->header['notif_permohonan'] = LogSurat::when($isAdmin->jabatan_id == '1', static function ($q) {
                return $q->when(setting('tte') == 1, static function ($tte) {
                    return $tte->where('verifikasi_kades', '=', 0)->orWhere('tte', '=', 0);
                });
            })
                ->when($isAdmin->jabatan_id == '2', static function ($q) {
                    return $q->where('verifikasi_sekdes', '=', '0');
                })
                ->when($isAdmin == null || ! in_array($isAdmin->jabatan_id, ['1', '2']), static function ($q) {
                    return $q->where('verifikasi_operator', '=', '0')->orWhere('verifikasi_operator', '=', '-1');
                })
                ->count();
        }

        view()->share([
            'ci'           => get_instance(),
            'auth'         => $this->session->isAdmin,
            'controller'   => $this->controller,
            'desa'         => $this->header['desa'],
            'list_setting' => $this->list_setting,
            'modul'        => $this->header['modul'],
            'modul_ini'    => $this->modul_ini,
            'notif'        => [
                'surat'           => $this->header['notif_permohonan_surat'],
                'opendkpesan'     => $this->header['notif_pesan_opendk'],
                'inbox'           => $this->header['notif_inbox'],
                'komentar'        => $this->header['notif_komentar'],
                'langganan'       => $this->header['notif_langganan'],
                'pengumuman'      => $this->header['notif_pengumuman'],
                'permohonansurat' => $this->header['notif_permohonan'],
            ],
            'kategori'      => $this->header['kategori'],
            'sub_modul_ini' => $this->sub_modul_ini,
            'session'       => $this->session,
            'setting'       => $this->setting,
            'token'         => $this->security->get_csrf_token_name(),
        ]);
    }

    private function cek_pengumuman()
    {
        if (config_item('demo_mode') || ENVIRONMENT === 'development') {
            return null;
        }

        // Hanya untuk user administrator
        if ($this->grup == 1) {
            $notifikasi = $this->notif_model->get_semua_notif();

            foreach ($notifikasi as $notif) {
                $pengumuman = $this->notif_model->notifikasi($notif);
                if ($notif['jenis'] == 'persetujuan') {
                    break;
                }
            }
        }

        return $pengumuman;
    }

    // Untuk kasus di mana method controller berbeda hak_akses. Misalnya 'setting_qrcode' readonly, tetapi 'setting/analisis' boleh ubah
    protected function redirect_hak_akses_url($akses, $redirect = '', $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }
        if (! $this->user_model->hak_akses_url($this->grup, $controller, $akses)) {
            session_error('Anda tidak mempunyai akses pada fitur ini');
            if (empty($this->grup)) {
                ci_redirect('siteman');
            }
            empty($redirect) ? ci_redirect($_SERVER['HTTP_REFERER']) : ci_redirect($redirect);
        }
    }

    protected function redirect_hak_akses($akses, $redirect = '', $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }
        if (! $this->user_model->hak_akses($this->grup, $controller, $akses)) {
            session_error('Anda tidak mempunyai akses pada fitur ini');
            if (empty($this->grup)) {
                ci_redirect('siteman');
            }
            empty($redirect) ? ci_redirect($_SERVER['HTTP_REFERER']) : ci_redirect($redirect);
        }
    }

    // Untuk kasus di mana method controller berbeda hak_akses. Misalnya 'setting_qrcode' readonly, tetapi 'setting/analisis' boleh ubah
    public function cek_hak_akses_url($akses, $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }

        return $this->user_model->hak_akses_url($this->grup, $controller, $akses);
    }

    public function cek_hak_akses($akses, $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }

        return $this->user_model->hak_akses($this->grup, $controller, $akses);
    }

    public function redirect_tidak_valid($valid)
    {
        if ($valid) {
            return;
        }

        session_error('Aksi ini tidak diperbolehkan');
        ci_redirect($_SERVER['HTTP_REFERER']);
    }

    public function render($view, ?array $data = null)
    {
        $this->load->view('header', $this->header);
        $this->load->view('nav');
        $this->load->view($view, $data);
        $this->load->view('footer');
    }

    public function modal_penandatangan()
    {
        $this->load->model('pamong_model');

        return [
            'pamong'         => $this->pamong_model->list_data(),
            'pamong_ttd'     => Pamong::kepalaDesa()->first(),
            'pamong_ketahui' => Pamong::ttd('a.n')->first(),
        ];
    }
}