<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Proyek Aktif & Selesai</h3>
                    <a href="{{ route('proyek.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                        + Proyek Baru
                    </a>
                </div>

                <div class="p-6">
                    <table id="proyekTable" class="w-full cell-border stripe hover">
                        <thead>
                            <tr class="text-left text-gray-500 uppercase text-xs tracking-wider">
                                <th>Nama Proyek</th>
                                <th>Client</th>
                                <th>Nilai Kontrak</th>
                                <th>Mulai</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach ($proyeks as $p)
                                <tr>
                                    <td class="font-bold">{{ $p->nama }}</td>
                                    <td>{{ $p->nama_pemberi }}</td>
                                    <td class="text-emerald-600 font-semibold">Rp
                                        {{ number_format($p->nilai_kontrak, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }}</td>
                                    <td>
                                        <span
                                            class="px-2 py-1 {{ $p->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} rounded text-[10px] font-bold uppercase">
                                            {{ $p->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('proyek.edit', $p->id_proyek) }}"
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button type="button"
                                                onclick="deleteProyek('{{ $p->id_proyek }}', '{{ $p->nama }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $p->id_proyek }}"
                                                action="{{ route('proyek.destroy', $p->id_proyek) }}" method="POST"
                                                class="hidden">@csrf @method('DELETE')</form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th><input type="text" placeholder="Cari Proyek"
                                        class="w-full text-xs rounded border-gray-200"></th>
                                <th><input type="text" placeholder="Cari Client"
                                        class="w-full text-xs rounded border-gray-200"></th>
                                <th></th>
                                <th></th>
                                <th></th>
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
            padding-right: 2rem;
            border-radius: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.5rem;
            padding: 0.4rem 1rem;
            border: 1px solid #e5e7eb;
        }

        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
            margin-bottom: 2rem !important;
        }

        table.dataTable thead th {
            background-color: #f9fafb;
            border-bottom: 1px solid #f3f4f6 !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#proyekTable').DataTable({
                responsive: true,
                "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lfr>t<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
            });
            table.columns().every(function() {
                var that = this;
                $('input', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        });

        function deleteProyek(id, name) {
            Swal.fire({
                title: 'Hapus Proyek?',
                text: "Semua data transaksi di proyek " + name + " akan ikut terpengaruh!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
