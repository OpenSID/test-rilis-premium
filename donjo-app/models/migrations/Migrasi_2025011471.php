<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025011471 extends MY_Model
{
    public function up()
    {
        $this->migrasi_2025011472();
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
}