<?php


namespace App\Exports;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\Penggajian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenggajianExport implements FromCollection, WithHeadings, WithMapping
{
    protected $periode;
    protected $total_pengeluaran;

    // Menangkap data periode yang dikirim dari Controller
    public function __construct($periode)
    {
        $this->periode = $periode;
    }



    public function collection()
    {
        // 1. Ambil data asli dari database
        $data = Penggajian::with('karyawan.jabatan')->where('periode', $this->periode)->get();

        // 2. Hitung total gaji bersih dari data yang didapat
        $total_pengeluaran = $data->sum('gaji_bersih');

        // 3. SUNTIKKAN 1 baris "data buatan" di urutan paling bawah khusus untuk total
        $data->push((object)[
            'is_baris_total' => true, // Ini adalah tanda pengenal rahasia kita
            'total_angka' => $total_pengeluaran
        ]);

        return $data;
    }
    // Membuat Baris Pertama (Judul Kolom Excel)
    public function headings(): array
    {
        return [
            'NIK',
            'Nama Karyawan',
            'Jabatan',
            'Periode',
            'Jumlah Telat',
            'Jumlah Alpa',
            'Jumlah Izin',
            'Jam Lembur',
            'Uang Lembur',
            'Gaji Pokok',
            'Tunjangan',
            'Potongan Absensi',
            'Total Potongan',
            'Gaji Bersih (Take Home Pay)'

        ];
    }

    public function map($penggajian): array
    {
        // 4. CEK LOGIKA: Apakah baris yang sedang diproses ini adalah baris total buatan kita?
        if (isset($penggajian->is_baris_total)) {
            // Jika YA, kosongkan kolom 1 sampai 7, dan isi kolom 8 dan 9
            return [
                '', // NIK kosong
                '', // Nama kosong
                '', // Jabatan kosong
                '', // Periode kosong
                '', // 
                '', //
                '', // 
                '', // 
                '', // Gaji Pokok kosong
                '', // Tunjangan kosong
                '', // 
                '', // Lembur kosong
                'TOTAL PENGELUARAN:',
                $penggajian->total_angka
            ];
        }
        $absensi = Absensi::where('nik', $penggajian->nik)
            ->where('periode', $penggajian->periode)
            ->first();

        // Jika BUKAN baris total (berarti ini data karyawan asli), cetak seperti biasa
        return [
            $penggajian->nik,
            $penggajian->karyawan ? $penggajian->karyawan->nama_karyawan : 'Data Karyawan Dihapus',
            $penggajian->karyawan && $penggajian->karyawan->jabatan ? $penggajian->karyawan->jabatan->nama_jabatan : '-',
            $absensi ? $absensi->jumlah_telat : 0,
            $absensi ? $absensi->jumlah_tidak_hadir : 0,
            $absensi ? $absensi->jumlah_izin : 0,
            $absensi ? $absensi->jam_lembur : 0,
            $penggajian->uang_lembur,
            $penggajian->gaji_pokok_saat_ini,
            $penggajian->total_tunjangan,
            $penggajian->potongan_absensi,
            $penggajian->total_potongan,
            $penggajian->gaji_bersih,
        ];
    }
}
