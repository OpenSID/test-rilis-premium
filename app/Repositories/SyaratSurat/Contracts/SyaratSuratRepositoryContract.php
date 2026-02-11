<?php

namespace App\Repositories\SyaratSurat\Contracts;

/**
 * SyaratSuratRepositoryContract
 *
 * Interface untuk Syarat Surat data access layer.
 * Menerapkan Dependency Inversion Principle (DIP)
 */
interface SyaratSuratRepositoryContract
{
    public function getAllWithFormatSurat();

    public function findById($id);

    public function create(array $data);

    public function update($id, array $data): bool;

    public function delete($id): bool;

    public function deleteMultiple(array $ids): bool;
}
