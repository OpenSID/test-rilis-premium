<?php

use App\Enums\TipeInventarisEnum;
use App\Models\InventarisKlasifikasi;
use App\Exports\KlasifikasiInventarisExport;
use App\Imports\KlasifikasiInventarisImports;

defined('BASEPATH') || exit('No direct script access allowed');

class InventarisKlasifikasiController extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'inventaris-klasifikasi';

    public function index()
    {
        $data = [
            'modul_ini'     => $this->modul_ini,
            'sub_modul_ini' => $this->sub_modul_ini,
        ];

        return view('admin.inventaris.klasifikasi.index', $data);
    }

    // datatable
    public function datatables()
    {
        if ($this->input->is_ajax_request()) {

            $inventarisKlasifikasis = InventarisKlasifikasi::select('*');

            return datatables()->of($inventarisKlasifikasis)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';
                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('inventaris_klasifikasi.form', $row->id) . '" class="btn btn-warning btn-sm" title="Ubah" style="margin-right:4px;"><i class="fa fa-edit"></i></a>';

                        if (can('h')) {
                            $aksi .= '<a href="#" data-href="' . ci_route('inventaris_klasifikasi.delete', $row->id) . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                        }
                    }

                    return $aksi;
                })
                ->addColumn('checkbox', static function ($row): string {
                    $checkbox = '';
                    if (can('u')) {
                        $checkbox .= '<input type="checkbox" name="id_cb[]" value="' . $row->id . '" />';
                    }

                    return $checkbox;
                })
                ->editColumn('tipe_inventaris', static function ($row): string {
                    $badgeClasses = [
                        'jalan'         => 'label-primary',
                        'gedung'        => 'label-success',
                        'aset'          => 'label-info',
                        'konstruksi'    => 'label-warning',
                        'peralatan'     => 'label-danger',
                        'tanah'         => 'label-default',
                    ];

                    // Default badge class if type not found
                    $badgeClass = $badgeClasses[$row->tipe_inventaris] ?? 'badge-default';
                    return '<span class="label ' . $badgeClass . '">' . ucfirst($row->tipe_inventaris) . '</span>';
                })
                ->editColumn('deskripsi', static function ($row): string {
                    return $row->deskripsi ?? '-';
                })
                ->rawColumns(['aksi', 'checkbox', 'tipe_inventaris'])
                ->make();
        }
    }

    public function form($id = null)
    {
        isCan('u');

        if ($id) {
            $data['data']        = InventarisKlasifikasi::where('id', $id)->firstOrFail();
            $data['form_action'] = ci_route('inventaris_klasifikasi.update', $id);
        } else {
            $data['data']        = null;
            $data['form_action'] = ci_route('inventaris_klasifikasi.store');
        }

        $data['tipes'] = TipeInventarisEnum::all();

        return view('admin.inventaris.klasifikasi.form', $data);
    }

    public function store(): void
    {
        isCan('u');

        $data = $this->validateRequest();

        // Pastikan tipe_inventaris disimpan dalam format konsisten (lowercase)
        if (!empty($data['tipe_inventaris'])) {
            $data['tipe_inventaris'] = strtolower($data['tipe_inventaris']);
        }

        // Ubah deskripsi kosong menjadi null
        $data['deskripsi'] = $data['deskripsi'] === '' ? null : $data['deskripsi'];

        try {
            InventarisKlasifikasi::create($data);

            redirect_with('success', 'Klasifikasi inventaris berhasil ditambahkan', route('inventaris_klasifikasi.index'));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            redirect_with('error', 'Terjadi kesalahan: ' . $e->getMessage(), route('inventaris_klasifikasi.index'));
        }
    }

    public function update($id = ''): void
    {
        isCan('u');

        $data = $this->validateRequest();

        // Pastikan tipe_inventaris disimpan dalam format konsisten (lowercase)
        if (!empty($data['tipe_inventaris'])) {
            $data['tipe_inventaris'] = strtolower($data['tipe_inventaris']);
        }

        // Ubah deskripsi kosong menjadi null
        $data['deskripsi'] = $data['deskripsi'] === '' ? null : $data['deskripsi'];

        try {
            $inventarisKlasifikasi = InventarisKlasifikasi::where('id', $id)->firstOrFail();
            $inventarisKlasifikasi->update($data);

            redirect_with('success', 'Klasifikasi inventaris berhasil diperbarui', route('inventaris_klasifikasi.index'));
        } catch (Exception $e) {
            log_message('error', $e);
            redirect_with('error', 'Terjadi kesalahan: ' . $e->getMessage(), route('inventaris_klasifikasi.index'));
        }
    }

    public function delete($id = ''): void
    {
        isCan('h');

        try {
            $inventarisKlasifikasi = InventarisKlasifikasi::find($id);

            if (!$inventarisKlasifikasi) {
                redirect_with('error', 'Klasifikasi inventaris tidak ditemukan', route('inventaris_klasifikasi.index'));
                return;
            }

            $inventarisKlasifikasi->delete();

            redirect_with('success', 'Klasifikasi inventaris berhasil dihapus', route('inventaris_klasifikasi.index'));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Terjadi kesalahan saat menghapus Klasifikasi inventaris', route('inventaris_klasifikasi.index'));
        }
    }

    public function delete_all(): void
    {
        isCan('h');

        if (empty($this->request['id_cb'])) {
            redirect_with('error', 'Tidak ada data yang dipilih untuk dihapus', route('inventaris_klasifikasi.index'));
            return;
        }

        try {
            InventarisKlasifikasi::whereIn('id', $this->request['id_cb'])->delete();

            redirect_with('success', 'Klasifikasi inventaris berhasil dihapus', route('inventaris_klasifikasi.index'));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Terjadi kesalahan saat menghapus Klasifikasi inventaris', route('inventaris_klasifikasi.index'));
        }
    }

    public function unduh()
    {
        return (new KlasifikasiInventarisExport())->download();
    }

    public function unggah()
    {
        isCan('u');
        $data['form_action']            = ci_route('inventaris_klasifikasi.proses_unggah');
        $data['format_impor']           = ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'format-impor-klasifikasi-inventaris.xlsx'));
        $data['klasifikasi_inventaris'] = ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'klasifikasi-inventaris.xlsx'));

        return view('admin.inventaris.klasifikasi.import', $data);
    }

    public function proses_unggah(): void
    {
        isCan('u');

        $this->load->library('upload');
        $this->upload->initialize([
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'xls|xlsx|xlsm',
            'file_name'     => namafile('Unggah Klasifikasi Inventaris'),
        ]);

        if ($this->upload->do_upload('klasifikasi')) {
            $upload = $this->upload->data();
            log_message('info', 'Upload file berjalan..');
            $result = (new KlasifikasiInventarisImports($upload['full_path']))->import();
            if (! $result) {
                redirect_with('error', 'Klasifikasi inventaris gagal diunggah');
            }
        }

        redirect_with('success', 'Klasifikasi inventaris berhasil diunggah', route('inventaris_klasifikasi.index'));
    }

    protected function validateRequest()
    {
        // Ambil semua key dari TipeInventarisEnum
        $tipeInventarisValid = implode(',', array_keys(TipeInventarisEnum::all()));

        $rules = [
            'kode'              => 'required|string',
            'nama'              => 'required|string',
            'deskripsi'         => 'nullable|string',
            'tipe_inventaris'   => "nullable|in:{$tipeInventarisValid}",
        ];

        $messages = [
            'kode.required'         => 'Kode Inventaris harus diisi.',
            'nama.required'         => 'Nama harus diisi.',
            'tipe_inventaris.in'    => 'Tipe Inventaris yang dipilih tidak valid.',
        ];

        return $this->validated(request(), $rules, $messages);
    }
}
