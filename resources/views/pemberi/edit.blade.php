<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pemberi Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-amber-500 px-6 py-4 text-white font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Update Pemberi Proyek
                </div>

                <form action="{{ route('pemberi.update', $pemberi->id_pemberi) }}" method="POST"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Jenis
                            Pemberi</label>
                        <select name="jenis" required
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500">
                            <option value="Perorangan"
                                {{ old('jenis', $pemberi->jenis) == 'Perorangan' ? 'selected' : '' }}>Perorangan
                            </option>
                            <option value="Swasta" {{ old('jenis', $pemberi->jenis) == 'Swasta' ? 'selected' : '' }}>
                                Swasta</option>
                            <option value="Pemerintah"
                                {{ old('jenis', $pemberi->jenis) == 'Pemerintah' ? 'selected' : '' }}>Pemerintah
                            </option>
                        </select>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nama
                            Instansi / Owner</label>
                        <input type="text" name="nama" required value="{{ old('nama', $pemberi->nama) }}"
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Contoh: PT. Maju Jaya">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Alamat
                            Lengkap</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $pemberi->alamat) }}"
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Penanggung
                            Jawab</label>
                        <input type="text" name="penanggung_jawab" required
                            value="{{ old('penanggung_jawab', $pemberi->penanggung_jawab) }}"
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nomor
                            Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $pemberi->no_telp) }}"
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2">
                        <label
                            class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Email</label>
                        <input type="email" name="email" value="{{ old('email', $pemberi->email) }}"
                            class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2 flex justify-end gap-3 mt-4">
                        <a href="{{ route('pemberi.index') }}"
                            class="px-6 py-2 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">Batal</a>
                        <button type="submit"
                            class="px-8 py-2 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Update
                            Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
