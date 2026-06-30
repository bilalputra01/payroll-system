<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/karyawan/index.css') }}">

    {{-- ── Page header ── --}}
    <div class="kry-header">
        <div>
            <p class="kry-eyebrow">Manajemen SDM</p>
            <h2 class="kry-title">Daftar <em>Karyawan</em> Aktif</h2>
        </div>
        <a href="{{ route('karyawan.create') }}" class="btn-tambah">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Karyawan
        </a>
    </div>

    {{-- ── Table card ── --}}
    <div class="kry-card">
        <table id="tabel-karyawan" class="kry-table">
            <thead>
                <tr>
                    <th>Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>Gaji Pokok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($karyawan as $k)
                    <tr>
                        <td>{{ $k->nama_karyawan }}</td>
                        <td>
                            <span class="badge-jabatan">
                                {{ $k->jabatan->nama_jabatan }}
                            </span>
                        </td>
                        <td>
                            @php
                                // Menentukan class CSS berdasarkan status (diubah ke huruf kecil semua agar aman)
                                $statusClass = '';
                                $statusText = strtolower($k->status); // Sesuaikan '$k' dengan variabel foreach Anda

                                if ($statusText == 'tetap') {
                                    $statusClass = 'status-tetap';
                                } elseif ($statusText == 'kontrak') {
                                    $statusClass = 'status-kontrak';
                                    // } elseif ($statusText == 'magang') {
                                    //     $statusClass = 'status-magang';
                                }
                            @endphp

                            <div class="badge-status {{ $statusClass }}">
                                <span class="dot"></span>
                                {{ $k->status }}
                            </div>
                        </td>
                        <td class="td-salary">
                            Rp {{ number_format($k->jabatan->gaji_pokok, 0, ',', '.') }}
                        </td>
                        <td>
                            {{-- Tombol Edit --}}
                            <a href="{{ route('karyawan.edit', $k->nik) }}" class="btn-edit">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('karyawan.destroy', $k->nik) }}" method="POST" class="inline-block"
                                id="form-hapus-{{ $k->nik }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-hapus"
                                    onclick="konfirmasiHapus('{{ $k->nik }}', '{{ $k->nama_karyawan }}')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabel-karyawan').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                dom: '<"dt-top"lf>rt<"dt-bottom"ip>',
                initComplete: function() {
                    // Style the search input after DataTables injects it
                    $('.dataTables_filter input').attr('placeholder', 'Cari karyawan...');
                }
            });
        });

        function konfirmasiHapus(nik, namaKaryawan) {
            Swal.fire({
                title: 'Hapus Karyawan?',
                text: 'Data ' + namaKaryawan +
                    ' beserta akun loginnya akan dihapus permanen dan tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus Permanen',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-hapus-' + nik).submit();
                }
            });
        }
    </script>

</x-app-layout>
