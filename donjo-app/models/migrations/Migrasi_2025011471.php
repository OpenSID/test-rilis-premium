<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025011471 extends MY_Model
{
    public function up()
    {
        $this->migrasi_2025011472();
        $this->migrasi_2025011473();
    }

    public function migrasi_2025011472()
    {
        $this->createModul([
            'modul'       => 'Klasifikasi Inventaris',
            'slug'        => 'inventaris-klasifikasi',
            'url'         => 'inventaris_klasifikasi',
            'aktif'       => 1,
            'ikon'        => 'fa-code',
            'urut'        => 6,
            'level'       => 2,
            'hidden'      => 0,
            'parent'      => 15,
        ]);
    }

    public function migrasi_2025011473()
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

                // Tambahkan unique index untuk mendukung upsert
                $table->unique(['kode', 'config_id'], 'unique_kode_config_id');
            });
        }
    }
}