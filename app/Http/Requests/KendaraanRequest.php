<?php

namespace App\Http\Requests;

use App\Models\Pegawai;
use App\Support\Referensi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class KendaraanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->filled('nomor_polisi')) {
            // Normalisasi: huruf besar dan satu spasi, mis. "b1234pqa" tetap ditolak regex, "b 1234  pqa" -> "B 1234 PQA".
            $merge['nomor_polisi'] = preg_replace('/\s+/', ' ', strtoupper(trim($this->input('nomor_polisi'))));
        }
        foreach (['nomor_rangka', 'nomor_mesin'] as $field) {
            if ($this->filled($field)) {
                $merge[$field] = strtoupper(trim($this->input($field)));
            }
        }
        if ($this->input('pegawai_id') === '') {
            $merge['pegawai_id'] = null;
        }

        $this->merge($merge);
    }

    public function rules(): array
    {
        $id = $this->route('kendaraan')?->id;

        return [
            'nomor_polisi' => [
                'required', 'string', 'max:12',
                'regex:/^[A-Z]{1,2} [0-9]{1,4} [A-Z]{1,3}$/',
                Rule::unique('kendaraan', 'nomor_polisi')->ignore($id),
            ],
            'jenis' => ['required', Rule::in(Referensi::JENIS_KENDARAAN)],
            'merek' => ['required', 'string', 'max:50'],
            'tipe' => ['required', 'string', 'max:100'],
            'tahun_produksi' => ['required', 'integer', 'min:1980', 'max:'.(now()->year + 1)],
            'warna' => ['required', 'string', 'max:30'],
            'kondisi' => ['required', Rule::in(Referensi::KONDISI)],
            'nomor_rangka' => ['nullable', 'string', 'max:30', Rule::unique('kendaraan', 'nomor_rangka')->ignore($id)],
            'nomor_mesin' => ['nullable', 'string', 'max:30', Rule::unique('kendaraan', 'nomor_mesin')->ignore($id)],
            'perangkat_daerah_id' => ['required', 'integer', 'exists:perangkat_daerah,id'],
            'pegawai_id' => ['nullable', 'integer', 'exists:pegawai,id'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Aturan bisnis: penanggung jawab harus pegawai dari perangkat daerah pemilik kendaraan.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->hasAny(['pegawai_id', 'perangkat_daerah_id']) || ! $this->filled('pegawai_id')) {
                    return;
                }

                $pdPegawai = Pegawai::whereKey($this->integer('pegawai_id'))->value('perangkat_daerah_id');

                if ((int) $pdPegawai !== $this->integer('perangkat_daerah_id')) {
                    $validator->errors()->add(
                        'pegawai_id',
                        'Penanggung jawab harus pegawai dari perangkat daerah pemilik kendaraan.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_polisi.regex' => 'Format nomor polisi tidak valid (contoh: B 1234 PQA).',
        ];
    }

    public function attributes(): array
    {
        return [
            'nomor_polisi' => 'nomor polisi',
            'tahun_produksi' => 'tahun produksi',
            'perangkat_daerah_id' => 'perangkat daerah',
            'pegawai_id' => 'penanggung jawab',
            'nomor_rangka' => 'nomor rangka',
            'nomor_mesin' => 'nomor mesin',
        ];
    }
}
