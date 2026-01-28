<?php

/*
 * File ini bagian dari OpenSID
 * Global helper functions for dual CI3 + Laravel support
 */

use App\Helpers\RedirectHelper;
use App\Helpers\RouteHelper;

if (! function_exists('route')) {
    /**
     * Generate the URL to a named route.
     * Override untuk support dual CI3+Laravel routing system.
     *
     * @param  string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = true)
    {
        // Use RouteHelper untuk dual system support (CI3 + Laravel)
        if (class_exists(\App\Helpers\RouteHelper::class)) {
            return \App\Helpers\RouteHelper::route($name, $parameters);
        }

        // Fallback ke Laravel default
        return app('url')->route($name, $parameters, $absolute);
    }
}


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

if (! function_exists('redirect')) {
    /**
     * Redirect dengan dual CI3+Laravel support
     * Override untuk support dual CI3+Laravel redirect system.
     *
     * @param string|null $to
     * @param int         $status
     * @param array       $headers
     * @param bool|null   $secure
     *
     * @return Illuminate\Http\RedirectResponse|Illuminate\Routing\Redirector|void
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        // Jika $to adalah null, return redirector instance (Laravel)
        if (null === $to) {
            return app('redirect');
        }

        // Use RedirectHelper untuk dual system support (CI3 + Laravel)
        if (class_exists(\App\Helpers\RedirectHelper::class)) {
            return \App\Helpers\RedirectHelper::redirect($to, 'location', $status);
        }

        // Fallback ke Laravel default
        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

