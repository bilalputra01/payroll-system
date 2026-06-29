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
            ->paginate(5);

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
     * Kalkulasi payroll dengan dukungan PRORATA gaji untuk karyawan masuk tengah bulan.
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

        // Parse periode menjadi bulan dan tahun
        $periode_carbon = \Carbon\Carbon::parse($request->periode . '-01');
        $tahun = $periode_carbon->year;
        $bulan = $periode_carbon->month;

        // Hitung total hari kerja (Senin-Jumat) dalam bulan tersebut
        $awal_bulan = \Carbon\Carbon::create($tahun, $bulan, 1);
        $akhir_bulan = $awal_bulan->copy()->endOfMonth();
        $total_hari_kerja_bulan = 0;
        $tanggal_cek = $awal_bulan->copy();
        while ($tanggal_cek->lte($akhir_bulan)) {
            if ($tanggal_cek->isWeekday()) {
                $total_hari_kerja_bulan++;
            }
            $tanggal_cek->addDay();
        }

        foreach ($data_absensi as $absen) {
            if (!$absen->karyawan || !$absen->karyawan->jabatan) continue;

            // ═══════════════════════════════════════════════════
            // LOGIKA PRORATA: Cek apakah karyawan masuk tengah bulan
            // ═══════════════════════════════════════════════════
            $rasio_prorata = 1; // Default: gaji penuh (100%)
            $is_prorata = false;

            if ($absen->karyawan->tanggal_masuk) {
                $tanggal_masuk = \Carbon\Carbon::parse($absen->karyawan->tanggal_masuk);

                // Jika tanggal masuk berada di DALAM bulan periode ini
                if ($tanggal_masuk->year == $tahun && $tanggal_masuk->month == $bulan) {
                    // Hitung hari kerja sejak tanggal masuk sampai akhir bulan
                    $hari_kerja_aktual = 0;
                    $tanggal_hitung = $tanggal_masuk->copy();
                    while ($tanggal_hitung->lte($akhir_bulan)) {
                        if ($tanggal_hitung->isWeekday()) {
                            $hari_kerja_aktual++;
                        }
                        $tanggal_hitung->addDay();
                    }

                    // Rasio prorata = hari kerja aktual / total hari kerja bulan
                    $rasio_prorata = $total_hari_kerja_bulan > 0
                        ? $hari_kerja_aktual / $total_hari_kerja_bulan
                        : 0;
                    $is_prorata = true;
                }
            }

            // ═══════════════════════════════════════════════════
            // KALKULASI GAJI (dengan prorata jika berlaku)
            // ═══════════════════════════════════════════════════
            $gapok_penuh = $absen->karyawan->jabatan->gaji_pokok;
            $tunjangan_penuh = $absen->karyawan->jabatan->tunjangan_tetap;

            // Terapkan prorata ke gaji pokok dan tunjangan
            $gapok = round($gapok_penuh * $rasio_prorata, 2);
            $tunjangan = round($tunjangan_penuh * $rasio_prorata, 2);
            $upah_tetap = $gapok + $tunjangan;

            // THR tetap penuh (tidak diprorata) sesuai regulasi
            $thr = $request->has('is_thr') ? ($gapok_penuh + $tunjangan_penuh) : 0;

            // 1. Hitung Lembur (PP 35/2021) - berdasarkan upah tetap PENUH
            $upah_sejam = ($gapok_penuh + $tunjangan_penuh) / 173;
            $jam = $absen->jam_lembur;
            $uang_lembur = $jam > 0 ? (1.5 * $upah_sejam) + (($jam - 1) * 2 * $upah_sejam) : 0;

            // 2. Potongan Absensi (denda flat, tidak diprorata)
            $potongan_telat = $absen->jumlah_telat * 50000;
            $potongan_tidak_hadir = $absen->jumlah_tidak_hadir * 100000;
            $potongan_izin = $absen->jumlah_izin * 25000;
            $total_denda_absen = $potongan_izin + $potongan_telat + $potongan_tidak_hadir;

            // 3. Potongan BPJS (berdasarkan upah tetap yang sudah diprorata)
            $bpjs_kes = min($upah_tetap, 12000000) * 0.01;
            $bpjs_tk = ($upah_tetap * 0.02) + (min($upah_tetap, 10042300) * 0.01);

            // 4. Estimasi PPh 21 (Sederhana)
            $bruto = $upah_tetap + $uang_lembur + $thr;
            $biaya_jabatan = min($bruto * 0.05, 500000);
            $pkp_sebulan = max(0, ($bruto - $biaya_jabatan - $bpjs_tk) - 4500000);
            $pph21 = $pkp_sebulan * 0.05;

            // 5. Total & Simpan
            $total_potongan = $total_denda_absen + $bpjs_kes + $bpjs_tk + $pph21;

            \App\Models\Penggajian::updateOrCreate(
                // 6. Simpan ke database dengan kunci 'nik' dan 'periode'
                ['nik' => $absen->nik, 'periode' => $request->periode],
                [
                    'gaji_pokok_saat_ini' => $gapok,
                    'total_tunjangan' => $tunjangan,
                    'uang_lembur' => $uang_lembur,
                    'thr' => $thr,
                    'potongan_absensi' => $total_denda_absen,
                    'total_potongan' => $total_potongan,
                    'bpjs_kesehatan' => $bpjs_kes,
                    'bpjs_ketenagakerjaan' => $bpjs_tk,
                    'pph21' => $pph21,
                    'gaji_bersih' => $bruto - $total_potongan,
                ]
            );
        }
        return redirect()->back()->with('success', 'Kalkulasi Payroll Enterprise Berhasil! (Prorata otomatis diterapkan untuk karyawan baru)');
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
        $data_absensi = \App\Models\Absensi::with('karyawan.jabatan')
            ->where('periode', $request->periode)->get();

        if ($data_absensi->isEmpty()) {
            return redirect()->back()->with('error', 'Gagal! Belum ada data absensi untuk periode tersebut.');
        }
        $periode = $request->periode;
        $nama_file = 'Laporan_Payroll_' . $periode . '.xlsx';

        return Excel::download(new PenggajianExport($periode), $nama_file);
    }
}
