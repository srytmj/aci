<x-app-layout>
    @section('title', 'Edit Data Termin')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Proyek: ') }} {{ $proyek->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-amber-500 px-6 py-4 text-white font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Update Informasi Kontrak
                </div>
                
                <form action="{{ route('proyek.update', $proyek->id_proyek) }}" method="POST" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nama Proyek</label>
                        <input type="text" name="nama" value="{{ old('nama', $proyek->nama) }}" required 
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Pemberi Proyek (Client)</label>
                        <select name="id_pemberi" required class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">
                            @foreach($pemberis as $pb)
                                <option value="{{ $pb->id_pemberi }}" {{ $proyek->id_pemberi == $pb->id_pemberi ? 'selected' : '' }}>
                                    {{ $pb->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nilai Kontrak (Rp)</label>
                        <input type="number" name="nilai_kontrak" value="{{ old('nilai_kontrak', $proyek->nilai_kontrak) }}" required 
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $proyek->tanggal_mulai) }}" required 
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Estimasi Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $proyek->tanggal_selesai) }}" required 
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Jumlah Termin</label>
                        <input type="number" name="jumlah_termin" value="{{ old('jumlah_termin', $proyek->jumlah_termin) }}" 
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Status Proyek</label>
                        <select name="status" class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">
                            <option value="aktif" {{ $proyek->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $proyek->status == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Deskripsi / Catatan Tambahan</label>
                        <textarea name="deskripsi" rows="3" 
                            class="w-full rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-amber-500">{{ old('deskripsi', $proyek->deskripsi) }}</textarea>
                    </div>

                    <div class="col-span-2 flex justify-end gap-3 mt-4 border-t pt-6 border-gray-100 dark:border-gray-700">
                        <a href="{{ route('proyek.index') }}" class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-200 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-10 py-2.5 rounded-xl bg-amber-500 text-white font-bold shadow-lg shadow-amber-200 hover:bg-amber-600 transition transform active:scale-95">
                            Update Kontrak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>