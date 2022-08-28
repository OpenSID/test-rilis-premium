<?php

namespace App\Core;

use App\Core\MY_Controller;

class Web_Controller extends MY_Controller
{
    // Constructor
    public function __construct()
    {
        parent::__construct();
        if ($this->setting->offline_mode == 2) {
            $this->view_maintenance();
        } elseif ($this->setting->offline_mode == 1) {
            $this->load->model('user_model');
            $grup = $this->user_model->sesi_grup($this->session->sesi);
            if (! $this->user_model->hak_akses($grup, 'web', 'b')) {
                $this->view_maintenance();
            }
        }

        $this->load->model('theme_model');
        $this->theme        = $this->theme_model->tema;
        $this->theme_folder = $this->theme_model->folder;

        // Variabel untuk tema
        $this->set_template();
        $this->includes['folder_themes'] = "../../{$this->theme_folder}/{$this->theme}";

        $this->load->model('web_menu_model');
    }

    /**
     * set_template function
     *
     * @param string $template_file
     *
     * @return void
     */
    public function set_template($template_file = 'template')
    {
        $this->template = "../../{$this->theme_folder}/{$this->theme}/{$template_file}";
    }

    public function _get_common_data(&$data)
    {
        $this->load->library('statistik_pengunjung');

        $this->load->model('first_menu_m');
        $this->load->model('teks_berjalan_model');
        $this->load->model('first_artikel_m');
        $this->load->model('web_widget_model');
        $this->load->model('anjungan_model');
        $this->load->model('keuangan_grafik_manual_model');
        $this->load->model('keuangan_grafik_model');
        $this->load->model('pengaduan_model');

        // Counter statistik pengunjung
        $this->statistik_pengunjung->counter_visitor();

        // Data statistik pengunjung
        $data['statistik_pengunjung'] = $this->statistik_pengunjung->get_statistik();

        $data['latar_website'] = $this->theme_model->latar_website();
        $data['desa']          = $this->header;
        $data['menu_atas']     = $this->first_menu_m->list_menu_atas();
        $data['menu_kiri']     = $this->first_menu_m->list_menu_kiri();
        $data['teks_berjalan'] = $this->teks_berjalan_model->list_data(true);
        $data['slide_artikel'] = $this->first_artikel_m->slide_show();
        $data['slider_gambar'] = $this->first_artikel_m->slider_gambar();
        $data['w_cos']         = $this->web_widget_model->get_widget_aktif();
        $data['cek_anjungan']  = $this->anjungan_model->cek_anjungan();

        $this->web_widget_model->get_widget_data($data);
        $data['data_config'] = $this->header;
        if ($this->setting->apbdes_footer && $this->setting->apbdes_footer_all) {
            $data['transparansi'] = $this->setting->apbdes_manual_input
                ? $this->keuangan_grafik_manual_model->grafik_keuangan_tema()
                : $this->keuangan_grafik_model->grafik_keuangan_tema();
        }
        // Pembersihan tidak dilakukan global, karena artikel yang dibuat oleh
        // petugas terpecaya diperbolehkan menampilkan <iframe> dsbnya..
        $list_kolom = [
            'arsip',
            'w_cos',
        ];

        foreach ($list_kolom as $kolom) {
            $data[$kolom] = $this->security->xss_clean($data[$kolom]);
        }
    }

    private function view_maintenance()
    {
        $this->load->model('pamong_model');

        $main         = $this->header;
        $pamong_kades = $this->pamong_model->get_ttd();

        // TODO : Gunakan view blade
        if (file_exists(DESAPATH . 'offline_mode.php')) {
            include DESAPATH . 'offline_mode.php';
        } else {
            include VIEWPATH . 'offline_mode.php';
        }

        exit();
    }
}