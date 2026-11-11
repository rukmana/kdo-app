<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kdo.test'],
            ['name' => 'Administrator BMD', 'password' => 'password'],
        );

        $this->call([
            PerangkatDaerahSeeder::class,
            PegawaiSeeder::class,
            KendaraanSeeder::class,
        ]);
    }
}
