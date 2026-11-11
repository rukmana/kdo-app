<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /** Jumlah data per halaman untuk API: ?per_page=1..100 (default 15). */
    protected function perPage(Request $request): int
    {
        return max(1, min(100, $request->integer('per_page', 15)));
    }
}
