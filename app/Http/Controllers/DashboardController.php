<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Penggajian;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Logika Admin
            $total_karyawan = Karyawan::count();
            $total_jabatan = Jabatan::count();

            // ── FITUR FILTER BULAN YANG BARU KITA BUAT ──
            $periode_dipilih = $request->input('periode_gaji', date('Y-m'));
            $pengeluaran_total = Penggajian::where('periode', $periode_dipilih)->sum('gaji_bersih');

            // Data untuk grafik (tetap ambil 5 bulan terakhir)
            $grafik_periode = Penggajian::selectRaw('periode, SUM(gaji_bersih) as total')
                ->groupBy('periode')->orderBy('periode', 'asc')->take(5)->get();

            return view('dashboard', [
                'total_karyawan' => $total_karyawan,
                'total_jabatan' => $total_jabatan,
                'pengeluaran_total' => $pengeluaran_total,
                'label_grafik' => $grafik_periode->pluck('periode'),
                'data_grafik' => $grafik_periode->pluck('total'),
                'periode_dipilih' => $periode_dipilih, // Kirim ke view agar input kotaknya sesuai
            ]);
        } else {
            // Logika Karyawan
            $riwayat_gaji = Penggajian::where('nik', $user->nik)
                ->orderBy('periode', 'desc')
                ->get();

            return view('dashboard', compact('riwayat_gaji'));
        }
    }
}
