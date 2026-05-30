<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/absensi/index.css?v=' . time()) }}">

    {{-- ── Page header ── --}}
    <div class="kry-header">
        <div>
            <p class="kry-eyebrow">Manajemen SDM</p>
            <h2 class="kry-title">Kelola Data <em>Absensi</em> Karyawan</h2>
        </div>
    </div>

    <div class="kry-card" style="margin-bottom: 1.5rem;">
        <p class="section-label">Import Data Absensi</p>

        <form action="{{ route('absensi.import') }}" method="POST" enctype="multipart/form-data"
            style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;">
            @csrf
            <div class="field" style="flex: 1; min-width: 250px; margin-bottom: 0;">
                <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required
                    style="width: 100%; padding: 0.5rem; border: 1px dashed #cbd5e1; border-radius: 0.5rem;; cursor: pointer;">
            </div>

            <button type="submit" class="btn-upload">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Upload Excel
            </button>
        </form>
    </div>

    {{-- ── Form card ── --}}
    <div class="kry-card">
        <p class="section-label">Input Data Absensi</p>

        <form action="{{ route('absensi.store') }}" method="POST">
            @csrf

            <div class="kry-grid">

                <div class="field">
                    <label>Nama Karyawan</label>
                    <div class="select-wrap">
                        <select name="nik" required>
                            <option value="" disabled selected>-- Pilih Karyawan --</option>
                            @foreach ($karyawan as $k)
                                <option value="{{ $k->nik }}">{{ $k->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>Periode</label>
                    <input type="month" name="periode" required>
                </div>

                <div class="field">
                    <label>Jumlah Hadir</label>
                    <input type="number" name="jumlah_hadir" placeholder="Misal: 20" min="0" max="31"
                        required>
                </div>

                <div class="field">
                    <label>Hari Terlambat</label>
                    <input type="number" name="jumlah_telat" placeholder="Misal: 2" min="0" max="31"
                        required>
                </div>

                <div class="field">
                    <label>Jumlah Izin</label>
                    <input type="number" name="jumlah_izin" placeholder="Misal: 2" min="0" max="31"
                        required>
                </div>

                <div class="field">
                    <label>Jumlah Alpa</label>
                    <input type="number" name="jumlah_tidak_hadir" placeholder="Misal: 2" min="0" max="31"
                        required>
                </div>

                <div class="field">
                    <label>Jam Lembur</label>
                    <input type="number" name="jam_lembur" placeholder="0" min="0">
                </div>

            </div>

            <div class="kry-grid-2">
            </div>

            <hr class="kry-divider">

            <div class="kry-footer">
                <button type="submit" class="btn-simpan">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Data Absensi
                </button>
            </div>

        </form>
    </div>

</x-app-layout>
