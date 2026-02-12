<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'kelas' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'nama_murid' => 'required|string|max:255',
            'jenis_laporan' => 'required|string|max:100',
            'isi_laporan' => 'required|string',
            'email_murid' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'secret_code' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'school_id.required' => 'Sekolah wajib dipilih.',
            'school_id.exists' => 'Sekolah tidak terdaftar.',
            'secret_code.required' => 'Kode rahasia wajib diisi.',
            'title.required' => 'Judul laporan wajib diisi.',
            'nama_murid.required' => 'Nama pelapor wajib diisi.',
            'isi_laporan.required' => 'Isi laporan wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Log::warning('Report validation failed', [
            'errors' => $validator->errors()->toArray(),
            'input' => $this->all(),
        ]);
        
        parent::failedValidation($validator);
    }
}
