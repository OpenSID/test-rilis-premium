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

namespace App\Listeners;

use App\Libraries\OTP\OtpManager;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Container\Container;

class Login2faAdminListener
{
    private OtpManager $otp;

    public function __construct(protected Container $app)
    {
        $this->otp = new OtpManager();
    }

    public function handle(Login $login): void
    {
        if (! in_array($login->guard, ['admin'])) {
            return;
        }

        //cek tfa aktif atau tidak, kalau aktif kirim email otp
        if ($login->user->tfa_enabled == 1) {
            $email   = $login->user->email;
            $token   = hash('sha256', $raw_token = random_int(100000, 999999));
            $id_user = $login->user->id;
            try {
                if ($this->otp->driver('emailLogin')->cekAkunTerdaftar(['email' => $email, 'id' => $id_user])) {
                    // TODO: OpenKab - Perlu disesuaikan ulang setelah semua modul selesai
                    User::where('id', $id_user)->update([
                        'email'                => $email,
                        'email_token'          => $token,
                        'email_tgl_kadaluarsa' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +5 minutes')),
                    ]);

                    $this->otp->driver('emailLogin')->kirimOtp($email, $raw_token);
                }
            } catch (\Exception $e) {}
        }
    }
}
