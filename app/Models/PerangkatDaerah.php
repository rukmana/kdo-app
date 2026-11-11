<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('perangkat_daerah')]
#[Fillable(['kode', 'nama', 'singkatan', 'alamat', 'telepon'])]
class PerangkatDaerah extends Model
{
    use HasFactory;

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class);
    }

    public function kendaraan(): HasMany
    {
        return $this->hasMany(Kendaraan::class);
    }

    public function scopeCari(Builder $query, ?string $kata): Builder
    {
        return $query->when($kata, fn (Builder $q) => $q->where(fn (Builder $w) => $w
            ->where('nama', 'like', "%{$kata}%")
            ->orWhere('kode', 'like', "%{$kata}%")
            ->orWhere('singkatan', 'like', "%{$kata}%")));
    }
}
