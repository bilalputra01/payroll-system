<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data karyawan, dan tarik juga data 'jabatan' yang berelasi dengannya
        $karyawan = \App\Models\Karyawan::with('jabatan')->get();

        // Kirim data tersebut ke halaman tampilan (view)
        return view('karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatan = \App\Models\Jabatan::all();
        $nik_terakhir = \App\Models\Karyawan::orderBy('nik', 'desc')->first();
        if ($nik_terakhir) {
            $angka_terakhir = (int) substr($nik_terakhir->nik, 4);
            $nik_baru = $angka_terakhir + 1;
        } else {
            $nik_baru = 1;
        }
        $nik_otomatis = 'KRY-' . str_pad($nik_baru, 3, '0', STR_PAD_LEFT);
        return view('karyawan.create', compact('jabatan', 'nik_otomatis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Semua Inputan
        $request->validate([
            'nik' => 'required|string|unique:karyawan,nik',
            'nama_karyawan' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'email' => 'required|email|unique:karyawan,email|unique:users,email',
            'status' => 'required|in:Tetap,Kontrak,Magang',
            'jabatan_id' => 'required',
            'nama_bank' => 'required|in:BCA',
            'nomor_rekening' => 'required|numeric|unique:karyawan,nomor_rekening|max_digits:50',
            'tanggal_masuk' => 'required|date',
        ]);

        // 2. Simpan Data Karyawan (Termasuk Bank & Rekening)
        $karyawan_baru = \App\Models\Karyawan::create([
            'nik' => $request->nik,
            'nama_karyawan' => $request->nama_karyawan,
            'email' => $request->email,
            'status' => $request->status,
            'jabatan_id' => $request->jabatan_id,
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'tanggal_masuk' => $request->tanggal_masuk,
        ]);

        // 3. Otomatis Buatkan Akun Login
        $username_karyawan = strtolower(str_replace(' ', '', $request->nama_karyawan));

        \App\Models\User::create([
            'name' => $karyawan_baru->nama_karyawan,
            'username' => $username_karyawan . rand(100, 999), // Username unik
            'email' => $karyawan_baru->email,
            'role' => 'karyawan',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'nik' => $karyawan_baru->nik, // Tali pengikat ke karyawan
        ]);

        // 4. Kembali ke halaman tabel
        return redirect()->route('karyawan.index')->with('success', 'Data Karyawan Dan Akun Login berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        $jabatan = \App\Models\Jabatan::all();
        return view('karyawan.edit', compact('karyawan', 'jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        // Validasi — unique dikecualikan untuk data milik karyawan itu sendiri
        $request->validate([
            'nama_karyawan' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\x27\-]+$/'],
            'email'         => 'required|email|unique:karyawan,email,' . $karyawan->nik . ',nik|unique:users,email,' . $karyawan->nik . ',nik',
            'status'        => 'required|in:Tetap,Kontrak,Magang',
            'jabatan_id'    => 'required|exists:jabatan,id',
            'nama_bank'     => 'required|in:BCA',
            'nomor_rekening'=> 'required|numeric|unique:karyawan,nomor_rekening,' . $karyawan->nik . ',nik|max_digits:50',
            'tanggal_masuk' => 'required|date',
        ]);

        // Update data karyawan
        $karyawan->update([
            'nama_karyawan'  => $request->nama_karyawan,
            'email'          => $request->email,
            'status'         => $request->status,
            'jabatan_id'     => $request->jabatan_id,
            'nama_bank'      => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'tanggal_masuk'  => $request->tanggal_masuk,
        ]);

        // Sinkronisasi email & nama di tabel users jika berubah
        \App\Models\User::where('nik', $karyawan->nik)->update([
            'name'  => $request->nama_karyawan,
            'email' => $request->email,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        // 1. Cari data karyawan berdasarkan NIK
        $karyawan = \App\Models\Karyawan::where('nik', $nik)->firstOrFail();

        // 2. Hapus akun login (User) yang terhubung dengan NIK tersebut
        \App\Models\User::where('nik', $karyawan->nik)->delete();

        // 3. Hapus data karyawannya
        $karyawan->delete();

        // 4. Kembalikan ke halaman depan dengan pesan sukses
        return redirect()->route('karyawan.index')->with('success', 'Data karyawan beserta akun loginnya berhasil dihapus permanen!');
    }
}
