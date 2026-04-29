<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->foreign('nik')->references('nik')->on('karyawan')->cascadeOnDelete();
            $table->string('periode'); // Format: YYYY-MM
            $table->integer('jumlah_hadir');
            $table->integer('jumlah_telat');
            $table->integer('jam_lembur')->default(0); // Langsung ada di sini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
