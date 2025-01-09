<?php

namespace Database\Seeders;

use App\Traits\Migrator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventarisKlasifikasiSeeder extends Seeder
{
    use Migrator;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        // Menu Inventaris Klasifikasi
        $this->createModul([
            'modul'  => 'Inventaris',
            'slug'   => 'inventaris-klasifikasi',
            'url'    => 'inventaris-klasifikasi',
            'ikon'   => 'fa-code',
            'level'  => 1,
            'parent' => 0,
        ]);
    }
}
