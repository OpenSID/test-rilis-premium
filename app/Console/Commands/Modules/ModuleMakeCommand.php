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

namespace App\Console\Commands\Modules;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ModuleMakeCommand extends Command
{
    protected $signature   = 'make:module';
    protected $description = 'Create a new module with default structure and files';
    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): void
    {
        $moduleName      = $this->ask('Masukkan nama modul (contoh: Blog)');
        $moduleNameLower = $this->ask('Masukkan nama modul kecil / lowercase (contoh: blog)', Str::lower($moduleName));

        $moduleDir = base_path("Modules/{$moduleName}");
        $moduleClass = Str::ucfirst($moduleName);

        if ($this->files->exists($moduleDir)) {
            $this->error("Module {$moduleName} sudah ada!");

            return;
        }

        // Buat struktur folder dasar
        $folders = [
            'Database/Migrations',
            'Database/Seeders',
            'Http/Controllers',
            'Models',
            'Providers',
            'Config',
            'Views',
            'Routes',
            'Helpers',
        ];

        foreach ($folders as $folder) {
            $this->files->makeDirectory("{$moduleDir}/{$folder}", 0755, true);
        }

        // Buat file default dari stub
        $this->createFileFromStub("{$moduleDir}/Providers/{$moduleName}ServiceProvider.php", 'provider.stub', [
            '{{ nameSpace }}'      => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
            '{{ class }}' => "{$moduleClass}ServiceProvider",
        ]);

        $this->createFileFromStub("{$moduleDir}/Http/Controllers/{$moduleClass}Controller.php", 'controller.stub', [
            '{{ nameSpace }}' => "Modules\\{$moduleName}\\Http\\Controllers",
            '{{ class }}'     => "{$moduleClass}Controller",
        ]);

        $this->createFileFromStub("{$moduleDir}/Models/{$moduleClass}Model.php", 'model.stub', [
            '{{ nameSpace }}' => "Modules\\{$moduleName}\\Models",
            '{{ class }}'     => "{$moduleClass}Model",
        ]);

        $this->createFileFromStub("{$moduleDir}/Database/Seeders/{$moduleClass}Seeder.php", 'seed.stub', [
            '{{ nameSpace }}' => "Modules\\{$moduleName}\\Database\\Seeders",
            '{{ class }}'     => "{$moduleClass}Seeder",
        ]);

        $this->createFileFromStub("{$moduleDir}/Config/config.php", 'config.stub', [
            '{{ moduleName }}'      => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
        ]);

        // $this->createFileFromStub("{$moduleDir}/Routes/web.php", 'routes.stub', [
        //     '{{ nameSpace }}'      => $moduleName,
        //     '{{ moduleLower }}' => $moduleNameLower,
        // ]);

        // $this->createFileFromStub("{$moduleDir}/Views/index.blade.php", 'view.stub', [
        //     '{{ nameSpace }}'      => $moduleName,
        //     '{{ moduleLower }}' => $moduleNameLower,
        // ]);

        $this->createFileFromStub("{$moduleDir}/Helpers/{$moduleNameLower}_helper.php", 'helper.stub', [
            '{{ moduleName }}'      => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
        ]);

        // Buat migrasi contoh
        $migrationFile = date('Y_m_d_His') . "_create_{$moduleNameLower}_table.php";
        $this->createFileFromStub("{$moduleDir}/Database/Migrations/{$migrationFile}", 'migration.stub', [
            '{{ class }}' => "Create{$moduleClass}Table",
        ]);

        // composer.json
        $this->createFileFromStub("{$moduleDir}/composer.json", 'composer.stub', [
            '{{ nameSpace }}'      => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
        ]);

        // module.json
        $this->createFileFromStub("{$moduleDir}/module.json", 'module.stub', [
            '{{ nameSpace }}'      => $moduleName,
            '{{ moduleLower }}' => $moduleNameLower,
        ]);

        $this->info("Module {$moduleName} berhasil dibuat!");
    }

    protected function createFileFromStub(string $path, string $stub, array $replace = []): void
    {
        $stubPath = base_path("app/Console/Commands/Modules/Stubs/{$stub}");
        if (! $this->files->exists($stubPath)) {
            $this->error("Stub {$stub} tidak ditemukan di {$stubPath}");

            return;
        }

        $content = $this->files->get($stubPath);
        $content = str_replace(array_keys($replace), array_values($replace), $content);

        $this->files->put($path, $content);
    }
}
