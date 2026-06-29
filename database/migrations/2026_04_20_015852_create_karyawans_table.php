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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->string('nik')->primary();
            $table->foreignId('jabatan_id')->constrained('jabatan')->cascadeOnDelete();
            $table->string('nama_karyawan');
            $table->string('email')->unique();
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('status');
            $table->date('tanggal_masuk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
