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
        // 2. Buat Jabatan & Simpan ke dalam variabel masing-masing
        $jabatan_fsd = \App\Models\Jabatan::create([
            'nama_jabatan' => 'Full-Stack Developer',
            'gaji_pokok' => 8000000,
            'tunjangan_tetap' => 2000000,
        ]);

        $jabatan_hr = \App\Models\Jabatan::create([
            'nama_jabatan' => 'HR Manager',
            'gaji_pokok' => 12000000,
            'tunjangan_tetap' => 3000000,
        ]);

        $jabatan_finance = \App\Models\Jabatan::create([
            'nama_jabatan' => 'Staff Finance',
            'gaji_pokok' => 6000000,
            'tunjangan_tetap' => 1500000,
        ]);

        $jabatan_dm = \App\Models\Jabatan::create([
            'nama_jabatan' => 'Digital Marketing',
            'gaji_pokok' => 5500000,
            'tunjangan_tetap' => 1000000,
        ]);

        $jabatan_it = \App\Models\Jabatan::create([
            'nama_jabatan' => 'Staff IT',
            'gaji_pokok' => 6000000,
            'tunjangan_tetap' => 1500000,
        ]);

        $data_karyawan = [
            ['nik' => 'KRY-001', 'nama' => 'Bilal Putra Wibowo', 'email' => 'bilal@perusahaan.com', 'nomor_rekening' => '1234567891', 'status' => 'Tetap',  'jabatan_id' => $jabatan_fsd->id],
            ['nik' => 'KRY-002', 'nama' => 'Andi Susanto', 'email' => 'andi@perusahaan.com', 'nomor_rekening' => '1234567892', 'status' => 'Kontrak', 'jabatan_id' => $jabatan_it->id],
            ['nik' => 'KRY-003', 'nama' => 'Budi Prakoso', 'email' => 'budi@perusahaan.com', 'nomor_rekening' => '1234567893', 'status' => 'Tetap', 'jabatan_id' => $jabatan_finance->id],
            ['nik' => 'KRY-004', 'nama' => 'Citra Lestari', 'email' => 'citra@perusahaan.com', 'nomor_rekening' => '1234567894', 'status' => 'Kontrak', 'jabatan_id' => $jabatan_hr->id],
            ['nik' => 'KRY-005', 'nama' => 'Dewi Sartika', 'email' => 'dewi@perusahaan.com', 'nomor_rekening' => '1234567895', 'status' => 'Tetap', 'jabatan_id' => $jabatan_dm->id],
            ['nik' => 'KRY-006', 'nama' => 'Eko Prasetyo', 'email' => 'eko@perusahaan.com', 'nomor_rekening' => '1234567896', 'status' => 'Kontrak', 'jabatan_id' => $jabatan_it->id],
            ['nik' => 'KRY-007', 'nama' => 'Fajar Nugraha', 'email' => 'fajar@perusahaan.com', 'nomor_rekening' => '1234567897', 'status' => 'Tetap', 'jabatan_id' => $jabatan_finance->id],
            ['nik' => 'KRY-008', 'nama' => 'Gita Gutawa', 'email' => 'gita@perusahaan.com', 'nomor_rekening' => '1234567898', 'status' => 'Kontrak', 'jabatan_id' => $jabatan_dm->id],
            ['nik' => 'KRY-009', 'nama' => 'Hadi Sucipto', 'email' => 'hadi@perusahaan.com', 'nomor_rekening' => '1234567899', 'status' => 'Tetap', 'jabatan_id' => $jabatan_it->id],
            ['nik' => 'KRY-010', 'nama' => 'Indah Permatasari', 'email' => 'indah@perusahaan.com', 'nomor_rekening' => '1234567890', 'status' => 'Kontrak', 'jabatan_id' => $jabatan_fsd->id],
        ];

        foreach ($data_karyawan as $data) {

            $karyawan = Karyawan::create([
                'nik' => $data['nik'],
                'nama_karyawan' => $data['nama'],
                'email' => $data['email'],
                'status' => $data['status'],
                'jabatan_id' => $data['jabatan_id'],
                'nama_bank' => 'BCA',
                'nomor_rekening' => $data['nomor_rekening'],
            ]);

            $username = strtolower(explode(' ', $data['nama'])[0]);

            User::create([
                'name' => $karyawan->nama_karyawan,
                'username' => $username . rand(10, 99),
                'email' => $karyawan->email,
                'role' => 'karyawan',
                'password' => Hash::make('password123'),
                'nik' => $karyawan->nik,
            ]);
        }
        // akun admin
        User::create([
            'name' => 'Admin HRD',
            'username' => 'admin',
            'email' => 'admin@perusahaan.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'nik' => null,
        ]);
    }
}
