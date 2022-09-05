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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Carbon\Carbon;

if (! function_exists('ci_asset')) {
    function ci_asset($uri = '', $default = true)
    {
        if ($default) {
            $uri = 'assets/' . $uri;
        }
        $path = PUBLICPATH . $uri;

        return base_url($uri . '?v' . md5_file($path));
    }
}

if (! function_exists('set_session')) {
    function set_session($key = 'success', $value = '')
    {
        return get_instance()->session->set_flashdata($key, $value);
    }
}

if (! function_exists('ci_session')) {
    function ci_session($nama = '')
    {
        return get_instance()->session->flashdata($nama);
    }
}

if (! function_exists('can')) {
    function can($akses, $controller = '')
    {
        $CI = &get_instance();
        $CI->load->model('user_model');

        if (empty($controller)) {
            $controller = $CI->controller;
        }

        return $CI->user_model->hak_akses($CI->grup, $controller, $akses);
    }
}

// response()->json(array_data);
if (! function_exists('json')) {
    function json($content = [], $header = 200)
    {
        get_instance()->output
            ->set_status_header($header)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($content))
            ->_display();

        exit();
    }
}

// redirect()->route('example')->with('success', 'information');
if (! function_exists('redirect_with')) {
    function redirect_with($key = 'success', $value = '', $to = '')
    {
        set_session($key, $value);

        if (empty($to)) {
            $to = get_instance()->controller;
        }

        return ci_redirect($to);
    }
}

// route('example');
if (! function_exists('ci_route')) {
    function ci_route($to = null, $params = null)
    {
        if (in_array($to, [null, '', '/'])) {
            return site_url();
        }

        $to = str_replace('.', '/', $to);

        if (null !== $params) {
            $to .= '/' . $params;
        }

        return site_url($to);
    }
}

// setting('sebutan_desa');
if (! function_exists('setting')) {
    function setting($params = null)
    {
        $getSetting = get_instance()->setting;

        if ($params) {
            if (property_exists($getSetting, $params)) {
                return $getSetting->{$params};
            }

            return null;
        }

        return $getSetting;
    }
}

if (! function_exists('calculate_days')) {
    /**
     * Calculate minute between 2 date.
     *
     * @return int
     */
    function calculate_days(string $dateStart, string $format = 'Y-m-d')
    {
        return abs(Carbon::createFromFormat($format, $dateStart)->getTimestamp() - Carbon::now()->getTimestamp()) / (60 * 60 * 24);
    }
}

if (! function_exists('calculate_date_intervals')) {
    /**
     * Calculate list dates interval to minutes.
     *
     * @return int
     */
    function calculate_date_intervals(array $date)
    {
        $reference = Carbon::now();
        $endTime   = clone $reference;

        foreach ($date as $dateInterval) {
            $endTime = $endTime->add(DateInterval::createFromDateString(calculate_days($dateInterval) . 'days'));
        }

        return $reference->diff($endTime)->days;
    }
}

// Parsedown
if (! function_exists('parsedown')) {
    function parsedown($params = null)
    {
        $parsedown = new \App\Libraries\Parsedown();

        if (null !== $params) {
            return $parsedown->text(file_get_contents(FCPATH . $params));
        }

        return $parsedown;
    }
}

// SebutanDesa('Surat [Desa]');
if (! function_exists('SebutanDesa')) {

    function SebutanDesa($params = null)
    {
        return str_replace(['[Desa]', '[desa]'], ucwords(setting('sebutan_desa')), $params);
    }
}

if (! function_exists('underscore')) {
    /**
     * Membuat spasi menjadi underscore atau sebaliknya
     *
     * @param string $str           string yang akan dibuat spasi
     * @param bool   $to_underscore true jika ingin membuat spasi menjadi underscore, false jika sebaliknya
     * @param bool   $lowercase     true jika ingin mengubah huruf menjadi kecil semua
     *
     * @return string string yang sudah dibuat spasi
     */
    function underscore($str, $to_underscore = true, $lowercase = false)
    {
        // membersihkan string di akhir dan di awal
        $str = trim($str);

        // membuat text lowercase jika diperlukan
        if ($lowercase) {
            $str = MB_ENABLED ? mb_strtolower($str) : strtolower($str);
        }

        if ($to_underscore) {
            // mengganti spasi dengan underscore
            $str = str_replace(' ', '_', $str);
        } else {
            // mengganti underscore dengan spasi
            $str = str_replace('_', ' ', $str);
        }

        // menyajikan hasil akhir
        return $str;
    }
}

if (! function_exists('akun_demo')) {
    /**
     * Membuat batasan agar akun demo tidak dapat dihapus pada demo_mode
     *
     * @param int   $id
     * @param mixed $redirect
     */
    function akun_demo($id, $redirect = true)
    {
        if (config_item('demo_mode') && in_array($id, array_keys(config_item('demo_akun')))) {
            if ($redirect) {
                session_error(', tidak dapat mengubah / menghapus akun demo');
                ci_redirect($_SERVER['HTTP_REFERER']);
            }

            return true;
        }
    }
}

if (! function_exists('folder')) {
    /**
     * Membuat folder jika tidak tersedia
     *
     * @param string     $folder
     * @param string     $permissions
     * @param mixed|null $htaccess
     */
    function folder($folder = null, $permissions = 0755, $htaccess = null)
    {
        $hasil = true;

        get_instance()->load->helper('file');

        $folder = PUBLICPATH . $folder;

        // Buat folder
        $hasil = is_dir($folder) || mkdir($folder, $permissions, true);

        if ($hasil) {
            if ($htaccess !== null) {
                write_file($folder . '.htaccess', config_item($htaccess), 'x');
            }

            // File index.hmtl
            write_file($folder . 'index.html', config_item('index_html'), 'x');

            return true;
        }

        return false;
    }
}

if (! function_exists('folder_desa')) {
    /**
     * Membuat folder desa dan isinya
     */
    function folder_desa()
    {
        get_instance()->load->config('installer');
        $list_folder = array_merge(config_item('desa'), config_item('lainnya'));

        // Buat folder dan subfolder desa
        foreach ($list_folder as $folder => $lainnya) {
            folder($folder, $lainnya[0], $lainnya[1]);
        }

        // Buat file offline_mode.php, config.php dan database.php awal
        write_file(LOKASI_CONFIG_DESA . 'config.php', config_item('config'), 'x');
        write_file(LOKASI_CONFIG_DESA . 'database.php', config_item('database'), 'x');
        write_file(DESAPATH . 'pengaturan/siteman/siteman.css', config_item('siteman_css'), 'x');
        write_file(DESAPATH . 'pengaturan/siteman/siteman_mandiri.css', config_item('siteman_mandiri_css'), 'x');
        write_file(DESAPATH . 'offline_mode.php', config_item('offline_mode'), 'x');

        return true;
    }
}

if (! function_exists('ci_auth')) {
    /**
     * Ambil data user login
     *
     * @param mixed|null $params
     */
    function ci_auth($params = null)
    {
        $CI = &get_instance();

        if (null !== $params) {
            return $CI->session->isAdmin->{$params};
        }

        return $CI->session->isAdmin;
    }
}

if (!function_exists('ci_redirect')) {
    /**
     * Header Redirect
     *
     * Header redirect in two flavors
     * For very fine grained control over headers, you could use the Output
     * Library's set_header() function.
     *
     * @param	string	$uri	URL
     * @param	string	$method	Redirect method
     *			'auto', 'location' or 'refresh'
     * @param	int	$code	HTTP Response status code
     * @return	void
     */
    function ci_redirect($uri = '', $method = 'auto', $code = NULL)
    {
        if (!preg_match('#^(\w+:)?//#i', $uri)) {
            $uri = site_url($uri);
        }

        // IIS environment likely? Use 'refresh' for better compatibility
        if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE) {
            $method = 'refresh';
        } elseif ($method !== 'refresh' && (empty($code) or !is_numeric($code))) {
            if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1') {
                $code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
                ? 303    // reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
                    : 307;
            } else {
                $code = 302;
            }
        }

        switch ($method) {
            case 'refresh':
                header('Refresh:0;url=' . $uri);
                break;
            default:
                header('Location: ' . $uri, TRUE, $code);
                break;
        }
        exit;
    }
}
