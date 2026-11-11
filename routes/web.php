<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PerangkatDaerahController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::view('/docs', 'docs')->name('docs');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/kendaraan/export', [KendaraanController::class, 'export'])->name('kendaraan.export');
    Route::resource('kendaraan', KendaraanController::class);
    Route::resource('pegawai', PegawaiController::class)->parameters(['pegawai' => 'pegawai']);
    Route::resource('perangkat-daerah', PerangkatDaerahController::class)
        ->parameters(['perangkat-daerah' => 'perangkat_daerah']);
});
