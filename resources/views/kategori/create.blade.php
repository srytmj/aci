<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Kategori Kas') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ jenis: 'masuk' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="px-6 py-4 text-white font-bold flex items-center justify-between transition-colors duration-500"
                    :class="jenis === 'masuk' ? 'bg-emerald-600' : 'bg-rose-600'">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span
                            x-text="jenis === 'masuk' ? 'Konfigurasi Kategori Kas Masuk' : 'Konfigurasi Kategori Kas Keluar'"></span>
                    </div>
                    <span class="text-[10px] bg-white/20 px-3 py-1 rounded-full tracking-widest uppercase"
                        x-text="jenis"></span>
                </div>

                <form action="{{ route('kategori.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-3 tracking-widest">Jenis
                                Transaksi</label>
                            <div class="flex gap-4">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="jenis" value="masuk" x-model="jenis"
                                        class="hidden peer">
                                    <div
                                        class="p-3 text-center rounded-xl border-2 border-gray-100 dark:border-gray-700 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-400 font-bold transition-all text-xs uppercase italic">
                                        📥 Kas Masuk
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="jenis" value="keluar" x-model="jenis"
                                        class="hidden peer">
                                    <div
                                        class="p-3 text-center rounded-xl border-2 border-gray-100 dark:border-gray-700 peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 peer-checked:text-rose-700 dark:peer-checked:text-rose-400 font-bold transition-all text-xs uppercase italic">
                                        📤 Kas Keluar
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nama
                                Kategori</label>
                            <input type="text" name="nama_kategori" required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all"
                                placeholder="Misal: Pembayaran Proyek">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Akun
                                    Debit (+)</label>
                                <select name="id_coa_debit" required
                                    class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500">
                                    <option value="">-- Pilih Akun Debit --</option>
                                    @foreach ($coa as $c)
                                        <option value="{{ $c->id_coa }}"
                                            {{ isset($data) && $data->id_coa_debit == $c->id_coa ? 'selected' : '' }}>
                                            [{{ $c->kode_akun }}] {{ $c->nama_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Akun
                                    Kredit (-)</label>
                                <select name="id_coa_kredit" required
                                    class="w-full rounded-xl border-gray-200 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500">
                                    <option value="">-- Pilih Akun Kredit --</option>
                                    @foreach ($coa as $c)
                                        <option value="{{ $c->id_coa }}"
                                            {{ isset($data) && $data->id_coa_kredit == $c->id_coa ? 'selected' : '' }}>
                                            [{{ $c->kode_akun }}] {{ $c->nama_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Deskripsi</label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all"
                                placeholder="Jelaskan detail peruntukan kategori ini..."></textarea>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 mt-10 border-t pt-6 border-gray-100 dark:border-gray-700">
                        <a href="{{ route('kategori.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-200 transition duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-10 py-2.5 rounded-xl text-white font-bold shadow-lg transition duration-200 transform active:scale-95"
                            :class="jenis === 'masuk' ? 'bg-emerald-600 shadow-emerald-100 hover:bg-emerald-700' :
                                'bg-rose-600 shadow-rose-100 hover:bg-rose-700'">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
