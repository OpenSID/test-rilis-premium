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

use Carbon\Carbon;
use App\Models\Modul;
use App\Models\Config;
use App\Models\UserGrup;
use App\Traits\Migrator;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Data_awal extends MY_Model
{
    use Migrator;
    
    public function up()
    {
        $hasil = true;

        cache()->forget('identitas_desa');

        // Ubah config
        $hasil = $hasil && $this->isi_config($hasil);

        // Pengaturan Aplikasi
        $hasil = $hasil && $this->tambah_pengaturan_aplikasi($hasil);

        // Tambah Modul
        $hasil = $hasil && $this->tambah_modul($hasil);

        // Klasifikasi Surat
        // $hasil = $hasil && $this->tambah_klasifikasi_surat($hasil);

        // Template Surat
        $hasil = $hasil && $this->tambah_template_surat($hasil);

        // Statistik - Umur
        $hasil = $hasil && $this->tambah_rentang_umur($hasil);

        // Keuangan Manual
        return $hasil && $this->keuangan_manual($hasil);
    }

    // Tambah rentang umum pada tabel tweb_penduduk_umur
    protected function tambah_rentang_umur($hasil)
    {
        $this->load->model('seeders/dataAwal/RentangUmur', 'rentangUmur');
        $data = $this->rentangUmur->getData();

        return $hasil && $this->data_awal('tweb_penduduk_umur', $data);
    }

    // Tambah syarat surat pada tabel surat
    public function tambah_modul($hasil): bool
    {
        $this->load->model('seeders/dataAwal/SettingModul', 'settingModul');
        $data   = $this->settingModul->getData();
        $parent = [
            '2'   => 'kependudukan',
            '3'   => 'statistik',
            '4'   => 'layanan-surat',
            '5'   => 'analisis',
            '6'   => 'bantuan',
            '7'   => 'pertanahan',
            '9'   => 'pemetaan',
            '10'  => 'hubung-warga',
            '11'  => 'pengaturan',
            '13'  => 'admin-web',
            '14'  => 'layanan-mandiri',
            '15'  => 'sekretariat',
            '200' => 'info-desa',
            '201' => 'keuangan',
            '206' => 'kesehatan',
            '220' => 'pembangunan',
            '301' => 'buku-administrasi-desa',
            '312' => 'anjungan',
            '324' => 'lapak',
            '334' => 'pengaduan',
            '337' => 'kehadiran',
            '343' => 'opendk',
            '352' => 'satu-data',
            '354' => 'buku-tamu',
        ];
        // jika parent belum ada maka tambahkan dulu
        $cekParent = DB::table('setting_modul')->where(['slug' => 'kependudukan', 'config_id' => $this->config_id])->count();
        if (! $cekParent) {
            $slugParent = implode("','", $parent);
            DB::statement("
                insert into setting_modul (config_id, modul, slug, url, aktif, ikon, urut, `level`, hidden , ikon_kecil , parent)
                select {$this->config_id}, modul, slug, url, aktif, ikon, urut, `level`, hidden , ikon_kecil , parent  from setting_modul where config_id = 1 and slug in ('{$slugParent}')
            ");
        }
        $hasil = $hasil && $this->data_awal('setting_modul', $data);

        foreach ($parent as $key => $value) {
            DB::table('setting_modul')->where('id', $key)->update(['slug' => $value]);

            // Cari parent_id
            $parent_id = DB::table('setting_modul')->where('config_id', $this->config_id)->where('slug', $value)->value('id');

            // Update parent submodul
            DB::table('setting_modul')->where('config_id', $this->config_id)->where('parent', $key)->update(['parent' => $parent_id]);
        }

        return $hasil;
    }

    // Keuangan Manual
    protected function keuangan_manual($hasil)
    {
        //insert keuangan_manual_rinci_tpl
        $this->db->truncate('keuangan_manual_rinci_tpl');
        $query = "INSERT INTO `keuangan_manual_rinci_tpl` (`id`, `Tahun`, `Kd_Akun`, `Kd_Keg`, `Kd_Rincian`, `Nilai_Anggaran`, `Nilai_Realisasi`) VALUES
            (1, '2020', '4.PENDAPATAN', '', '4.1.1. Hasil Usaha Desa', '0', '0'),
            (2, '2020', '4.PENDAPATAN', '', '4.1.2. Hasil Aset Desa', '0', '0'),
            (3, '2020', '4.PENDAPATAN', '', '4.1.3. Swadaya, Partisipasi dan Gotong Royong', '0', '0'),
            (4, '2020', '4.PENDAPATAN', '', '4.1.4. Lain-Lain Pendapatan Asli Desa', '0', '0'),
            (5, '2020', '4.PENDAPATAN', '', '4.2.1. Dana Desa', '0', '0'),
            (6, '2020', '4.PENDAPATAN', '', '4.2.2. Bagi Hasil Pajak dan Retribusi', '0', '0'),
            (7, '2020', '4.PENDAPATAN', '', '4.2.3. Alokasi Dana Desa', '0', '0'),
            (8, '2020', '4.PENDAPATAN', '', '4.2.4. Bantuan Keuangan Provinsi', '0', '0'),
            (9, '2020', '4.PENDAPATAN', '', '4.2.5. Bantuan Keuangan Kabupaten/Kota', '0', '0'),
            (10, '2020', '4.PENDAPATAN', '', '4.3.1. Penerimaan dari Hasil Kerjasama Antar Desa', '0', '0'),
            (11, '2020', '4.PENDAPATAN', '', '4.3.2. Penerimaan dari Hasil Kerjasama dengan Pihak Ketiga', '0', '0'),
            (12, '2020', '4.PENDAPATAN', '', '4.3.3. Penerimaan Bantuan dari Perusahaan yang Berlokasi di Desa', '0', '0'),
            (13, '2020', '4.PENDAPATAN', '', '4.3.4. Hibah dan Sumbangan dari Pihak Ketiga', '0', '0'),
            (14, '2020', '4.PENDAPATAN', '', '4.3.5. Koreksi Kesalahan Belanja Tahun-tahun Sebelumnya', '0', '0'),
            (15, '2020', '4.PENDAPATAN', '', '4.3.6. Bunga Bank', '0', '0'),
            (16, '2020', '4.PENDAPATAN', '', '4.3.9. Lain-lain Pendapatan Desa Yang Sah', '0', '0'),
            (17, '2020', '5.BELANJA', '00.0000.01 BIDANG PENYELENGGARAN PEMERINTAHAN DESA', '5.0.0', '0', '0'),
            (18, '2020', '5.BELANJA', '00.0000.02 BIDANG PELAKSANAAN PEMBANGUNAN DESA', '5.0.0', '0', '0'),
            (19, '2020', '5.BELANJA', '00.0000.03 BIDANG PEMBINAAN KEMASYARAKATAN DESA', '5.0.0', '0', '0'),
            (20, '2020', '5.BELANJA', '00.0000.04 BIDANG PEMBERDAYAAN MASYARAKAT DESA', '5.0.0', '0', '0'),
            (21, '2020', '5.BELANJA', '00.0000.05 BIDANG PENANGGULANGAN BENCANA, DARURAT DAN MENDESAK DESA', '5.0.0', '0', '0'),
            (22, '2020', '6.PEMBIAYAAN', '', '6.1.1. SILPA Tahun Sebelumnya', '0', '0'),
            (23, '2020', '6.PEMBIAYAAN', '', '6.1.2. Pencairan Dana Cadangan', '0', '0'),
            (24, '2020', '6.PEMBIAYAAN', '', '6.1.3. Hasil Penjualan Kekayaan Desa Yang Dipisahkan', '0', '0'),
            (25, '2020', '6.PEMBIAYAAN', '', '6.1.9. Penerimaan Pembiayaan Lainnya', '0', '0'),
            (26, '2020', '6.PEMBIAYAAN', '', '6.2.1. Pembentukan Dana Cadangan', '0', '0'),
            (27, '2020', '6.PEMBIAYAAN', '', '6.2.2. Penyertaan Modal Desa', '0', '0'),
            (28, '2020', '6.PEMBIAYAAN', '', '6.2.9. Pengeluaran Pembiayaan Lainnya', '0', '0')";

        $this->db->query($query);

        return true;
    }
}
