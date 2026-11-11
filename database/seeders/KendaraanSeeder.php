<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use App\Models\Pegawai;
use App\Models\PerangkatDaerah;
use Illuminate\Database\Seeder;

class KendaraanSeeder extends Seeder
{
    public function run(): void
    {
        // [nopol, jenis, merek, tipe, tahun, warna, kondisi, rangka, mesin, PD, NIP penanggung jawab|null, keterangan]
        $data = [
            ['B 1101 PQA', 'Roda 4', 'Toyota', 'Kijang Innova 2.0 G', 2019, 'Hitam', 'Baik', 'MHFJW8EM5K1001101', '1TR7101101', 'Disdik', '198203152008011004', 'Kendaraan operasional kepala dinas'],
            ['B 1102 PQA', 'Roda 4', 'Toyota', 'Avanza 1.3 E', 2016, 'Silver', 'Rusak Ringan', 'MHKM1BA2JGK001102', '1NR7101102', 'Disdik', '199005222015032001', 'AC tidak dingin, menunggu servis'],
            ['B 3201 PQB', 'Roda 2', 'Honda', 'Vario 125', 2021, 'Hitam', 'Baik', 'MH1JM9113MK003201', 'JM91E3003201', 'Disdik', null, null],
            ['B 1201 PQA', 'Roda 4', 'Toyota', 'Hiace Ambulans', 2020, 'Putih', 'Baik', 'JTFSS22P0L0001201', '2KD7201201', 'Dinkes', '197811042005011002', 'Ambulans puskesmas keliling'],
            ['B 1202 PQA', 'Roda 4', 'Mitsubishi', 'Xpander GLS', 2022, 'Putih', 'Baik', 'MK2NCWHANNJ001202', '4A917201202', 'Dinkes', '199207172019022003', null],
            ['B 3202 PQB', 'Roda 2', 'Yamaha', 'NMAX 155', 2018, 'Biru', 'Rusak Berat', 'MH3SG3190JK003202', 'G3E4E3202', 'Dinkes', null, 'Rangka patah, diusulkan penghapusan'],
            ['B 9301 PQC', 'Roda 6', 'Mitsubishi', 'Colt Diesel FE 74', 2017, 'Kuning', 'Baik', 'MHMFE74P5HK009301', '4D34T9301', 'DBM', '198506302010011005', 'Truk pengangkut material perbaikan jalan'],
            ['B 1301 PQA', 'Roda 4', 'Toyota', 'Hilux Single Cabin', 2018, 'Kuning', 'Rusak Ringan', 'MR0HA3CD1J0001301', '2GD7301301', 'DBM', '199111092017031002', 'Ban perlu diganti'],
            ['B 1401 PQA', 'Roda 4', 'Toyota', 'Fortuner 2.4 VRZ', 2021, 'Hitam', 'Baik', 'MHFGB8GS3M0001401', '2GD7401401', 'DSDA', '198008202006042001', null],
            ['B 9401 PQC', 'Roda 6', 'Isuzu', 'Elf NMR 71', 2015, 'Biru', 'Rusak Berat', 'MHCNMR71HFJ009401', '4HG19401', 'DSDA', null, 'Mesin turun, biaya perbaikan melebihi nilai buku'],
            ['B 3401 PQB', 'Roda 2', 'Honda', 'Beat CBS', 2020, 'Merah', 'Baik', 'MH1JM8116LK003401', 'JM81E3003401', 'DSDA', '199302142020121004', null],
            ['B 9501 PQC', 'Roda 6', 'Hino', 'Dutro 130 MDL', 2019, 'Oranye', 'Baik', 'MJEC1JG43K5009501', 'W04DTR9501', 'DLH', '197604122003121003', 'Truk compactor sampah'],
            ['B 9502 PQC', 'Roda 6', 'Hino', 'Dutro 110 SDL', 2016, 'Oranye', 'Rusak Ringan', 'MJEC1JG43G5009502', 'W04DTR9502', 'DLH', '198812012014031001', 'Hidrolik bocor'],
            ['B 1601 PQA', 'Roda 4', 'Suzuki', 'Ertiga GX', 2019, 'Putih', 'Baik', 'MHYKZE81SKJ001601', 'K15B7601601', 'Dishub', '198409252009011006', 'Kendaraan patroli'],
            ['B 3601 PQB', 'Roda 2', 'Yamaha', 'Vixion 150', 2017, 'Putih', 'Baik', 'MH3RG1820HK003601', 'G3D4E3601', 'Dishub', '199404032022032002', 'Motor patroli lalu lintas'],
            ['B 1701 PQA', 'Roda 4', 'Toyota', 'Camry 2.5 V', 2020, 'Hitam', 'Baik', 'MR053REH0L4001701', 'A25A7701701', 'BPAD', '197912182005011001', null],
            ['B 1702 PQA', 'Roda 4', 'Daihatsu', 'Gran Max Blind Van', 2014, 'Silver', 'Rusak Berat', 'MHKV1AA2JEK001702', '3SZ7701702', 'BPAD', null, 'Menunggu proses lelang'],
            ['B 1703 PQA', 'Roda 4', 'Honda', 'CR-V 1.5 Turbo', 2023, 'Abu-abu', 'Baik', 'MHRRW1850PJ001703', 'L15B7701703', 'BPAD', '199106062016092001', null],
            ['B 1801 PQA', 'Roda 4', 'Toyota', 'Kijang Innova Zenix', 2024, 'Putih', 'Baik', 'MHFAB8GS6R0001801', 'M20A7801801', 'BKD', '198305112009032004', null],
            ['B 3801 PQB', 'Roda 2', 'Honda', 'PCX 160', 2023, 'Putih', 'Baik', 'MH1KF2117PK003801', 'KF21E3003801', 'BKD', '199508282021021001', null],
        ];

        $pd = PerangkatDaerah::pluck('id', 'singkatan');
        $pegawai = Pegawai::pluck('id', 'nip');

        foreach ($data as [$nopol, $jenis, $merek, $tipe, $tahun, $warna, $kondisi, $rangka, $mesin, $singkatan, $nip, $ket]) {
            Kendaraan::updateOrCreate(['nomor_polisi' => $nopol], [
                'jenis' => $jenis,
                'merek' => $merek,
                'tipe' => $tipe,
                'tahun_produksi' => $tahun,
                'warna' => $warna,
                'kondisi' => $kondisi,
                'nomor_rangka' => $rangka,
                'nomor_mesin' => $mesin,
                'perangkat_daerah_id' => $pd[$singkatan],
                'pegawai_id' => $nip ? $pegawai[$nip] : null,
                'keterangan' => $ket,
            ]);
        }
    }
}
