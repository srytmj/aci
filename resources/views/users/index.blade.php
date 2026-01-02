<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div
                    class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daftar Pengguna Sistem</h3>
                    <a href="{{ route('users.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md shadow-indigo-200">
                        + Tambah User
                    </a>
                </div>

                <div class="p-6">
                    <table id="userTable" class="w-full cell-border stripe hover">
                        <thead>
                            <tr class="text-left text-gray-500 uppercase text-xs tracking-wider">
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Jabatan</th>
                                <th>Level</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-300">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span
                                            class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs font-bold uppercase">
                                            {{ $user->nama_jabatan ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="px-2 py-1 bg-amber-50 text-amber-600 rounded text-xs font-bold uppercase">
                                            {{ $user->nama_level ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <button type="button"
                                                onclick="deleteUser('{{ $user->id }}', '{{ $user->name }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                            <form id="delete-form-{{ $user->id }}"
                                                action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>

                                    <script>
                                        function deleteUser(id, name) {
                                            Swal.fire({
                                                title: 'Hapus User?',
                                                text: "User " + name + " akan dihapus permanen dari sistem!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#e11d48', // Warna Rose-600
                                                cancelButtonColor: '#6b7280', // Warna Gray-500
                                                confirmButtonText: 'Ya, Hapus Sekarang!',
                                                cancelButtonText: 'Batal',
                                                reverseButtons: true
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // Submit form berdasarkan ID yang spesifik
                                                    document.getElementById('delete-form-' + id).submit();
                                                }
                                            });
                                        }

                                        // Notifikasi Toast untuk Success/Error
                                        @if (session('success'))
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil!',
                                                text: "{{ session('success') }}",
                                                timer: 2500,
                                                showConfirmButton: false
                                            });
                                        @endif

                                        @if (session('error'))
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Waduh!',
                                                text: "{{ session('error') }}",
                                                confirmButtonColor: '#indigo-600'
                                            });
                                        @endif
                                    </script>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="p-2"><input type="text" placeholder="Filter Nama"
                                        class="w-full text-xs rounded-lg border-gray-200 focus:ring-indigo-500"></th>
                                <th class="p-2"><input type="text" placeholder="Filter Email"
                                        class="w-full text-xs rounded-lg border-gray-200 focus:ring-indigo-500"></th>
                                <th class="p-2"><input type="text" placeholder="Filter Jabatan"
                                        class="w-full text-xs rounded-lg border-gray-200 focus:ring-indigo-500"></th>
                                <th class="p-2"><input type="text" placeholder="Filter Level"
                                        class="w-full text-xs rounded-lg border-gray-200 focus:ring-indigo-500"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* CSS Tambahan biar DataTables nggak berantakan */
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
            border-spacing: 0 !important;
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
            var table = $('#userTable').DataTable({
                responsive: true,
                "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lfr>t<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
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
</x-app-layout>
