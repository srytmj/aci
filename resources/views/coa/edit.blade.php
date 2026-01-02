<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Akun: ') }} {{ $coa->nama_akun }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100">

                <div class="bg-amber-500 px-6 py-4 text-white font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Konfigurasi Akun
                </div>

                <form action="{{ route('coa.update', $coa->id_coa) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kode Akun</label>
                            <input type="text" name="kode_akun" value="{{ old('kode_akun', $coa->kode_akun) }}"
                                required
                                class="w-full rounded-xl border-gray-200 focus:ring-amber-500 @error('kode_akun') border-red-500 @enderror">
                            @error('kode_akun')
                                <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nama Akun</label>
                            <input type="text" name="nama_akun" value="{{ old('nama_akun', $coa->nama_akun) }}"
                                required class="w-full rounded-xl border-gray-200 focus:ring-amber-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Level</label>
                            <select name="level" id="level_select"
                                class="w-full rounded-xl border-gray-200 focus:ring-amber-500">
                                <option value="1" {{ $coa->level == 1 ? 'selected' : '' }}>Level 1</option>
                                <option value="2" {{ $coa->level == 2 ? 'selected' : '' }}>Level 2</option>
                                <option value="3" {{ $coa->level == 3 ? 'selected' : '' }}>Level 3</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Parent Akun</label>
                            <select name="parent_id" id="parent_id"
                                class="w-full rounded-xl border-gray-200 focus:ring-amber-500 disabled:bg-gray-100">
                                <option value="">-- Tanpa Induk --</option>
                                @foreach ($parents as $p)
                                    <option value="{{ $p->id_coa }}"
                                        {{ $coa->parent_id == $p->id_coa ? 'selected' : '' }}>
                                        [{{ $p->kode_akun }}] {{ $p->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Urutan</label>
                            <input type="number" name="urutan" value="{{ old('urutan', $coa->urutan) }}"
                                class="w-full rounded-xl border-gray-200">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-10 border-t pt-6">
                        <a href="{{ route('coa.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold">Batal</a>
                        <button type="submit"
                            class="px-10 py-2.5 rounded-xl bg-amber-500 text-white font-bold shadow-lg shadow-amber-200 hover:bg-amber-600 transition transform active:scale-95">
                            Update Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Logic JS yang sama untuk mendisable parent jika level 1 dipilih
        const levelSelect = document.getElementById('level_select');
        const parentSelect = document.getElementById('parent_id');

        function toggleParent() {
            if (levelSelect.value == "1") {
                parentSelect.value = "";
                parentSelect.disabled = true;
            } else {
                parentSelect.disabled = false;
            }
        }
        levelSelect.addEventListener('change', toggleParent);
        toggleParent();
    </script>
</x-app-layout>
