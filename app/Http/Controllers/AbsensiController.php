<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

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
}
