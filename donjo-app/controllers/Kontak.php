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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

use App\DataTables\Contracts\KontakDataTableInterface;
use App\DataTables\KontakDataTable;
use App\Models\DaftarKontak;
use App\Repositories\KontakRepository;
use App\Services\Contracts\KontakServiceInterface;
use App\Services\KontakService;

// TODO:: Hapus bagian ini karena tidak digunakan, menggunakan controller DaftarKontak
/**
 * Controller Kontak - Menangani HTTP request/response untuk Kontak
 * 
 * Refactored sesuai prinsip SOLID:
 * - Single Responsibility: Controller hanya menangani HTTP request/response
 * - Open/Closed: Mudah diperluas dengan implementasi baru tanpa modifikasi
 * - Liskov Substitution: Service dapat diganti dengan implementasi lain
 * - Interface Segregation: Interface spesifik untuk tiap kebutuhan
 * - Dependency Inversion: Bergantung pada abstraksi (interface), bukan konkrit class
 */
class Kontak extends Admin_Controller
{
    public $modul_ini           = 'hubung-warga';
    public $sub_modul_ini       = 'daftar-kontak';
    public $kategori_pengaturan = 'Hubung Warga';

    /**
     * @var KontakServiceInterface
     */
    protected $kontakService;

    /**
     * @var KontakDataTableInterface
     */
    protected $dataTable;

    /**
     * Constructor dengan Dependency Injection
     */
    public function __construct()
    {
        parent::__construct();
        isCan('b');
        
        // Dependency Injection: Inject services melalui constructor
        $this->kontakService = new KontakService(
            new KontakRepository(new DaftarKontak())
        );
        $this->dataTable = new KontakDataTable();
    }

    /**
     * Halaman index kontak
     */
    public function index()
    {
        return view('admin.kontak.index');
    }

    /**
     * Datatables untuk list kontak - delegasi ke DataTable class
     */
    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return $this->dataTable->generate();
        }

        return show_404();
    }

    /**
     * Form tambah/edit kontak
     * 
     * @param string $id
     */
    public function form($id = '')
    {
        isCan('u');

        if ($id) {
            $action      = 'Ubah';
            $form_action = ci_route('kontak.update', $id);
            $kontak      = DaftarKontak::findOrFail($id);
        } else {
            $action      = 'Tambah';
            $form_action = ci_route('kontak.insert');
            $kontak      = null;
        }

        return view('admin.kontak.form', ['action' => $action, 'form_action' => $form_action, 'kontak' => $kontak]);
    }

    /**
     * Insert kontak baru - delegasi ke service layer
     */
    public function insert(): void
    {
        isCan('u');

        if ($this->kontakService->create($this->request)) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    /**
     * Update kontak - delegasi ke service layer
     * 
     * @param string $id
     */
    public function update($id = ''): void
    {
        isCan('u');

        if ($this->kontakService->update((int) $id, $this->request)) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    /**
     * Delete kontak - delegasi ke service layer
     * 
     * @param string $id
     */
    public function delete($id = ''): void
    {
        isCan('h');

        if ($this->kontakService->delete((int) $id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    /**
     * Delete multiple kontak - delegasi ke service layer
     */
    public function deleteAll(): void
    {
        isCan('h');

        if ($this->kontakService->deleteMultiple($this->request['id_cb'] ?? [])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }
}
