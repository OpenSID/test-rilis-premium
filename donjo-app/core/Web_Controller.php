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

use App\Models\Menu;
use App\Models\Widget;
use App\Libraries\Keuangan;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Web_Controller extends MY_Controller
{
    public $CI;
    public $cek_anjungan;    

    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();
        $CI           = &get_instance();
        $this->header = identitas();
        $this->load->helper('theme');

        $theme = theme_active();

        // set view path theme active
        app('view')->addLocation($theme->path . '/resources/views');

        if (setting('offline_mode') == 2 || (setting('offline_mode') == 1 && can('b', 'web'))) {
            $this->maintenance();

            exit;
        }

        $this->load->model('web_menu_model');

        $this->viewShare();
    }

    public function viewShare(): void
    {
        $models = [
            'statistik_pengunjung_model',
            'first_menu_m',
            'teks_berjalan_model',
            'first_artikel_m',
            'web_widget_model',
            'keuangan_grafik_manual_model',
            'keuangan_grafik_model',
            'pengaduan_model',
        ];
        array_walk($models, fn($model) => $this->load->model($model));

        $this->statistik_pengunjung_model->counter_visitor();
        $statistik_pengunjung = $this->statistik_pengunjung_model->get_statistik();

        $widgetAktif = Widget::status()
            ->when(setting('layanan_mandiri') == '0', fn($query) => $query->where('isi', '!=', 'layanan_mandiri.php')->orWhere('isi', '!=', 'layanan_mandiri.blade.php'))
            ->orderBy('urut')
            ->get()
            ->map(function ($item) {
                $item->judul = SebutanDesa($item->judul);
                if ($item->jenis_widget == 3) {
                    $item->isi = bersihkan_xss($item->isi);
                }
                $item->isi = strpos($item->isi, '.blade.php') === false ? str_replace(['.php', '.blade.php'], '', $item->isi) . '' : $item->isi;

                return $item;
            });

        $sharedData = [
            'statistik_pengunjung' => $statistik_pengunjung,
            'latar_website'        => default_file($this->theme_model->lokasi_latar_website() . setting('latar_website'), DEFAULT_LATAR_WEBSITE),
            'menu_kiri'            => $this->first_menu_m->list_menu_kiri(),
            'teks_berjalan'        => $this->db->field_exists('tipe', 'teks_berjalan') ? $this->teks_berjalan_model->list_data(true) : null,
            'slide_artikel'        => $this->first_artikel_m->slide_show(),
            'slider_gambar'        => $this->first_artikel_m->slider_gambar(),
            'cek_anjungan'         => $this->cek_anjungan,
            'widgetAktif'          => $widgetAktif,            
        ];

        if (setting('apbdes_footer') && setting('apbdes_footer_all')) {
            $sharedData['transparansi'] = (new Keuangan())->grafik_keuangan_tema();
        }

        foreach (['arsip'] as $kolom) {
            if (isset($sharedData[$kolom])) {
                $sharedData[$kolom] = $this->security->xss_clean($sharedData[$kolom]);
            }
        }

        // dd($sharedData);
        $widgetData = $this->web_widget_model->get_widget_data();
        View::share(array_merge($sharedData, $widgetData));
    }

    private function maintenance()
    {
        return view('partials.maintenance.index');
    }

    public function menuAktif($link)
    {
        return Menu::active()->whereLink($link)->exists();
    }

    protected function hak_akses_menu($link)
    {
        $menuAktif = $this->menuAktif($link);
        if (! $menuAktif) {
            view('menu_not_active');

            exit;
        }
    }
}
