<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatistikKdo;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __invoke(StatistikKdo $statistik): JsonResponse
    {
        return response()->json(['data' => $statistik->ringkasan()]);
    }
}
