<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PegawaiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nip' => $this->nip,
            'nama' => $this->nama,
            'jabatan' => $this->jabatan,
            'golongan' => $this->golongan,
            'no_hp' => $this->no_hp,
            'email' => $this->email,
            'perangkat_daerah_id' => $this->perangkat_daerah_id,
            'perangkat_daerah' => new PerangkatDaerahResource($this->whenLoaded('perangkatDaerah')),
            'jumlah_kendaraan' => $this->whenCounted('kendaraan'),
            'kendaraan' => KendaraanResource::collection($this->whenLoaded('kendaraan')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
