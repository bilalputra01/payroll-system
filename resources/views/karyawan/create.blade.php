<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('karyawan.store') }}" method="POST">
                        @csrf <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block font-medium text-sm text-gray-700">NIK Karyawan</label>
                                <input type="text" name="nik" required oninput="formatNIK(this)"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    placeholder="Cth: EMP-001">
                                @error('nik')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Nama Lengkap Karyawan</label>
                                <input type="text" name="nama_karyawan" value="{{ old('nama_karyawan') }}" required
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    placeholder="Cth: Budi Santoso">
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    placeholder="Cth: budi@perusahaan.com">
                                @error('email')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Bank</label>
                                <select name="nama_bank" id="bank_select" onchange="toggleRekening()" required
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="" disabled selected>BCA (Bank Central Asia)</option>
                                </select>
                            </div>

                            <div id="rekening_container">
                                <label class="block font-medium text-sm text-gray-700">Nomor Rekening</label>
                                <input type="number" name="nomor_rekening" id="nomor_rekening" required
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    placeholder="Pilih bank terlebih dahulu...">
                            </div>

                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Status Karyawan</label>
                                <select name="status" id="status_select" onchange="toggleJabatan()" required
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="" disabled selected>-- Pilih Status --</option>
                                    <option value="Tetap">Karyawan Tetap</option>
                                    <option value="Kontrak">Kontrak</option>
                                    {{-- <option value="Magang">Magang (Intern)</option> --}}
                                </select>
                            </div>

                            <div id="jabatan_container" class="mb-4 hidden">
                                <label class="block font-medium text-sm text-gray-700">Pilih Jabatan</label>
                                <select name="jabatan_id"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="" disabled selected>-- Pilih Jabatan --</option>
                                    @foreach ($jabatan as $j)
                                        <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t pt-4">
                            <a href="{{ route('karyawan.index') }}"
                                class="text-gray-600 hover:text-gray-900 mr-4 font-medium">Batal</a>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md font-semibold hover:bg-indigo-700 shadow-sm">
                                💾 Simpan Data Karyawan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleJabatan() {
            const status = document.getElementById('status_select').value;
            const container = document.getElementById('jabatan_container');

            if (status) {
                container.classList.remove('hidden'); // Munculkan dropdown jika status dipilih
            }
        }

        function toggleRekening() {
            const bank = document.getElementById('bank_select').value;
            const container = document.getElementById('rekening_container');
            const inputRekening = document.getElementById('nomor_rekening');

            if (bank) {
                container.classList.remove('hidden'); // Munculkan kolom rekening

                // Ubah contoh isian (placeholder) agar lebih interaktif
                if (bank === 'BCA') {
                    inputRekening.placeholder = 'Cth: 0123456789 (BCA)';
                } else if (bank === 'Mandiri') {
                    inputRekening.placeholder = 'Cth: 1370001234567 (Mandiri)';
                } else if (bank === 'BRI') {
                    inputRekening.placeholder = 'Cth: 001201000123301 (BRI)';
                }
            }
        }

        function formatNIK(input) {
            // 1. Jadikan huruf besar semua & buang karakter selain huruf/angka (agar aman saat menekan tombol backspace/hapus)
            let value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');

            // 2. Jika panjang teks sudah lebih dari 3 huruf (misal: EMP1), otomatis sisipkan tanda strip (-)
            if (value.length > 3) {
                value = value.substring(0, 3) + '-' + value.substring(3);
            }

            // 3. Tampilkan kembali teks yang sudah rapi ke dalam form
            input.value = value;
        }
    </script>

</x-app-layout>
