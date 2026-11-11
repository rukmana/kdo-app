<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use App\Models\PerangkatDaerah;
use App\Support\Referensi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kendaraan>
 */
class KendaraanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nomor_polisi' => 'B '.fake()->unique()->numerify('####').' '.fake()->randomElement(['PQA', 'PQB', 'PQC']),
            'jenis' => 'Roda 4',
            'merek' => fake()->randomElement(['Toyota', 'Honda', 'Mitsubishi', 'Suzuki']),
            'tipe' => fake()->randomElement(['Kijang Innova', 'Avanza', 'Xpander', 'Ertiga']),
            'tahun_produksi' => fake()->numberBetween(2012, (int) date('Y')),
            'warna' => fake()->randomElement(['Hitam', 'Putih', 'Silver']),
            'kondisi' => fake()->randomElement(Referensi::KONDISI),
            'nomor_rangka' => strtoupper(fake()->unique()->bothify('MHF##########??')),
            'nomor_mesin' => strtoupper(fake()->unique()->bothify('1TR#######')),
            'perangkat_daerah_id' => PerangkatDaerah::factory(),
            'pegawai_id' => null,
            'keterangan' => null,
        ];
    }
}
