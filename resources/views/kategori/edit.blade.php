<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Kategori Kas') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ jenis: '{{ $jenis }}' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">

                <div class="px-6 py-4 text-white font-bold flex items-center justify-between transition-colors duration-500"
                    :class="jenis === 'masuk' ? 'bg-emerald-600' : 'bg-rose-600'">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Update Konfigurasi Kategori</span>
                    </div>
                    <span class="text-[10px] bg-white/20 px-3 py-1 rounded-full tracking-widest uppercase italic"
                        x-text="'ID: ' + {{ $data->$pk }}"></span>
                </div>

                <form action="{{ route('kategori.update', $data->$pk) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="jenis" value="{{ $jenis }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nama
                                Kategori</label>
                            <input type="text" name="nama_kategori" value="{{ $data->nama_kategori }}" required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>

                        <div class="md:col-span-1">
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest text-emerald-600">Akun
                                Debit (DB)</label>
                            <select name="id_coa_debit" required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                                <option value="">-- Pilih Akun Debit --</option>
                                @foreach ($coa as $c)
                                    <option value="{{ $c->id_coa }}"
                                        {{ $data->id_coa_debit == $c->id_coa ? 'selected' : '' }}>
                                        [{{ $c->kode_akun }}] {{ $c->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[9px] text-gray-400 mt-1 italic">*Akun yang akan bertambah di sisi Debit.</p>
                        </div>

                        <div class="md:col-span-1">
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest text-rose-600">Akun
                                Kredit (CR)</label>
                            <select name="id_coa_kredit" required
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-rose-500">
                                <option value="">-- Pilih Akun Kredit --</option>
                                @foreach ($coa as $c)
                                    <option value="{{ $c->id_coa }}"
                                        {{ $data->id_coa_kredit == $c->id_coa ? 'selected' : '' }}>
                                        [{{ $c->kode_akun }}] {{ $c->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[9px] text-gray-400 mt-1 italic">*Akun yang akan bertambah di sisi Kredit.
                            </p>
                        </div>

                        <div class="col-span-2">
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Deskripsi</label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">{{ $data->deskripsi }}</textarea>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 mt-10 border-t pt-6 border-gray-100 dark:border-gray-700">
                        <a href="{{ route('kategori.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-200 transition duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-10 py-2.5 rounded-xl text-white font-bold shadow-lg transition duration-200 transform active:scale-95 uppercase text-xs tracking-wider"
                            :class="jenis === 'masuk' ? 'bg-emerald-600 shadow-emerald-100 hover:bg-emerald-700' :
                                'bg-rose-600 shadow-rose-100 hover:bg-rose-700'">
                            Update Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Trigger SweetAlert2 kalau ada session error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Waduh, Ada Masalah!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#e11d48', // Warna Rose
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl px-10 py-3 uppercase text-xs font-bold'
                }
            });
        @endif

        // Menampilkan error validasi Laravel (misal: field kosong)
        @if ($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Input Belum Lengkap',
                html: '{!! implode('<br>', $errors->all()) !!}',
                confirmButtonColor: '#4f46e5',
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        @endif
    </script>
</x-app-layout>
