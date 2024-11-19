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

use App\Enums\Statistik\StatistikEnum;
use App\Libraries\Keuangan;
use App\Models\Penduduk;
use App\Models\PendudukSaja;
use App\Models\Widget;
use App\Services\LaporanPenduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class First extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        parent::clear_cluster_session();

        // $this->load->library('security/security_header', null, 'security_header');
        // $this->security_header->handle();

        // $this->load->library('security/security_trusted_host', null, 'security_trusted_host');
        // $this->security_trusted_host->handle();

        $this->load->model('first_artikel_m');
        $this->load->model('first_penduduk_m');
        $this->load->model('penduduk_model');
        $this->load->model('surat_model'); // TODO: Cek digunakan halaman apa saja
        $this->load->model('keluarga_model'); // TODO: Cek digunakan halaman apa saja
        $this->load->model('laporan_penduduk_model');
        $this->load->model('keluar_model'); // TODO: Cek digunakan halaman apa saja
        $this->load->model('keuangan_model'); // TODO: Cek digunakan halaman apa saja
        $this->load->model('web_dokumen_model');
        $this->load->model('program_bantuan_model');
        $this->load->model('plan_lokasi_model'); // TODO: Cek digunakan halaman apa saja
        $this->load->model('plan_area_model'); // TODO: Cek digunakan halaman apa saja
        $this->load->model('plan_garis_model'); // TODO: Cek digunakan halaman apa saja
        $this->load->model('analisis_import_model');
    }

    public function index($p = 1)
    {
        // $data = $this->includes;

        // $data['p']            = $p;
        // $data['paging']       = $this->first_artikel_m->paging($p);
        // $data['paging_page']  = 'index';
        // $data['paging_range'] = 3;
        // $data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
        // $data['end_paging']   = min($data['paging']->end_link, $p + $data['paging_range']);
        // $data['pages']        = range($data['start_paging'], $data['end_paging']);
        // $data['artikel']      = $this->first_artikel_m->artikel_show($data['paging']->offset, $data['paging']->per_page);

        // $data['headline'] = $this->first_artikel_m->get_headline();
        // $data['cari']     = $this->input->get('cari', true);
        // if ($this->setting->covid_rss) {
        //     $data['feed'] = [
        //         'items' => $this->first_artikel_m->get_feed(),
        //         'title' => 'BERITA COVID19.GO.ID',
        //         'url'   => 'https://www.covid19.go.id',
        //     ];
        // }

        // TODO: OpenKAB - Sesuaikan jika Modul Admin sudah disesuaikan
        if ($this->setting->apbdes_footer) {
            $data['transparansi'] = (new Keuangan())->grafik_keuangan_tema();
        }

        // $data['covid'] = $this->laporan_penduduk_model->list_data('covid');

        // $cari = trim($this->input->get('cari', true));
        // if ($cari !== '') {
        //     // Judul artikel bisa digunakan untuk serangan XSS
        //     $data['judul_kategori'] = 'Hasil pencarian : ' . substr(e($cari), 0, 50);
        // }

        // theme_view($this->template, $data);
    }

    public function unduh_dokumen_artikel($id): void
    {
        // Ambil nama berkas dari database
        $dokumen = $this->first_artikel_m->get_dokumen_artikel($id);
        ambilBerkas($dokumen, $this->controller, null, LOKASI_DOKUMEN);
    }    

    public function gallery($p = 1): void
    {
        if ($p > 1) {
            $index = '/index/' . $p;
        }

        redirect('galeri' . $index);
    }

    public function sub_gallery($parent = 0, $p = 1): void
    {
        if ($parent) {
            $index = '/' . $parent;

            if ($p > 1) {
                $index .= '/index/' . $p;
            }
        }

        redirect('galeri' . $index);
    }

    // redirect ke halaman data-statistik
    public function statistik($stat = null, $tipe = 0): void
    {
        if ($slug = StatistikEnum::slugFromKey($stat)) {
            redirect('data-statistik/' . $slug);
        }

        $cekMenu = $this->web_menu_model->menu_aktif('statistik/' . $stat);

        $data                = $this->includes;
        $selectedTahun       = $this->input->get('tahun');
        $data['heading']     = LaporanPenduduk::judulStatistik($stat);
        $data['title']       = 'Statistik ' . $data['heading'];
        $data['stat']        = $this->laporan_penduduk_model->setTahun($selectedTahun)->list_data($stat);
        $data['tipe']        = $tipe;
        $data['st']          = $stat;
        $data['slug_aktif']  = $stat;
        $data['bantuan']     = (int) $stat > 50 || in_array($stat, ['bantuan_keluarga', 'bantuan_penduduk']);
        $data['last_update'] = Penduduk::latest()->first()->updated_at;
        $data['tampil']      = $cekMenu;

        if ($data['bantuan']) {
            $data['list_tahun']         = range(date('Y'), date('Y') - 5);
            $data['selected_tahun']     = $selectedTahun;
            $data['default_chart_type'] = 'column';
        }

        $this->set_template('layouts/stat.tpl.php');
        theme_view($this->template, $data);
    }

    public function kelompok($slug = ''): void
    {
        redirect('data-kelompok/' . $slug);
    }

    public function suplemen($slug = ''): void
    {
        redirect('data-suplemen/' . $slug);
    }

    public function ajax_peserta_program_bantuan(): void
    {
        $selectedTahun = $this->input->get('tahun');
        $peserta       = $this->program_bantuan_model->setTahun($selectedTahun)->get_peserta_bantuan();
        $data          = [];
        $no            = $_POST['start'];

        foreach ($peserta as $baris) {
            $no++;
            $row    = [];
            $row[]  = $no;
            $row[]  = $baris['program'];
            $row[]  = $baris['peserta'];
            $row[]  = $baris['alamat'];
            $data[] = $row;
        }

        $output = [
            'recordsTotal'    => $this->program_bantuan_model->setTahun($selectedTahun)->count_peserta_bantuan_all(),
            'recordsFiltered' => $this->program_bantuan_model->setTahun($selectedTahun)->count_peserta_bantuan_filtered(),
            'data'            => $data,
        ];
        echo json_encode($output, JSON_THROW_ON_ERROR);
    }

    // TODO: OpenKAB - Sesuaikan jika Modul Admin sudah disesuaikan
    public function data_analisis(): void
    {
        $cekMenu = $this->web_menu_model->menu_aktif('data_analisis');

        $master = $this->input->get('master', true);

        $data                     = $this->includes;
        $data['master_indikator'] = $this->first_penduduk_m->master_indikator();
        $data['list_indikator']   = $this->first_penduduk_m->list_indikator($master);
        $data['tampil']           = $cekMenu;

        $this->set_template('layouts/analisis.tpl.php');
        theme_view($this->template, $data);
    }

    // TODO: OpenKAB - Sesuaikan jika Modul Admin sudah disesuaikan
    public function jawaban_analisis($stat = '', $sb = 0, $per = 0): void
    {
        $cekMenu = $this->web_menu_model->menu_aktif('data_analisis');

        $data               = $this->includes;
        $data['list_jawab'] = $this->first_penduduk_m->list_jawab($stat, $sb, $per);
        $data['indikator']  = $this->first_penduduk_m->get_indikator($stat);
        $data['tampil']     = $cekMenu;

        $this->set_template('layouts/analisis.tpl.php');
        theme_view($this->template, $data);
    }

    public function dpt(): void
    {
        redirect('data-dpt');
    }

    public function add_comment($id = 0): void
    {
        $this->form_validation->set_rules('komentar', 'Komentar', 'required');
        $this->form_validation->set_rules('owner', 'Nama', 'required|max_length[50]');
        $this->form_validation->set_rules('no_hp', 'No HP', 'numeric|required|max_length[15]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|max_length[50]');

        $post = $this->input->post();

        if ($this->form_validation->run() == true) {
            // Periksa isian captcha
            $captcha = new App\Libraries\Captcha();
            if (! $captcha->check($this->input->post('captcha_code'))) {
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => 'Kode anda salah. Silakan ulangi lagi.',
                    'data'   => $post,
                ];
            } else {
                $data = [
                    'komentar'   => htmlentities($post['komentar']),
                    'owner'      => htmlentities($post['owner']),
                    'no_hp'      => bilangan($post['no_hp']),
                    'email'      => email($post['email']),
                    'status'     => 2,
                    'id_artikel' => $id,
                    'config_id'  => identitas('id'),
                ];

                $res = $this->first_artikel_m->insert_comment($data);

                if ($res) {
                    $respon = [
                        'status' => 1, // Notif berhasil
                        'pesan'  => 'Komentar anda telah berhasil dikirim dan perlu dimoderasi untuk ditampilkan.',
                    ];
                } else {
                    $respon = [
                        'status' => -1, // Notif gagal
                        'pesan'  => 'Komentar anda gagal dikirim. Silakan ulangi lagi.',
                        'data'   => $post,
                    ];
                }
            }
        } else {
            $respon = [
                'status' => -1, // Notif gagal
                'pesan'  => validation_errors(),
                'data'   => $post,
            ];
        }

        $this->session->set_flashdata('notif', $respon);

        redirect($_SERVER['HTTP_REFERER'] . '#kolom-komentar');
    }

    public function load_apbdes(): void
    {
        $data['transparansi'] = (new Keuangan())->grafik_keuangan_tema();

        view('web.gis.apbdes_web', $data);
    }

    public function load_aparatur_desa(): void
    {
        $data['tampilkanJabatan'] = Widget::getSetting('aparatur_desa', 'overlay');
        view('web.gis.aparatur_desa', $data);
    }

    public function load_aparatur_wilayah($id = '', $kd_jabatan = 0): void
    {
        $data['penduduk'] = PendudukSaja::find($id);
        $kepala_dusun     = ucwords(setting('sebutan_kepala_dusun'));

        switch ($kd_jabatan) {
            case '1':
            default:
                $data['jabatan'] = $kepala_dusun;
                break;

            case '2':
                $data['jabatan'] = 'Ketua RW';
                break;

            case '3':
                $data['jabatan'] = 'Ketua RT';
                break;
        }

        view('web.gis.aparatur_wilayah', $data);
    }

    public function get_form_info(): void
    {
        $redirect_link = $this->input->get('redirectLink', true);

        if ($this->session->inside_retry == false) {
            // Untuk kondisi SEBELUM autentikasi dan SETELAH RETRY hit API
            if ($this->input->get('outsideRetry', true) == 'true') {
                $this->session->inside_retry = true;
            }
            $this->session->google_form_id = $this->input->get('formId', true);
            $result                        = $this->analisis_import_model->import_gform($redirect_link);

            echo json_encode($result, JSON_THROW_ON_ERROR);
        } else {
            // Untuk kondisi SESAAT setelah Autentikasi
            $redirect_link = $this->session->inside_redirect_link;

            $this->session->unset_userdata(['inside_retry', 'inside_redirect_link']);

            header('Location: ' . $redirect_link . '?outsideRetry=true&code=' . $this->input->get('code', true) . '&formId=' . $this->session->google_form_id);
        }
    }

    public function utama(): void
    {
        redirect('/');
    }
}
