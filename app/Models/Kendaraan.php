<?php

namespace App\Models;

use App\Observers\KendaraanObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('kendaraan')]
#[ObservedBy(KendaraanObserver::class)]
#[Fillable([
    'nomor_polisi', 'jenis', 'merek', 'tipe', 'tahun_produksi', 'warna', 'kondisi',
    'nomor_rangka', 'nomor_mesin', 'perangkat_daerah_id', 'pegawai_id', 'keterangan',
])]
class Kendaraan extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['tahun_produksi' => 'integer'];
    }

    public function perangkatDaerah(): BelongsTo
    {
        return $this->belongsTo(PerangkatDaerah::class);
    }

    /** Pegawai penanggung jawab saat ini. */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function riwayatPenanggungJawab(): HasMany
    {
        return $this->hasMany(RiwayatPenanggungJawab::class)->latest('tanggal_mulai')->latest('id');
    }

    /**
     * Filter daftar kendaraan: kata kunci (nopol/merek/tipe), kondisi, jenis, perangkat daerah.
     *
     * @param  array<string, mixed>  $filter
     */
    public function scopeFilter(Builder $query, array $filter): Builder
    {
        return $query
            ->when($filter['q'] ?? null, fn (Builder $q, $kata) => $q->where(fn (Builder $w) => $w
                ->where('nomor_polisi', 'like', "%{$kata}%")
                ->orWhere('merek', 'like', "%{$kata}%")
                ->orWhere('tipe', 'like', "%{$kata}%")))
            ->when($filter['kondisi'] ?? null, fn (Builder $q, $v) => $q->where('kondisi', $v))
            ->when($filter['jenis'] ?? null, fn (Builder $q, $v) => $q->where('jenis', $v))
            ->when($filter['perangkat_daerah_id'] ?? null, fn (Builder $q, $v) => $q->where('perangkat_daerah_id', $v));
    }
}
