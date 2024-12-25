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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class KeuanganManualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('keuangan_manual_rinci_tpl')->truncate();

        $data = [
            ['id' => 1, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.1.1. Hasil Usaha Desa', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 2, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.1.2. Hasil Aset Desa', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 3, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.1.3. Swadaya, Partisipasi dan Gotong Royong', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 4, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.1.4. Lain-Lain Pendapatan Asli Desa', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 5, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.2.1. Dana Desa', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 6, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.2.2. Bagi Hasil Pajak dan Retribusi', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 7, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.2.3. Alokasi Dana Desa', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 8, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.2.4. Bantuan Keuangan Provinsi', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 9, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.2.5. Bantuan Keuangan Kabupaten/Kota', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 10, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.1. Penerimaan dari Hasil Kerjasama Antar Desa', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 11, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.2. Penerimaan dari Hasil Kerjasama dengan Pihak Ketiga', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 12, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.3. Penerimaan Bantuan dari Perusahaan yang Berlokasi di Desa', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 13, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.4. Hibah dan Sumbangan dari Pihak Ketiga', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 14, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.5. Koreksi Kesalahan Belanja Tahun-tahun Sebelumnya', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 15, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.6. Bunga Bank', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 16, 'Tahun' => '2020', 'Kd_Akun' => '4.PENDAPATAN', 'Kd_Keg' => '', 'Kd_Rincian' => '4.3.9. Lain-lain Pendapatan Desa Yang Sah', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 17, 'Tahun' => '2020', 'Kd_Akun' => '5.BELANJA', 'Kd_Keg' => '00.0000.01 BIDANG PENYELENGGARAN PEMERINTAHAN DESA', 'Kd_Rincian' => '5.0.0', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 18, 'Tahun' => '2020', 'Kd_Akun' => '5.BELANJA', 'Kd_Keg' => '00.0000.02 BIDANG PELAKSANAAN PEMBANGUNAN DESA', 'Kd_Rincian' => '5.0.0', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 19, 'Tahun' => '2020', 'Kd_Akun' => '5.BELANJA', 'Kd_Keg' => '00.0000.03 BIDANG PEMBINAAN KEMASYARAKATAN DESA', 'Kd_Rincian' => '5.0.0', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 20, 'Tahun' => '2020', 'Kd_Akun' => '5.BELANJA', 'Kd_Keg' => '00.0000.04 BIDANG PEMBERDAYAAN MASYARAKAT DESA', 'Kd_Rincian' => '5.0.0', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 21, 'Tahun' => '2020', 'Kd_Akun' => '5.BELANJA', 'Kd_Keg' => '00.0000.05 BIDANG PENANGGULANGAN BENCANA, DARURAT DAN MENDESAK DESA', 'Kd_Rincian' => '5.0.0', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 22, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.1.1. SILPA Tahun Sebelumnya', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 23, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.1.2. Pencairan Dana Cadangan', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 24, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.1.3. Hasil Penjualan Kekayaan Desa Yang Dipisahkan', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 25, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.1.9. Penerimaan Pembiayaan Lainnya', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 26, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.2.1. Pembentukan Dana Cadangan', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 27, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.2.2. Penyertaan Modal Desa', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
            ['id' => 28, 'Tahun' => '2020', 'Kd_Akun' => '6.PEMBIAYAAN', 'Kd_Keg' => '', 'Kd_Rincian' => '6.2.9. Pengeluaran Pembiayaan Lainnya', 'Nilai_Anggaran' => '0', 'Nilai_Realisasi' => '0'],
        ];

        DB::table('keuangan_manual_rinci_tpl')->insert($data);
    }
}

