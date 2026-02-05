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

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SmsStoreRequest;
use App\Libraries\OTP\OtpManager;
use App\Models\AnggotaGrup;
use App\Models\DaftarKontak;
use App\Models\GrupKontak;
use App\Models\HubungWarga;
use App\Models\Inbox;
use App\Models\Outbox;
use App\Models\Penduduk;
use App\Models\SentItem;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SmsController extends BaseController
{
    protected string $modulIni = 'hubung-warga';
    protected string $subModulIni = 'kirim-pesan';
    protected string $kategoriPengaturan = 'Hubung Warga';
    protected OtpManager $otp;

    /**
     * Constructor
     */
    public function __construct(OtpManager $otp)
    {
        parent::__construct();
        
        $this->otp = $otp;
        
        // Check authorization
        if (!can('b')) {
            abort(403, 'Unauthorized');
        }

        // Set metadata untuk layout
        $this->shareViewData([
            'modul_ini' => $this->modulIni,
            'sub_modul_ini' => $this->subModulIni,
            'kategori_pengaturan' => $this->kategoriPengaturan,
        ]);
    }

    /**
     * Display inbox SMS
     */
    public function index(): View
    {
        return view('admin.sms.inbox.index', [
            'navigasi' => 'inbox',
        ]);
    }

    /**
     * DataTables untuk inbox
     */
    public function datatables(): JsonResponse
    {
        $query = Inbox::with(['penduduk', 'kontak']);

        return DataTables::of($query)
            ->addColumn('ceklist', function ($row) {
                if (can('h')) {
                    return '<input type="checkbox" name="id_cb[]" value="' . $row->ID . '"/>';
                }
                return '';
            })
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $aksi = '<a href="' . route('sms.form', ['tipe' => 1, 'id' => $row->ID]) 
                    . '" class="btn bg-orange btn-sm" data-remote="false" data-toggle="modal" '
                    . 'data-target="#modalBox" data-title="Lihat Pesan" title="Tampilkan dan Balas">'
                    . '<i class="fa fa-reply"></i></a> ';
                
                if (can('h')) {
                    $aksi .= '<a href="#" data-href="' . route('sms.delete', ['tipe' => 1, 'id' => $row->ID]) 
                        . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" '
                        . 'data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                }

                return $aksi;
            })
            ->addColumn('nama', fn ($row) => $row->kontak?->nama ?? ($row->penduduk?->nama ?? ''))
            ->editColumn('ReceivingDateTime', fn ($row) => tgl_indo2($row->ReceivingDateTime))
            ->rawColumns(['ceklist', 'aksi'])
            ->make(true);
    }

    /**
     * Show form untuk balas/edit pesan
     */
    public function form(Request $request): View|string
    {
        $tipe = (int) $request->get('tipe', 0);
        $id = (int) $request->get('id', 0);

        if (!can('u')) {
            abort(403, 'Unauthorized');
        }

        $data = [
            'tipe' => $tipe,
            'kontakPenduduk' => Penduduk::select(['id', 'nama', 'telepon'])
                ->whereNotNull('telepon')
                ->status()
                ->get(),
            'kontakEksternal' => DaftarKontak::select(['id_kontak', 'nama', 'telepon'])
                ->whereNotNull('telepon')
                ->get(),
        ];

        if ($id) {
            $sms = match ($tipe) {
                2 => SentItem::findOrFail($id),
                1 => Inbox::selectRaw('SenderNumber AS DestinationNumber, TextDecoded')->findOrFail($id),
                default => Outbox::findOrFail($id),
            };

            $data['sms'] = $sms;
            $data['form_action'] = route('sms.insert', ['tipe' => $tipe, 'id' => $id]);

            return view('admin.sms.ajax_sms_form', $data)->render();
        }

        $data['sms'] = null;
        $data['form_action'] = route('sms.insert', ['tipe' => $tipe]);

        return view('admin.sms.ajax_sms_form_kirim', $data)->render();
    }

    /**
     * Show form broadcast
     */
    public function broadcast(): string
    {
        $data = [
            'grupKontak' => GrupKontak::withCount('anggota')->get(),
            'form_action' => route('sms.broadcast_proses'),
        ];

        return view('admin.sms.ajax_broadcast_form', $data)->render();
    }

    /**
     * Process broadcast ke grup kontak
     */
    public function broadcastProses(Request $request): RedirectResponse
    {
        if (!can('u')) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'id_grup' => 'required|integer',
            'TextDecoded' => 'required|string',
        ]);

        $isiPesan = htmlentities($validated['TextDecoded']);
        $idGrup = bilangan($validated['id_grup']);

        // Ambil daftar anggota grup kontak
        $daftarAnggota = AnggotaGrup::where('id_grup', $idGrup)
            ->dataAnggota()
            ->get();

        foreach ($daftarAnggota as $anggota) {
            Outbox::create([
                'DestinationNumber' => $anggota->telepon,
                'TextDecoded' => $isiPesan,
            ]);
        }

        return redirect()->route('sms.outbox')
            ->with('success', 'Data berhasil disimpan');
    }

    /**
     * Insert/update pesan
     */
    public function insert(Request $request): RedirectResponse
    {
        if (!can('u')) {
            abort(403, 'Unauthorized');
        }

        $tipe = (int) $request->get('tipe', 0);
        $id = (int) $request->get('id', 0);

        $validated = $request->validate([
            'DestinationNumber' => 'nullable|string',
            'TextDecoded' => 'required|string',
        ]);

        if ($tipe == 3) {
            Outbox::where('id', $id)->update([
                'TextDecoded' => htmlentities($validated['TextDecoded']),
            ]);

            return redirect()->route('sms.pending')
                ->with('success', 'Data berhasil disimpan');
        }

        Outbox::create([
            'DestinationNumber' => bilangan($validated['DestinationNumber'] ?? ''),
            'TextDecoded' => htmlentities($validated['TextDecoded']),
        ]);

        return match ($tipe) {
            1 => redirect()->route('sms.index')->with('success', 'Data berhasil disimpan'),
            2 => redirect()->route('sms.sentitem')->with('success', 'Data berhasil disimpan'),
            default => redirect()->route('sms.outbox')->with('success', 'Data berhasil disimpan'),
        };
    }

    /**
     * Update pesan
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        if (!can('u')) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'TextDecoded' => 'required|string',
        ]);

        Outbox::where('id', $id)->update([
            'TextDecoded' => htmlentities($validated['TextDecoded']),
        ]);

        return redirect()->route('sms.index')
            ->with('success', 'Data berhasil disimpan');
    }

    /**
     * Delete pesan
     */
    public function delete(Request $request): RedirectResponse
    {
        if (!can('h')) {
            abort(403, 'Unauthorized');
        }

        $tipe = (int) $request->get('tipe', 0);
        $id = (int) $request->get('id', 0);
        $idCb = $request->input('id_cb', []);

        $ids = !empty($idCb) ? $idCb : [$id];

        match ($tipe) {
            2 => SentItem::destroy($ids),
            1 => Inbox::destroy($ids),
            default => Outbox::destroy($ids),
        };

        $route = match ($tipe) {
            1 => 'sms.index',
            2 => 'sms.sentitem',
            3 => 'sms.pending',
            default => 'sms.outbox',
        };

        return redirect()->route($route)
            ->with('success', 'Data berhasil dihapus');
    }

    /**
     * Display arsip hubung warga
     */
    public function arsip(): View
    {
        return view('admin.sms.hubung_warga.index', [
            'navigasi' => 'arsip',
        ]);
    }

    /**
     * DataTables untuk arsip
     */
    public function arsipDatatables(): JsonResponse
    {
        $query = HubungWarga::query();

        return DataTables::of($query)
            ->addColumn('ceklist', function ($row) {
                if (can('h')) {
                    return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                }
                return '';
            })
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                if (can('h')) {
                    return '<a href="#" data-href="' . route('sms.hubung_delete', $row->id) 
                        . '" class="btn bg-maroon btn-sm" title="Hapus Data" data-toggle="modal" '
                        . 'data-target="#confirm-delete"><i class="fa fa-trash"></i></a>';
                }
                return '';
            })
            ->rawColumns(['ceklist', 'aksi'])
            ->make(true);
    }

    /**
     * Show form kirim hubung warga
     */
    public function kirim(): View
    {
        if (!can('u')) {
            abort(403, 'Unauthorized');
        }

        return view('admin.sms.hubung_warga.form', [
            'grupKontak' => GrupKontak::withCount('anggota')->get(),
            'formAction' => route('sms.proses_kirim'),
            'navigasi' => 'kirim',
        ]);
    }

    /**
     * Process kirim hubung warga
     */
    public function prosesKirim(Request $request): RedirectResponse
    {
        if (!can('u')) {
            abort(403, 'Unauthorized');
        }

        $validated = $this->validateHubungWarga($request);
        $notif = $this->kirimPesanGrup($validated);

        if ($notif['jumlahBerhasil'] > 0) {
            HubungWarga::create($validated);
            
            return redirect()->route('sms.arsip')
                ->with('information', "Laporan Pengiriman Pesan: <br/>{$notif['pesanError']}");
        }

        return redirect()->route('sms.arsip')
            ->with('error', "Gagal Kirim Pesan <br/>{$notif['pesanError']}");
    }

    /**
     * Delete hubung warga
     */
    public function hubungDelete(Request $request, ?int $id = null): RedirectResponse
    {
        if (!can('h')) {
            abort(403, 'Unauthorized');
        }

        $ids = $request->input('id_cb', []);
        $finalId = !empty($ids) ? $ids : [$id];

        if (HubungWarga::destroy($finalId)) {
            return redirect()->route('sms.arsip')
                ->with('success', 'Berhasil Hapus Data');
        }

        return redirect()->route('sms.arsip')
            ->with('error', 'Gagal Hapus Data');
    }

    /**
     * Validate hubung warga input
     */
    protected function validateHubungWarga(Request $request): array
    {
        return $request->validate([
            'id_grup' => 'required|integer',
            'subjek' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);
    }

    /**
     * Transform validated data ke model
     */
    protected function hubungWargaTransform(array $validated): array
    {
        return [
            'config_id' => identitas('id'),
            'id_grup' => bilangan($validated['id_grup']),
            'subjek' => htmlentities($validated['subjek']),
            'isi' => htmlentities($validated['isi']),
            'created_by' => auth('admin')->id(),
            'updated_by' => auth('admin')->id(),
        ];
    }

    /**
     * Kirim pesan ke grup kontak via SMS/Email/Telegram
     */
    protected function kirimPesanGrup(array $data): array
    {
        $result = [
            'jumlahBerhasil' => 0,
            'pesanError' => '',
            'jumlahData' => 0,
        ];

        $data = $this->hubungWargaTransform($data);
        $daftarAnggota = AnggotaGrup::where('id_grup', $data['id_grup'])
            ->dataAnggota()
            ->get();

        foreach ($daftarAnggota as $anggota) {
            $kirim = false;

            try {
                switch (true) {
                    case (bool) setting('aktifkan_sms') && $anggota->hubung_warga === 'SMS' && !empty($anggota->telepon):
                        $kirim = Outbox::create([
                            'DestinationNumber' => $anggota->telepon,
                            'TextDecoded' => sprintf(
                                "SUBJEK :\n%s\n\nISI :\n%s",
                                $data['subjek'],
                                $data['isi']
                            ),
                        ]);

                        if ($kirim) {
                            $result['jumlahBerhasil']++;
                        } else {
                            $result['pesanError'] .= "Gagal kirim pesan SMS ke : {$anggota->nama} <br/>";
                        }
                        break;

                    case $anggota->hubung_warga === 'Email' && !empty($anggota->email):
                        if (empty(setting('email_notifikasi'))) {
                            $result['pesanError'] .= 'Pengaturan notifikasi email belum diaktifkan. <br/>';
                        } else {
                            $kirim = $this->otp->driver('email')->kirimPesan([
                                'tujuan' => $anggota->email,
                                'subjek' => $data['subjek'],
                                'isi' => $data['isi'],
                                'nama' => $anggota->nama,
                            ]);

                            if ($kirim) {
                                $result['pesanError'] .= "Berhasil kirim pesan Email ke : {$anggota->nama} <br/>";
                                $result['jumlahBerhasil']++;
                            }
                        }
                        break;

                    default:
                        if (!empty($anggota->telegram)) {
                            if (empty(setting('telegram_notifikasi'))) {
                                $result['pesanError'] .= 'Pengaturan notifikasi telegram belum diaktifkan. <br/>';
                            } else {
                                $kirim = $this->otp->driver('telegram')->kirimPesan([
                                    'tujuan' => $anggota->telegram,
                                    'subjek' => $data['subjek'],
                                    'isi' => $data['isi'],
                                ]);

                                if ($kirim) {
                                    $result['pesanError'] .= "Berhasil kirim pesan Telegram ke : {$anggota->nama} <br/>";
                                    $result['jumlahBerhasil']++;
                                }
                            }
                        }
                        break;
                }
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                $result['pesanError'] .= "Error pada {$anggota->nama}: {$e->getMessage()} <br/>";
            }
        }

        $result['jumlahData'] = count($daftarAnggota);

        return $result;
    }
}
