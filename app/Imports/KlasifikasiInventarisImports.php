<?php

namespace App\Imports;

use App\Models\InventarisKlasifikasi;
use Exception;
use Rap2hpoutre\FastExcel\FastExcel;

class KlasifikasiInventarisImports
{
    protected $path;
    protected $fields = [
        'kode',
        'nama',
        'deskripsi',
        'tipe_inventaris'
    ];

    // constructor with a parameter
    public function __construct($path = null)
    {
        $this->path = $path ?? DEFAULT_LOKASI_IMPOR . 'klasifikasi-inventaris.xlsx';
    }

    public function import(): bool
    {
        $configId = identitas('id');

        try {
            $dataImport = [];

            reset_auto_increment('inventaris_klasifikasi');

            (new FastExcel())->import($this->path, static function (array $line) use ($configId, &$dataImport): void {
                $dataUpdate = [
                    'kode'              => alfanumerik_titik($line['kode']),
                    'nama'              => alfa_spasi($line['nama']),
                    'deskripsi'         => $line['deskripsi'] === '' ? null : strip_tags((string) $line['deskripsi']),
                    'tipe_inventaris'   => strip_tags((string) $line['tipe_inventaris']),
                    'config_id'         => $configId,
                ];

                $dataImport[] = $dataUpdate;
            });

            InventarisKlasifikasi::upsert($dataImport, ['kode', 'config_id']);
        } catch (Exception $e) {
            log_message('error', $e);

            return false;
        }

        return true;
    }
}
