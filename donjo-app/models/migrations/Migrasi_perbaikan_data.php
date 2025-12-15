<?php

use App\Services\Install\CreateGrupAksesService;
use App\Enums\SHDKEnum;
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
            "Unknown column 'pemohon' in 'log_surat'" => 'perbaikiLogSurat',
            "There is no table with name \"alias_kodeisian\"" => 'perbaikialiasKodeIsian',
            "log_notifikasi_admin' doesn't exist" => 'perbaikiLogNotifikasiAdmin',
            "log_notifikasi_mandiri' doesn't exist" => 'perbaikiLogNotifikasiMandiri',
            "fcm_token' doesn't exist" => 'perbaikiFcmToken',
            "fcm_token_mandiri' doesn't exist" => 'perbaikiFcmTokenMandiri',
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
        log_message('notice', "Memperbaiki data dan relasi tabel `grup_akses`...");

        // 1. Hapus data dari `grup_akses` menggunakan LEFT JOIN.
        // Cara ini lebih aman dan efisien daripada subquery dengan `NOT IN`.
        $deletedRows = DB::table('grup_akses as ga')
            ->leftJoin('config as c', 'ga.config_id', '=', 'c.id')
            ->leftJoin('user_grup as ug', 'ga.id_grup', '=', 'ug.id')
            ->leftJoin('setting_modul as sm', 'ga.id_modul', '=', 'sm.id')
            ->whereNull('c.id')
            ->orWhereNull('ug.id')
            ->orWhereNull('sm.id')
            ->delete();

        if ($deletedRows > 0) {
            log_message('notice', "Menghapus {$deletedRows} baris data orphaned records dari `grup_akses`.");
        } else {
            log_message('notice', "Tidak ada data yatim yang ditemukan di `grup_akses`.");
        }

        // 2. Jalankan migrasi untuk memastikan foreign key constraint sudah ada.
        // Trait `runMigration` sudah cukup pintar untuk tidak menjalankan ulang migrasi yang sudah ada.
        $this->runMigration('2025_12_15_015245_add_foreign_keys_to_group_akses_table');
        log_message('notice', "Verifikasi foreign key pada `grup_akses` selesai.");

        // 3. Jalankan seeder hak akses hanya jika ada data yang dihapus atau jika tabel kosong.
        // Ini membuat fungsi menjadi idempoten (aman dijalankan berulang kali tanpa efek samping).
        $shouldSeed = $deletedRows > 0 || DB::table('grup_akses')->count() === 0;

        if ($shouldSeed) {
            log_message('notice', "Menjalankan seeder untuk hak akses grup default...");
            (new CreateGrupAksesService())->handle();
            log_message('notice', "Seeder hak akses grup default berhasil dijalankan.");
        } else {
            log_message('notice', "Seeder hak akses tidak perlu dijalankan karena data sudah konsisten.");
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

        //1. cek kolom email apakah ada unique key
        $cekUnique = DB::select("
            SHOW INDEX FROM user 
            WHERE Column_name = 'email' 
            AND Non_unique = 1
        ");

        //2. jika ada, maka lewati
        if($cekUnique){
            log_message('notice', "Tabel `user` sudah ada dengan unique key `email`.");
            return;
        }

        //3. jika tidak ada jalankan fungsi update email, cek email yg null atau duplikat

        // NULL / kosong
        DB::statement("
            UPDATE user
            SET email = CONCAT(LOWER(username), '@gmail.com')
            WHERE email IS NULL
            OR LENGTH(email) = 0
            OR email REGEXP '^[[:space:]]*$'
            OR HEX(email) IN ('00', 'EFBBBF');

        ");

        // DUPLIKAT (kecuali ID terkecil)
        DB::statement("
            UPDATE user u
            JOIN (
                SELECT username, MIN(id) AS keep_id
                FROM user
                GROUP BY username
                HAVING COUNT(*) > 1
            ) d ON d.username = u.username
            SET u.email = CONCAT(LOWER(u.username), '@gmail.com')
            WHERE u.id <> d.keep_id
        ");


        //4. tambahkan unique key pada kolom email
        DB::statement("
            ALTER TABLE user
            ADD UNIQUE KEY email_config (email)
        ");

        log_message('notice', "Perbaikan email pengguna selesai. Kolom email kini bersifat UNIQUE.");
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

        $this->runMigration('2023_12_22_015242_create_alias_kodeisian_table');
        
        log_message('notice', "Tabel `alias_kodeisian` berhasil dibuat/diverifikasi.");
    }
    
    /**
     * Buat tabel `log_notifikasi_admin` (berdasarkan nama fungsi).
     */
    public function perbaikiLogNotifikasiAdmin()
    {
        log_message('notice', "Memperbaiki tabel `log_notifikasi_admin`...");
        
        // cek apakah sudah ada table `log_notifikasi_admin`
        if (Schema::hasTable('log_notifikasi_admin')) {
            log_message('notice', "Tabel `log_notifikasi_admin` sudah ada.");
            return;
        }
        
        // jika belum ada jalankan migrasi
        $this->runMigration('2023_12_22_015242_create_log_notifikasi_admin_table');

        log_message('notice', "Tabel `log_notifikasi_admin` berhasil dibuat/diverifikasi.");
    }
    
    /**
     * Buat tabel `fcm_token` (berdasarkan nama fungsi).
     */
    public function perbaikiFcmToken()
    {
        log_message('notice', "Memperbaiki tabel `fcm_token`...");
        
        // cek apakah sudah ada table `fcm_token`
        if (Schema::hasTable('fcm_token')) {
            log_message('notice', "Tabel `fcm_token` sudah ada.");
            return;
        }
        
        // jika belum ada jalankan migrasi
        $this->runMigration('2023_12_22_015242_create_fcm_token_table');

        log_message('notice', "Tabel `fcm_token` berhasil dibuat/diverifikasi.");
    }
   
    /**
     * Buat tabel `fcm_token_mandiri` (berdasarkan nama fungsi).
     */
    public function perbaikiFcmTokenMandiri()
    {
        log_message('notice', "Memperbaiki tabel `fcm_token_mandiri`...");
        
        // cek apakah sudah ada table `fcm_token_mandiri`
        if (Schema::hasTable('fcm_token_mandiri')) {
            log_message('notice', "Tabel `fcm_token_mandiri` sudah ada.");
            return;
        }
        
        // jika belum ada jalankan migrasi
        $this->runMigration('2023_12_22_015242_create_fcm_token_mandiri_table');

        log_message('notice', "Tabel `fcm_token_mandiri` berhasil dibuat/diverifikasi.");
    }

    /**
     * Buat tabel `log_notifikasi_mandiri` (berdasarkan nama fungsi).
     */
    public function perbaikiLogNotifikasiMandiri()
    {
        log_message('notice', "Memperbaiki tabel `log_notifikasi_mandiri`...");

        // cek apakah sudah ada table `log_notifikasi_mandiri`
        if (Schema::hasTable('log_notifikasi_mandiri')) {
            log_message('notice', "Tabel `log_notifikasi_mandiri` sudah ada.");
            return;
        }
        
        // jika belum ada jalankan migrasi
        $this->runMigration('2023_12_22_015242_create_log_notifikasi_mandiri_table');

        log_message('notice', "Tabel `log_notifikasi_mandiri` berhasil dibuat/diverifikasi.");
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
        //1. cek kolom unique key = no_anggota_config -> [config_id, id_kelompok, no_anggota] apakah ada unique key
        $exists = DB::select("
            SELECT 1
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
            AND table_name = 'kelompok_anggota'
            AND non_unique = 0
            GROUP BY index_name
            HAVING GROUP_CONCAT(column_name ORDER BY seq_in_index)
                = 'config_id,id_kelompok,no_anggota'
            LIMIT 1
        ");

        //2. jika ada, maka lewati
        if($exists){
            log_message('notice', "Tabel `kelompok_anggota` sudah ada dengan unique key `no_anggota_config`.");
            return;
        }

        //3. jika tidak ada jalankan fungsi update dan hapus duplikat
        log_message('notice', "Memperbaiki duplikasi di tabel `kelompok_anggota`...");

        $subQuery = DB::table('kelompok_anggota')
            ->selectRaw('MIN(id) as min_id, config_id, id_kelompok, no_anggota')
            ->groupBy('config_id', 'id_kelompok', 'no_anggota')
            ->havingRaw('COUNT(*) > 1')
            ->toSql();

        DB::statement("DELETE t1 FROM kelompok_anggota t1 INNER JOIN ({$subQuery}) t2 ON t1.config_id = t2.config_id AND t1.id_kelompok = t2.id_kelompok AND t1.no_anggota = t2.no_anggota WHERE t1.id > t2.min_id");

        //4. tambahkan unique key pada kolom 
        DB::statement("
            ALTER TABLE kelompok_anggota
            ADD UNIQUE KEY no_anggota_config (config_id, id_kelompok, no_anggota)
        ");

        log_message('notice', "Pembersihan duplikasi `kelompok_anggota` selesai.");
    }

    /**
     * Atur id_kk di tweb_penduduk menjadi NULL jika nilainya 0, untuk menghindari error.
     */
    public function perbaikiTwebPendudukIdKk()
    {
        log_message('notice', "Memperbaiki id_kk=0 di tabel `tweb_penduduk`...");

        // 1. update data `id_kk` ada yang terisi 0 ke NULL
        DB::table('tweb_penduduk')->where('id_kk', 0)->update(['id_kk' => null]);
        log_message('notice', "Perbaikan id_kk di `tweb_penduduk` selesai.");

        // 2. update tipe data `id_kk` ke integer
        Schema::table('tweb_penduduk', function ($table) {
            $table->integer('id_kk')->nullable(true)->change();
        });

        log_message('notice', "Data dan tipe data pada id_kk sudah di perbaiki");
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

        // Asumsi SMALLINT unsigned (0 to 65535) jika tidak ada tanda. Jika signed (-32768 to 32767).
        DB::table('tweb_penduduk')
            ->whereNull('kk_level')
            ->update(['kk_level' => SHDKEnum::LAINNYA]);
        log_message('notice', "Perbaikan kolom `kk_level` di tabel `tweb_penduduk` selesai.");
    }
}