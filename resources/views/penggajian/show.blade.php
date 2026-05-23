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
            </div>
            <div>
                <p><span class="font-bold inline-block w-24">Jabatan</span>:
                    {{ $penggajian->karyawan->jabatan->nama_jabatan }}</p>
                <p><span class="font-bold inline-block w-24">No. Rekening</span>:
                    {{ $penggajian->karyawan->nomor_rekening }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-6">
            <div>
                <h3 class="font-bold text-gray-800 border-b border-gray-400 mb-2">PENERIMAAN</h3>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between"><span>Gaji Pokok</span> <span>Rp
                            {{ number_format($penggajian->gaji_pokok_saat_ini, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Tunjangan</span> <span>Rp
                            {{ number_format($penggajian->total_tunjangan, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Uang Lembur</span> <span>Rp
                            {{ number_format($penggajian->uang_lembur, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Pendapatan Gaji + Tunjangan</span> <span>Rp
                            {{ number_format($penggajian->gaji_pokok_saat_ini + $penggajian->total_tunjangan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-gray-800 border-b border-gray-400 mb-2">POTONGAN</h3>
                <div class="space-y-1 text-sm text-red-600">
                    <div class="flex justify-between"><span>Absensi/Telat</span> <span>- Rp
                            {{ number_format($penggajian->total_potongan - ($penggajian->bpjs_kesehatan + $penggajian->bpjs_ketenagakerjaan + $penggajian->pph21), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between"><span>BPJS Kesehatan</span> <span>- Rp
                            {{ number_format($penggajian->bpjs_kesehatan, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>BPJS Ketenagakerjaan</span> <span>- Rp
                            {{ number_format($penggajian->bpjs_ketenagakerjaan, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>PPh 21</span> <span>- Rp
                            {{ number_format($penggajian->pph21, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Total Potongan</span> <span>Rp
                            {{ number_format($penggajian->total_potongan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t-2 border-gray-800 pt-4 mb-8">
            <div class="flex justify-between text-lg font-bold">
                <span>TOTAL DITERIMA (TAKE HOME PAY)</span>
                <span class="text-green-600">Rp {{ number_format($penggajian->gaji_bersih, 0, ',', '.') }}</span>
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
