<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\PerangkatDaerah;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // [singkatan PD, NIP, nama, jabatan, golongan, no_hp]
        // Nama dan NIP fiktif; format NIP 18 digit: lahir(8) + TMT CPNS(6) + jenis kelamin(1) + urut(3).
        $data = [
            ['Disdik', '198203152008011004', 'Ahmad Fauzi', 'Kepala Subbagian Umum', 'III/d', '081234560101'],
            ['Disdik', '199005222015032001', 'Siti Rahmawati', 'Pengurus Barang', 'III/a', '081234560102'],
            ['Dinkes', '197811042005011002', 'Bambang Sutrisno', 'Kepala Subbagian Umum', 'IV/a', '081234560201'],
            ['Dinkes', '199207172019022003', 'Dewi Lestari', 'Pengurus Barang', 'II/c', '081234560202'],
            ['DBM', '198506302010011005', 'Rudi Hartono', 'Kepala Seksi Pemeliharaan Jalan', 'III/c', '081234560301'],
            ['DBM', '199111092017031002', 'Yusuf Maulana', 'Pengemudi', 'II/a', '081234560302'],
            ['DSDA', '198008202006042001', 'Rina Marlina', 'Kepala Subbagian Umum', 'III/d', '081234560401'],
            ['DSDA', '199302142020121004', 'Hendra Saputra', 'Pengurus Barang', 'II/b', '081234560402'],
            ['DLH', '197604122003121003', 'Agus Salim', 'Kepala Seksi Pengangkutan Sampah', 'IV/a', '081234560501'],
            ['DLH', '198812012014031001', 'Fajar Nugroho', 'Pengemudi', 'II/d', '081234560502'],
            ['Dishub', '198409252009011006', 'Wahyu Pratama', 'Kepala Subbagian Umum', 'III/c', '081234560601'],
            ['Dishub', '199404032022032002', 'Nur Aini', 'Pengurus Barang', 'II/c', '081234560602'],
            ['BPAD', '197912182005011001', 'Eko Prasetyo', 'Kepala Bidang Pemanfaatan Aset', 'IV/b', '081234560701'],
            ['BPAD', '199106062016092001', 'Maya Anggraini', 'Analis Barang Milik Daerah', 'III/b', '081234560702'],
            ['BKD', '198305112009032004', 'Lina Kusuma', 'Kepala Subbagian Umum', 'III/d', '081234560801'],
            ['BKD', '199508282021021001', 'Rizky Ramadhan', 'Pengurus Barang', 'II/b', '081234560802'],
        ];

        $pd = PerangkatDaerah::pluck('id', 'singkatan');

        foreach ($data as [$singkatan, $nip, $nama, $jabatan, $golongan, $hp]) {
            Pegawai::updateOrCreate(['nip' => $nip], [
                'nama' => $nama,
                'jabatan' => $jabatan,
                'golongan' => $golongan,
                'perangkat_daerah_id' => $pd[$singkatan],
                'no_hp' => $hp,
                'email' => str($nama)->lower()->replace(' ', '.').'@contoh.go.id',
            ]);
        }
    }
}
