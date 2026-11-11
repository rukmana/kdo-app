<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\PerangkatDaerah;
use App\Support\Referensi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pegawai>
 */
class PegawaiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nip' => fake()->unique()->numerify('19##########1#####'),
            'nama' => fake()->name(),
            'jabatan' => fake()->randomElement(['Pengurus Barang', 'Kepala Seksi', 'Staf Umum', 'Kepala Subbagian Umum']),
            'golongan' => fake()->randomElement(Referensi::GOLONGAN),
            'perangkat_daerah_id' => PerangkatDaerah::factory(),
            'no_hp' => fake()->numerify('08##########'),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
