<?php

namespace App\Services\SyaratSurat;

use App\Repositories\SyaratSurat\Contracts\SyaratSuratRepositoryContract;

/**
 * SyaratSuratService
 *
 * Service class untuk handle business logic Syarat Surat.
 * Memisahkan business logic dari controller dan presentation concerns.
 */
class SyaratSuratService
{
    private SyaratSuratRepositoryContract $repository;

    public function __construct(SyaratSuratRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAllWithFormatSurat();
    }

    public function getById($id)
    {
        if (empty($id)) {
            throw new \Exception('ID tidak boleh kosong');
        }

        return $this->repository->findById($id);
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        $this->getById($id);

        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        $this->getById($id);

        return $this->repository->delete($id);
    }

    public function deleteMultiple(array $ids)
    {
        return $this->repository->deleteMultiple($ids);
    }

    public function formatForForm($id = null): array
    {
        if ($id) {
            return [
                'action' => 'Ubah',
                'form_action' => ci_route('surat_mohon.update', $id),
                'ref_syarat_surat' => $this->getById($id),
            ];
        }

        return [
            'action' => 'Tambah',
            'form_action' => ci_route('surat_mohon.insert'),
            'ref_syarat_surat' => null,
        ];
    }
}
