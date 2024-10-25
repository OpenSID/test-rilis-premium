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

function processFiles(array $includeDirs, array $excludeDirs)
{
    $excludeDirs = array_map(static function ($dir) {
        return rtrim($dir, '/') . '/';
    }, $excludeDirs);

    foreach ($includeDirs as $includeDir) {
        $directory = new RecursiveDirectoryIterator($includeDir);
        $iterator  = new RecursiveIteratorIterator($directory);
        $phpFiles  = new RegexIterator($iterator, '/\.php$/');

        foreach ($phpFiles as $file) {
            $isExcluded = false;

            foreach ($excludeDirs as $excludeDir) {
                if (strpos($file->getPathname(), $excludeDir) === 0) {
                    $isExcluded = true;
                    break;
                }
            }

            if ($isExcluded) {
                continue;
            }

            $content = file_get_contents($file->getPathname());

            if (strpos($content, 'defined(\'BASEPATH\') || exit(\'No direct script access allowed\');') === false) {
                if (preg_match('/^(class\s+\w+)/m', $content)) {
                    $newContent = preg_replace('/^(class\s+\w+)/m', "defined('BASEPATH') || exit('No direct script access allowed');\n\n$1", $content);
                    file_put_contents($file->getPathname(), $newContent);
                    echo 'Updated: ' . $file->getPathname() . "\n";
                }
            }
        }
    }
}

processFiles(
    // Iclude directories
    [
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/donjo-app',
        __DIR__ . '/Modules',
    ],
    // Exclude directories
    [
        
    ]
);
