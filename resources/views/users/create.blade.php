<x-app-layout>
    @section('title', 'Tambah Data User')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div
            class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="bg-indigo-600 px-6 py-4">
                <h3 class="text-white font-bold text-lg">Konfigurasi Akses Staff</h3>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="name" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all"
                            placeholder="Contoh: Budi Santoso">
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email
                            Perusahaan</label>
                        <input type="email" name="email" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all"
                            placeholder="budi@perusahaan.com">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jabatan /
                            Posisi</label>
                        <select name="id_jabatan" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="">Pilih Jabatan</option>
                            @foreach ($jabatans as $j)
                                <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Level
                            Otoritas</label>
                        <select name="id_level" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="">Pilih Level</option>
                            @foreach ($levels as $l)
                                <option value="{{ $l->id_level }}">{{ $l->nama_level }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t pt-6">
                    <a href="{{ route('users.index') }}"
                        class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">Batal</a>
                    <button type="submit"
                        class="px-8 py-2.5 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform active:scale-95">
                        Simpan Data Staff
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
