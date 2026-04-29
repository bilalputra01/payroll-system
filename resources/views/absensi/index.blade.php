<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Absensi Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('absensi.store') }}">
                    @csrf <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mb-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nama Karyawan</label>
                            <select name="nik"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach ($karyawan as $k)
                                    <option value="{{ $k->nik }}">{{ $k->nama_karyawan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Periode</label>
                            <input type="month" name="periode"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                required>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Hari Hadir</label>
                            <input type="number" name="jumlah_hadir" placeholder="Misal: 20" min="0"
                                max="31"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                required>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Hari Terlambat</label>
                            <input type="number" name="jumlah_telat" placeholder="Misal: 2" min="0"
                                max="31"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                required>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Simpan Data Absensi
                        </button>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Jam Lembur</label>
                        <input type="number" name="jam_lembur" placeholder="0" min="0"
                            class="border-gray-300 rounded-md shadow-sm block mt-1 w-full">
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
