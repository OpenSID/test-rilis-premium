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

use App\Services\LaporanPenduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Utama extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('first_artikel_m');
        $this->load->model('track_model');
        $this->load->model('keuangan_grafik_model');
        $this->load->model('keuangan_grafik_manual_model');
    }

    public function index($p = 1)
    {
        $data = $this->includes;

        // TODO : ubah menjadi ORM Laravel jika sudah ada
        $data['p']            = $p;
        $data['paging']       = $this->first_artikel_m->paging($p);
        $data['paging_page']  = 'index';
        $data['paging_range'] = 3;
        $data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
        $data['end_paging']   = min($data['paging']->end_link, $p + $data['paging_range']);
        $data['pages']        = range($data['start_paging'], $data['end_paging']);
        $data['artikel']      = $this->first_artikel_m->artikel_show($data['paging']->offset, $data['paging']->per_page);
        
        $data['headline'] = $this->first_artikel_m->get_headline();
        $data['cari']     = $this->input->get('cari', true);
        if ($this->setting->covid_rss) {
            $data['feed'] = [
                'items' => $this->first_artikel_m->get_feed(),
                'title' => 'BERITA COVID19.GO.ID',
                'url'   => 'https://www.covid19.go.id',
            ];
        }

        // TODO: OpenKAB - Sesuaikan jika Modul Admin sudah disesuaikan
        if ($this->setting->apbdes_footer) {
            $data['transparansi'] = $this->setting->apbdes_manual_input
                ? $this->keuangan_grafik_manual_model->grafik_keuangan_tema()
                : $this->keuangan_grafik_model->grafik_keuangan_tema();
        }

        $data['covid'] = (new LaporanPenduduk())->listData('covid');

        $cari = trim($this->input->get('cari', true));
        if ($cari !== '') {
            // Judul artikel bisa digunakan untuk serangan XSS
            $data['judul_kategori'] = 'Hasil pencarian : ' . substr(e($cari), 0, 50);
        }

        $this->_get_common_data($data);
        $this->track_model->track_desa('first');
        // dd($data);

        return view('template', $data);
    }
}
