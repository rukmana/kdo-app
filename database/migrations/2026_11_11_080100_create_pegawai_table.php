<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->char('nip', 18)->unique();
            $table->string('nama', 150);
            $table->string('jabatan', 150);
            $table->string('golongan', 10);
            $table->foreignId('perangkat_daerah_id')
                ->constrained('perangkat_daerah')
                ->restrictOnDelete();
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 150)->nullable()->unique();
            $table->timestamps();

            $table->index('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
