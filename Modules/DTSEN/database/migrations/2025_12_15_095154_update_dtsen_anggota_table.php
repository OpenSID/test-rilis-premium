<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dtsen_anggota', function (Blueprint $table) {
            // Cek apakah kolom id_dtsen belum ada
            if (!Schema::hasColumn('dtsen_anggota', 'id_dtsen')) {
                $table->integer('id_dtsen')->unsigned()->nullable()->after('id');
                
                // Tambahkan foreign key constraint
                $table->foreign('id_dtsen')
                    ->references('id')
                    ->on('dtsen')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                
                // Tambahkan index untuk performa
                $table->index('id_dtsen');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dtsen_anggota', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            if (Schema::hasColumn('dtsen_anggota', 'id_dtsen')) {
                $table->dropForeign(['id_dtsen']);
                $table->dropIndex(['id_dtsen']);
                $table->dropColumn('id_dtsen');
            }
        });
    }
};