<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('pegawai')]
#[Fillable(['nip', 'nama', 'jabatan', 'golongan', 'perangkat_daerah_id', 'no_hp', 'email'])]
class Pegawai extends Model
{
    use HasFactory;

    public function perangkatDaerah(): BelongsTo
    {
        return $this->belongsTo(PerangkatDaerah::class);
    }

    /** Kendaraan yang saat ini menjadi tanggung jawab pegawai. */
    public function kendaraan(): HasMany
    {
        return $this->hasMany(Kendaraan::class);
    }

    public function riwayatPenanggungJawab(): HasMany
    {
        return $this->hasMany(RiwayatPenanggungJawab::class);
    }

    public function scopeCari(Builder $query, ?string $kata): Builder
    {
        return $query->when($kata, fn (Builder $q) => $q->where(fn (Builder $w) => $w
            ->where('nama', 'like', "%{$kata}%")
            ->orWhere('nip', 'like', "%{$kata}%")
            ->orWhere('jabatan', 'like', "%{$kata}%")));
    }
}
