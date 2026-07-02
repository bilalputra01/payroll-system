<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $penggajian->karyawan->nama_karyawan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Sembunyikan tombol print saat dicetak ke kertas/PDF */
        @media print {
            .no-print {
                display: none;
            }

            body {
                background-color: white;
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-8 font-sans">

    <div class="max-w-2xl mx-auto bg-white p-8 border border-gray-300 shadow-lg">

        <div class="text-center border-b-4 border-gray-800 pb-4 mb-6">
            <h1 class="text-2xl font-bold uppercase tracking-wider">PT. Teknologi Masa Depan</h1>
            <p class="text-sm text-gray-600">Jl. Inovasi Digital No. 99, Jakarta Selatan</p>
            <p class="text-sm font-semibold mt-2">SLIP GAJI KARYAWAN</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <p><span class="font-bold inline-block w-24">Periode</span>:
                    {{ \Carbon\Carbon::parse($penggajian->periode)->translatedFormat('F Y') }}</p>
                <p><span class="font-bold inline-block w-24">Nama</span>: {{ $penggajian->karyawan->nama_karyawan }}</p>
                <p><span class="font-bold inline-block w-24">Tgl. Masuk</span>:
                    {{ $penggajian->karyawan->tanggal_masuk ? \Carbon\Carbon::parse($penggajian->karyawan->tanggal_masuk)->translatedFormat('d F Y') : '-' }}</p>
            </div>
            <div>
                <p><span class="font-bold inline-block w-24">Jabatan</span>:
                    {{ $penggajian->karyawan->jabatan->nama_jabatan }}</p>
                <p><span class="font-bold inline-block w-24">No. Rekening</span>:
                    {{ $penggajian->karyawan->nomor_rekening }}</p>
                @php
                    // Deteksi apakah gaji diprorata: bandingkan gaji tersimpan dengan gaji penuh jabatan/khusus
                    $gaji_penuh = $penggajian->karyawan->gaji_pokok ?? $penggajian->karyawan->jabatan->gaji_pokok ?? 0;
                    $is_prorata = $gaji_penuh > 0 && abs($penggajian->gaji_pokok_saat_ini - $gaji_penuh) > 100;
                    $rasio = $gaji_penuh > 0 ? round(($penggajian->gaji_pokok_saat_ini / $gaji_penuh) * 100) : 100;
                @endphp
                @if($is_prorata)
                    <p class="mt-1">
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-0.5 rounded">
                            PRORATA {{ $rasio }}% — Masuk tengah bulan
                        </span>
                    </p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-6">
            <div>
                <h3 class="font-bold text-gray-800 border-b border-gray-400 mb-2">PENERIMAAN</h3>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between"><span>Gaji Pokok</span> <span class=" text-green-600">Rp
                            {{ number_format($penggajian->gaji_pokok_saat_ini, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Tunjangan</span> <span class=" text-green-600">Rp
                            {{ number_format($penggajian->total_tunjangan, 0, ',', '.') }}</span></div>
                    @if ($penggajian->thr > 0)
                        <div class="flex justify-between"><span>Thr</span> <span class=" text-green-600">Rp
                                {{ number_format($penggajian->thr, 0, ',', '.') }}</span></div>
                    @endif
                    <div class="flex justify-between"><span>Uang Lembur</span> <span class=" text-green-600">Rp
                            {{ number_format($penggajian->uang_lembur, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Total Gaji</span> <span class=" text-green-600">Rp
                            {{ number_format($penggajian->gaji_pokok_saat_ini + $penggajian->thr + $penggajian->total_tunjangan + $penggajian->uang_lembur, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-gray-800 border-b border-gray-400 mb-2">POTONGAN</h3>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between"><span>Potongan Kehadiran</span> <span class=" text-red-600">- Rp
                            {{ number_format($penggajian->potongan_absensi, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between"><span>BPJS Kesehatan</span> <span class=" text-red-600">- Rp
                            {{ number_format($penggajian->bpjs_kesehatan, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>BPJS Ketenagakerjaan</span> <span class=" text-red-600">- Rp
                            {{ number_format($penggajian->bpjs_ketenagakerjaan, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>PPh 21</span> <span class=" text-red-600">- Rp
                            {{ number_format($penggajian->pph21, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Total Potongan</span> <span class=" text-red-600">Rp
                            {{ number_format($penggajian->total_potongan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t-2 border-gray-800 pt-4 mb-8">
            <div class="flex justify-between items-center text-lg font-bold">
                <span>Total Diterima </span>

                <div class="flex items-center gap-3">
                    <div class="font-mono-data text-[12px]">
                        (<span class=" text-green-600">Rp
                            {{ number_format($penggajian->gaji_pokok_saat_ini + $penggajian->thr + $penggajian->total_tunjangan + $penggajian->uang_lembur, 0, ',', '.') }}</span>
                        <span class=" text-red-600">- Rp
                            {{ number_format($penggajian->total_potongan, 0, ',', '.') }}</span>)
                    </div>
                    <span class="text-green-600">Rp {{ number_format($penggajian->gaji_bersih, 0, ',', '.') }}</span>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-center mt-12 text-sm">
            <div>
                <p class="mb-16">Penerima,</p>
                <p class="font-bold underline">{{ $penggajian->karyawan->nama_karyawan }}</p>
            </div>
            <div>
                <p class="mb-16">Mengetahui, HRD</p>
                <p class="font-bold underline">{{ Auth::user()->name ?? 'Admin HRD' }}</p>
            </div>
        </div>

        <div class="mt-8 text-center no-print">
            <button onclick="window.print()"
                class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-indigo-700">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>

    </div>

</body>
