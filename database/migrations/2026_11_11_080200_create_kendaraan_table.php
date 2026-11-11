<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_polisi', 12)->unique();
            $table->string('jenis', 20);
            $table->string('merek', 50);
            $table->string('tipe', 100);
            $table->unsignedSmallInteger('tahun_produksi');
            $table->string('warna', 30);
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->string('nomor_rangka', 30)->nullable()->unique();
            $table->string('nomor_mesin', 30)->nullable()->unique();
            $table->foreignId('perangkat_daerah_id')
                ->constrained('perangkat_daerah')
                ->restrictOnDelete();
            $table->foreignId('pegawai_id')
                ->nullable()
                ->constrained('pegawai')
                ->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('kondisi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
