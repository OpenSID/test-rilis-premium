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

namespace App\Services;

use App\Repositories\Contracts\KontakRepositoryInterface;
use App\Services\Contracts\KontakServiceInterface;

class KontakService implements KontakServiceInterface
{
    /**
     * @var KontakRepositoryInterface
     */
    protected $kontakRepository;

    /**
     * Dependency Injection untuk repository
     *
     * @param KontakRepositoryInterface $kontakRepository
     */
    public function __construct(KontakRepositoryInterface $kontakRepository)
    {
        $this->kontakRepository = $kontakRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validateData(array $data): array
    {
        return [
            'nama'         => nama_terbatas($data['nama'] ?? ''),
            'hubung_warga' => htmlentities((string) ($data['hubung_warga'] ?? '')),
            'telepon'      => bilangan($data['telepon'] ?? ''),
            'email'        => htmlentities((string) ($data['email'] ?? '')),
            'telegram'     => bilangan($data['telegram'] ?? ''),
            'keterangan'   => htmlentities((string) ($data['keterangan'] ?? '')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): bool
    {
        try {
            $validatedData = $this->validateData($data);
            $this->kontakRepository->create($validatedData);

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Gagal membuat kontak: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $id, array $data): bool
    {
        try {
            $validatedData = $this->validateData($data);

            return $this->kontakRepository->update($id, $validatedData);
        } catch (\Exception $e) {
            log_message('error', 'Gagal update kontak: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int $id): bool
    {
        try {
            return $this->kontakRepository->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Gagal hapus kontak: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMultiple(array $ids): bool
    {
        try {
            return $this->kontakRepository->deleteMultiple($ids);
        } catch (\Exception $e) {
            log_message('error', 'Gagal hapus multiple kontak: ' . $e->getMessage());

            return false;
        }
    }
}
