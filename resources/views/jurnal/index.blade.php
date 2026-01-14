<x-app-layout>
    @section('title', 'Jurnal Umum')

    <script>
        function extractExcel() {
            let table = document.querySelector("#jurnalTable");
            let html = `
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid black; padding: 5px; text-align: left; font-family: Arial; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                </style>
                ${table.outerHTML}
            `;
            let blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            let url = URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'Jurnal_Umum_{{ $bulan }}_{{ $tahun }}.xls';
            a.click();
        }
    </script>

    <style>
        /* Mentok Atas */
        main { padding-top: 0 !important; }
        
        @media print {
            body * { visibility: hidden; background: white !important; color: black !important; }
            #printArea, #printArea * { visibility: visible; }
            #printArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; box-shadow: none !important; border: none !important; }
            .no-print { display: none !important; }
            
            table, th, td { border: 1px solid black !important; color: black !important; box-shadow: none !important; }
            .bg-gray-800, .bg-indigo-50, .bg-emerald-50 { background-color: transparent !important; color: black !important; }
            tfoot { font-weight: bold; border-top: 2px solid black !important; }
        }
    </style>

    <div class="pt-0 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Toolbar: Auto-Submit Filter & Action --}}
            <div class="no-print bg-white dark:bg-gray-800 p-4 rounded-b-xl shadow-sm border-x border-b border-gray-100 dark:border-gray-700 mb-6 flex flex-wrap items-end justify-between gap-4 sticky top-0 z-20">
                <form action="{{ route('jurnal.index') }}" method="GET" class="flex items-end gap-3" id="filterForm">
                    <div>
                        <label class="block text-[10px] font-bold text-indigo-500 uppercase mb-1 ml-1 tracking-widest">Bulan Periode</label>
                        <select name="bulan" onchange="document.getElementById('filterForm').submit()" 
                            class="rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500 w-48 font-bold cursor-pointer">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-indigo-500 uppercase mb-1 ml-1 tracking-widest">Tahun</label>
                        <select name="tahun" onchange="document.getElementById('filterForm').submit()" 
                            class="rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500 w-32 font-bold cursor-pointer">
                            @foreach (range(date('Y') - 5, date('Y') + 1) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="flex gap-2">
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-black transition flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        PRINT PDF
                    </button>
                    <button onclick="extractExcel()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-emerald-700 transition flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        EXCEL
                    </button>
                </div>
            </div>

            {{-- Area Laporan --}}
            <div id="printArea" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                
                <div class="p-10 text-center border-b border-black">
                    <h1 class="text-2xl font-bold uppercase tracking-tighter">Buku Jurnal Umum</h1>
                    <p class="text-md uppercase mt-1 font-medium">Laporan Transaksi Konstruksi</p>
                    <div class="mt-2 inline-block px-4 py-1 text-xs font-bold uppercase">
                        Periode: {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} {{ $tahun }}
                    </div>
                </div>

                <div class="p-8 overflow-x-auto">
                    <table id="jurnalTable" class="w-full border-collapse border border-black text-[13px]">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-800 font-bold uppercase tracking-wider">
                                <th class="p-3 text-left border border-black w-24">Tanggal</th>
                                <th class="p-3 text-left border border-black w-32">No. Ref</th>
                                <th class="p-3 text-left border border-black w-24">Kode</th>
                                <th class="p-3 text-left border border-black">Keterangan Akun & Deskripsi</th>
                                <th class="p-3 text-right border border-black w-40">Debit (Rp)</th>
                                <th class="p-3 text-right border border-black w-40">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jurnals as $j)
                                <tr>
                                    <td class="p-3 border border-black text-center">
                                        {{ \Carbon\Carbon::parse($j->tanggal)->format('d/m/y') }}
                                    </td>
                                    <td class="p-3 border border-black text-center font-bold text-[11px]">
                                        {{ $j->no_ref ?? '-' }}
                                    </td>
                                    <td class="p-3 border border-black font-mono text-center">
                                        {{ $j->kode_akun }}
                                    </td>
                                    <td class="p-3 border border-black">
                                        <div class="{{ $j->posisi_dr_cr == 'cr' ? 'ml-8 italic' : 'font-bold' }}">
                                            {{ $j->nama_akun }}
                                        </div>
                                        @if($j->deskripsi)
                                            <div class="text-[10px] text-gray-500 italic mt-0.5 leading-tight">{{ $j->deskripsi }}</div>
                                        @endif
                                    </td>
                                    <td class="p-3 border border-black text-right font-mono">
                                        {{ $j->posisi_dr_cr == 'dr' && $j->nominal > 0 ? 'Rp ' . number_format($j->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="p-3 border border-black text-right font-mono">
                                        {{ $j->posisi_dr_cr == 'cr' && $j->nominal > 0 ? 'Rp ' . number_format($j->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-20 text-center text-gray-400 italic border border-black uppercase tracking-widest">
                                        Data jurnal tidak ditemukan pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold">
                            <tr>
                                <td colspan="4" class="p-4 text-right uppercase text-xs border border-black tracking-widest">Total Akumulasi</td>
                                <td class="p-4 text-right border border-black font-mono text-indigo-700">
                                    Rp {{ number_format($jurnals->where('posisi_dr_cr', 'dr')->sum('nominal'), 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-right border border-black font-mono text-indigo-700">
                                    Rp {{ number_format($jurnals->where('posisi_dr_cr', 'cr')->sum('nominal'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- Footer Tanda Tangan --}}
                    {{-- <div class="hidden print:grid grid-cols-2 gap-20 mt-16 text-center">
                        <div>
                            <p class="font-bold uppercase text-xs">Dibuat Oleh,</p>
                            <div class="mt-20 border-b border-black w-48 mx-auto"></div>
                            <p class="mt-2 text-[10px] uppercase font-bold">Staf Akuntansi</p>
                        </div>
                        <div>
                            <p class="font-bold uppercase text-xs">Disetujui Oleh,</p>
                            <div class="mt-20 border-b border-black w-48 mx-auto"></div>
                            <p class="mt-2 text-[10px] uppercase font-bold">Manajer Keuangan</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 Integration --}}
    @if (session('error') || $jurnals->isEmpty())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('error'))
            Swal.fire({
                title: 'Sistem Error',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
        @elseif ($jurnals->isEmpty())
            Swal.fire({
                title: 'Data Kosong',
                text: 'Tidak ada transaksi jurnal untuk periode {{ date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) }}',
                icon: 'info',
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>
    @endif
</x-app-layout>