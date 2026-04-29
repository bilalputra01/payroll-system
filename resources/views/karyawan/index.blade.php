<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Daftar Karyawan Aktif</h3>

                <a href="{{ route('karyawan.create') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
                    + Tambah Karyawan
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <table id="tabel-karyawan" class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b-2 py-3 px-4">Nama Karyawan</th>
                            <th class="border-b-2 py-3 px-4">Jabatan</th>
                            <th class="border-b-2 py-3 px-4">Status</th>
                            <th class="border-b-2 py-3 px-4">Gaji Pokok</th>
                            <th class="border-b-2 py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($karyawan as $k)
                            <tr class="hover:bg-gray-50">
                                <td class="border-b py-3 px-4">{{ $k->nama_karyawan }}</td>
                                <td class="border-b py-3 px-4">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $k->jabatan->nama_jabatan }}
                                    </span>
                                </td>
                                <td class="border-b py-3 px-4">{{ $k->status }}</td>
                                <td class="py-4 px-6 text-sm text-gray-900">
                                    Rp {{ number_format($k->jabatan->gaji_pokok, 0, ',', '.') }}
                                </td>
                                <td class="border-b py-3 px-4">
                                    <form action="{{ route('karyawan.destroy', $k->nik) }}" method="POST"
                                        class="inline-block" id="form-hapus-{{ $k->nik }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                            class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded shadow"
                                            onclick="konfirmasiHapus('{{ $k->nik }}', '{{ $k->nama_karyawan }}')">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Mengubah tabel biasa menjadi DataTables yang canggih
            $('#tabel-karyawan').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json" // Mengubah bahasanya jadi Bahasa Indonesia
                }
            });
        });

        function konfirmasiHapus(nik, namaKaryawan) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data karyawan " + namaKaryawan +
                    " beserta akun loginnya akan dihapus permanen dan tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // Warna merah sejajar dengan Tailwind red-500
                cancelButtonColor: '#6b7280', // Warna abu-abu
                confirmButtonText: 'Ya, Hapus Permanen!',
                cancelButtonText: 'Batal',
                reverseButtons: true // Menukar posisi tombol agar 'Batal' di kiri
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika tombol 'Ya' diklik, cari form berdasarkan ID NIK, lalu jalankan submit!
                    document.getElementById('form-hapus-' + nik).submit();
                }
            });
        }
    </script>

</x-app-layout>
