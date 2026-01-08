<x-app-layout>
    @section('title', 'Data Kas Masuk')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaksi Kas Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 text-600">Penerimaan Kas</h3>
                        <p class="text-xs text-gray-500 font-medium">Monitoring dana masuk dari termin proyek & umum</p>
                    </div>
                    <a href="{{ route('kas-masuk.create') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md shadow-emerald-100 uppercase tracking-tighter">
                        + Input Kas Masuk
                    </a>
                </div>

                <div class="p-6">
                    <table id="kasMasukTable" class="w-full cell-border stripe hover">
                        <thead>
                            <tr class="text-left text-gray-500 uppercase text-[10px] tracking-wider">
                                <th>No. Form</th>
                                <th>Tanggal</th>
                                <th>Kategori & Proyek</th>
                                <th>Metode</th>
                                <th>Nominal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach ($kas_masuks as $k)
                                <tr>
                                    <td class="font-mono font-bold text-indigo-600">{{ $k->no_form }}</td>
                                    <td>{{ \Carbon\Carbon::parse($k->tanggal_masuk)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="font-bold text-gray-800">{{ $k->nama_kategori }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase leading-none italic">
                                            {{ $k->nama_proyek ?? 'Penerimaan Umum' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $k->nama_metode_bayar == 'Bank' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $k->nama_metode_bayar }}
                                        </span>
                                    </td>
                                    <td class="text-emerald-600 font-black">
                                        Rp {{ number_format($k->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('kas-masuk.edit', $k->id_kas_masuk) }}"
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button type="button"
                                                onclick="confirmDelete('{{ $k->id_kas_masuk }}', '{{ $k->no_form }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                                title="Hapus Transaksi">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                            <form id="delete-form-{{ $k->id_kas_masuk }}"
                                                action="{{ route('kas-masuk.destroy', $k->id_kas_masuk) }}"
                                                method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th><input type="text" placeholder="No Form"
                                        class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th><input type="text" placeholder="Tanggal"
                                        class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th><input type="text" placeholder="Kategori/Proyek"
                                        class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th><input type="text" placeholder="Metode"
                                        class="w-full text-[10px] rounded border-gray-200 p-1"></th>
                                <th class="text-right">Total Page:</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dataTables_wrapper .dataTables_length select {
            border-radius: 0.75rem;
            font-size: 0.75rem;
            border-color: #f3f4f6;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.75rem;
            border-color: #f3f4f6;
            padding: 0.5rem 1rem;
        }

        table.dataTable.cell-border tbody td {
            border-top: 1px solid #f9fafb !important;
            border-left: none !important;
            border-right: none !important;
            padding: 12px 10px;
        }

        table.dataTable thead th {
            border-bottom: 2px solid #f3f4f6 !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#kasMasukTable').DataTable({
                responsive: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lfr>t<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari transaksi..."
                }
            });

            // Filter per kolom
            table.columns().every(function() {
                var that = this;
                $('input', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        });
    </script>

    <script>
        // Fungsi Konfirmasi Hapus
        function confirmDelete(id, no_form) {
            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Transaksi " + no_form + " akan dihapus permanen dan tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48', // Warna Merah Rose
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Saja!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jalankan form delete
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Menampilkan Pesan Error/Success dari Controller
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{!! addslashes(session('error')) !!}",
                    confirmButtonColor: '#e11d48'
                });
            @endif
        });
    </script>
</x-app-layout>
