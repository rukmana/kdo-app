<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerangkatDaerahResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode' => $this->kode,
            'nama' => $this->nama,
            'singkatan' => $this->singkatan,
            'alamat' => $this->alamat,
            'telepon' => $this->telepon,
            'jumlah_pegawai' => $this->whenCounted('pegawai'),
            'jumlah_kendaraan' => $this->whenCounted('kendaraan'),
            'pegawai' => PegawaiResource::collection($this->whenLoaded('pegawai')),
            'kendaraan' => KendaraanResource::collection($this->whenLoaded('kendaraan')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
