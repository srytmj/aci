<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Update Termin: {{ $termin->nama_proyek }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                
                <div class="p-6 bg-indigo-600 text-white">
                    <p class="text-xs font-bold uppercase tracking-widest opacity-80">Total Nilai Kontrak Proyek</p>
                    <h3 class="text-2xl font-black">Rp {{ number_format($termin->nilai_kontrak, 0, ',', '.') }}</h3>
                </div>

                <form action="{{ route('termin.update', $termin->id_termin_proyek) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Tipe Termin</label>
                            <select name="id_tipe_termin" required class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                                @foreach($tipe_termin as $tt)
                                    <option value="{{ $tt->id_tipe_termin }}" {{ $termin->id_tipe_termin == $tt->id_tipe_termin ? 'selected' : '' }}>
                                        {{ $tt->nama_termin }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Nominal Termin (Rp)</label>
                            <input type="number" name="nominal" value="{{ old('nominal', $termin->nominal) }}" required
                                class="w-full rounded-xl border-gray-200 text-lg font-bold text-emerald-600 focus:ring-emerald-500"
                                placeholder="0">
                            <p class="text-[10px] text-gray-400 mt-1 italic">*Pastikan total seluruh termin tidak melebihi nilai kontrak.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-widest">Keterangan / Milestone</label>
                            <textarea name="keterangan" rows="3" 
                                class="w-full rounded-xl border-gray-200 focus:ring-indigo-500" 
                                placeholder="Contoh: Pembayaran setelah pondasi selesai 100%">{{ old('keterangan', $termin->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-10 border-t pt-6">
                        <a href="{{ route('termin.index') }}" 
                            class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">
                            Batal
                        </a>
                        <button type="submit" 
                            class="px-10 py-2.5 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition transform active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>