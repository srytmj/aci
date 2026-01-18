<x-app-layout>
    @section('title', 'Laporan LRA')

    <script>
        function extractExcel() {
            let table = document.querySelector("#reportTable");
            let html = `
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid black; padding: 5px; text-align: left; font-family: Arial; }
                    .text-right { text-align: right; }
                    .text-center { text-align: center; }
                    .font-bold { font-weight: bold; }
                </style>
                ${table.outerHTML}
            `;
            let blob = new Blob([html], {
                type: 'application/vnd.ms-excel'
            });
            let url = URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'Laporan_Realisasi_Anggaran.xls';
            a.click();
        }
    </script>

    <style>
        main {
            padding-top: 0 !important;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #e5e7eb;
        }

        .dark .report-table th,
        .dark .report-table td {
            border: 1px solid #374151;
        }

        @media print {
            body * {
                visibility: hidden;
                background: white !important;
                color: black !important;
            }

            #printArea,
            #printArea * {
                visibility: visible;
            }

            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                box-shadow: none !important;
                border: none !important;
            }

            .no-print {
                display: none !important;
            }

            table,
            th,
            td {
                border: 1px solid black !important;
                color: black !important;
            }

            .bg-gray-50,
            .bg-gray-100 {
                background-color: #f9fafb !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>

    <div class="pt-0 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Toolbar --}}
            <div
                class="no-print bg-white dark:bg-gray-800 p-4 rounded-b-xl shadow-sm border-x border-b border-gray-100 dark:border-gray-700 mb-4 flex justify-between items-center sticky top-0 z-20">
                <form action="{{ route('lra.laporan') }}" method="GET" class="flex gap-3">
                    <select name="proyek_id" onchange="this.form.submit()"
                        class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 font-bold dark:bg-gray-700 dark:text-white">
                        <option value="">-- Silahkan Pilih Proyek --</option>
                        @foreach ($listProyek as $p)
                            <option value="{{ $p->id_proyek }}"
                                {{ $selectedProyek == $p->id_proyek ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </form>

                @if ($selectedProyek && count($dataLra) > 0)
                    <div class="flex gap-2">
                        <button onclick="window.print()"
                            class="bg-gray-800 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-black transition">PRINT
                            PDF</button>
                        <button onclick="extractExcel()"
                            class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-emerald-700 transition">EXCEL</button>
                    </div>
                @endif
            </div>

            {{-- Print Area --}}
            <div id="printArea"
                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-sm rounded-2xl overflow-hidden">

                {{-- Header Laporan --}}
                <div class="p-10 text-center border-b border-gray-100 dark:border-gray-800">
                    <h1 class="text-2xl font-bold uppercase dark:text-white">Laporan Realisasi Anggaran</h1>
                    @if ($selectedProyek)
                        @php $proyekDetail = $listProyek->where('id_proyek', $selectedProyek)->first(); @endphp
                        <p class="text-lg uppercase mt-1 text-indigo-600 font-bold">{{ $proyekDetail->nama }}</p>
                        <p class="text-sm text-gray-500 mt-1">Nilai Kontrak: Rp
                            {{ number_format($proyekDetail->nilai_kontrak, 0, ',', '.') }}</p>
                    @else
                        <p class="text-md uppercase mt-1 text-gray-400 font-medium italic">Harap Pilih Proyek Terlebih
                            Dahulu</p>
                    @endif
                </div>

                <div class="p-8">
                    @if (!$selectedProyek)
                        <div
                            class="py-20 text-center border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-400 font-medium">Data belum tersedia. Silakan pilih proyek pada dropdown
                                di atas.</p>
                        </div>
                    @else
                        <table id="reportTable" class="w-full report-table border-collapse text-sm dark:text-gray-300">
                            <thead>
                                <tr
                                    class="bg-gray-100 dark:bg-gray-800 font-bold uppercase text-gray-700 dark:text-gray-200">
                                    <th class="p-3 text-left border border-gray-300 dark:border-gray-600">Keterangan
                                        Anggaran</th>
                                    <th class="p-3 text-right border border-gray-300 dark:border-gray-600">Budget (Rp)
                                    </th>
                                    <th class="p-3 text-right border border-gray-300 dark:border-gray-600">Realisasi
                                        (Rp)</th>
                                    <th class="p-3 text-center border border-gray-300 dark:border-gray-600">% Alokasi
                                    </th>
                                    <th class="p-3 text-right border border-gray-300 dark:border-gray-600">Selisih Sisa
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @php
                                    $grandTotalAnggaran = 0;
                                    $grandTotalRealisasi = 0;
                                @endphp

                                @forelse ($dataLra as $row)
                                    @php
                                        $grandTotalAnggaran += $row->anggaran;
                                        $grandTotalRealisasi += $row->realisasi;

                                        // Hitung Persentase Penyerapan (Realisasi / Anggaran)
                                        $persenSerap =
                                            $row->anggaran > 0 ? ($row->realisasi / $row->anggaran) * 100 : 0;

                                        $isOver = $row->selisih < 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                        <td class="p-3 border border-gray-200 dark:border-gray-700 font-medium">
                                            {{ $row->keterangan }}
                                            <div class="text-[10px] text-gray-400 font-normal italic">Alokasi Plafon:
                                                {{ $row->persentase }}%</div>
                                        </td>
                                        <td
                                            class="p-3 text-right border border-gray-200 dark:border-gray-700 bg-gray-50/30">
                                            Rp {{ number_format($row->anggaran, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="p-3 text-right border border-gray-200 dark:border-gray-700 font-bold">
                                            Rp {{ number_format($row->realisasi, 0, ',', '.') }}
                                        </td>
                                        <td class="p-3 text-center border border-gray-200 dark:border-gray-700">
                                            <div class="flex flex-col items-center">
                                                <span
                                                    class="font-bold {{ $persenSerap > 100 ? 'text-rose-600' : 'text-indigo-600' }}">
                                                    {{ round($persenSerap, 1) }}%
                                                </span>
                                                {{-- Progress Bar Mini --}}
                                                <div
                                                    class="w-full bg-gray-200 rounded-full h-1.5 mt-1 dark:bg-gray-700 w-24">
                                                    <div class="h-1.5 rounded-full {{ $persenSerap > 100 ? 'bg-rose-500' : 'bg-indigo-500' }}"
                                                        style="width: {{ $persenSerap > 100 ? 100 : $persenSerap }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="p-3 text-right border border-gray-200 dark:border-gray-700 {{ $isOver ? 'text-rose-600 font-bold' : 'text-emerald-600' }}">
                                            {{ $isOver ? '(' : '' }} Rp
                                            {{ number_format(abs($row->selisih), 0, ',', '.') }}
                                            {{ $isOver ? ')' : '' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-10 text-center text-gray-400 italic">Data LRA tidak
                                            ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot
                                class="bg-gray-100 dark:bg-gray-800 font-bold text-gray-900 dark:text-white border-t-2 border-gray-400">
                                @php
                                    $totalPersenSerap =
                                        $grandTotalAnggaran > 0
                                            ? ($grandTotalRealisasi / $grandTotalAnggaran) * 100
                                            : 0;
                                @endphp
                                <tr>
                                    <td class="p-4 border border-gray-300 dark:border-gray-600 uppercase">Total
                                        Akumulasi</td>
                                    <td class="p-4 text-right border border-gray-300 dark:border-gray-600">
                                        Rp {{ number_format($grandTotalAnggaran, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4 text-right border border-gray-300 dark:border-gray-600 text-lg">
                                        Rp {{ number_format($grandTotalRealisasi, 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="p-4 border border-gray-300 dark:border-gray-600 text-center text-indigo-700 dark:text-indigo-400">
                                        {{ round($totalPersenSerap, 1) }}%
                                    </td>
                                    <td class="p-4 text-right border border-gray-300 dark:border-gray-600 text-lg">
                                        Rp {{ number_format($grandTotalAnggaran - $grandTotalRealisasi, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        {{-- Tanda Tangan (Hanya Muncul saat Print) --}}
                        <div class="hidden print:grid grid-cols-2 gap-20 mt-16 text-center">
                            <div>
                                <p class="font-bold uppercase">Dibuat Oleh,</p>
                                <div class="mt-20 border-b border-black w-48 mx-auto"></div>
                                <p class="mt-2 text-xs uppercase font-bold">Admin Keuangan</p>
                            </div>
                            <div>
                                <p class="font-bold uppercase">Mengetahui,</p>
                                <div class="mt-20 border-b border-black w-48 mx-auto"></div>
                                <p class="mt-2 text-xs uppercase font-bold">Manajer Proyek</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Alert --}}
    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>
</x-app-layout>
