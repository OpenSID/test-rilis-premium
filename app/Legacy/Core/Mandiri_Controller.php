<?php

namespace App\Legacy\Core;

use App\Models\Config;
use App\Legacy\Core\MY_Controller;
use Illuminate\Support\Facades\Schema;

class Mandiri_Controller extends MY_Controller
{
    public $is_login;

    public function __construct()
    {
        parent::__construct();
        $this->is_login = $this->session->is_login;
        $this->header   = Schema::hasColumn('tweb_desa_pamong', 'jabatan_id') ? Config::first() : null;

        if ($this->setting->layanan_mandiri == 0 && ! $this->cek_anjungan) {
            show_404();
        }

        if ($this->session->mandiri != 1) {
            if (! $this->session->login_ektp) {
                ci_redirect('layanan-mandiri/masuk');
            } else {
                ci_redirect('layanan-mandiri/masuk-ektp');
            }
        }
    }

    public function render($view, ?array $data = null)
    {
        $data['desa']         = $this->header;
        $data['cek_anjungan'] = $this->cek_anjungan;
        $data['konten']       = $view;
        $this->load->view(MANDIRI . '/template', $data);
    }
}