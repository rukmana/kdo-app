<?php

namespace App\Support;

/**
 * Daftar nilai referensi yang dipakai di validasi, form, dan dokumentasi API.
 */
final class Referensi
{
    public const KONDISI = ['Baik', 'Rusak Ringan', 'Rusak Berat'];

    public const JENIS_KENDARAAN = ['Roda 2', 'Roda 4', 'Roda 6'];

    public const GOLONGAN = [
        'I/a', 'I/b', 'I/c', 'I/d',
        'II/a', 'II/b', 'II/c', 'II/d',
        'III/a', 'III/b', 'III/c', 'III/d',
        'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e',
    ];
}
