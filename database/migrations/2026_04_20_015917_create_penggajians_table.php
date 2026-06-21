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
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->foreign('nik')->references('nik')->on('karyawan')->cascadeOnDelete();
            $table->string('periode');
            $table->decimal('gaji_pokok_saat_ini', 15, 2);
            $table->decimal('total_tunjangan', 15, 2);
            $table->decimal('uang_lembur', 15, 2)->default(0);
            $table->decimal('thr', 15, 2)->default(0);
            $table->decimal('potongan_telat')->default(0);
            $table->decimal('potongan_tidak_hadir')->default(0);
            $table->decimal('potongan_izin')->default(0);
            $table->decimal('potongan_absensi')->default(0);
            $table->decimal('total_potongan', 15, 2);
            $table->decimal('bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('bpjs_ketenagakerjaan', 15, 2)->default(0);
            $table->decimal('pph21', 15, 2)->default(0);
            $table->decimal('gaji_bersih', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
