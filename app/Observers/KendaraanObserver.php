<?php

namespace App\Observers;

use App\Models\Kendaraan;
use App\Models\RiwayatPenanggungJawab;

/**
 * Mencatat riwayat penanggung jawab secara otomatis setiap kali
 * pegawai penanggung jawab sebuah kendaraan ditetapkan atau diganti.
 */
class KendaraanObserver
{
    public function created(Kendaraan $kendaraan): void
    {
        if ($kendaraan->pegawai_id) {
            $this->mulaiRiwayat($kendaraan);
        }
    }

    public function updated(Kendaraan $kendaraan): void
    {
        if (! $kendaraan->wasChanged('pegawai_id')) {
            return;
        }

        RiwayatPenanggungJawab::where('kendaraan_id', $kendaraan->id)
            ->whereNull('tanggal_selesai')
            ->update(['tanggal_selesai' => now()->toDateString()]);

        if ($kendaraan->pegawai_id) {
            $this->mulaiRiwayat($kendaraan);
        }
    }

    private function mulaiRiwayat(Kendaraan $kendaraan): void
    {
        RiwayatPenanggungJawab::create([
            'kendaraan_id' => $kendaraan->id,
            'pegawai_id' => $kendaraan->pegawai_id,
            'tanggal_mulai' => now()->toDateString(),
        ]);
    }
}
