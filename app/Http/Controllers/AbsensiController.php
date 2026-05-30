<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Imports\AbsensiImport;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = \App\Models\Karyawan::all();
        return view('absensi.index', compact('karyawan'));
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
        // 1. Validasi Input
        $request->validate([
            'nik' => 'required|string',
            'periode' => 'required',
            'jumlah_hadir' => 'required|numeric|min:0|max:31',
            'jumlah_telat' => 'required|numeric|min:0|max:31',
            'jumlah_izin' => 'required|numeric|min:0|max:31',
            'jumlah_tidak_hadir' => 'required|numeric|min:0|max:31',
            'jam_lembur' => 'required|numeric|min:0',
        ]);


        // 2. CEK DUPLIKAT 
        $cek_absen = \App\Models\Absensi::where('nik', $request->nik)
            ->where('periode', $request->periode)
            ->first();

        if ($cek_absen) {
            // Jika sudah ada, tendang kembali dengan pesan merah
            return redirect()->back()->with('error', 'Gagal! Data absensi untuk karyawan ini pada periode tersebut sudah ada!');
        }

        // 3. SIMPAN DATA KE DATABASE
        \App\Models\Absensi::create([
            'nik' => $request->nik,
            'periode' => $request->periode,
            'jumlah_hadir' => $request->jumlah_hadir,
            'jumlah_telat' => $request->jumlah_telat,
            'jam_lembur' => $request->jam_lembur,
            'jumlah_tidak_hadir' => $request->jumlah_tidak_hadir,
            'jumlah_izin' => $request->jumlah_izin,
        ]);

        // 4. KEMBALI DENGAN PESAN SUKSES
        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        //
    }

    public function importExcel(Request $request)
    {
        // Validasi file yang diupload harus berupa Excel/CSV
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file_excel.required' => 'Pilih file Excel terlebih dahulu!',
            'file_excel.mimes' => 'Format file harus .xlsx atau .xls'
        ]);

        try {
            // Eksekusi import
            Excel::import(new AbsensiImport, $request->file('file_excel'));

            return redirect()->back()->with('success', 'Data absensi massal berhasil diimpor!');
        } catch (\Exception $e) {
            // Tangkap jika ada error dari sistem Excel
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
