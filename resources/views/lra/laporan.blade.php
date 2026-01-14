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
            let blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            let url = URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'Laporan_Realisasi_Anggaran.xls';
            a.click();
        }
    </script>

    <style>
        main { padding-top: 0 !important; }
        .report-table th, .report-table td { border: 1px solid #e5e7eb; }
        .dark .report-table th, .dark .report-table td { border: 1px solid #374151; }

        @media print {
            body * { visibility: hidden; background: white !important; color: black !important; }
            #printArea, #printArea * { visibility: visible; }
            #printArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; box-shadow: none !important; border: none !important; }
            .no-print { display: none !important; }
            table, th, td { border: 1px solid black !important; color: black !important; box-shadow: none !important; }
            .bg-gray-50, .bg-gray-100, .bg-gray-200 { background-color: transparent !important; }
        }
    </style>

    <div class="pt-0 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Toolbar --}}
            <div class="no-print bg-white dark:bg-gray-800 p-4 rounded-b-xl shadow-sm border-x border-b border-gray-100 dark:border-gray-700 mb-4 flex justify-between items-center sticky top-0 z-20">
                <form action="{{ route('lra.laporan') }}" method="GET" class="flex gap-3">
                    <select name="proyek_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 font-bold">
                        <option value="">-- Silahkan Pilih Proyek --</option>
                        @foreach ($listProyek as $proyek)
                            <option value="{{ $proyek->id_proyek }}" {{ $selectedProyek == $proyek->id_proyek ? 'selected' : '' }}>{{ $proyek->nama }}</option>
                        @endforeach
                    </select>
                </form>
                @if($selectedProyek)
                <div class="flex gap-2">
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-black transition">PRINT PDF</button>
                    <button onclick="extractExcel()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-emerald-700 transition">EXCEL</button>
                </div>
                @endif
            </div>

            {{-- Print Area --}}
            <div id="printArea" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-none">
                
                <div class="p-10 text-center border-b border-black">
                    <h1 class="text-2xl font-bold uppercase">Laporan Realisasi Anggaran</h1>
                    <p class="text-md uppercase mt-1">{{ $selectedProyek ? $listProyek->where('id_proyek', $selectedProyek)->first()->nama : 'Harap Pilih Proyek' }}</p>
                    <p class="text-xs text-gray-500 mt-1 italic uppercase tracking-widest">Dokumen Resmi Internal</p>
                </div>

                <div class="p-8">
                    @if(!$selectedProyek)
                        {{-- Tampilan saat proyek belum dipilih --}}
                        <div class="py-20 text-center border-2 border-dashed border-gray-200 rounded-2xl">
                            <p class="text-gray-400 font-medium">Pilih proyek pada dropdown di atas untuk menampilkan detail laporan.</p>
                        </div>
                    @else
                        <table id="reportTable" class="w-full report-table border-collapse border border-black text-sm">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-800 font-bold uppercase">
                                    <th class="p-3 text-left border border-black">Keterangan</th>
                                    <th class="p-3 text-right border border-black">Anggaran (Rp)</th>
                                    <th class="p-3 text-right border border-black">Realisasi (Rp)</th>
                                    <th class="p-3 text-center border border-black">%</th>
                                    <th class="p-3 text-right border border-black">Lebih / (Kurang)</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- PENDAPATAN --}}
                                <tr class="bg-gray-50 font-bold italic">
                                    <td colspan="5" class="p-2 border border-black">A. PENDAPATAN</td>
                                </tr>
                                @php 
                                    $totalReal_P = 0; 
                                    $totalSelisih_P = 0; 
                                    $totalAgg_P = count($dataLra['pendapatan']) * $totalAnggaranProyek; 
                                @endphp
                                @foreach ($dataLra['pendapatan'] as $lra)
                                    @php
                                        $realisasi = ($lra->persentase / 100) * $totalAnggaranProyek;
                                        $selisih = $totalAnggaranProyek - $realisasi;
                                        $totalReal_P += $realisasi;
                                        $totalSelisih_P += $selisih;
                                    @endphp
                                    <tr>
                                        <td class="p-3 border border-black">{{ $lra->keterangan }}</td>
                                        <td class="p-3 text-right border border-black">Rp {{ number_format($totalAnggaranProyek, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right border border-black font-bold">Rp {{ number_format($realisasi, 0, ',', '.') }}</td>
                                        <td class="p-3 text-center border border-black">{{ $lra->persentase }}%</td>
                                        <td class="p-3 text-right border border-black">Rp {{ number_format($selisih, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold bg-gray-50">
                                    <td class="p-3 border border-black">TOTAL PENDAPATAN</td>
                                    <td class="p-3 text-right border border-black">Rp {{ number_format($totalAgg_P, 0, ',', '.') }}</td>
                                    <td class="p-3 text-right border border-black">Rp {{ number_format($totalReal_P, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center border border-black">-</td>
                                    <td class="p-3 text-right border border-black">Rp {{ number_format($totalSelisih_P, 0, ',', '.') }}</td>
                                </tr>

                                <tr><td colspan="5" class="h-6 border-x border-black"></td></tr>

                                {{-- PENGELUARAN --}}
                                <tr class="bg-gray-50 font-bold italic">
                                    <td colspan="5" class="p-2 border border-black">B. PENGELUARAN</td>
                                </tr>
                                @php 
                                    $totalReal_E = 0; 
                                    $totalSelisih_E = 0; 
                                    $totalAgg_E = count($dataLra['pengeluaran']) * $totalAnggaranProyek;
                                @endphp
                                @foreach ($dataLra['pengeluaran'] as $lra)
                                    @php
                                        $realisasi = ($lra->persentase / 100) * $totalAnggaranProyek;
                                        $selisih = $totalAnggaranProyek - $realisasi;
                                        $totalReal_E += $realisasi;
                                        $totalSelisih_E += $selisih;
                                    @endphp
                                    <tr>
                                        <td class="p-3 border border-black">{{ $lra->keterangan }}</td>
                                        <td class="p-3 text-right border border-black">Rp {{ number_format($totalAnggaranProyek, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right border border-black font-bold">Rp {{ number_format($realisasi, 0, ',', '.') }}</td>
                                        <td class="p-3 text-center border border-black">{{ $lra->persentase }}%</td>
                                        <td class="p-3 text-right border border-black">Rp {{ number_format($selisih, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold bg-gray-50">
                                    <td class="p-3 border border-black">TOTAL PENGELUARAN</td>
                                    <td class="p-3 text-right border border-black">Rp {{ number_format($totalAgg_E, 0, ',', '.') }}</td>
                                    <td class="p-3 text-right border border-black">Rp {{ number_format($totalReal_E, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center border border-black">-</td>
                                    <td class="p-3 text-right border border-black">Rp {{ number_format($totalSelisih_E, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-100 font-bold text-black border-2 border-black">
                                <tr>
                                    <td class="p-4 border border-black uppercase">Surplus / (Defisit) LRA</td>
                                    <td class="p-4 text-right border border-black">Rp {{ number_format($totalAgg_P - $totalAgg_E, 0, ',', '.') }}</td>
                                    <td class="p-4 text-right border border-black text-lg">Rp {{ number_format($totalReal_P - $totalReal_E, 0, ',', '.') }}</td>
                                    <td class="p-4 border border-black"></td>
                                    <td class="p-4 text-right border border-black text-lg">Rp {{ number_format($totalSelisih_P - $totalSelisih_E, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>

                        {{-- <div class="hidden print:grid grid-cols-2 gap-20 mt-16 text-center">
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
                        </div> --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>