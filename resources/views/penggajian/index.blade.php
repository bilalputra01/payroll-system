<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mesin Kalkulasi Penggajian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form action="{{ route('penggajian.store') }}" method="POST" class="flex flex-wrap items-end gap-4 mb-6">
                    @csrf

                    <div class="flex flex-col">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Periode Hitung</label>
                        <input type="month" name="periode" value="{{ request('periode') }}"
                            class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                    </div>

                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-medium transition duration-150 flex items-center">
                        ⚡ Hitung Gaji Massal
                    </button>

                    <button type="button" onclick="downloadLaporan()"
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-medium transition duration-150 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Download Laporan Excel
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Penggajian Karyawan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border-b-2 py-3 px-4 text-sm">Periode</th>
                                <th class="border-b-2 py-3 px-4 text-sm">Nama Karyawan</th>
                                <th class="border-b-2 py-3 px-4 text-sm">Pendapatan (Gaji+Tunjangan)</th>
                                <th class="border-b-2 py-3 px-4 text-sm text-red-600">Potongan</th>
                                <th class="border-b-2 py-3 px-4 text-sm text-green-600 font-bold">Gaji Bersih (THP)</th>
                                <th class="border-b-2 py-3 px-4 text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penggajian as $gaji)
                                <tr class="hover:bg-gray-50">
                                    <td class="border-b py-3 px-4">{{ $gaji->periode }}</td>
                                    <td class="border-b py-3 px-4">
                                        {{ $gaji->karyawan->nama_karyawan ?? 'Data Terhapus' }}</td>
                                    <td class="border-b py-3 px-4 font-mono">Rp
                                        {{ number_format($gaji->gaji_pokok_saat_ini + $gaji->total_tunjangan, 0, ',', '.') }}
                                    </td>
                                    <td class="border-b py-3 px-4 font-mono text-red-600">- Rp
                                        {{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
                                    <td class="border-b py-3 px-4 font-mono text-green-600 font-bold">Rp
                                        {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</td>
                                    <td class="border-b py-3 px-4">
                                        <a href="{{ route('penggajian.show', $gaji->id) }}" target="_blank"
                                            class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                                            📄 Lihat Slip
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        function downloadLaporan() {
            // 1. Ambil nilai dari kotak input periode
            let bulanDipilih = document.querySelector('input[name="periode"]').value;

            // 2. Jika HRD belum memilih bulan, munculkan peringatan
            if (!bulanDipilih) {
                alert('⚠️ Gagal: Silakan isi kotak Pilih Periode Hitung terlebih dahulu!');
                return; // Hentikan proses
            }

            // 3. Jika sudah diisi, arahkan browser untuk mendownload file
            window.location.href = "{{ route('penggajian.export') }}?periode=" + bulanDipilih;
        }
    </script>

</x-app-layout>
