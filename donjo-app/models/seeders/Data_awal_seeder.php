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

use App\Models\Config;
use Illuminate\Support\Facades\DB;
use Database\Seeders\DatabaseSeeder;
use App\Imports\KlasifikasiSuratImports;

defined('BASEPATH') || exit('No direct script access allowed');

class Data_awal_seeder extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '512M');
        set_time_limit(5400);
    }

    public function run()
    {

        $this->load->helper('directory');
        $directoryTable = 'donjo-app/models/migrations/struktur_tabel';
        $migrations     = directory_map($directoryTable, 1);
        // sort by name
        usort($migrations, static fn ($a, $b) => strcmp($a, $b));

        foreach ($migrations as $migrate) {
            $migrateFile = require $directoryTable . DIRECTORY_SEPARATOR . $migrate;
            $migrateFile->up();
        }

        // Panggil DatabaseSeeder
        (new DatabaseSeeder())->run();
        
        $this->addDataMaster();
    }

    private function addDataMaster()
    {
        

        

        

        

        

        

        

        

        

        

        

        

        
        

        
        

        

        

        

        

        

        // DB::table('tweb_penduduk_umur')->insert(); ikut data awal

        

        

        

        

        

        DB::table('ref_dokumen')->insert([
            ['id' => 1, 'nama' => 'Informasi Publik'],
            ['id' => 2, 'nama' => 'SK Kades'],
            ['id' => 3, 'nama' => 'Perdes'],
        ]);

        DB::table('ref_asal_tanah_kas')->insert([
            ['id' => 1, 'nama' => 'Jual Beli'],
            ['id' => 2, 'nama' => 'Hibah / Sumbangan'],
            ['id' => 3, 'nama' => 'Lain - lain'],
        ]);

        DB::table('ref_peruntukan_tanah_kas')->insert([
            ['id' => 1, 'nama' => 'Sewa'],
            ['id' => 2, 'nama' => 'Pinjam Pakai'],
            ['id' => 3, 'nama' => 'Kerjasama Pemanfaatan'],
            ['id' => 4, 'nama' => 'Bangun Guna Serah atau Bangun Serah Guna'],
        ]);

        DB::table('keuangan_manual_ref_bidang')->insert([
            [
                'id'          => 1,
                'Kd_Bid'      => '00.0000.01',
                'Nama_Bidang' => 'BIDANG PENYELENGGARAN PEMERINTAHAN DESA',
            ],
            [
                'id'          => 2,
                'Kd_Bid'      => '00.0000.02',
                'Nama_Bidang' => 'BIDANG PELAKSANAAN PEMBANGUNAN DESA',
            ],
            [
                'id'          => 3,
                'Kd_Bid'      => '00.0000.03',
                'Nama_Bidang' => 'BIDANG PEMBINAAN KEMASYARAKATAN DESA',
            ],
            [
                'id'          => 4,
                'Kd_Bid'      => '00.0000.04',
                'Nama_Bidang' => 'BIDANG PEMBERDAYAAN MASYARAKAT DESA',
            ],
            [
                'id'          => 5,
                'Kd_Bid'      => '00.0000.05',
                'Nama_Bidang' => 'BIDANG PENANGGULANGAN BENCANA, DARURAT DAN MENDESAK DESA',
            ],
        ]);

        DB::table('keuangan_manual_ref_rek1')->insert([
            ['id' => 1, 'Akun' => '1.', 'Nama_Akun' => 'ASET'],
            ['id' => 2, 'Akun' => '2.', 'Nama_Akun' => 'KEWAJIBAN'],
            ['id' => 3, 'Akun' => '3.', 'Nama_Akun' => 'EKUITAS'],
            ['id' => 4, 'Akun' => '4.', 'Nama_Akun' => 'PENDAPATAN'],
            ['id' => 5, 'Akun' => '5.', 'Nama_Akun' => 'BELANJA'],
            ['id' => 6, 'Akun' => '6.', 'Nama_Akun' => 'PEMBIAYAAN'],
            ['id' => 7, 'Akun' => '7.', 'Nama_Akun' => 'NON ANGGARAN'],
        ]);

        DB::table('keuangan_manual_ref_rek2')->insert(
            [
                [
                    'id'            => 1,
                    'Akun'          => '1.',
                    'Kelompok'      => '1.1.',
                    'Nama_Kelompok' => 'Aset Lancar',
                ],
                [
                    'id'            => 2,
                    'Akun'          => '1.',
                    'Kelompok'      => '1.2.',
                    'Nama_Kelompok' => 'Investasi',
                ],
                [
                    'id'            => 3,
                    'Akun'          => '1.',
                    'Kelompok'      => '1.3.',
                    'Nama_Kelompok' => 'Aset Tetap',
                ],
                [
                    'id'            => 4,
                    'Akun'          => '1.',
                    'Kelompok'      => '1.4.',
                    'Nama_Kelompok' => 'Dana Cadangan',
                ],
                [
                    'id'            => 5,
                    'Akun'          => '1.',
                    'Kelompok'      => '1.5.',
                    'Nama_Kelompok' => 'Aset Tidak Lancar Lainnya',
                ],
                [
                    'id'            => 6,
                    'Akun'          => '2.',
                    'Kelompok'      => '2.1.',
                    'Nama_Kelompok' => 'Kewajiban Jangka Pendek',
                ],
                [
                    'id'            => 7,
                    'Akun'          => '3.',
                    'Kelompok'      => '3.1.',
                    'Nama_Kelompok' => 'Ekuitas',
                ],
                [
                    'id'            => 8,
                    'Akun'          => '4.',
                    'Kelompok'      => '4.1.',
                    'Nama_Kelompok' => 'Pendapatan Asli Desa',
                ],
                [
                    'id'            => 9,
                    'Akun'          => '4.',
                    'Kelompok'      => '4.2.',
                    'Nama_Kelompok' => 'Pendapatan Transfer',
                ],
                [
                    'id'            => 10,
                    'Akun'          => '4.',
                    'Kelompok'      => '4.3.',
                    'Nama_Kelompok' => 'Pendapatan Lain-lain',
                ],
                [
                    'id'            => 11,
                    'Akun'          => '5.',
                    'Kelompok'      => '5.1.',
                    'Nama_Kelompok' => 'Belanja Pegawai',
                ],
                [
                    'id'            => 12,
                    'Akun'          => '5.',
                    'Kelompok'      => '5.2.',
                    'Nama_Kelompok' => 'Belanja Barang dan Jasa',
                ],
                [
                    'id'            => 13,
                    'Akun'          => '5.',
                    'Kelompok'      => '5.3.',
                    'Nama_Kelompok' => 'Belanja Modal',
                ],
                [
                    'id'            => 14,
                    'Akun'          => '5.',
                    'Kelompok'      => '5.4.',
                    'Nama_Kelompok' => 'Belanja Tidak Terduga',
                ],
                [
                    'id'            => 15,
                    'Akun'          => '6.',
                    'Kelompok'      => '6.1.',
                    'Nama_Kelompok' => 'Penerimaan Pembiayaan',
                ],
                [
                    'id'            => 16,
                    'Akun'          => '6.',
                    'Kelompok'      => '6.2.',
                    'Nama_Kelompok' => 'Pengeluaran Pembiayaan',
                ],
                [
                    'id'            => 17,
                    'Akun'          => '7.',
                    'Kelompok'      => '7.1.',
                    'Nama_Kelompok' => 'Perhitungan Fihak Ketiga',
                ],
            ]
        );

        DB::table('keuangan_manual_ref_rek3')->insert(
            [
                [
                    'id'         => 1,
                    'Kelompok'   => '1.1.',
                    'Jenis'      => '1.1.1.',
                    'Nama_Jenis' => 'Kas dan Bank',
                ],
                [
                    'id'         => 2,
                    'Kelompok'   => '1.1.',
                    'Jenis'      => '1.1.2.',
                    'Nama_Jenis' => 'Piutang',
                ],
                [
                    'id'         => 3,
                    'Kelompok'   => '1.1.',
                    'Jenis'      => '1.1.3.',
                    'Nama_Jenis' => 'Persediaan',
                ],
                [
                    'id'         => 4,
                    'Kelompok'   => '1.2.',
                    'Jenis'      => '1.2.1.',
                    'Nama_Jenis' => 'Penyertaan Modal Pemerintah Desa',
                ],
                [
                    'id'         => 5,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.1.',
                    'Nama_Jenis' => 'Tanah',
                ],
                [
                    'id'         => 6,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.2.',
                    'Nama_Jenis' => 'Peralatan dan Mesin',
                ],
                [
                    'id'         => 7,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.3.',
                    'Nama_Jenis' => 'Gedung dan Bangunan',
                ],
                [
                    'id'         => 8,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.4.',
                    'Nama_Jenis' => 'Jalan, Irigasi dan Jaringan',
                ],
                [
                    'id'         => 9,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.5.',
                    'Nama_Jenis' => 'Aset Tetap Lainnya',
                ],
                [
                    'id'         => 10,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.6.',
                    'Nama_Jenis' => 'Konstruksi Dalam Pengerjaan',
                ],
                [
                    'id'         => 11,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.7.',
                    'Nama_Jenis' => 'Aset Tak Berwujud',
                ],
                [
                    'id'         => 12,
                    'Kelompok'   => '1.3.',
                    'Jenis'      => '1.3.8.',
                    'Nama_Jenis' => 'Akumulasi Penyusutan Aktiva Tetap',
                ],
                [
                    'id'         => 13,
                    'Kelompok'   => '1.4.',
                    'Jenis'      => '1.4.1.',
                    'Nama_Jenis' => 'Dana Cadangan',
                ],
                [
                    'id'         => 14,
                    'Kelompok'   => '1.5.',
                    'Jenis'      => '1.5.1.',
                    'Nama_Jenis' => 'Tagihan Piutang Penjualan Angsuran',
                ],
                [
                    'id'         => 15,
                    'Kelompok'   => '1.5.',
                    'Jenis'      => '1.5.2.',
                    'Nama_Jenis' => 'Tagihan Tuntutan Ganti Kerugian Daerah',
                ],
                [
                    'id'         => 16,
                    'Kelompok'   => '1.5.',
                    'Jenis'      => '1.5.3.',
                    'Nama_Jenis' => 'Kemitraan dengan Pihak Ketiga',
                ],
                [
                    'id'         => 17,
                    'Kelompok'   => '1.5.',
                    'Jenis'      => '1.5.4.',
                    'Nama_Jenis' => 'Aktiva Tidak Berwujud',
                ],
                [
                    'id'         => 18,
                    'Kelompok'   => '1.5.',
                    'Jenis'      => '1.5.5.',
                    'Nama_Jenis' => 'Aset Lain-lain',
                ],
                [
                    'id'         => 19,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.1.',
                    'Nama_Jenis' => 'Hutang Perhitungan Pihak Ketiga',
                ],
                [
                    'id'         => 20,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.2.',
                    'Nama_Jenis' => 'Hutang Bunga',
                ],
                [
                    'id'         => 21,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.3.',
                    'Nama_Jenis' => 'Hutang Pajak',
                ],
                [
                    'id'         => 22,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.4.',
                    'Nama_Jenis' => 'Pendapatan Diterima Dimuka',
                ],
                [
                    'id'         => 23,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.5.',
                    'Nama_Jenis' => 'Bagian Lancar Hutang Jangka Panjang',
                ],
                [
                    'id'         => 24,
                    'Kelompok'   => '2.1.',
                    'Jenis'      => '2.1.6.',
                    'Nama_Jenis' => 'Hutang Jangka Pendek Lainnya',
                ],
                [
                    'id'         => 25,
                    'Kelompok'   => '3.1.',
                    'Jenis'      => '3.1.1.',
                    'Nama_Jenis' => 'Ekuitas',
                ],
                [
                    'id'         => 26,
                    'Kelompok'   => '3.1.',
                    'Jenis'      => '3.1.2.',
                    'Nama_Jenis' => 'Ekuitas SAL',
                ],
                [
                    'id'         => 27,
                    'Kelompok'   => '4.1.',
                    'Jenis'      => '4.1.1.',
                    'Nama_Jenis' => 'Hasil Usaha Desa',
                ],
                [
                    'id'         => 28,
                    'Kelompok'   => '4.1.',
                    'Jenis'      => '4.1.2.',
                    'Nama_Jenis' => 'Hasil Aset Desa',
                ],
                [
                    'id'         => 29,
                    'Kelompok'   => '4.1.',
                    'Jenis'      => '4.1.3.',
                    'Nama_Jenis' => 'Swadaya, Partisipasi dan Gotong Royong',
                ],
                [
                    'id'         => 30,
                    'Kelompok'   => '4.1.',
                    'Jenis'      => '4.1.4.',
                    'Nama_Jenis' => 'Lain-Lain Pendapatan Asli Desa',
                ],
                [
                    'id'         => 31,
                    'Kelompok'   => '4.2.',
                    'Jenis'      => '4.2.1.',
                    'Nama_Jenis' => 'Dana Desa',
                ],
                [
                    'id'         => 32,
                    'Kelompok'   => '4.2.',
                    'Jenis'      => '4.2.2.',
                    'Nama_Jenis' => 'Bagi Hasil Pajak dan Retribusi',
                ],
                [
                    'id'         => 33,
                    'Kelompok'   => '4.2.',
                    'Jenis'      => '4.2.3.',
                    'Nama_Jenis' => 'Alokasi Dana Desa',
                ],
                [
                    'id'         => 34,
                    'Kelompok'   => '4.2.',
                    'Jenis'      => '4.2.4.',
                    'Nama_Jenis' => 'Bantuan Keuangan Provinsi',
                ],
                [
                    'id'         => 35,
                    'Kelompok'   => '4.2.',
                    'Jenis'      => '4.2.5.',
                    'Nama_Jenis' => 'Bantuan Keuangan Kabupaten/Kota',
                ],
                [
                    'id'         => 36,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.1.',
                    'Nama_Jenis' => 'Penerimaan dari Hasil Kerjasama Antar Desa',
                ],
                [
                    'id'         => 37,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.2.',
                    'Nama_Jenis' => 'Penerimaan dari Hasil Kerjasama dengan Pihak Ketiga',
                ],
                [
                    'id'         => 38,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.3.',
                    'Nama_Jenis' => 'Penerimaan Bantuan dari Perusahaan yang Berlokasi di Desa',
                ],
                [
                    'id'         => 39,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.4.',
                    'Nama_Jenis' => 'Hibah dan Sumbangan dari Pihak Ketiga',
                ],
                [
                    'id'         => 40,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.5.',
                    'Nama_Jenis' => 'Koreksi Kesalahan Belanja Tahun-tahun Sebelumnya',
                ],
                [
                    'id'         => 41,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.6.',
                    'Nama_Jenis' => 'Bunga Bank',
                ],
                [
                    'id'         => 42,
                    'Kelompok'   => '4.3.',
                    'Jenis'      => '4.3.9.',
                    'Nama_Jenis' => 'Lain-lain Pendapatan Desa Yang Sah',
                ],
                [
                    'id'         => 43,
                    'Kelompok'   => '5.1.',
                    'Jenis'      => '5.1.1.',
                    'Nama_Jenis' => 'Penghasilan Tetap dan Tunjangan Kepala Desa',
                ],
                [
                    'id'         => 44,
                    'Kelompok'   => '5.1.',
                    'Jenis'      => '5.1.2.',
                    'Nama_Jenis' => 'Penghasilan Tetap dan Tunjangan Perangkat Desa',
                ],
                [
                    'id'         => 45,
                    'Kelompok'   => '5.1.',
                    'Jenis'      => '5.1.3.',
                    'Nama_Jenis' => 'Jaminan Sosial Kepala Desa dan Perangkat Desa',
                ],
                [
                    'id'         => 46,
                    'Kelompok'   => '5.1.',
                    'Jenis'      => '5.1.4.',
                    'Nama_Jenis' => 'Tunjangan BPD',
                ],
                [
                    'id'         => 47,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.1.',
                    'Nama_Jenis' => 'Belanja Barang Perlengkapan',
                ],
                [
                    'id'         => 48,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.2.',
                    'Nama_Jenis' => 'Belanja Jasa Honorarium',
                ],
                [
                    'id'         => 49,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.3.',
                    'Nama_Jenis' => 'Belanja Perjalanan Dinas',
                ],
                [
                    'id'         => 50,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.4.',
                    'Nama_Jenis' => 'Belanja Jasa Sewa',
                ],
                [
                    'id'         => 51,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.5.',
                    'Nama_Jenis' => 'Belanja Operasional Perkantoran',
                ],
                [
                    'id'         => 52,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.6.',
                    'Nama_Jenis' => 'Belanja Pemeliharaan',
                ],
                [
                    'id'         => 53,
                    'Kelompok'   => '5.2.',
                    'Jenis'      => '5.2.7.',
                    'Nama_Jenis' => 'Belanja Barang dan Jasa yang Diserahkan kepada Masyarakat',
                ],
                [
                    'id'         => 54,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.1.',
                    'Nama_Jenis' => 'Belanja Modal Pengadaan Tanah',
                ],
                [
                    'id'         => 55,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.2.',
                    'Nama_Jenis' => 'Belanja Modal Pengadaan Peralatan, Mesin dan Alat Berat',
                ],
                [
                    'id'         => 56,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.3.',
                    'Nama_Jenis' => 'Belanja Modal Kendaraan',
                ],
                [
                    'id'         => 57,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.4.',
                    'Nama_Jenis' => 'Belanja Modal Gedung, Bangunan dan Taman',
                ],
                [
                    'id'         => 58,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.5.',
                    'Nama_Jenis' => 'Belanja Modal Jalan/Prasarana Jalan',
                ],
                [
                    'id'         => 59,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.6.',
                    'Nama_Jenis' => 'Belanja Modal Jembatan',
                ],
                [
                    'id'         => 60,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.7.',
                    'Nama_Jenis' => 'Belanja Modal Irigasi/Embung/Drainase/Air Limbah/Persampahan',
                ],
                [
                    'id'         => 61,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.8.',
                    'Nama_Jenis' => 'Belanja Modal Jaringan/Instalasi',
                ],
                [
                    'id'         => 62,
                    'Kelompok'   => '5.3.',
                    'Jenis'      => '5.3.9.',
                    'Nama_Jenis' => 'Belanja Modal Lainnya',
                ],
                [
                    'id'         => 63,
                    'Kelompok'   => '5.4.',
                    'Jenis'      => '5.4.1.',
                    'Nama_Jenis' => 'Belanja Tidak Terduga',
                ],
                [
                    'id'         => 64,
                    'Kelompok'   => '6.1.',
                    'Jenis'      => '6.1.1.',
                    'Nama_Jenis' => 'SILPA Tahun Sebelumnya',
                ],
                [
                    'id'         => 65,
                    'Kelompok'   => '6.1.',
                    'Jenis'      => '6.1.2.',
                    'Nama_Jenis' => 'Pencairan Dana Cadangan',
                ],
                [
                    'id'         => 66,
                    'Kelompok'   => '6.1.',
                    'Jenis'      => '6.1.3.',
                    'Nama_Jenis' => 'Hasil Penjualan Kekayaan Desa Yang Dipisahkan',
                ],
                [
                    'id'         => 67,
                    'Kelompok'   => '6.1.',
                    'Jenis'      => '6.1.9.',
                    'Nama_Jenis' => 'Penerimaan Pembiayaan Lainnya',
                ],
                [
                    'id'         => 68,
                    'Kelompok'   => '6.2.',
                    'Jenis'      => '6.2.1.',
                    'Nama_Jenis' => 'Pembentukan Dana Cadangan',
                ],
                [
                    'id'         => 69,
                    'Kelompok'   => '6.2.',
                    'Jenis'      => '6.2.2.',
                    'Nama_Jenis' => 'Penyertaan Modal Desa',
                ],
                [
                    'id'         => 70,
                    'Kelompok'   => '6.2.',
                    'Jenis'      => '6.2.9.',
                    'Nama_Jenis' => 'Pengeluaran Pembiayaan Lainnya',
                ],
                [
                    'id'         => 71,
                    'Kelompok'   => '7.1.',
                    'Jenis'      => '7.1.1.',
                    'Nama_Jenis' => 'Perhitungan PFK - Potongan Pajak',
                ],
                [
                    'id'         => 72,
                    'Kelompok'   => '7.1.',
                    'Jenis'      => '7.1.2.',
                    'Nama_Jenis' => 'Perhitungan PFK - Potongan Pajak Daerah',
                ],
                [
                    'id'         => 73,
                    'Kelompok'   => '7.1.',
                    'Jenis'      => '7.1.3.',
                    'Nama_Jenis' => 'Perhitungan PFK - Uang Muka dan Jaminan',
                ],
            ]
        );

        DB::table('ref_sinkronisasi')->insert([
            [
                'tabel'        => 'tweb_keluarga',
                'server'       => '6',
                'jenis_update' => 1,
                'tabel_hapus'  => 'log_keluarga',
            ],
            [
                'tabel'        => 'tweb_penduduk',
                'server'       => '6',
                'jenis_update' => 1,
                'tabel_hapus'  => 'log_hapus_penduduk',
            ],
        ]);

        // DB::table('notifikasi')->insert(); ikut data awal

        DB::table('tweb_keluarga_sejahtera')->insert([
            ['id' => 1, 'nama' => 'Keluarga Pra Sejahtera'],
            ['id' => 2, 'nama' => 'Keluarga Sejahtera I'],
            ['id' => 3, 'nama' => 'Keluarga Sejahtera II'],
            ['id' => 4, 'nama' => 'Keluarga Sejahtera III'],
            ['id' => 5, 'nama' => 'Keluarga Sejahtera III Plus'],
        ]);

        $this->load->model('seeders/dataAwal/Twebaset', 'twebaset');
        $this->load->model('seeders/dataAwal/KeuanganManualRefKegiatan', 'keuanganRefKegiatan');
        $this->load->model('seeders/dataAwal/PendudukSuku', 'pendudukSuku');
        DB::table('tweb_aset')->insert($this->twebaset->getData());
        DB::table('keuangan_manual_ref_kegiatan')->insert($this->keuanganRefKegiatan->getData());
        $this->impor_klasifikasi();
        DB::table('ref_penduduk_suku')->insert($this->pendudukSuku->getData());
        // DB::table('tweb_format_surat')->insert(); ikut data awal
    }

    public function impor_klasifikasi()
    {
        (new KlasifikasiSuratImports())->import();
    }
}
