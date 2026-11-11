<?php

namespace Database\Factories;

use App\Models\PerangkatDaerah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PerangkatDaerah>
 */
class PerangkatDaerahFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode' => 'PD-'.fake()->unique()->numerify('####'),
            'nama' => 'Dinas '.fake()->unique()->words(2, true),
            'singkatan' => strtoupper(fake()->lexify('???')),
            'alamat' => fake()->address(),
            'telepon' => fake()->numerify('021-#######'),
        ];
    }
}
