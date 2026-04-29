<?php

namespace App\Exports;

use App\Models\Penggajian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenggajianExport implements FromCollection, WithHeadings, WithMapping
{
    protected $periode;

    // Menangkap data periode yang dikirim dari Controller
    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    // Mengambil data dari database berdasarkan periode
    public function collection()
    {
        return Penggajian::with('karyawan.jabatan')->where('periode', $this->periode)->get();
    }

    // Membuat Baris Pertama (Judul Kolom Excel)
    public function headings(): array
    {
        return [
            'NIK',
            'Nama Karyawan',
            'Jabatan',
            'Periode',
            'Gaji Pokok',
            'Tunjangan',
            'Uang Lembur',
            'Potongan BPJS & Telat',
            'Gaji Bersih (Take Home Pay)'
        ];
    }

    // Memasukkan data ke masing-masing kolom Excel
    public function map($penggajian): array
    {
        return [
            $penggajian->nik,
            $penggajian->karyawan ? $penggajian->karyawan->nama_karyawan : 'Data Karyawan Dihapus',
            $penggajian->karyawan && $penggajian->karyawan->jabatan ? $penggajian->karyawan->jabatan->nama_jabatan : '-',
            $penggajian->periode,
            $penggajian->gaji_pokok_saat_ini,
            $penggajian->total_tunjangan,
            $penggajian->uang_lembur,
            $penggajian->total_potongan,
            $penggajian->gaji_bersih,
        ];
    }
}
