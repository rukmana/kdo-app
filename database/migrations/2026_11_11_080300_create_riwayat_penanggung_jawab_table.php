<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Entitas tambahan: mencatat histori pegawai penanggung jawab setiap kendaraan.
 * Baris dibuat otomatis oleh KendaraanObserver saat penanggung jawab berubah.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_penanggung_jawab', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')
                ->constrained('kendaraan')
                ->cascadeOnDelete();
            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->index(['kendaraan_id', 'tanggal_selesai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_penanggung_jawab');
    }
};
