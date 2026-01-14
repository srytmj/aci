<x-app-layout>
    @section('title', 'Master LRA')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Laporan Realisasi Anggaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Form Input (Style Proyek Baru) --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6 transition-colors duration-300">
                <h3 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 mb-4 uppercase tracking-widest">Tambah Master LRA</h3>
                <form action="{{ route('lra.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Keterangan</label>
                        <input type="text" name="keterangan" required placeholder="Nama Kategori LRA"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Persentase (%)</label>
                        <input type="number" name="persentase" required min="0" max="100"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Jenis</label>
                        <select name="jenis" required
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500">
                            <option value="pendapatan">PENDAPATAN</option>
                            <option value="pengeluaran">PENGELUARAN</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-indigo-500/30">
                        + Simpan Data
                    </button>
                </form>
            </div>

            {{-- Table LRA (Style Proyek Table) --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daftar Struktur LRA</h3>
                </div>

                <div class="p-6">
                    <table id="lraTable" class="w-full cell-border stripe hover">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400 uppercase text-xs tracking-wider">
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th class="text-center">Persentase</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                            {{-- Filter Row --}}
                            <tr class="bg-gray-50 dark:bg-gray-900/50 filter-row">
                                <th class="p-2">
                                    <select class="w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                                        <option value="">Semua</option>
                                        <option value="pendapatan">PENDAPATAN</option>
                                        <option value="pengeluaran">PENGELUARAN</option>
                                    </select>
                                </th>
                                <th class="p-2">
                                    <input type="text" placeholder="Cari Keterangan"
                                        class="w-full text-[10px] rounded-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 focus:ring-indigo-500">
                                </th>
                                <th class="p-2"></th>
                                <th class="p-2"></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 dark:text-gray-300">
                            @foreach ($lras as $l)
                                <tr>
                                    <td>
                                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $l->jenis == 'pendapatan' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400' }}">
                                            {{ $l->jenis }}
                                        </span>
                                    </td>
                                    <td class="font-bold text-gray-900 dark:text-white">{{ $l->keterangan }}</td>
                                    <td class="text-center font-mono text-indigo-600 dark:text-indigo-400 font-bold">
                                        {{ $l->persentase }}%
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <button type="button" 
                                                onclick="deleteLra('{{ $l->id_lra }}', '{{ $l->keterangan }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $l->id_lra }}" action="{{ route('lra.destroy', $l->id_lra) }}" method="POST" class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .filter-row th { background-image: none !important; cursor: default !important; border-bottom: 1px solid #e5e7eb !important; }
        .dark .filter-row th { border-bottom: 1px solid #374151 !important; }
        table.dataTable { border-collapse: collapse !important; width: 100% !important; margin-bottom: 2rem !important; }
        table.dataTable thead th { background-color: #f9fafb; border-bottom: 1px solid #f3f4f6 !important; }
        .dark table.dataTable thead th { background-color: #111827; border-bottom: 1px solid #374151 !important; color: #9ca3af; }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#lraTable').DataTable({
                responsive: true,
                dom: '<"flex justify-between items-center mb-4"l>t<"flex justify-between items-center mt-4"ip>',
                orderCellsTop: true,
                order: [[0, 'asc']]
            });

            // Filter Otomatis
            $('#lraTable thead tr:eq(1) th').each(function(i) {
                $('input, select', this).on('input change', function() {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });
        });

        function deleteLra(id, name) {
            Swal.fire({
                title: 'Hapus Master LRA?',
                text: "Kategori " + name + " akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#4f46e5' });
        @endif
    </script>
</x-app-layout>