<?php

namespace App\Exports;

use App\Models\InventarisKlasifikasi;
use App\Models\KlasifikasiSurat;
use Rap2hpoutre\FastExcel\FastExcel;

class KlasifikasiInventarisExport
{
    protected $fields = [
        'kode',
        'nama',
        'deskripsi',
        'tipe_inventaris',
    ];

    public function filename($name = null)
    {
        return $name ?? namafile('klasifikasi_inventaris' . date('d-m-Y')) . '.xlsx';
    }

    public function data()
    {
        $dataExport = InventarisKlasifikasi::get($this->fields)->toArray();

        if (empty($dataExport)) {
            return [
                [
                    'kode'              => '',
                    'nama'              => '', 
                    'deskripsi'         => '',
                    'tipe_inventaris'   => ''
                ]
            ];
        }

        return $dataExport;
    }

    public function download()
    {
        return (new FastExcel())
            ->data($this->data())
            ->download(
                $this->filename()
            );
    }
}
