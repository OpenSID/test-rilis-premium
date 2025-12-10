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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Modules\DTSEN\App\Providers;

use Composer\Autoload\ClassLoader;
use Illuminate\Support\ServiceProvider;

class DTSENServiceProvider extends ServiceProvider
{
    protected string $moduleName      = 'DTSEN';
    protected string $moduleNameLower = 'dtsen';

    public function boot(): void
    {
        $this->registerModuleAutoload();
        $this->registerConfig();
        $this->registerHelpers();
        $this->registerViews();
    }

    public function register(): void
    {
    }

    protected function registerModuleAutoload(): void
    {
        $loader = require FCPATH . 'vendor/autoload.php';

        if ($loader instanceof ClassLoader) {
            $base = FCPATH . "Modules/{$this->moduleName}/";

            $loader->addPsr4("Modules\\{$this->moduleName}\\App\\", $base . 'app/');
            $loader->addPsr4("Modules\\{$this->moduleName}\\Database\\Factories\\", $base . 'database/factories/');
            $loader->addPsr4("Modules\\{$this->moduleName}\\Database\\Seeders\\", $base . 'database/seeders/');
        }
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            FCPATH . "Modules/{$this->moduleName}/Config/config.php",
            $this->moduleNameLower
        );
    }

    protected function registerHelpers(): void
    {
        $helperPath = FCPATH . "Modules/{$this->moduleName}/App/Helpers/dtsen_helper.php";

        if (is_file($helperPath)) {
            require_once $helperPath;
        }
    }

    protected function registerViews(): void
    {
        $sourcePath = FCPATH . "Modules/{$this->moduleName}/resources/Views";

        $this->loadViewsFrom($sourcePath, $this->moduleNameLower);
    }
}
