<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KendaraanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nomor_polisi' => $this->nomor_polisi,
            'jenis' => $this->jenis,
            'merek' => $this->merek,
            'tipe' => $this->tipe,
            'tahun_produksi' => $this->tahun_produksi,
            'warna' => $this->warna,
            'kondisi' => $this->kondisi,
            'nomor_rangka' => $this->nomor_rangka,
            'nomor_mesin' => $this->nomor_mesin,
            'keterangan' => $this->keterangan,
            'perangkat_daerah_id' => $this->perangkat_daerah_id,
            'pegawai_id' => $this->pegawai_id,
            'perangkat_daerah' => new PerangkatDaerahResource($this->whenLoaded('perangkatDaerah')),
            'penanggung_jawab' => new PegawaiResource($this->whenLoaded('pegawai')),
            'riwayat_penanggung_jawab' => $this->whenLoaded('riwayatPenanggungJawab', fn () => $this->riwayatPenanggungJawab->map(fn ($r) => [
                'pegawai_id' => $r->pegawai_id,
                'nama' => $r->pegawai?->nama,
                'nip' => $r->pegawai?->nip,
                'tanggal_mulai' => $r->tanggal_mulai->toDateString(),
                'tanggal_selesai' => $r->tanggal_selesai?->toDateString(),
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
