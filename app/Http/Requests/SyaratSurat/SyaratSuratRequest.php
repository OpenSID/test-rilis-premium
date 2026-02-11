<?php

namespace App\Http\Requests\SyaratSurat;

use App\Http\Requests\BaseFormRequest as FormRequest;

/**
 * SyaratSuratRequest
 *
 * Unified FormRequest untuk create dan update operation pada Syarat Surat.
 * Context (create/update) di-detect dari ada/tidaknya $id parameter.
 */
class SyaratSuratRequest extends FormRequest
{
    private $updateId;

    public function __construct($id = null)
    {
        $this->updateId = $id;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->updateId) {
            return [
                'ref_syarat_nama' => 'required|string|max:255|unique:ref_syarat_surat,ref_syarat_nama,' . $this->updateId . ',ref_syarat_id',
            ];
        }

        return [
            'ref_syarat_nama' => 'required|string|max:255|unique:ref_syarat_surat,ref_syarat_nama',
        ];
    }

    public function messages(): array
    {
        return [
            'ref_syarat_nama.required' => 'Nama Dokumen harus diisi',
            'ref_syarat_nama.string' => 'Nama Dokumen harus berupa teks',
            'ref_syarat_nama.max' => 'Nama Dokumen maksimal 255 karakter',
            'ref_syarat_nama.unique' => 'Nama Dokumen sudah terdaftar',
        ];
    }

    public function attributes(): array
    {
        return [
            'ref_syarat_nama' => 'Nama Dokumen',
        ];
    }

    public function prepareForValidation(): void
    {
        $data = $this->getData();
        $data = array_map('trim', $data);
        $this->setData($data);
    }
}
