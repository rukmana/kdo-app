<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\KendaraanController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\PerangkatDaerahController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('api.login');

Route::middleware(['auth:sanctum', 'throttle:120,1'])->name('api.')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::apiResource('kendaraan', KendaraanController::class);
    Route::apiResource('pegawai', PegawaiController::class)->parameters(['pegawai' => 'pegawai']);
    Route::apiResource('perangkat-daerah', PerangkatDaerahController::class)
        ->parameters(['perangkat-daerah' => 'perangkat_daerah']);
});
