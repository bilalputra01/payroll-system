<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Jabatan; // Panggil model Jabatan

class KaryawanFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Pilih ID Jabatan secara acak dari tabel jabatan yang sudah ada
            'jabatan_id' => Jabatan::inRandomOrder()->first()->id,

            // Generate nama palsu (id_ID agar namanya khas Indonesia, misal: Budi, Siti)
            'nama_karyawan' => fake('id_ID')->name(),

            // Generate email palsu yang unik
            'email' => fake()->unique()->safeEmail(),

            // Generate nomor rekening palsu
            'nomor_rekening' => fake()->bankAccountNumber(),
        ];
    }
}
