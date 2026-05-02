<x-app-layout>

    @can('admin')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endcan

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <x-slot name="header">
        <div class="dash-page-header">
            <p class="dash-eyebrow">
                @can('admin')
                    Ringkasan Sistem
                @else
                    Akun Saya
                @endcan
            </p>
            <h2 class="dash-title">
                @can('admin')
                    Dasbor <em>Admin</em>
                @else
                    Halo, <em>{{ Auth::user()->name }}</em>
                @endcan
            </h2>
        </div>
    </x-slot>

    <div class="dash-main">

        @can('admin')

            {{-- ── STAT CARDS ── --}}
            <div class="stats-grid">
                <div class="stat-card ">
                    <p class="stat-label">Total Karyawan Aktif</p>
                    <p class="stat-value">{{ $total_karyawan }} <span>Orang</span></p>
                </div>
                <div class="stat-card ">
                    <p class="stat-label">Total Posisi Jabatan</p>
                    <p class="stat-value">{{ $total_jabatan }} <span>Posisi</span></p>
                </div>
                <div class="stat-card ">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <p class="stat-label">Total Pengeluaran Gaji</p>

                        {{-- ── FORM FILTER PERIODE ── --}}
                        <form action="{{ route('dashboard') }}" method="GET" id="form-periode-gaji">
                            <input type="month" name="periode_gaji" value="{{ request('periode_gaji', date('Y-m')) }}"
                                onchange="document.getElementById('form-periode-gaji').submit();"
                                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #F0EDE6; padding: 4px 8px; font-size: 11px; outline: none; color-scheme: dark; cursor: pointer;">
                        </form>
                    </div>

                    <p class="stat-value--rp">Rp {{ number_format($pengeluaran_total, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- ── PAYROLL CHART ── --}}
            <div class="dash-card dash-card--anim1">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Tren Pengeluaran Gaji</h3>
                        <p class="card-sub">Total Gaji Bersih per bulan</p>
                    </div>
                </div>
                <div style="position: relative; height: 360px; width: 100%">
                    <canvas id="payrollChart" data-labels="{{ json_encode($label_grafik ?? []) }}"
                        data-values="{{ json_encode($data_grafik ?? []) }}">
                    </canvas>
                </div>
            </div>
        @else
            {{-- ── EMPLOYEE GREETING ── --}}
            <div class="dash-card dash-card--anim1">


                @if (Auth::user()->nik)
                    {{-- ── SALARY TABLE ── --}}
                    <div class="card-header" style="margin-top: 4px;">
                        <h3 class="card-title">Riwayat Slip Gaji</h3>
                        <span class="card-badge">{{ $riwayat_gaji->count() }} Periode</span>
                    </div>

                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Gaji Bersih</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat_gaji as $gaji)
                                <tr>
                                    <td class="td-period">
                                        {{ \Carbon\Carbon::parse($gaji->periode)->translatedFormat('F Y') }}
                                    </td>
                                    <td class="td-amount">
                                        Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('penggajian.show', $gaji->id) }}" target="_blank"
                                            class="btn-slip">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Slip
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="td-empty">
                                        Belum ada data gaji yang dihitung untuk Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                    {{-- ── UNLINKED ACCOUNT WARNING ── --}}
                    <div class="warn-box">
                        <div class="warn-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                        </div>
                        <p class="warn-text">
                            Akun Anda belum terhubung ke data karyawan. Silakan hubungi Admin HRD untuk menautkan akun Anda.
                        </p>
                    </div>
                @endif
            </div>

        @endcan

    </div>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</x-app-layout>
