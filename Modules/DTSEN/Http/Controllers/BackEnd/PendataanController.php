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

use Modules\Template\Enums\TemplateEnum;
use Modules\Template\Libraries\TemplateLibrary;
use Modules\Template\Models\TemplateModel;
use Modules\Template\Services\TemplateService;
use Modules\Template\Traits\TemplateTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class PendataanController extends AdminModulController
{
    public $modul_ini           = 'dtsen';
    public $sub_modul_ini       = 'dtsen-pendataan';
    public $kategori_pengaturan = 'DTSEN';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    // Views
    public function index()
    {
        // dd('ini index');
        return view('dtsen::backend.pendataan.index');
    }

    public function form()
    {
        // dd('ini form');
        return view('dtsen::backend.pendataan.form');
    }

    public function storage()
    {
        $file = module_storage('dtsen', 'app/template.txt');

        // $file = FCPATH . 'Modules/DTSEN/Storage/app/template.txt';

        if (file_exists($file)) {
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            readfile($file);
        } else {
            show_error('File tidak ditemukan');
        }

        exit;
    }
}
