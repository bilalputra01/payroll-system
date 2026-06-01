<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/karyawan/create.css') }}">

    {{-- ── Page header ── --}}
    <div class="kry-header">
        <div>
            <p class="kry-eyebrow">Manajemen SDM</p>
            <h2 class="kry-title">Tambah <em>Karyawan</em> Baru</h2>
        </div>
    </div>

    {{-- ── Form card ── --}}
    <div class="kry-card">
        <p class="section-label">Informasi Karyawan</p>

        <form action="{{ route('karyawan.store') }}" method="POST">
            @csrf

            <div class="kry-grid">

                <div class="field">
                    <label>NIK</label>
                    <input type="text" name="nik" value="{{ $nik_otomatis }}" readonly
                        style="background-color: #334155; color: #94a3b8; cursor: not-allowed; border-color: #475569;"
                        required>
                </div>

                <div class="field">
                    <label>Nama Lengkap Karyawan</label>
                    <input type="text" name="nama_karyawan" value="{{ old('nama_karyawan') }}" required
                        placeholder="Cth: Budi Santoso">
                </div>

                <div class="field">
                    <label>Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="Cth: budi@perusahaan.com">
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label>Bank</label>
                    <div class="select-wrap">
                        <select name="nama_bank" id="bank_select" onchange="toggleRekening()" required>
                            <option value="BCA">BCA (Bank Central Asia)</option>
                        </select>
                    </div>
                </div>

                <div class="field" id="rekening_container">
                    <label>Nomor Rekening</label>
                    <input type="number" name="nomor_rekening" id="nomor_rekening" required
                        placeholder="Pilih bank terlebih dahulu...">
                </div>

                <div class="field">
                    <label>Status Karyawan</label>
                    <div class="select-wrap">
                        <select name="status" id="status_select" onchange="toggleJabatan()" required>
                            <option value="" disabled selected>-- Pilih Status --</option>
                            <option value="Tetap">Karyawan Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                        </select>
                    </div>
                </div>

                <div class="field field-hidden" id="jabatan_container">
                    <label>Pilih Jabatan</label>
                    <div class="select-wrap">
                        <select name="jabatan_id">
                            <option value="" disabled selected>-- Pilih Jabatan --</option>
                            @foreach ($jabatan as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            <div class="kry-footer">
                <a href="{{ route('karyawan.index') }}" class="btn-batal">Batal</a>
                <button type="submit" class="btn-simpan">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Data Karyawan
                </button>
            </div>

        </form>
    </div>

    <script>
        function toggleJabatan() {
            const status = document.getElementById('status_select').value;
            const el = document.getElementById('jabatan_container');
            if (status) el.classList.remove('field-hidden');
        }

        function toggleRekening() {
            const bank = document.getElementById('bank_select').value;
            const container = document.getElementById('rekening_container');
            const input = document.getElementById('nomor_rekening');
            if (bank) {
                container.classList.remove('field-hidden');
                if (bank === 'BCA') input.placeholder = 'Cth: 0123456789 (BCA)';
                else if (bank === 'Mandiri') input.placeholder = 'Cth: 1370001234567 (Mandiri)';
                else if (bank === 'BRI') input.placeholder = 'Cth: 001201000123301 (BRI)';
            }
        }

        function formatNIK(input) {
            let value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            if (value.length > 3) {
                value = value.substring(0, 3) + '-' + value.substring(3);
            }
            input.value = value;
        }
    </script>

</x-app-layout>
