<?php

namespace App\Services;

use App\Models\Kendaraan;
use App\Models\Pegawai;
use App\Models\PerangkatDaerah;
use App\Support\Referensi;

/**
 * Ringkasan data untuk dashboard web dan endpoint API /api/dashboard.
 */
class StatistikKdo
{
    public function ringkasan(): array
    {
        $perKondisi = Kendaraan::selectRaw('kondisi, count(*) as total')->groupBy('kondisi')->pluck('total', 'kondisi');
        $perJenis = Kendaraan::selectRaw('jenis, count(*) as total')->groupBy('jenis')->pluck('total', 'jenis');

        return [
            'total_kendaraan' => Kendaraan::count(),
            'total_pegawai' => Pegawai::count(),
            'total_perangkat_daerah' => PerangkatDaerah::count(),
            'tanpa_penanggung_jawab' => Kendaraan::whereNull('pegawai_id')->count(),
            'per_kondisi' => collect(Referensi::KONDISI)->mapWithKeys(fn ($k) => [$k => (int) ($perKondisi[$k] ?? 0)])->all(),
            'per_jenis' => collect(Referensi::JENIS_KENDARAAN)->mapWithKeys(fn ($j) => [$j => (int) ($perJenis[$j] ?? 0)])->all(),
            'per_perangkat_daerah' => PerangkatDaerah::query()
                ->withCount([
                    'kendaraan',
                    'kendaraan as baik_count' => fn ($q) => $q->where('kondisi', 'Baik'),
                    'kendaraan as rusak_ringan_count' => fn ($q) => $q->where('kondisi', 'Rusak Ringan'),
                    'kendaraan as rusak_berat_count' => fn ($q) => $q->where('kondisi', 'Rusak Berat'),
                ])
                ->orderByDesc('kendaraan_count')
                ->orderBy('nama')
                ->get(['id', 'nama', 'singkatan'])
                ->map(fn ($pd) => [
                    'id' => $pd->id,
                    'nama' => $pd->nama,
                    'singkatan' => $pd->singkatan,
                    'total' => $pd->kendaraan_count,
                    'baik' => $pd->baik_count,
                    'rusak_ringan' => $pd->rusak_ringan_count,
                    'rusak_berat' => $pd->rusak_berat_count,
                ])->all(),
        ];
    }
}
