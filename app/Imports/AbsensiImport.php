<?php

namespace App\Imports;

use App\Models\Absensi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AbsensiImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Validasi: Abaikan baris jika NIK atau Periode kosong
            if (!isset($row['nik']) || !isset($row['periode'])) {
                continue;
            }

            // Gunakan updateOrCreate agar tidak error jika ada data ganda (otomatis tertimpa yang baru)
            Absensi::updateOrCreate(
                [
                    'NIK' => $row['nik'],
                    'periode' => $row['periode'],
                ],
                [
                    'jumlah_hadir' => $row['jumlah_hadir'] ?? 0,
                    'jumlah_telat' => $row['jumlah_telat'] ?? 0,
                    'jam_lembur' => $row['jam_lembur'] ?? 0,
                    'jumlah_tidak_hadir' => $row['jumlah_alpa'] ?? 0, // Di excel kita namakan jumlah_alpa
                    'jumlah_izin' => $row['jumlah_izin'] ?? 0,
                ]
            );
        }
    }
}
