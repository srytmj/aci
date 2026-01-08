<x-app-layout>
    @section('title', 'Edit Data User')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User Baru') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div
            class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="bg-amber-500 px-6 py-4 flex items-center gap-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <h3 class="text-white font-bold text-lg">Edit Profil Staff: {{ $user->name }}</h3>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email
                            Perusahaan</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jabatan /
                            Posisi</label>
                        <select name="id_jabatan" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all">
                            @foreach ($jabatans as $j)
                                <option value="{{ $j->id_jabatan }}"
                                    {{ $user->id_jabatan == $j->id_jabatan ? 'selected' : '' }}>
                                    {{ $j->nama_jabatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Level
                            Otoritas</label>
                        <select name="id_level" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all">
                            @foreach ($levels as $l)
                                <option value="{{ $l->id_level }}"
                                    {{ $user->id_level == $l->id_level ? 'selected' : '' }}>
                                    {{ $l->nama_level }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div
                        class="col-span-2 p-4 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 rounded-r-lg flex gap-3">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-xs text-amber-700 dark:text-amber-400 font-medium">Kosongkan kolom password
                            di bawah jika Anda <strong>tidak ingin</strong> mengubah password user ini.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password
                            Baru (Opsional)</label>
                        <input type="password" name="password"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Konfirmasi
                            Password Baru</label>
                        <input type="password" name="password_confirmation"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t pt-6">
                    <a href="{{ route('users.index') }}"
                        class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">Batal</a>
                    <button type="submit"
                        class="px-8 py-2.5 rounded-xl bg-amber-500 text-white font-bold shadow-lg shadow-amber-200 hover:bg-amber-600 transition transform active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
