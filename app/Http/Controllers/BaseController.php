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

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Notifikasi;
use App\Models\Pamong;
use App\Models\Setting;
use App\Models\UserGrup;
use App\Models\Wilayah;
use App\Services\NotificationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

/**
 * Base Controller untuk semua Laravel Controllers
 * 
 * Menggantikan Admin_Controller (CI3) dengan fitur Laravel native
 */
class BaseController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected array $sharedData = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->shareCommonViewData();
    }

    /**
     * Share data yang biasa digunakan untuk semua views
     */
    protected function shareCommonViewData(): void
    {
        View::share([
            'list_setting' => app('ci')->list_setting ?? [],
            'kategori_pengaturan' => app('ci')->kategori_pengaturan ?? [],
            'notif_categories' => NotificationService::getCategories(),
            'notif_counts' => auth('admin')->check() 
                ? NotificationService::getNotificationCounts(auth('admin')->user())
                : [],
            'notif_list' => auth('admin')->check()
                ? NotificationService::getRecentNotifications(auth('admin')->user(), 10)
                : [],
        ]);
    }

    /**
     * Share custom data ke view
     */
    protected function shareViewData(array $data): self
    {
        View::share($data);
        $this->sharedData = array_merge($this->sharedData, $data);
        
        return $this;
    }

    /**
     * Check identitas desa
     * Equivalent dari CI3 $this->cek_identitas_desa()
     */
    protected function cekIdentitasDesa(): void
    {
        $identitas = identitas();
        if (empty($identitas['nama_desa'])) {
            redirect(route('install.index'));
        }
    }

    /**
     * Helper untuk authorization check
     */
    protected function authorize(string $akses): bool
    {
        if (!can($akses)) {
            abort(403, 'Unauthorized');
        }

        return true;
    }

    /**
     * Redirect dengan flash message (success)
     */
    protected function redirectWithSuccess(string $route, string $message, array $params = [])
    {
        return redirect()->route($route, $params)->with('success', $message);
    }

    /**
     * Redirect dengan flash message (error)
     */
    protected function redirectWithError(string $route, string $message, array $params = [])
    {
        return redirect()->route($route, $params)->with('error', $message);
    }

    /**
     * Redirect dengan flash message (info)
     */
    protected function redirectWithInfo(string $route, string $message, array $params = [])
    {
        return redirect()->route($route, $params)->with('information', $message);
    }
}
