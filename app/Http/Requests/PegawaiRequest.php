<?php

namespace App\Http\Requests;

use App\Support\Referensi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('nip')) {
            // Izinkan NIP diketik dengan spasi, mis. "19820315 200801 1 004".
            $this->merge(['nip' => preg_replace('/\s+/', '', (string) $this->input('nip'))]);
        }
    }

    public function rules(): array
    {
        $id = $this->route('pegawai')?->id;

        return [
            'nip' => ['required', 'digits:18', Rule::unique('pegawai', 'nip')->ignore($id)],
            'nama' => ['required', 'string', 'max:150'],
            'jabatan' => ['required', 'string', 'max:150'],
            'golongan' => ['required', Rule::in(Referensi::GOLONGAN)],
            'perangkat_daerah_id' => ['required', 'integer', 'exists:perangkat_daerah,id'],
            'no_hp' => ['nullable', 'string', 'max:20', 'regex:/^(\+62|0)[0-9]{8,13}$/'],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('pegawai', 'email')->ignore($id)],
        ];
    }

    public function messages(): array
    {
        return [
            'nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'no_hp.regex' => 'Format nomor HP tidak valid (contoh: 081234567890).',
        ];
    }

    public function attributes(): array
    {
        return [
            'nip' => 'NIP',
            'perangkat_daerah_id' => 'perangkat daerah',
            'no_hp' => 'nomor HP',
        ];
    }
}
