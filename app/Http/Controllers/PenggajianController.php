<?php

namespace App\Http\Controllers;

use App\Exports\PenggajianExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Penggajian;
use Illuminate\Http\Request;


class PenggajianController extends Controller
{

    protected $table = 'penggajian';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Tangkap bulan yang dipilih dari form filter (Default: bulan ini)
        $filter_periode = $request->input('filter_periode', date('Y-m'));

        // 2. Filter data penggajian berdasarkan bulan tersebut
        $penggajian = \App\Models\Penggajian::with('karyawan')
            ->where('periode', $filter_periode)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('penggajian.index', compact('penggajian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form (menggunakan nama 'periode')
        $request->validate(['periode' => 'required']);

        // 2. Cari di database pada kolom 'periode' yang isinya sama dengan input 'periode'
        $data_absensi = \App\Models\Absensi::with('karyawan.jabatan')
            ->where('periode', $request->periode)->get();

        if ($data_absensi->isEmpty()) {
            return redirect()->back()->with('error', 'Gagal! Belum ada data absensi untuk periode tersebut.');
        }

        foreach ($data_absensi as $absen) {
            if (!$absen->karyawan || !$absen->karyawan->jabatan) continue;

            $gapok = $absen->karyawan->jabatan->gaji_pokok;
            $tunjangan = $absen->karyawan->jabatan->tunjangan_tetap;
            $upah_tetap = $gapok + $tunjangan;

            // 1. Hitung Lembur (PP 35/2021)
            $upah_sejam = $upah_tetap / 173;
            $jam = $absen->jam_lembur;
            $uang_lembur = $jam > 0 ? (1.5 * $upah_sejam) + (($jam - 1) * 2 * $upah_sejam) : 0;

            // 2. Potongan Absensi
            $potongan_telat = $absen->jumlah_telat * 50000;
            $potongan_tidak_hadir = $absen->jumlah_tidak_hadir * 100000;
            $potongan_izin = $absen->jumlah_izin * 25000;

            // 3. Potongan BPJS (Karyawan)
            $bpjs_kes = min($upah_tetap, 12000000) * 0.01;
            $bpjs_tk = ($upah_tetap * 0.02) + (min($upah_tetap, 10042300) * 0.01);

            // 4. Estimasi PPh 21 (Sederhana)
            $bruto = $upah_tetap + $uang_lembur;
            $biaya_jabatan = min($bruto * 0.05, 500000);
            $pkp_sebulan = max(0, ($bruto - $biaya_jabatan - $bpjs_tk) - 4500000);
            $pph21 = $pkp_sebulan * 0.05;

            // 5. Total & Simpan
            $total_potongan = $potongan_telat + $potongan_tidak_hadir + $potongan_izin + $uang_lembur + $bpjs_kes + $bpjs_tk + $pph21;

            \App\Models\Penggajian::updateOrCreate(
                // 6. Simpan ke database dengan kunci 'nik' dan 'periode'
                ['nik' => $absen->nik, 'periode' => $request->periode],
                [
                    'gaji_pokok_saat_ini' => $gapok,
                    'total_tunjangan' => $tunjangan,
                    'uang_lembur' => $uang_lembur,
                    'total_potongan' => $total_potongan,
                    'bpjs_kesehatan' => $bpjs_kes,
                    'bpjs_ketenagakerjaan' => $bpjs_tk,
                    'pph21' => $pph21,
                    'gaji_bersih' => $bruto - $total_potongan,
                ]
            );
        }
        return redirect()->back()->with('success', 'Kalkulasi Payroll Enterprise Berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penggajian $penggajian)
    {

        $penggajian->load('karyawan.jabatan');

        return view('penggajian.show', compact('penggajian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penggajian $penggajian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penggajian $penggajian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penggajian $penggajian)
    {
        //
    }

    public function exportExcel(Request $request)
    {
        // Validasi agar HRD memilih periode dulu sebelum download
        $request->validate([
            'periode' => 'required'
        ], [
            'periode.required' => 'Pilih bulan/periode terlebih dahulu untuk mencetak Excel!'
        ]);

        $periode = $request->periode;
        $nama_file = 'Laporan_Payroll_' . $periode . '.xlsx';

        return Excel::download(new PenggajianExport($periode), $nama_file);
    }
}
