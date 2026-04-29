<?php

namespace Database\Seeders;


use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $jabatan_it = \App\Models\Jabatan::create([
            'nama_jabatan' => 'Full-Stack Developer',
            'gaji_pokok' => 8000000,
            'tunjangan_tetap' => 2000000,
        ]);
        \App\Models\Jabatan::create([
            'nama_jabatan' => 'HR Manager',
            'gaji_pokok' => 12000000,
            'tunjangan_tetap' => 3000000,
        ]);

        \App\Models\Jabatan::create([
            'nama_jabatan' => 'Staff Finance',
            'gaji_pokok' => 6000000,
            'tunjangan_tetap' => 1500000,
        ]);

        \App\Models\Jabatan::create([
            'nama_jabatan' => 'Digital Marketing',
            'gaji_pokok' => 5500000,
            'tunjangan_tetap' => 1000000,
        ]);
        // 1. BUAT AKUN ADMIN HRD 
        User::create([
            'name' => 'Admin HRD',
            'username' => 'admin',
            'email' => 'admin@perusahaan.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'nik' => null,
        ]);

        $karyawan_1 = Karyawan::create([
            'nik' => 'KRY-2024-001',
            'jabatan_id' => $jabatan_it->id,
            'nama_karyawan' => 'Bilal Putra Wibowo',
            'email' => 'bilal@perusahaan.com',
            'nama_bank' => 'BCA', // <-- Tambahkan ini
            'nomor_rekening' => '1234567890',
            'status' => 'Tetap',
        ]);
    }
}
