<?php

defined('BASEPATH') || exit('No direct script access allowed');

// use App\Exports\KlasifikasiSuratExport;

class InventarisKlasifikasi extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'inventaris';

    public function index()
    {
        $data = [
            'modul_ini'     => $this->modul_ini,
            'sub_modul_ini' => $this->sub_modul_ini,
        ];

        return view('admin.inventaris.klasifikasi.index', $data);
    }
}