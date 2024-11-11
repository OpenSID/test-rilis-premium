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

use App\Enums\SasaranEnum;
use App\Libraries\Shortcode;
use App\Models\Artikel as ModelsArtikel;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\Kategori;
use App\Models\Komentar;

defined('BASEPATH') || exit('No direct script access allowed');

class Artikel extends Web_Controller
{
    /*
    | Artikel bisa ditampilkan menggunakan parameter pertama sebagai id, dan semua parameter lainnya dikosongkan. url artikel/:id
    | Kalau menggunakan slug, dipanggil menggunakan url artikel/:thn/:bln/:hri/:slug
    */
    public function index($thn = null, $bln = null, $hr = null, $url = null): void
    {
        if ($url == null || $thn == null || $bln == null || $hr == null) {
            show_404();
        }

        if (is_numeric($url)) {
            $data_artikel = ModelsArtikel::find($url);

            if ($data_artikel) {
                $data_artikel['slug'] = $this->security->xss_clean($data_artikel['slug']);
                redirect('artikel/' . buat_slug($data_artikel));
            }
        }
        $this->load->model('shortcode_model');
        $data = $this->includes;

        ModelsArtikel::read($url);
        $artikel        = ModelsArtikel::with(['author', 'category', 'agenda'])->sitemap()->berdasarkan($thn, $bln, $hr, $url)->first();
        $artikel->judul = htmlspecialchars_decode(bersihkan_xss($artikel->judul));
        $singleArtikel  = $artikel->toArray() + [
            'kategori' => $artikel->category->kategori,
            'kat_slug' => $artikel->category->slug,
            'owner'    => $artikel->author->nama,
        ];
        $data['single_artikel'] = $singleArtikel;
        $data['links']          = $artikel;
        // replace isi artikel dengan shortcodify
        $data['single_artikel']['isi'] = (new Shortcode())->shortcode($artikel->isi);
        $data['title']                 = ucwords($data['single_artikel']['judul']);
        $data['detail_agenda']         = $artikel->agenda;
        $data['komentar']              = Komentar::with('children')
            ->where('id_artikel', $artikel->id)
            ->where('status', Komentar::ACTIVE)
            ->whereNull('parent_id')
            ->get()->toArray();

        $this->_get_common_data($data);
        view('artikel', $data);
    }

    public function kategori($id, $p = 1): void
    {
        $data = $this->includes;
        $this->load->model('first_artikel_m');
        $data['judul_kategori'] = ['kategori' => Kategori::where(static fn ($q) => $q->where('id', $id)->orWhere('slug', $id))->first()?->kategori ?? "Artikel Kategori {$id}"];
        $data['title']          = 'Artikel ' . $data['judul_kategori']['kategori'];
        $artikel                = ModelsArtikel::kategori($id)->paginate();
        $data['artikel']        = $artikel;
        $data['links']          = $artikel;
        $this->_get_common_data($data);
        view('template', $data);
    }

    public function datatables_peserta_bantuan($id)
    {
            if ($this->input->is_ajax_request()) {
                $filter  = [];
                $sasaran = SasaranEnum::PENDUDUK;
                $query   = BantuanPeserta::join('program', 'program.id', '=', 'program_peserta.program_id')
                    ->when($filter['tahun'], static fn ($q) => $q->whereRaw("YEAR(sdate) <= {$filter['tahun']}")->whereRaw("YEAR(edate) >= {$filter['tahun']}"))
                    ->when($filter['status'], static fn ($q) => $q->whereStatus($filter['status']));
                $cluster = $filter['cluster'];

                switch($id) {
                    case 'bantuan_penduduk':
                        $sasaran = SasaranEnum::PENDUDUK;
                        break;

                    case 'bantuan_keluarga':
                        $sasaran = SasaranEnum::KELUARGA;
                        break;

                    default:
                        $query->where('program.id', $id);
                        $sasaran = Bantuan::find($id)->sasaran;
                }
                $query->whereSasaran($sasaran);

                switch($sasaran) {
                    case SasaranEnum::PENDUDUK:
                        $query->when($cluster, static fn ($r) => $r->whereHas('penduduk', static fn ($s) => $s->whereIn('id_cluster', $cluster)));
                        break;

                    case SasaranEnum::KELUARGA:
                        $query->when($cluster, static fn ($r) => $r->whereHas('keluarga', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($r) => $r->whereIn('id_cluster', $cluster))));
                        break;

                    case SasaranEnum::RUMAH_TANGGA:
                        $query->when($cluster, static fn ($r) => $r->whereHas('rtm', static fn ($s) => $s->whereHas('kepalaKeluarga', static fn ($r) => $r->whereIn('id_cluster', $cluster))));
                        break;

                    case SasaranEnum::KELOMPOK:
                        break;
                }

                return datatables()->of($query)
                    ->addIndexColumn()
                    ->make();
            }
    }
}
