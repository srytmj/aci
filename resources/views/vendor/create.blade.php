<x-app-layout>
    @section('title', 'Buat Data Vendor')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Vendor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="bg-indigo-500 px-6 py-4 text-white font-bold text-lg">
                    Tambah Data Vendor
                </div>

                <form action="{{ route('vendor.store') }}" method="POST"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                            Nama Vendor / Perusahaan
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                            Alamat Kantor
                        </label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                            Penanggung Jawab (PJ)
                        </label>
                        <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" required
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                            Nomor Telepon / WA
                        </label>
                        <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                            Email Vendor
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-amber-500 transition-all">
                    </div>

                    <div class="col-span-2 flex justify-end gap-3 mt-4 border-t pt-6">
                        <a href="{{ route('vendor.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-8 py-2.5 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform active:scale-95">
                            Simpan Vendor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
