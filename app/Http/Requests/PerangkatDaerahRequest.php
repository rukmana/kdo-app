<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerangkatDaerahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('perangkat_daerah')?->id;

        return [
            'kode' => ['required', 'string', 'max:20', Rule::unique('perangkat_daerah', 'kode')->ignore($id)],
            'nama' => ['required', 'string', 'max:150', Rule::unique('perangkat_daerah', 'nama')->ignore($id)],
            'singkatan' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
        ];
    }

    public function attributes(): array
    {
        return ['kode' => 'kode', 'nama' => 'nama perangkat daerah'];
    }
}
