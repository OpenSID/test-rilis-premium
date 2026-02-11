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

use App\Http\Requests\SyaratSurat\SyaratSuratRequest;
use App\Repositories\SyaratSurat\SyaratSuratRepository;
use App\Services\SyaratSurat\SyaratSuratService;
use App\Services\SyaratSurat\SyaratSuratDataTableService;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat_mohon extends Admin_Controller
{
    public $modul_ini     = 'layanan-surat';
    public $sub_modul_ini = 'daftar-persyaratan';

    /**
     * Service instance
     *
     * @var SyaratSuratService
     */
    private SyaratSuratService $service;

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        $repository = new SyaratSuratRepository();
        $this->service = new SyaratSuratService($repository);
    }

    /**
     * Show index page
     *
     * @return string
     */
    public function index()
    {
        return view('admin.syaratan_surat.index');
    }

    /**
     * Handle DataTables AJAX request
     *
     * @return mixed
     */
    public function datatables()
    {
        if (! request()->ajax()) {
            return show_404();
        }

        $repository = new SyaratSuratRepository();
        $dataTableService = new SyaratSuratDataTableService($repository);
        return $dataTableService->builder();
    }

    /**
     * Show form for create or edit
     *
     * @param string $id
     * @return string
     */
    public function form($id = '')
    {
        isCan('u');

        $data = $this->service->formatForForm($id ?: null);

        return view('admin.syaratan_surat.form', $data);
    }

    /**
     * Store new syarat surat
     *
     * @return void
     */
    public function insert()
    {
        isCan('u');

        $data = (new SyaratSuratRequest())->validated();
        $result = $this->service->store($data);

        if ($result) {
            return redirect_with('success', __('notification.created.success'));
        }

        return redirect_with('error', __('notification.created.error'));
    }

    /**
     * Update existing syarat surat
     * 
     * @param string $id
     * @return void
     */
    public function update($id = '')
    {
        isCan('u');

        $data = (new SyaratSuratRequest($id))->validated();
        $result = $this->service->update($id, $data);

        if ($result) {
            return redirect_with('success', __('notification.updated.success'));
        }

        return redirect_with('error', __('notification.updated.error'));
    }

    /**
     * Delete single syarat surat
     *
     * @param string $id
     * @return void
     */
    public function delete($id = '')
    {
        isCan('h');

        $result = $this->service->delete($id);

        if ($result) {
            return redirect_with('success', __('notification.deleted.success'));
        }

        return redirect_with('error', __('notification.deleted.error'));
    }

    /**
     * Delete multiple syarat surat
     *
     * @return void
     */
    public function deleteAll()
    {
        isCan('h');

        $ids    = request()->input('id_cb', []);
        $result = $this->service->deleteMultiple($ids);

        if ($result) {
            return redirect_with('success', __('notification.deleted.success'));
        }

        return redirect_with('error', __('notification.deleted.error'));
    }
}
