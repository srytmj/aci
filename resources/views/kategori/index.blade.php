<x-app-layout>
    <style>
        /* Custom CSS dari lo */
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
            padding: 1rem !important;
        }

        /* Tambahan biar makin smooth */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4f46e5 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
        }
    </style>

    <div class="py-12" x-data="{ tab: 'masuk' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-black tracking-tighter text-gray-800 uppercase">Kategori Kas</h2>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Parameter Akuntansi &
                        Mapping Jurnal</p>
                </div>
                <a href="{{ route('kategori.create') }}"
                    class="bg-gray-900 text-white px-6 py-3 rounded-xl font-bold text-xs hover:bg-black transition shadow-lg uppercase">
                    + Kategori Baru
                </a>
            </div>

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="flex border-b border-gray-100 bg-gray-50/30">
                    <button @click="tab = 'masuk'"
                        :class="tab === 'masuk' ? 'border-b-2 border-emerald-500 text-emerald-600 bg-white' : 'text-gray-400'"
                        class="flex-1 py-4 font-bold text-xs uppercase transition-all">📥 Kas Masuk</button>
                    <button @click="tab = 'keluar'"
                        :class="tab === 'keluar' ? 'border-b-2 border-rose-500 text-rose-600 bg-white' : 'text-gray-400'"
                        class="flex-1 py-4 font-bold text-xs uppercase transition-all">📤 Kas Keluar</button>
                </div>

                <div class="p-6">
                    <div x-show="tab === 'masuk'">
                        <table id="tableMasuk" class="display nowrap text-sm">
                            <thead>
                                <tr class="text-gray-500 text-[10px] uppercase">
                                    <th>Nama Kategori</th>
                                    <th>Mapping Akun (COA)</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($masuk as $m)
                                    <tr>
                                        <td class="font-bold text-gray-700">{{ $m->nama_kategori }}</td>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-2 group/d">
                                                    <span
                                                        class="bg-emerald-50 text-emerald-600 text-[9px] font-black px-1.5 py-0.5 rounded border border-emerald-100">DR</span>
                                                    <span
                                                        class="text-[11px] font-mono text-gray-500">{{ $m->kode_debit ?? '---' }}</span>
                                                    <span
                                                        class="text-xs text-gray-700 font-medium line-clamp-1">{{ $m->nama_debit ?? 'Belum diset' }}</span>
                                                </div>

                                                <div class="flex items-center gap-2 group/k">
                                                    <span
                                                        class="bg-rose-50 text-rose-600 text-[9px] font-black px-1.5 py-0.5 rounded border border-rose-100">CR</span>
                                                    <span
                                                        class="text-[11px] font-mono text-gray-500">{{ $m->kode_kredit ?? '---' }}</span>
                                                    <span
                                                        class="text-xs text-gray-700 font-medium line-clamp-1">{{ $m->nama_kredit ?? 'Belum diset' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-gray-400 text-xs italic">{{ $m->deskripsi ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('kategori.edit', [$m->id_kategori_masuk, 'masuk']) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 transition font-bold text-xs uppercase">Edit</a>
                                                <span class="text-gray-200">|</span>
                                                <button onclick="confirmDelete('{{ $m->id_kategori_masuk }}', 'masuk')"
                                                    class="text-rose-500 hover:text-rose-700 font-bold text-xs uppercase">Hapus</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'keluar'">
                        <table id="tableKeluar" class="display nowrap text-sm">
                            <thead>
                                <tr class="text-gray-500 text-[10px] uppercase">
                                    <th>Nama Kategori</th>
                                    <th>Mapping Akun (COA)</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keluar as $k)
                                    <tr>
                                        <td class="font-bold text-gray-700">{{ $k->nama_kategori }}</td>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-2 group/d">
                                                    <span
                                                        class="bg-emerald-50 text-emerald-600 text-[9px] font-black px-1.5 py-0.5 rounded border border-emerald-100">DR</span>
                                                    <span
                                                        class="text-[11px] font-mono text-gray-500">{{ $m->kode_debit ?? '---' }}</span>
                                                    <span
                                                        class="text-xs text-gray-700 font-medium line-clamp-1">{{ $m->nama_debit ?? 'Belum diset' }}</span>
                                                </div>

                                                <div class="flex items-center gap-2 group/k">
                                                    <span
                                                        class="bg-rose-50 text-rose-600 text-[9px] font-black px-1.5 py-0.5 rounded border border-rose-100">CR</span>
                                                    <span
                                                        class="text-[11px] font-mono text-gray-500">{{ $m->kode_kredit ?? '---' }}</span>
                                                    <span
                                                        class="text-xs text-gray-700 font-medium line-clamp-1">{{ $m->nama_kredit ?? 'Belum diset' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-gray-400 text-xs italic">{{ $k->deskripsi ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('kategori.edit', [$k->id_kategori_keluar, 'keluar']) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 transition font-bold text-xs uppercase">Edit</a>
                                                <span class="text-gray-200">|</span>
                                                <button
                                                    onclick="confirmDelete('{{ $k->id_kategori_keluar }}', 'keluar')"
                                                    class="text-rose-500 hover:text-rose-700 font-bold text-xs uppercase">Hapus</button>
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
    </div>
    <script>
        function confirmDelete(id, jenis) {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Kategori " + jenis +
                    " ini akan dihapus permanen. Data transaksi yang sudah terhubung mungkin akan bermasalah!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#111827', // Hitam biar senada sama style COA
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: '<span class="text-xs font-bold uppercase tracking-widest">Ya, Hapus!</span>',
                cancelButtonText: '<span class="text-xs font-bold uppercase tracking-widest text-gray-500">Batal</span>',
                reverseButtons: true, // Biar tombol Batal di kiri, Hapus di kanan
                customClass: {
                    popup: 'rounded-[2rem] border-none shadow-2xl',
                    confirmButton: 'rounded-xl px-8 py-3',
                    cancelButton: 'rounded-xl px-8 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading sebentar biar kerasa real-time
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    // Redirect ke route destroy yang udah kita buat
                    window.location.href = "{{ url('kategori/delete') }}/" + id + "/" + jenis;
                }
            })
        }

        // Logic buat nangkep Flash Session (Success/Error)
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'BERHASIL!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'WADUH!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#111827',
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        @endif
    </script>
    <script>
        $(document).ready(function() {
            const config = {
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                }
            };
            $('#tableMasuk').DataTable(config);
            $('#tableKeluar').DataTable(config);
        });

        function confirmDelete(id, jenis) {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Pastikan kategori ini tidak sedang digunakan di transaksi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#111827',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: '<span style="color: #6b7280">BATAL</span>'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/kategori/delete/${id}/${jenis}`;
                }
            });
        }
    </script>

    <script>
        // Popup kalau BERHASIL
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'UPDATE BERHASIL!',
                text: "{{ session('success') }}",
                timer: 2500, // Ilang sendiri dalam 2.5 detik
                showConfirmButton: false,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-[2rem] border-none shadow-2xl',
                    title: 'text-emerald-600 font-black'
                }
            });
        @endif

        // Popup kalau ERROR (yang tadi)
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'GAGAL UPDATE',
                text: "{{ session('error') }}",
                confirmButtonColor: '#111827',
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        @endif
    </script>
</x-app-layout>
