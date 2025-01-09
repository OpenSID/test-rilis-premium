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
        if (!Schema::hasTable('inventaris_klasifikasi')) {
            Schema::create('inventaris_klasifikasi', function (Blueprint $table) {
                $table->id();
                $table->configId();
                $table->string('kode', 180);
                $table->text('nama');
                $table->text('deskripsi')->nullable();
                $table->string('tipe_inventaris')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('inventaris_klasifikasi')) {
            Schema::dropIfExists('inventaris_klasifikasi');
        }
    }
};
