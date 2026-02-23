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

use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->tambahUniqueArtikel();
        $this->tambahKolomIdKelompokDokumen();
        $this->updateViewDokumenHidup();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    public function tambahUniqueArtikel(): void
    {
        try {
            if (Schema::hasTable('artikel') && ! Schema::hasIndex('artikel', 'artikel_unique_judul_config')) {
                Schema::table('artikel', static function (Blueprint $table): void {
                    $table->unique(['judul', 'config_id'], 'artikel_unique_judul_config');
                });
            }
        } catch (Exception $e) {
            log_message('error', 'Gagal menambahkan unique constraint pada tabel artikel: ' . $e->getMessage());
        }
    }

    public function tambahKolomIdKelompokDokumen(): void
    {
        try {
            if (Schema::hasTable('dokumen') && ! Schema::hasColumn('dokumen', 'id_kelompok')) {
                Schema::table('dokumen', static function (Blueprint $table): void {
                    $table->unsignedBigInteger('id_kelompok')->nullable()->after('config_id');

                    $table->foreign('id_kelompok')
                        ->references('id')
                        ->on('kelompok')
                        ->onDelete('cascade');
                });
            }
        } catch (Exception $e) {
            log_message('error', 'Gagal menambahkan kolom id_kelompok pada tabel dokumen: ' . $e->getMessage());
        }
    }

    public function updateViewDokumenHidup(): void
    {
        try {
            DB::statement('DROP VIEW IF EXISTS `dokumen_hidup`');
            DB::statement('CREATE VIEW `dokumen_hidup` AS select `dokumen`.`id` AS `id`,`dokumen`.`config_id` AS `config_id`,`dokumen`.`satuan` AS `satuan`,`dokumen`.`nama` AS `nama`,`dokumen`.`enabled` AS `enabled`,`dokumen`.`tgl_upload` AS `tgl_upload`,`dokumen`.`id_pend` AS `id_pend`,`dokumen`.`kategori` AS `kategori`,`dokumen`.`attr` AS `attr`,`dokumen`.`tipe` AS `tipe`,`dokumen`.`url` AS `url`,`dokumen`.`tahun` AS `tahun`,`dokumen`.`kategori_info_publik` AS `kategori_info_publik`,`dokumen`.`updated_at` AS `updated_at`,`dokumen`.`deleted` AS `deleted`,`dokumen`.`id_syarat` AS `id_syarat`,`dokumen`.`id_parent` AS `id_parent`,`dokumen`.`created_at` AS `created_at`,`dokumen`.`created_by` AS `created_by`,`dokumen`.`updated_by` AS `updated_by`,`dokumen`.`dok_warga` AS `dok_warga`,`dokumen`.`lokasi_arsip` AS `lokasi_arsip`,`dokumen`.`keterangan` AS `keterangan`,`dokumen`.`status` AS `status`,`dokumen`.`retensi_date` AS `retensi_date`,`dokumen`.`retensi_number` AS `retensi_number`,`dokumen`.`retensi_unit` AS `retensi_unit`,`dokumen`.`published_at` AS `published_at`,`dokumen`.`id_kelompok` AS `id_kelompok` from `dokumen` where `dokumen`.`deleted` <> 1');
        } catch (Exception $e) {
            log_message('error', 'Gagal update view dokumen_hidup: ' . $e->getMessage());
        }
    }
};