<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\DataTables;

use App\DataTables\Contracts\KontakDataTableInterface;
use App\Models\DaftarKontak;
use Illuminate\Support\Facades\View;

/**
 * DataTable untuk Kontak
 * Menangani presentation logic untuk datatables
 */
class KontakDataTable implements KontakDataTableInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        return datatables()->of(DaftarKontak::query())
            ->addColumn('ceklist', function ($row) {
                return $this->renderCheckbox($row);
            })
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                return $this->renderActionButtons($row);
            })
            ->rawColumns(['ceklist', 'aksi'])
            ->make();
    }

    /**
     * Render checkbox column
     *
     * @param object $row
     * @return string
     */
    protected function renderCheckbox($row): string
    {
        if (can('h')) {
            return '<input type="checkbox" name="id_cb[]" value="' . $row->id_kontak . '"/>';
        }

        return '';
    }

    /**
     * Render action buttons column
     *
     * @param object $row
     * @return string
     */
    protected function renderActionButtons($row): string
    {
        $aksi = '';

        $aksi .= View::make('admin.layouts.components.buttons.edit', [
            'url' => 'kontak/form/' . $row->id_kontak,
        ])->render();

        $aksi .= View::make('admin.layouts.components.buttons.hapus', [
            'url'           => ci_route('kontak.delete', $row->id_kontak),
            'confirmDelete' => true,
        ])->render();

        return $aksi;
    }
}
