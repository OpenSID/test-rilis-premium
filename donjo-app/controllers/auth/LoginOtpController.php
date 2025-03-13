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
use App\Libraries\OTP\OtpManager;
use App\Models\User;

class LoginOtpController extends MY_Controller
{
    private OtpManager $otp;

    public function __construct()
    {
        parent::__construct();

        $this->latar_login = default_file(LATAR_LOGIN . setting('latar_login'), DEFAULT_LATAR_SITEMAN);
        $this->header      = collect(identitas())->toArray();
        $this->otp = new OtpManager();
    }

    /**
     * Display the password reset view.
     *
     * @param mixed $token
     */
    public function create()
    {
        return view('admin.auth.login_otp', [
            'header'      => $this->header,
            'latar_login' => $this->latar_login,
            'logo_bsre'   => default_file(LOGO_BSRE, false)
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $request = request();
        $otp  = $request->token_email;
        $user = auth('admin')->user()->id;
        $nama = auth('admin')->user()->nama;
        // TODO: OpenKab - Perlu disesuaikan ulang setelah semua modul selesai
        $email = User::find($user)->email;

        if ($this->otp->driver('emailLogin')->verifikasiOtp($otp, $user)) {
            redirect('siteman');
        }

        set_session('notif', 'Tidak berhasil melakukan verifikasi, Token tidak sesuai atau waktu Anda habis, silakan mencoba kembali.');

        redirect('siteman/login_otp');
    
    }
}
