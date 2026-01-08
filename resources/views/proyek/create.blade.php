<x-app-layout>
    @section('title', 'Tambah Data Termin')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Proyek ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4 text-white font-bold">Pendaftaran Kontrak Proyek Baru</div>

                <form action="{{ route('proyek.store') }}" method="POST"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nama Proyek</label>
                        <input type="text" name="nama" required
                            class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Pemberi Proyek
                            (Client)</label>
                        <select name="id_pemberi" required class="w-full rounded-xl border-gray-200">
                            <option value="">Pilih Client</option>
                            @foreach ($pemberis as $pb)
                                <option value="{{ $pb->id_pemberi }}">{{ $pb->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nilai Kontrak (Rp)</label>
                        <input type="number" name="nilai_kontrak" required class="w-full rounded-xl border-gray-200"
                            placeholder="Hanya angka, contoh: 50000000">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required class="w-full rounded-xl border-gray-200">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Estimasi Selesai</label>
                        <input type="date" name="tanggal_selesai" required class="w-full rounded-xl border-gray-200">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Jumlah Termin</label>
                        <input type="number" name="jumlah_termin" value="1"
                            class="w-full rounded-xl border-gray-200">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Status Proyek</label>
                        <select name="status" class="w-full rounded-xl border-gray-200">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>

                    <div class="col-span-2 flex justify-end gap-3 mt-4 border-t pt-6">
                        <a href="{{ route('proyek.index') }}"
                            class="px-6 py-2 rounded-xl bg-gray-100 text-gray-600 font-bold">Batal</a>
                        <button type="submit"
                            class="px-8 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            Simpan Proyek
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
