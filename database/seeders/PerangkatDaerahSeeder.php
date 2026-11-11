<?php

namespace Database\Seeders;

use App\Models\PerangkatDaerah;
use Illuminate\Database\Seeder;

class PerangkatDaerahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['PD-0101', 'Dinas Pendidikan', 'Disdik', 'Jl. Gatot Subroto Kav. 40-41, Jakarta Selatan', '021-5255385'],
            ['PD-0102', 'Dinas Kesehatan', 'Dinkes', 'Jl. Kesehatan No. 10, Jakarta Pusat', '021-3522767'],
            ['PD-0103', 'Dinas Bina Marga', 'DBM', 'Jl. Taman Jatibaru No. 1, Jakarta Pusat', '021-3843530'],
            ['PD-0104', 'Dinas Sumber Daya Air', 'DSDA', 'Jl. Taman Jatibaru No. 1, Jakarta Pusat', '021-3840522'],
            ['PD-0105', 'Dinas Lingkungan Hidup', 'DLH', 'Jl. Mandala V No. 67, Jakarta Timur', '021-8092744'],
            ['PD-0106', 'Dinas Perhubungan', 'Dishub', 'Jl. Taman Jatibaru No. 1, Jakarta Pusat', '021-3455262'],
            ['PD-0201', 'Badan Pengelolaan Aset Daerah', 'BPAD', 'Jl. Medan Merdeka Selatan No. 8-9, Jakarta Pusat', '021-3822255'],
            ['PD-0202', 'Badan Kepegawaian Daerah', 'BKD', 'Jl. Medan Merdeka Selatan No. 8-9, Jakarta Pusat', '021-3823280'],
        ];

        foreach ($data as [$kode, $nama, $singkatan, $alamat, $telepon]) {
            PerangkatDaerah::updateOrCreate(['kode' => $kode], compact('nama', 'singkatan', 'alamat', 'telepon'));
        }
    }
}
