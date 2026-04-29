<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        // Logika Admin (Tetap sama seperti sebelumnya)
        $total_karyawan = \App\Models\Karyawan::count();
        $total_jabatan = \App\Models\Jabatan::count();
        $pengeluaran_total = \App\Models\Penggajian::sum('gaji_bersih');
        $grafik_periode = \App\Models\Penggajian::selectRaw('periode, SUM(gaji_bersih) as total')
            ->groupBy('periode')->orderBy('periode', 'asc')->take(5)->get();

        return view('dashboard', [
            'total_karyawan' => $total_karyawan,
            'total_jabatan' => $total_jabatan,
            'pengeluaran_total' => $pengeluaran_total,
            'label_grafik' => $grafik_periode->pluck('periode'),
            'data_grafik' => $grafik_periode->pluck('total'),
        ]);
    } else {
        // LOGIKA KARYAWAN: Ambil riwayat gaji MILIK SENDIRI
        $riwayat_gaji = \App\Models\Penggajian::where('nik', $user->nik)
            ->orderBy('periode', 'desc')
            ->get();

        return view('dashboard', compact('riwayat_gaji'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::resource('jabatan', JabatanController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('absensi', AbsensiController::class);
    // hanya bisa diakses karyawan
    Route::get('/penggajian', [PenggajianController::class, 'index'])->name('penggajian.index');
    Route::post('/penggajian', [PenggajianController::class, 'store'])->name('penggajian.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/penggajian/export', [App\Http\Controllers\PenggajianController::class, 'exportExcel'])->name('penggajian.export');
    Route::get('/penggajian/{penggajian}', [PenggajianController::class, 'show'])->name('penggajian.show');
});




require __DIR__ . '/auth.php';
