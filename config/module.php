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

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */
    'namespace' => 'Modules',

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path is used to save the generated module.
        | This path will also be added automatically to the list of scanned folders.
        |
        */
        'modules' => $this->app->basePath('Modules'),

        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules' assets path.
        |
        */
        'assets' => $this->app->basePath('assets/modules'),

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Setting the generate key to false will not generate that folder
        */
        'generator' => [
            // app/
            // 'actions' => ['path' => 'Actions', 'generate' => false],
            // 'casts' => ['path' => 'Casts', 'generate' => false],
            // 'channels' => ['path' => 'Broadcasting', 'generate' => false],
            // 'class' => ['path' => 'Classes', 'generate' => false],
            // 'command' => ['path' => 'Console', 'generate' => false],
            // 'component-class' => ['path' => 'View/Components', 'generate' => false],
            // 'emails' => ['path' => 'Emails', 'generate' => false],
            // 'event' => ['path' => 'Events', 'generate' => false],
            'enums' => ['path' => 'Enums', 'generate' => false],
            // 'exceptions' => ['path' => 'Exceptions', 'generate' => false],
            // 'jobs' => ['path' => 'Jobs', 'generate' => false],
            'helpers' => ['path' => 'Helpers', 'generate' => false],
            // 'interfaces' => ['path' => 'Interfaces', 'generate' => false],
            // 'listener' => ['path' => 'Listeners', 'generate' => false],
            'model' => ['path' => 'Models', 'generate' => false],
            // 'notifications' => ['path' => 'Notifications', 'generate' => false],
            'observer' => ['path' => 'Observers', 'generate' => false],
            // 'policies' => ['path' => 'Policies', 'generate' => false],
            'provider' => ['path' => 'Providers', 'generate' => false],
            'repository' => ['path' => 'Repositories', 'generate' => false],
            // 'resource' => ['path' => 'Transformers', 'generate' => false],
            // 'route-provider' => ['path' => 'Providers', 'generate' => true],
            // 'rules' => ['path' => 'Rules', 'generate' => false],
            // 'services' => ['path' => 'Services', 'generate' => false],
            // 'scopes' => ['path' => 'Models/Scopes', 'generate' => false],
            'traits' => ['path' => 'Traits', 'generate' => false],

            // // app/Http/
            'controller' => ['path' => 'Http/Controllers', 'generate' => false],
            // 'filter' => ['path' => 'Http/Middleware', 'generate' => false],
            // 'request' => ['path' => 'Http/Requests', 'generate' => false],

            // config/
            'config' => ['path' => 'Config', 'generate' => false],

            // // database/
            // 'factory' => ['path' => 'database/factories', 'generate' => true],
            'migration' => ['path' => 'Database/Migrations', 'generate' => false],
            'seeder' => ['path' => 'Database/Seeders', 'generate' => false],

            // // lang/
            // 'lang' => ['path' => 'lang', 'generate' => false],

            // // resource/
            'assets' => ['path' => 'Views/assets', 'generate' => false],
            // 'component-view' => ['path' => 'resources/views/components', 'generate' => false],
            'views' => ['path' => 'Views/', 'generate' => false],

            // routes/
            'routes' => ['path' => 'Routes', 'generate' => false],

            // // tests/
            // 'test-feature' => ['path' => 'tests/Feature', 'generate' => true],
            // 'test-unit' => ['path' => 'tests/Unit', 'generate' => true],
        ],
    ],
];
