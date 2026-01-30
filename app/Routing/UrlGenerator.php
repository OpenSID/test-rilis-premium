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

namespace App\Routing;

use BackedEnum;
use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Support\Str;
use InvalidArgumentException;
use OpenSID\RouteBuilder;

class UrlGenerator extends \Illuminate\Routing\UrlGenerator
{
    use InteractsWithTime;

    /**
     * Get the URL to a named route.
     * Support both CI3 routes (via RouteBuilder) dan Laravel routes
     *
     * @param string    $name
     * @param mixed     $parameters
     * @param bool|null $secure
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function route($name, mixed $parameters = [], $secure = null)
    {
        // Try CI3 routes first
        try {
            if (class_exists(RouteBuilder::class)) {
                $route = RouteBuilder::getByName($name);
                if ($route) {
                    $uri = $this->to($route->buildUrl($parameters), [], $secure);

                    $filteredParameters = array_filter(
                        $parameters,
                        static fn ($value, $key): bool => ! $route->hasParam($key),
                        ARRAY_FILTER_USE_BOTH
                    );

                    if ($filteredParameters) {
                        $uri .= '?' . http_build_query($filteredParameters);
                    }

                    return $uri;
                }
            }
        } catch (\Throwable $e) {
            // Continue to Laravel routes
        }

        // Fallback ke Laravel route
        return parent::route($name, $parameters, $secure);
    }

    /**
     * Create a signed route URL for a named route.
     * Support both CI3 routes dan Laravel routes
     *
     * @param BackedEnum|string                       $name
     * @param mixed                                   $parameters
     * @param DateInterval|DateTimeInterface|int|null $expiration
     * @param bool                                    $absolute
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function signedRoute($name, mixed $parameters = [], $expiration = null, $absolute = true)
    {
        $this->ensureSignedRouteParametersAreNotReserved(
            $parameters = Arr::wrap($parameters)
        );

        if ($expiration) {
            $parameters += ['expires' => $this->availableAt($expiration)];
        }

        ksort($parameters);

        $key = ($this->keyResolver)();

        return $this->route($name, $parameters + [
            'signature' => hash_hmac(
                'sha256',
                $this->route($name, $parameters, $absolute),
                is_array($key) ? $key[0] : trim((string) $key)
            ),
        ], $absolute);
    }
}
