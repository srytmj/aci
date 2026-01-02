<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Proyek ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-amber-500 px-6 py-4 text-white font-bold text-lg">Edit Data Vendor</div>

                <form action="{{ route('vendor.update', $vendor->id_vendor) }}" method="POST"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Vendor /
                            Perusahaan</label>
                        <input type="text" name="nama" value="{{ old('nama', $vendor->nama) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Kantor</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $vendor->alamat) }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Penanggung Jawab
                            (PJ)</label>
                        <input type="text" name="penanggung_jawab"
                            value="{{ old('penanggung_jawab', $vendor->penanggung_jawab) }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor Telepon /
                            WA</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $vendor->no_telp) }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email Vendor</label>
                        <input type="email" name="email" value="{{ old('email', $vendor->email) }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2 flex justify-end gap-3 mt-4 border-t pt-6">
                        <a href="{{ route('vendor.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">Batal</a>
                        <button type="submit"
                            class="px-10 py-2.5 rounded-xl bg-amber-500 text-white font-bold shadow-lg shadow-amber-200 hover:bg-amber-600 transition transform active:scale-95">
                            Update Vendor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
