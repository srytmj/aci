<x-app-layout>
    @section('title', 'Jurnal Umum')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Jurnal Umum Konstruksi') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter Periode --}}
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6 print:hidden transition-colors duration-300">
                <form action="{{ route('jurnal.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Bulan</label>
                            <select name="bulan"
                                class="rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500 w-40">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ sprintf('%02d', $m) }}"
                                        {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tahun</label>
                            <select name="tahun"
                                class="rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500 w-32">
                                @foreach (range(date('Y') - 2, date('Y') + 2) as $y)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl text-sm font-bold transition shadow-lg shadow-indigo-500/30">
                        Filter
                    </button>
                </form>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <div
                    class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest text-sm">
                            Buku Jurnal Umum</h3>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-tighter">
                            Periode: {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} {{ $tahun }}
                        </p>
                    </div>
                    <div id="exportButtons" class="flex gap-2"></div>
                </div>

                <div class="p-6 overflow-x-auto">
                    <table id="jurnalTable" class="w-full cell-border">
                        <thead>
                            <tr class="bg-gray-800 dark:bg-gray-900 text-white text-[11px] uppercase tracking-wider">
                                <th class="p-4 text-left rounded-l-xl">Tanggal</th>
                                <th class="p-4 text-left">Ref</th>
                                <th class="p-4 text-left">Kode Akun</th>
                                <th class="p-4 text-left">Keterangan & Akun</th>
                                <th class="p-4 text-right">Debit (Rp)</th>
                                <th class="p-4 text-right rounded-r-xl">Kredit (Rp)</th>
                            </tr>

                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50 dark:divide-gray-700">
                            @forelse ($jurnals as $j)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="p-4 text-gray-600 dark:text-gray-400 font-medium">
                                        {{ \Carbon\Carbon::parse($j->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td class="p-4">
                                        @if ($j->no_ref)
                                            <span
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold tracking-wider border 
                        {{ str_contains($j->no_ref, 'K') ? 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-500/10' : 'bg-indigo-50 text-indigo-600 border-indigo-100 dark:bg-indigo-500/10' }}">
                                                {{ $j->no_ref }}
                                            </span>
                                        @else
                                            <span
                                                class="text-gray-300 dark:text-gray-600 italic text-[10px]">Manual</span>
                                        @endif
                                    </td>
                                    <td class="p-4 font-mono text-xs text-indigo-600 dark:text-indigo-400 font-bold">
                                        {{ $j->kode_akun }}
                                    </td>
                                    <td class="p-4">
                                        <div
                                            class="{{ $j->posisi_dr_cr == 'cr' ? 'ml-8 text-gray-500 dark:text-gray-400' : 'font-bold text-gray-800 dark:text-gray-200' }}">
                                            {{ $j->nama_akun }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 dark:text-gray-500 italic">
                                            {{ $j->deskripsi }}
                                        </div>
                                    </td>

                                    {{-- Kolom Debit --}}
                                    <td class="p-4 text-right font-bold text-gray-700 dark:text-gray-300">
                                        @if ($j->posisi_dr_cr == 'dr' && $j->nominal > 0)
                                            Rp. {{ number_format($j->nominal, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Kolom Kredit --}}
                                    <td class="p-4 text-right font-bold text-gray-700 dark:text-gray-300">
                                        @if ($j->posisi_dr_cr == 'cr' && $j->nominal > 0)
                                            Rp. {{ number_format($j->nominal, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-10 text-center text-gray-400 dark:text-gray-600 italic">
                                        Data jurnal periode ini belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot
                            class="bg-indigo-50 dark:bg-indigo-900/20 font-black text-gray-900 dark:text-white border-t-2 border-indigo-200 dark:border-indigo-800">
                            <tr>
                                <td colspan="4" class="p-4 text-left uppercase tracking-widest text-[10px]">TOTAL
                                    PERIODE</td>
                                <td class="p-4 text-right text-indigo-600 dark:text-indigo-400">
                                    Rp
                                    {{ number_format($jurnals->where('posisi_dr_cr', 'dr')->sum('nominal'), 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-right text-indigo-600 dark:text-indigo-400">
                                    Rp
                                    {{ number_format($jurnals->where('posisi_dr_cr', 'cr')->sum('nominal'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <style>
        .filter-input {
            width: 100%;
            font-size: 10px;
            padding: 4px 8px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            color: #374151;
        }

        .dark .filter-input {
            background-color: #1f2937;
            border-color: #374151;
            color: #d1d5db;
        }

        .filter-row th {
            background-image: none !important;
            cursor: default !important;
        }

        table.dataTable.no-footer {
            border-bottom: none !important;
        }

        .dataTables_wrapper {
            border: none !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#jurnalTable').DataTable({
                responsive: true,
                paging: false,
                info: false,
                ordering: false,
                orderCellsTop: true,
                dom: 'Brt',
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        footer: true,
                        className: 'bg-emerald-500 text-white px-4 py-1 rounded-lg text-xs font-bold border-none hover:bg-emerald-600 transition-all',
                        title: 'Jurnal Umum - {{ $bulan }}-{{ $tahun }}'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        footer: true,
                        className: 'bg-rose-500 text-white px-4 py-1 rounded-lg text-xs font-bold border-none hover:bg-rose-600 transition-all',
                        title: 'Jurnal Umum - {{ $bulan }}-{{ $tahun }}'
                    }
                ]
            });

            // Automatic Column Filter
            $('.filter-input').on('keyup change input', function(e) {
                e.stopPropagation();
                var colIndex = $(this).parent().index();
                if (table.column(colIndex).search() !== this.value) {
                    table.column(colIndex).search(this.value).draw();
                }
            });

            table.buttons().container().appendTo('#exportButtons');
        });

        @if (session('error'))
            Swal.fire({
                icon: 'info', // 'info' lebih cocok untuk data kosong, 'error' untuk sistem meledak
                title: 'Informasi Jurnal',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>
</x-app-layout>
