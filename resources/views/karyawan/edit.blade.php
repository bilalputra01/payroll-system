<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/karyawan/create.css') }}">

    {{-- ── Page header ── --}}
    <div class="kry-header">
        <div>
            <p class="kry-eyebrow">Manajemen SDM</p>
            <h2 class="kry-title">Edit Data <em>Karyawan</em></h2>
        </div>
    </div>

    {{-- ── Form card ── --}}
    <div class="kry-card">
        <p class="section-label">Informasi Karyawan — {{ $karyawan->nik }}</p>

        <form action="{{ route('karyawan.update', $karyawan->nik) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="kry-grid">

                {{-- NIK: read-only, tidak bisa diubah --}}
                <div class="field">
                    <label>NIK</label>
                    <input type="text" name="nik" value="{{ $karyawan->nik }}" readonly
                        style="background-color: #334155; color: #94a3b8; cursor: not-allowed; border-color: #475569;">
                </div>

                <div class="field">
                    <label for="nama_karyawan">Nama Karyawan</label>
                    <input type="text" name="nama_karyawan" id="nama_karyawan"
                        value="{{ old('nama_karyawan', $karyawan->nama_karyawan) }}" pattern="[a-zA-Z\s\.\'\-]+"
                        title="Nama hanya boleh berisi huruf, spasi, titik, atau tanda kutip"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s\.\'\-]/g, '')"
                        placeholder="Contoh: Muhammad Bilal" required>
                    @error('nama_karyawan')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label>Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $karyawan->email) }}"
                        placeholder="Cth: budi@perusahaan.com" required>
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label>Bank</label>
                    <div class="select-wrap">
                        <select name="nama_bank" id="bank_select" required>
                            <option value="BCA"
                                {{ old('nama_bank', $karyawan->nama_bank) == 'BCA' ? 'selected' : '' }}>BCA (Bank
                                Central Asia)</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>Nomor Rekening</label>
                    <input type="number" name="nomor_rekening"
                        value="{{ old('nomor_rekening', $karyawan->nomor_rekening) }}"
                        placeholder="Masukkan nomor rekening" required>
                    @error('nomor_rekening')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label>Status Karyawan</label>
                    <div class="select-wrap">
                        <select name="status" id="status_select" required>
                            <option value="Tetap"
                                {{ old('status', $karyawan->status) == 'Tetap' ? 'selected' : '' }}>Karyawan Tetap
                            </option>
                            {{-- <option value="Kontrak" {{ old('status', $karyawan->status) == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="Magang"  {{ old('status', $karyawan->status) == 'Magang'  ? 'selected' : '' }}>Magang</option> --}}
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>Jabatan</label>
                    <div class="select-wrap">
                        <select name="jabatan_id" required>
                            <option value="" disabled>-- Pilih Jabatan --</option>
                            @foreach ($jabatan as $j)
                                <option value="{{ $j->id }}"
                                    {{ old('jabatan_id', $karyawan->jabatan_id) == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama_jabatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('jabatan_id')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label>Tanggal Masuk Kerja</label>
                    <input type="date" name="tanggal_masuk"
                        value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk ? \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('Y-m-d') : '') }}"
                        required>
                    <small style="color: #94a3b8; font-size: 11px; margin-top: 4px;">
                        * Digunakan untuk prorata gaji jika masuk di tengah bulan
                    </small>
                    @error('tanggal_masuk')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="kry-footer">
                <a href="{{ route('karyawan.index') }}" class="btn-batal">Batal</a>
                <button type="submit" class="btn-simpan">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</x-app-layout>
