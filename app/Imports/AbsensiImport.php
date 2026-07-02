<?php

namespace App\Imports;

use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;

class AbsensiImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (!isset($row['nik']) || !isset($row['periode'])) {
                continue;
            }

            $cek_karyawan = Karyawan::where('nik', $row['nik'])->first();
            if (!$cek_karyawan) {
                continue;
            }

            $absen_duplikat = Absensi::where('nik', $row['nik'])
                ->where('periode', $row['periode'])
                ->first();

            if ($absen_duplikat) {
                $error = strtoupper(($row['nik']));
                throw new Exception("Terdeteksi duplikat");
            }

            Absensi::updateOrCreate(
                [
                    'NIK' => $row['nik'],
                    'periode' => $row['periode'],
                ],
                [
                    'jumlah_hadir' => $row['jumlah_hadir'] ?? $row['hadir'] ?? 0,
                    'jumlah_telat' => $row['jumlah_telat'] ?? $row['telat'] ?? 0,
                    'jam_lembur' => $row['jam_lembur'] ?? $row['lembur'] ?? 0,
                    'jumlah_tidak_hadir' => $row['jumlah_alpa'] ?? $row['alpa'] ?? $row['tidak_hadir'] ?? 0,
                    'jumlah_izin' => $row['jumlah_izin'] ?? $row['izin'] ?? 0,
                ]
            );
        }
    }
}
