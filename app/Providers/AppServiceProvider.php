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

namespace App\Providers;

use App\Models\SettingAplikasi;
use App\Services\QueryDetector;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Compilers\BladeCompiler;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\SmallIntType;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loadModuleServiceProvider();

        // Register custom UrlGenerator untuk support CI3 routes
        $this->app->singleton('url', function ($app) {
            $urlGenerator = new \App\Routing\UrlGenerator(
                $app['router']->getRoutes(),
                $app->make('request'),
                $app['config']['app.asset_url']
            );

            // Set the default scheme/domain for URLs
            $urlGenerator->setRootControllerNamespace($app['config']['app.namespace']);

            // Setup key resolver for signed URLs
            $urlGenerator->setKeyResolver(function () {
                return $app['config']['app.key'];
            });

            return $urlGenerator;
        });

        // hanya daftarkan Type global
        $this->registerDoctrineTypes();

        // Register Blade extensions
        $this->callAfterResolving('blade.compiler', fn (BladeCompiler $bladeCompiler) => $this->registerBladeExtensions($bladeCompiler));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Override route() helper untuk dual CI3+Laravel support
        $this->overrideRouteHelper();
        
        // Override redirect() helper untuk dual CI3+Laravel support
        $this->overrideRedirectHelper();
        
        $this->registerMacros();
        $this->registerCoreViews();

        // mapping butuh DB connection, jadi aman dipanggil di boot
        $this->registerDoctrineTypeMappings();

        $this->app->make(QueryDetector::class)->boot();

        // Share header global ke semua views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $view->with('header', identitas());
        });

        // Boot view data sharing dan sensitive settings
        $this->bootShareViewData();
        $this->bootHideSensitiveSetting();
    }

    /**
     * Override route() helper untuk support both CI3 dan Laravel routes
     */
    private function overrideRouteHelper(): void
    {
        // Override using eval untuk replace fungsi yang sudah ada
        $routeHelper = \App\Helpers\RouteHelper::class;
        
        // Rename Laravel's original route function
        if (function_exists('route')) {
            // Store original route function
            if (!function_exists('laravel_route')) {
                eval('
                    function laravel_route($name = null, $parameters = [], $absolute = true) {
                        try {
                            return \Illuminate\Support\Facades\URL::route($name, $parameters, $absolute);
                        } catch (\Throwable $e) {
                            return "#";
                        }
                    }
                ');
            }
        }
    }

    /**
     * Override redirect() helper untuk support both CI3 dan Laravel redirects
     */
    private function overrideRedirectHelper(): void
    {
        // Store original Laravel redirect function
        if (!function_exists('laravel_redirect')) {
            eval('
                function laravel_redirect($location = "", $method = "location", $code = 302) {
                    try {
                        return \Illuminate\Support\Facades\Redirect::to($location)->setStatusCode($code);
                    } catch (\Throwable $e) {
                        header("Location: " . $location);
                        exit;
                    }
                }
            ');
        }
    }

    private function registerDoctrineTypes(): void
    {
        if (!class_exists(Type::class)) {
            return;
        }

        if (!Type::hasType('tinyinteger')) {
            Type::addType('tinyinteger', SmallIntType::class);
        }
    }

    private function registerDoctrineTypeMappings(): void
    {
        if (!class_exists(Type::class)) {
            return;
        }

        $platform = DB::connection()->getDoctrineConnection()->getDatabasePlatform();

        // Tinyint bawaan MySQL
        if (! $platform->hasDoctrineTypeMappingFor('tinyint')) {
            $platform->registerDoctrineTypeMapping('tinyint', 'smallint');
        }

        if (! $platform->hasDoctrineTypeMappingFor('tinyinteger')) {
            $platform->registerDoctrineTypeMapping('tinyinteger', 'smallint');
        }

        // Enum (sering dipakai di MySQL lama)
        if (! $platform->hasDoctrineTypeMappingFor('enum')) {
            $platform->registerDoctrineTypeMapping('enum', 'string');
        }

        // (Opsional) SET MySQL
        if (! $platform->hasDoctrineTypeMappingFor('set')) {
            $platform->registerDoctrineTypeMapping('set', 'string');
        }
    }

    /**
     * Register custom macros.
     *
     * @return void
     */
    protected function registerMacros()
    {
        $this->registerMacrosConfigId();
        $this->registerMacrosUserStamps();
        $this->registerMacrosStatus();
        $this->registerMacrosUrut();
        $this->registerMacrosSlug();
        $this->registerMacrosCreateIfNotExist();
        $this->registerMacrosDropIfExistsDBGabungan();
        $this->registerMacroConvertToBytes();
        $this->registerMacroHeaderKawinCerai();
        $this->registerMacroGroupByLabel();
    }

    protected function registerMacroGroupByLabel()
    {
        Collection::macro('groupByLabel', fn() => $this->groupBy(static function ($item): string {
            $label = $item->label ?? '';
            if (empty($label)) {
                $label = underscore($item->nama, false);
            }

            return ucwords($label);
        }));
    }

    protected function registerMacroConvertToBytes()
    {
        Str::macro('convertToBytes', static function (string $value): int {
            $value = trim($value);
    
            // Jika bernilai -1, berarti tidak terbatas
            if ($value === '-1') {
                return PHP_INT_MAX;
            }
    
            // Ambil angka dan unit secara lebih akurat
            if (preg_match('/^(\d+)([KMG]?)$/i', $value, $matches)) {
                $number = (int) $matches[1];
                $unit   = strtolower($matches[2] ?? '');
    
                return match ($unit) {
                    'g' => $number * 1024 * 1024 * 1024,
                    'm' => $number * 1024 * 1024,
                    'k' => $number * 1024,
                    default => $number,
                };
            }
    
            return 0; // Jika format tidak sesuai
        });
    }

    protected function registerMacroHeaderKawinCerai()
    {
        Str::macro('headerKawinCerai', static function (Collection|array $statuses): string {
            $hasKawin = collect($statuses)->contains(static fn ($status) => Str::contains($status, 'KAWIN'));
            $hasCerai = collect($statuses)->contains(static fn ($status) => Str::contains($status, 'CERAI'));

            return match (true) {
                $hasKawin && $hasCerai => 'Tanggal Perkawinan / Perceraian',
                $hasCerai              => 'Tanggal Perceraian',
                default                => 'Tanggal Perkawinan',
            };
        });
    }

    /**
     * Register macro for config_id column.
     *
     * @return void
     */
    protected function registerMacrosConfigId()
    {
        Blueprint::macro('configId', function (): void {
            $columns = $this->getColumns();
            if (in_array('id', $columns)) {
                $this->integer('config_id')->nullable()->after('id');
            } elseif (in_array('uuid', $columns)) {
                $this->integer('config_id')->nullable()->after('uuid');
            } else {
                $this->integer('config_id')->nullable();
            }
            $this->foreign('config_id')->references('id')->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Register macro for userstamps columns.
     *
     * @return void
     */
    protected function registerMacrosUserStamps()
    {
        Blueprint::macro('timesWithUserstamps', function (): void {
            $this->timestamp('created_at')->nullable()->useCurrent();
            $this->integer('created_by')->nullable();
            $this->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $this->integer('updated_by')->nullable();
            // $this->timestamp('deleted_at')->nullable();
            // $this->integer('deleted_by')->nullable();
        });
    }

    /**
     * Register macro for status column.
     *
     * @return void
     */
    protected function registerMacrosStatus()
    {
        Blueprint::macro('status', function (): void {
            $this->tinyInteger('status')->default(0);
        });
    }

    /**
     * Register macro for urut column.
     *
     * @return void
     */
    protected function registerMacrosUrut()
    {
        Blueprint::macro('urut', function (): void {
            $this->integer('urut')->default(0);
        });
    }

    /**
     * Register macro for slug column.
     *
     *
     * @return void
     */
    protected function registerMacrosSlug(mixed $uniqueColumns = ['config_id', 'slug'])
    {
        Blueprint::macro('slug', function () use ($uniqueColumns): void {
            $this->string('slug')->nullable();
            $this->unique($uniqueColumns);
        });
    }

    /**
     * Register Blueprint macro: createIfNotExist
     *
     * @return void
     */
    protected function registerMacrosCreateIfNotExist(): void
    {
        Blueprint::macro('createIfNotExist', function (string $table, \Closure $callback) {
            if (! Schema::hasTable($table)) {
                Schema::create($table, $callback);
            }
        });
    }


    /**
     * Register macro for dropIfExistsDBGabungan.
     *
     * @param mixed|null $table
     * @param mixed|null $model
     *
     * @return void
     */
    protected function registerMacrosDropIfExistsDBGabungan($table = null, $model = null)
    {
        Schema::macro('dropIfExistsDBGabungan', function ($table, $model): void {
            if (DB::table('config')->count() === 1) {
                Schema::dropIfExists($table);
            } elseif (Schema::hasTable($table)) {
                $model::withoutConfigId(identitas('id'))->delete();
            }
        });
    }

    /**
     * Register core views.
     */
    public function registerCoreViews(): void
    {
        $sourcePath = FCPATH . 'resources/views';

        $this->loadViewsFrom($sourcePath, 'core');
    }

    /**
     * Load service providers from modules.
     */
    private function loadModuleServiceProvider(): void
    {
        $modulesPath = $this->app->basePath('Modules');

        $modules = File::directories($modulesPath);

        foreach ($modules as $modulePath) {
            $moduleName = basename((string) $modulePath);

            $providerClassNew = "Modules\\{$moduleName}\\App\\Providers\\{$moduleName}ServiceProvider";
            $providerClassOld = "Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";

            if (class_exists($providerClassNew)) {
                $this->app->register($providerClassNew);
            } elseif (class_exists($providerClassOld)) {
                $this->app->register($providerClassOld);
            }
        }
    }

    /**
     * Register Blade extensions.
     */
    protected function registerBladeExtensions(BladeCompiler $bladeCompiler): void
    {
        $bladeCompiler->directive('selected', static fn ($condition): string => "<?= ({$condition}) ? 'selected' : ''; ?>");
        $bladeCompiler->directive('checked', static fn ($condition): string => "<?= ({$condition}) ? 'checked' : ''; ?>");
        $bladeCompiler->directive('disabled', static fn ($condition): string => "<?= ({$condition}) ? 'disabled' : ''; ?>");
        $bladeCompiler->directive('active', static fn ($condition): string => "<?= ({$condition}) ? 'active' : ''; ?>");
        $bladeCompiler->directive('display', static fn ($condition): string => "<?= ({$condition}) ? 'show' : 'hide'; ?>");
    }

    /**
     * Boot share view data.
     */
    protected function bootShareViewData(): void
    {
        $ci = app('ci');
        if (! $ci->session->instalasi) {
            try {
                $desa = identitas();
            } catch (Exception) {
            }
        }

        if ($ci->session->db_error['code'] === 1049) {
            $ci->session->error_db = null;
            $ci->session->unset_userdata(['db_error', 'message', 'heading', 'message_query', 'message_exception', 'sudah_mulai']);
        } else {
            View::share([
                'errors'      => $ci->session->errors ?: new ViewErrorBag(),
                'ci'          => $ci,
                'desa'        => $desa ?? null,
                'auth'        => $ci->session->isAdmin,
                'session'     => $ci->session,
                'token_name'  => $ci->security->get_csrf_token_name(),
                'token_value' => $ci->security->get_csrf_hash(),
            ]);
        }
    }

    /**
     * Boot hide sensitive setting.
     */
    protected function bootHideSensitiveSetting()
    {
        View::composer('*', function ($view): void {
            $ci = app('ci');

            foreach (SettingAplikasi::$sensitiveKeys as $key) {
                unset($ci->setting->{$key});
            }
        });
    }
}
