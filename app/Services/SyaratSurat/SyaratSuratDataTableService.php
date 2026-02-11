<?php

namespace App\Services\SyaratSurat;

use App\Repositories\SyaratSurat\Contracts\SyaratSuratRepositoryContract;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * SyaratSuratDataTableService
 *
 * Service untuk handle DataTables rendering dan formatting.
 * Memisahkan DataTable logic dari controller.
 */
class SyaratSuratDataTableService
{
    private SyaratSuratRepositoryContract $repository;

    public function __construct(SyaratSuratRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function builder()
    {
        try {
            $query = $this->repository->getAllWithFormatSurat();

            return DataTables::of($query)
                ->addColumn('ceklist', $this->renderChecklistColumn())
                ->addIndexColumn()
                ->addColumn('aksi', $this->renderActionColumn())
                ->rawColumns(['ceklist', 'aksi'])
                ->make(true);
        } catch (\Exception $e) {
            log_message('error', 'DataTables Error: ' . $e->getMessage());
            http_response_code(400);
            return json_encode([
                'draw'           => 0,
                'recordsTotal'   => 0,
                'recordsFiltered' => 0,
                'data'           => [],
            ]);
        }
    }

    private function renderChecklistColumn(): callable
    {
        return function ($row) {
            if (can('h')) {
                return '<input type="checkbox" name="id_cb[]" value="' . $row->ref_syarat_id . '"/>';
            }
            return '';
        };
    }

    private function renderActionColumn(): callable
    {
        return function ($row): string {
            $aksi = View::make('admin.layouts.components.buttons.edit', [
                'url' => 'surat_mohon/form/' . $row->ref_syarat_id,
            ])->render();

            if ($row->jumlah_format_surat == '0') {
                $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                    'url'           => ci_route('surat_mohon.delete', $row->ref_syarat_id),
                    'confirmDelete' => true,
                ])->render();
            }

            return $aksi;
        };
    }
}
