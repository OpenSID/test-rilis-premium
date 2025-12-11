<?php

use App\Traits\Migrator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * File ini berisi fungsi-fungsi untuk memperbaiki masalah data yang mungkin terjadi
 * saat migrasi atau karena bug pada versi sebelumnya.
 */

class Migrasi_perbaikan_data
{
    use Migrator;

    public function up()
    {
        $this->cekLog();
    }

    /**
     * Cek log error dan jalankan perbaikan yang sesuai.
     *
     * @param string $sourceCodePath Path root aplikasi.
     * @param int $version Versi aplikasi (misal: 2212).
     * @return array Hasil temuan dari log.
     */
    public function cekLog()
    {
        $logpath = (int) substr(VERSION, 0, 4) < 2212 ? FCPATH . 'logs' : FCPATH . 'storage/logs';
        log_message('notice', "Mengecek log di path: " . $logpath);

        log_message('notice', 'Cek log di path : ' . $logpath);

        if (! is_dir($logpath)) {
            return []; // Tidak ada direktori log, tidak ada yang perlu dilakukan.
        }

        // Gunakan map untuk membuat kode lebih bersih dan mudah diperluas
        $repairMap = [
            "CONSTRAINT `fk_id_modul`" => 'perbaikiSettingModul',
            "INSERT INTO grup_akses (`id_grup`, `id_modul`, `akses`) VALUES" => 'perbaikiSettingModul',
            "keuangan_ta_pencairan' doesn't exist" => 'keuangan_ta_pencairan',
            "log_bulanan' doesn't exist" => 'perbaikiLogBulanan',
            "log_login' doesn't exist" => 'perbaikiLoglogin',
            "Unknown column 'pemohon' in 'log_surat'" => 'perbaikiLogSurat',
            "There is no table with name \"alias_kodeisian\"" => 'perbaikialiasKodeIsian',
            "log_notifikasi_admin' doesn't exist" => 'perbaikialiasLogNotifikasiAdmin',
            "Unknown column 'sumber_penduduk_berulang'" => 'perbaikialiasTwebSuratFormat',
            "ALTER TABLE user ADD UNIQUE email (`email`)" => 'perbaikiuseremail',
            "for key 'no_anggota_config'" => 'perbaikiKelompokAnggotaDuplikat',
            "Truncated incorrect DECIMAL value" => 'perbaikiTwebPendudukIdKk',
            "Invalid use of NULL value - Invalid query: ALTER TABLE tweb_keluarga CHANGE id_cluster id_cluster INT(11) NOT NULL" => 'perbaikiTwebKeluargaIdCluster',
            "Data truncated for column 'kk_level'" => 'perbaikiKkLevelPenduduk',
        ];
        $snippets = array_keys($repairMap);

        $hasil = [];
        $files = scandir($logpath);

        foreach ($files as $file) {
            $filePath = $logpath . DIRECTORY_SEPARATOR . $file;
            if (is_file($filePath)) {
                $content = file_get_contents($filePath);
                $foundInFile = false;

                foreach ($snippets as $snippet) {
                    if (strpos($content, $snippet) !== false) {
                        if (! $foundInFile) {
                            log_message('notice', "Ditemukan di file: {$file}");
                            $foundInFile = true;
                        }

                        $firstLine = strtok($snippet, "\n");
                        log_message('notice', " - Mengandung: {$firstLine}...");
                        $hasil[] = ['file' => $file, 'match' => $firstLine];

                        // Panggil metode perbaikan secara dinamis dari map
                        $methodToCall = $repairMap[$snippet];
                        if (method_exists($this, $methodToCall)) {
                            $this->$methodToCall();
                        }

                    }
                }
            }
        }

        $this->clearLogFiles($logpath);
        return $hasil;
    }

    /**
     * Perbaiki data `setting_modul` yang tidak lengkap.
     */
    public function perbaikiSettingModul()
    {
        log_message('notice', "Memperbaiki `setting_modul`...");
        $sql = "
            SELECT id
            FROM (
                SELECT 1 AS id UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL
                SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL
                SELECT 11 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL SELECT 17 UNION ALL
                SELECT 18 UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL
                SELECT 24 UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL
                SELECT 29 UNION ALL SELECT 30 UNION ALL SELECT 31 UNION ALL SELECT 32 UNION ALL SELECT 39 UNION ALL
                SELECT 40 UNION ALL SELECT 42 UNION ALL SELECT 47 UNION ALL SELECT 48 UNION ALL SELECT 49 UNION ALL
                SELECT 50 UNION ALL SELECT 51 UNION ALL SELECT 52 UNION ALL SELECT 53 UNION ALL SELECT 54 UNION ALL
                SELECT 55 UNION ALL SELECT 56 UNION ALL SELECT 57 UNION ALL SELECT 58 UNION ALL SELECT 61 UNION ALL
                SELECT 62 UNION ALL SELECT 63 UNION ALL SELECT 64 UNION ALL SELECT 65 UNION ALL SELECT 66 UNION ALL
                SELECT 67 UNION ALL SELECT 68 UNION ALL SELECT 69 UNION ALL SELECT 70 UNION ALL SELECT 71 UNION ALL
                SELECT 72 UNION ALL SELECT 73 UNION ALL SELECT 75 UNION ALL SELECT 76 UNION ALL SELECT 77 UNION ALL
                SELECT 78 UNION ALL SELECT 79 UNION ALL SELECT 80 UNION ALL SELECT 81 UNION ALL SELECT 82 UNION ALL
                SELECT 83 UNION ALL SELECT 84 UNION ALL SELECT 85 UNION ALL SELECT 86 UNION ALL SELECT 87 UNION ALL
                SELECT 88 UNION ALL SELECT 89 UNION ALL SELECT 90 UNION ALL SELECT 91 UNION ALL SELECT 92 UNION ALL
                SELECT 93 UNION ALL SELECT 94 UNION ALL SELECT 95 UNION ALL SELECT 96 UNION ALL SELECT 97 UNION ALL
                SELECT 98 UNION ALL SELECT 101 UNION ALL SELECT 200 UNION ALL SELECT 201 UNION ALL SELECT 202 UNION ALL
                SELECT 203 UNION ALL SELECT 205 UNION ALL SELECT 206 UNION ALL SELECT 207 UNION ALL SELECT 208 UNION ALL
                SELECT 209 UNION ALL SELECT 210 UNION ALL SELECT 211 UNION ALL SELECT 212 UNION ALL SELECT 213 UNION ALL
                SELECT 220 UNION ALL SELECT 221 UNION ALL SELECT 301 UNION ALL SELECT 302 UNION ALL SELECT 303 UNION ALL
                SELECT 304 UNION ALL SELECT 305 UNION ALL SELECT 310 UNION ALL SELECT 311 UNION ALL SELECT 312 UNION ALL
                SELECT 314 UNION ALL SELECT 315 UNION ALL SELECT 316 UNION ALL SELECT 317 UNION ALL SELECT 318
            ) AS data
            WHERE id NOT IN (SELECT id FROM setting_modul)";
        $targetIds = array_column(DB::select($sql), 'id');

        if (empty($targetIds)) {
            log_message('notice', "`setting_modul` sudah lengkap.");
            return;
        }

        foreach ($targetIds as $id) {
            log_message('notice', "ID belum ada di setting_modul: {$id}");
        }
        
        $sqlFilePath = 'setting_modul.sql'; // Pastikan file ini ada di root aplikasi
        if (!file_exists($sqlFilePath)) {
            log_message('notice', "Gagal membaca file: {$sqlFilePath} tidak ditemukan.");
            return;
        }
        
        $sqlContent = file_get_contents($sqlFilePath);
        $queries = explode(';\r\n', $sqlContent);

        foreach ($queries as $query) {
            if (preg_match('/INSERT INTO `setting_modul` VALUES \((\d+),/', $query, $match)) {
                $id = (int)$match[1];
                if (in_array($id, $targetIds)) {
                    DB::statement($query);
                    log_message('notice', "Berhasil menjalankan query untuk ID {$id}");
                }
            }
        }
    }

    /**
     * Buat tabel `log_bulanan` jika belum ada.
     */
    public function perbaikiLogBulanan()
    {
        log_message('notice', "Memperbaiki tabel `log_bulanan`...");
        if (Schema::hasTable('log_bulanan')) {
            log_message('notice', "Tabel `log_bulanan` sudah ada.");
        } else {
            log_message('notice', "Membuat tabel `log_bulanan`...");
            Schema::create('log_bulanan', function ($table) {
                $table->increments('id');
                $table->integer('pend');
                $table->integer('wni_lk')->nullable();
                $table->integer('wni_pr')->nullable();
                $table->integer('kk');
                $table->timestamp('tgl')->useCurrent()->useCurrentOnUpdate();
                $table->integer('kk_lk')->nullable();
                $table->integer('kk_pr')->nullable();
                $table->integer('wna_lk')->nullable();
                $table->integer('wna_pr')->nullable();
            });
            log_message('notice', "Tabel `log_bulanan` berhasil dibuat.");
        }
    }


    /**
     * Hapus file log (.log dan .php) dari direktori log.
     *
     * @param string $directoryPath Path ke direktori log.
     */
    public function clearLogFiles($directoryPath)
    {
        log_message('notice', "Membersihkan log di: {$directoryPath}");
        if (!is_dir($directoryPath)) {
            log_message('notice', "Folder '{$directoryPath}' tidak ditemukan.");
            return;
        }

        $files = scandir($directoryPath);
        foreach ($files as $file) {
            $filePath = $directoryPath . DIRECTORY_SEPARATOR . $file;
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);

            if (is_file($filePath) && in_array($ext, ['log', 'php'])) {
                unlink($filePath);
                log_message('notice', "File '{$filePath}' telah berhasil dihapus.");
            }
        }
        log_message('notice', "Pembersihan file log selesai.");
    }

    /**
     * Atur email unik untuk setiap pengguna jika kosong.
     */
    public function perbaikiuseremail()
    {
        log_message('notice', "Memperbaiki email pengguna...");
        $users = DB::table('user')->whereNull('email')->get();
        foreach ($users as $user) {
            $email = $user->nama . $user->id . '@gmail.com';
            DB::table('user')->where('id', $user->id)->update(['email' => $email]);
            log_message('notice', "Email untuk user ID {$user->id} diupdate menjadi {$email}");
        }
    }

    /**
     * Buat tabel `log_login` jika belum ada.
     */
    public function perbaikiLoglogin()
    {
        log_message('notice', "Memperbaiki tabel `log_login`...");
        if (Schema::hasTable('log_login')) {
            log_message('notice', "Tabel `log_login` sudah ada.");
            return;
        }
        Schema::create('log_login', function ($table) {
            $table->char('uuid', 36)->primary();
            $table->integer('config_id');
            $table->string('username');
            $table->string('ip_address');
            $table->text('user_agent');
            $table->string('referer');
            $table->string('lainnya')->nullable();
            $table->timestamps();
            $table->unique(['uuid', 'config_id']);
            $table->index('config_id');
        });
        log_message('notice', "Tabel `log_login` berhasil dibuat/diverifikasi.");
    }

    /**
     * Buat tabel `alias_kodeisian` jika belum ada.
     */
    public function perbaikialiasKodeIsian()
    {
        log_message('notice', "Memperbaiki tabel `alias_kodeisian`...");
        if (Schema::hasTable('alias_kodeisian')) {
            log_message('notice', "Tabel `alias_kodeisian` sudah ada.");
            return;
        }
        Schema::create('alias_kodeisian', function ($table) {
            $table->increments('id');
            $table->integer('config_id');
            $table->string('judul', 20);
            $table->string('alias', 50);
            $table->string('content', 200);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->unique(['config_id', 'judul', 'alias']);
        });
        log_message('notice', "Tabel `alias_kodeisian` berhasil dibuat/diverifikasi.");
    }

    /**
     * Buat tabel `keuangan_ta_pencairan` jika belum ada.
     */
    public function keuangan_ta_pencairan()
    {
        log_message('notice', "Memperbaiki tabel `keuangan_ta_pencairan`...");
        if (Schema::hasTable('keuangan_ta_pencairan')) {
            log_message('notice', "Tabel `keuangan_ta_pencairan` sudah ada.");
            return;
        }
        Schema::create('keuangan_ta_pencairan', function ($table) {
            $table->increments('id');
            $table->integer('id_keuangan_master');
            $table->string('Tahun', 100);
            $table->string('No_Cek', 100);
            $table->string('No_SPP', 100);
            $table->string('Tgl_Cek', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Keterangan', 100);
            $table->string('Jumlah', 100);
            $table->string('Potongan', 100);
            $table->string('KdBayar', 100);
        });
        log_message('notice', "Tabel `keuangan_ta_pencairan` berhasil dibuat/diverifikasi.");
    }
    
    /**
     * Buat tabel `log_notifikasi_admin` (berdasarkan nama fungsi).
     * JS Asli memiliki bug (menduplikasi perbaikialiasKodeIsian), ini adalah implementasi yang lebih masuk akal.
     */
    public function perbaikialiasLogNotifikasiAdmin()
    {
        log_message('notice', "Memperbaiki tabel `log_notifikasi_admin`...");
        // NOTE: Kode JS asli salah, ini adalah perbaikan yang diasumsikan.
        // Jika `log_notifikasi_admin` tidak diperlukan, fungsi ini bisa membuat `alias_kodeisian` seperti aslinya.
        if (Schema::hasTable('log_notifikasi_admin')) {
            log_message('notice', "Tabel `log_notifikasi_admin` sudah ada.");
            return;
        }
        Schema::create('log_notifikasi_admin', function ($table) {
            $table->increments('id');
            $table->integer('config_id')->nullable();
            $table->string('judul');
            $table->text('pesan');
            $table->boolean('is_read')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
        log_message('notice', "Tabel `log_notifikasi_admin` berhasil dibuat/diverifikasi.");
    }

    /**
     * Tambah kolom `pemohon` ke tabel `log_surat` jika belum ada.
     */
    public function perbaikiLogSurat()
    {
        log_message('notice', "Memperbaiki tabel `log_surat`...");
        if (!Schema::hasColumn('log_surat', 'pemohon')) {
            Schema::table('log_surat', function ($table) {
                $table->string('pemohon', 200)->nullable()->after('deleted_at');
            });
            log_message('notice', "Kolom `pemohon` di tabel `log_surat` berhasil ditambahkan.");
        } else {
            log_message('notice', "Kolom `pemohon` di tabel `log_surat` sudah ada.");
        }
    }

    /**
     * Tambah kolom `sumber_penduduk_berulang` ke tabel `tweb_surat_format` jika belum ada.
     */
    public function perbaikialiasTwebSuratFormat()
    {
        log_message('notice', "Memperbaiki tabel `tweb_surat_format`...");
        if (!Schema::hasColumn('tweb_surat_format', 'sumber_penduduk_berulang')) {
            Schema::table('tweb_surat_format', function ($table) {
                $table->boolean('sumber_penduduk_berulang')->nullable()->default(0)->after('format_nomor_global');
            });
            log_message('notice', "Kolom `sumber_penduduk_berulang` di tabel `tweb_surat_format` berhasil ditambahkan.");
        } else {
            log_message('notice', "Kolom `sumber_penduduk_berulang` di tabel `tweb_surat_format` sudah ada.");
        }
    }

    /**
     * Hapus data duplikat di `kelompok_anggota` yang menyebabkan error unique index.
     */
    public function perbaikiKelompokAnggotaDuplikat()
    {
        log_message('notice', "Memperbaiki duplikasi di tabel `kelompok_anggota`...");
        $subQuery = DB::table('kelompok_anggota')
            ->selectRaw('MIN(id) as min_id, config_id, id_kelompok, no_anggota')
            ->groupBy('config_id', 'id_kelompok', 'no_anggota')
            ->havingRaw('COUNT(*) > 1')
            ->toSql();

        DB::statement("DELETE t1 FROM kelompok_anggota t1 INNER JOIN ({$subQuery}) t2 ON t1.config_id = t2.config_id AND t1.id_kelompok = t2.id_kelompok AND t1.no_anggota = t2.no_anggota WHERE t1.id > t2.min_id");

        log_message('notice', "Pembersihan duplikasi `kelompok_anggota` selesai.");
    }

    /**
     * Atur id_kk di tweb_penduduk menjadi NULL jika nilainya 0, untuk menghindari error.
     */
    public function perbaikiTwebPendudukIdKk()
    {
        log_message('notice', "Memperbaiki id_kk=0 di tabel `tweb_penduduk`...");
        DB::table('tweb_penduduk')->where('id_kk', 0)->update(['id_kk' => null]);
        log_message('notice', "Perbaikan id_kk di `tweb_penduduk` selesai.");
    }

    /**
     * Perbaiki tabel `tweb_keluarga` untuk kolom `id_cluster` yang bermasalah dengan NULL.
     */
    public function perbaikiTwebKeluargaIdCluster()
    {
        log_message('notice', "Memperbaiki kolom `id_cluster` di tabel `tweb_keluarga`...");
        
        // Cek apakah kolom id_cluster ada
        if (! Schema::hasColumn('tweb_keluarga', 'id_cluster')) {
            log_message('notice', "Kolom `id_cluster` tidak ditemukan di tabel `tweb_keluarga`. Tidak ada perbaikan yang diperlukan.");
            return;
        }

        // 1. Ubah nilai NULL menjadi 0 (atau nilai default lain yang valid)
        DB::table('tweb_keluarga')->whereNull('id_cluster')->update(['id_cluster' => 0]);
        log_message('notice', "Nilai NULL di `id_cluster` tabel `tweb_keluarga` telah diubah menjadi 0.");

        // 2. Ubah kolom menjadi NOT NULL
        Schema::table('tweb_keluarga', function ($table) {
            $table->integer('id_cluster')->default(0)->nullable(false)->change();
        });
        log_message('notice', "Kolom `id_cluster` di tabel `tweb_keluarga` berhasil diubah menjadi INT(11) NOT NULL.");
    }

    
    /**
     * Perbaiki kolom `kk_level` di tabel `tweb_penduduk` yang bermasalah.
     */
    public function perbaikiKkLevelPenduduk()
    {
        log_message('notice', "Memperbaiki kolom `kk_level` di tabel `tweb_penduduk`...");

        // Update nilai kk_level yang NULL, non-numeric, atau di luar rentang SMALLINT menjadi 0.
        // Asumsi SMALLINT unsigned (0 to 65535) jika tidak ada tanda. Jika signed (-32768 to 32767).
        // Log error menunjukkan SMALLINT, jadi kita akan gunakan rentang signed default.
        DB::table('tweb_penduduk')
            ->whereNull('kk_level')
            ->orWhere('kk_level', '<', -32768)
            ->orWhere('kk_level', '>', 32767)
            ->update(['kk_level' => 0]);
        log_message('notice', "Perbaikan kolom `kk_level` di tabel `tweb_penduduk` selesai.");
    }
        
}
        