<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/penggajian/index.css') }}">

    {{-- ── Page header ── --}}
    <div class="kry-header">
        <div>
            <p class="kry-eyebrow">Manajemen SDM</p>
            <h2 class="kry-title">Mesin <em>Kalkulasi</em> Penggajian</h2>
        </div>
    </div>

    {{-- ── Toolbar card ── --}}
    <div class="kry-card">
        <p class="section-label">Periode & Aksi</p>
        <form action="{{ route('penggajian.store') }}" method="POST" class="toolbar">
            @csrf
            <div class="field">
                <label>Pilih Periode Hitung</label>
                <input type="month" name="periode" value="{{ request('periode') }}" required>
            </div>
            <button type="submit" class="btn-hitung">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Hitung Gaji Massal
            </button>
            <button type="button" class="btn-export" onclick="downloadLaporan()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Laporan Excel
            </button>
        </form>
    </div>

    {{-- ── Table card ── --}}
    <div class="kry-card">
        <p class="section-label">Riwayat Penggajian Karyawan</p>
        <table class="kry-table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Nama Karyawan</th>
                    <th>Pendapatan (Gaji + Tunjangan)</th>
                    <th>Potongan</th>
                    <th>Gaji Bersih (THP)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penggajian as $gaji)
                    <tr>
                        <td class="td-period">{{ $gaji->periode }}</td>
                        <td>{{ $gaji->karyawan->nama_karyawan ?? 'Data Terhapus' }}</td>
                        <td class="td-income">
                            Rp {{ number_format($gaji->gaji_pokok_saat_ini + $gaji->total_tunjangan, 0, ',', '.') }}
                        </td>
                        <td class="td-deduct">
                            - Rp {{ number_format($gaji->total_potongan, 0, ',', '.') }}
                        </td>
                        <td class="td-net">
                            Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('penggajian.show', $gaji->id) }}" target="_blank" class="btn-slip">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Lihat Slip
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function downloadLaporan() {
            let bulanDipilih = document.querySelector('input[name="periode"]').value;
            if (!bulanDipilih) {
                alert('Silakan isi kotak Pilih Periode Hitung terlebih dahulu!');
                return;
            }
            window.location.href = "{{ route('penggajian.export') }}?periode=" + bulanDipilih;
        }
    </script>

</x-app-layout>
