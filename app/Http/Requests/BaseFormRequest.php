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

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;

abstract class BaseFormRequest
{
    /**
     * @var array The request data
     */
    protected $data = [];

    /**
     * @var array The validated data
     */
    protected $validated_data = [];

    /**
     * @var \Illuminate\Contracts\Validation\Validator|null
     */
    protected $validator;

    /**
     * Initialize form request with data
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * Override di child class sesuai kebutuhan:
     * public function authorize(): bool {
     *     return auth()->user()->can('create-post');
     * }
     *
     * @return bool
     */
    abstract public function authorize(): bool;

    /**
     * Get the validation rules that apply to the request.
     *
     * Override di child class:
     * public function rules(): array {
     *     return [
     *         'name' => 'required|string|max:255',
     *         'email' => 'required|email|unique:users',
     *     ];
     * }
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Prepare the data for validation.
     *
     * Dijalankan SEBELUM validation rules diterapkan.
     * Secara default melakukan trim whitespace pada semua field string.
     *
     * Child class dapat override untuk menambahkan custom preparation:
     * public function prepareForValidation(): void {
     *     parent::prepareForValidation(); // Jangan lupa call parent untuk trim default
     *     $this->data['field'] = strtoupper($this->data['field']);
     * }
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        // Trim whitespace dari semua string field
        $data = $this->getData();
        $data = array_map(function ($value) {
            return is_string($value) ? trim($value) : $value;
        }, $data);
        $this->setData($data);
    }

    /**
     * Perform additional validation after rules validation passes.
     *
     * Dijalankan SETELAH validation rules passed.
     * Berguna untuk:
     * - Conditional validation
     * - Cross-field validation
     * - Database checks
     * - Business logic validation
     *
     * Override example:
     * public function withValidator(Validator $validator): void {
     *     $validator->after(function ($validator) {
     *         if ($this->requestedDateIsPast()) {
     *             $validator->errors()->add('date', 'Tanggal sudah berlalu');
     *         }
     *     });
     * }
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        // Child classes dapat override untuk custom validation logic
    }

    /**
     * Get custom validation messages.
     *
     * Override untuk customize error message:
     * public function messages(): array {
     *     return [
     *         'email.required' => 'Email wajib diisi',
     *         'email.unique' => 'Email sudah terdaftar',
     *         'password.min' => 'Password minimal 8 karakter',
     *     ];
     * }
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get custom validation attribute names.
     *
     * Override untuk beautify nama field di error message:
     * public function attributes(): array {
     *     return [
     *         'email' => 'Alamat Email',
     *         'password' => 'Kata Sandi',
     *     ];
     * }
     *
     * @return array
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * Set the validator instance
     *
     * @param Validator $validator
     * @return self
     */
    public function setValidator(Validator $validator): self
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Get the validator instance
     *
     * @return Validator|null
     */
    public function getValidator(): ?Validator
    {
        return $this->validator;
    }

    /**
     * Get all validated data
     *
     * @return array
     */
    public function getValidated(): array
    {
        return $this->validated_data;
    }

    /**
     * Set validated data
     *
     * @param array $data
     * @return self
     */
    public function setValidated(array $data): self
    {
        $this->validated_data = $data;
        return $this;
    }

    /**
     * Get request data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set request data
     *
     * @param array $data
     * @return self
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * ======================================================================
     * HELPER METHODS - Untuk mempermudah akses data di child class
     * ======================================================================
     */

    /**
     * Get value dari field dengan fallback default.
     * Contoh: $this->getField('name', 'Guest')
     *
     * @param string $field
     * @param mixed $default
     * @return mixed
     */
    protected function getField(string $field, mixed $default = null): mixed
    {
        return $this->data[$field] ?? $default;
    }

    /**
     * Get multiple fields sebagai array.
     * Contoh: $this->getFields(['name', 'email', 'phone'])
     *
     * @param array $fields
     * @return array
     */
    protected function getFields(array $fields): array
    {
        $result = [];
        foreach ($fields as $field) {
            if (isset($this->data[$field])) {
                $result[$field] = $this->data[$field];
            }
        }
        return $result;
    }

    /**
     * Check apakah field berisi value.
     * Contoh: if ($this->hasValue('status')) { ... }
     *
     * @param string $field
     * @return bool
     */
    protected function hasValue(string $field): bool
    {
        return !empty($this->data[$field]);
    }

    /**
     * Get semua input kecuali sensitive fields dan specific exclude.
     * Berguna untuk logging/audit tanpa data sensitif.
     *
     * @param array $additionalExclude
     * @return array
     */
    protected function getAuditData(array $additionalExclude = []): array
    {
        $data = $this->validated_data;
        $excludeFields = array_merge(
            $this->sensitiveFields(),
            $additionalExclude
        );

        foreach ($excludeFields as $field) {
            unset($data[$field]);
        }

        return [
            'data'      => $data,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Field yang sensitif dan tidak boleh di-log.
     *
     * Override method ini untuk menambahkan field sensitif.
     *
     * @return array
     */
    protected function sensitiveFields(): array
    {
        return [
            'password',
            'password_confirmation',
            'pin',
            'token',
            'secret',
            'api_key',
            'authorization',
            'auth_token',
        ];
    }

    /**
     * ======================================================================
     * STATIC HELPERS - Untuk CI3 integration dengan parameter injection
     * ======================================================================
     */

    /**
     * Resolve dan validate FormRequest
     *
     * Accept request data dan rules, kemudian validate.
     * Jika validation gagal, store errors dan redirect back.
     * Jika validation sukses, return validated data.
     *
     * Usage:
     *   $data = (new SyaratSuratRequest())->validated($requestData, $rules);
     *
     * @param array $requestData Request input data
     * @param array $rules (Optional) Custom validation rules, jika tidak diberikan akan gunakan rules() dari class
     * @param array $messages (Optional) Custom error messages
     * @param array $customAttributes (Optional) Custom attribute names
     *
     * @return array Validated data
     * @throws Exception
     */
    public function validated($requestData = null, $rules = null, $messages = [], $customAttributes = [])
    {
        // Jika tidak ada request data, ambil dari CI3 input
        if ($requestData === null) {
            $ci = &get_instance();
            $requestData = $ci->input->post();
        }

        // Set data
        $this->setData($requestData);

        // Jika tidak ada rules, gunakan dari method rules()
        if ($rules === null) {
            $rules = $this->rules();
        }

        // Get messages & attributes dari method jika tidak disediakan
        if (empty($messages)) {
            $messages = $this->messages();
        }
        if (empty($customAttributes)) {
            $customAttributes = $this->attributes();
        }

        // Validate authorization
        // Note: Controller should already check authorization with isCan() before calling this method
        if (!$this->authorize()) {
            show_404();
        }

        // Call prepareForValidation hook
        $this->prepareForValidation();

        // Run validation
        $validator = \Illuminate\Support\Facades\Validator::make(
            $this->getData(),
            $rules,
            $messages,
            $customAttributes
        );

        // Allow withValidator hook
        if (method_exists($this, 'withValidator')) {
            $this->setValidator($validator);
            $this->withValidator($validator);
        }

        // Handle validation failure
        if ($validator->fails()) {
            $ci = &get_instance();

            // Jika AJAX request, return JSON response
            if ($ci->input->is_ajax_request()) {
                header('Content-Type: application/json');
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray(),
                ]);
                exit;
            }

            // Untuk request normal, store input & errors di session
            // Store input data dalam format Laravel untuk old() helper
            $ci->session->set_flashdata('input', $this->getData());

            // Store validation errors dalam format Laravel (MessageBag)
            // Sehingga @error() directive akan berfungsi
            $ci->session->set_flashdata('errors', $validator->messages());

            // Redirect back ke referrer atau form URL
            $referrer = $ci->input->server('HTTP_REFERER');
            if ($referrer) {
                redirect($referrer);
            } else {
                // Fallback ke halaman sebelumnya jika tidak ada referrer
                redirect_with('error', $validator->errors()->first());
            }
            exit;
        }

        // Set validated data
        $validated = $validator->validated();
        $this->setValidated($validated);

        return $validated;
    }
}
