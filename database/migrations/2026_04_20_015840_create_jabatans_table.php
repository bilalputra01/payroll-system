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
        // Menggunakan nama tabel 'jabatan' (tanpa 's' di belakang) agar rapi
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan'); // Contoh: Manajer, Staf IT
            $table->integer('gaji_pokok');
            $table->integer('tunjangan_tetap');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};
