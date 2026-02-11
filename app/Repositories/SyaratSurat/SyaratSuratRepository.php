<?php

namespace App\Repositories\SyaratSurat;

use App\Models\SyaratSurat;
use App\Repositories\SyaratSurat\Contracts\SyaratSuratRepositoryContract;

class SyaratSuratRepository implements SyaratSuratRepositoryContract
{
    public function getAllWithFormatSurat()
    {
        return SyaratSurat::formatSuratExist();
    }

    public function findById($id)
    {
        return SyaratSurat::findOrFail($id);
    }

    public function create(array $data)
    {
        return SyaratSurat::create($data);
    }

    public function update($id, array $data): bool
    {
        $model = $this->findById($id);
        return $model->update($data);
    }

    public function delete($id): bool
    {
        return SyaratSurat::deleteFormatSuratExist($id);
    }

    public function deleteMultiple(array $ids): bool
    {
        foreach ($ids as $id) {
            if (!$this->delete($id)) {
                return false;
            }
        }
        return true;
    }
}
