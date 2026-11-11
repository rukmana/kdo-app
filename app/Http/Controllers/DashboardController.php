<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Services\StatistikKdo;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(StatistikKdo $statistik): View
    {
        return view('dashboard', [
            'stat' => $statistik->ringkasan(),
            'perluPerhatian' => Kendaraan::with('perangkatDaerah')
                ->where('kondisi', '!=', 'Baik')
                ->orderByRaw("case kondisi when 'Rusak Berat' then 0 else 1 end")
                ->orderBy('nomor_polisi')
                ->limit(8)
                ->get(),
        ]);
    }
}
