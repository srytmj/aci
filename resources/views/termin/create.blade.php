<x-app-layout>
        @section('title', 'Buat Data Termin')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Termin Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden">
                <div class="bg-emerald-600 px-6 py-4 text-white font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Input Penerimaan Kas Baru
                </div>

                <form action="{{ route('kas-masuk.store') }}" method="POST" enctype="multipart/form-data" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">No. Form (Auto)</label>
                        <input type="text" name="no_form" value="{{ $no_form }}" readonly class="w-full rounded-xl bg-gray-50 border-gray-200 font-mono text-indigo-600 font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-gray-200">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kategori Penerimaan</label>
                        <select name="id_kategori_masuk" required class="w-full rounded-xl border-gray-200">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id_kategori_masuk }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Terkait Proyek (Optional)</label>
                        <select name="id_proyek" id="id_proyek" class="w-full rounded-xl border-gray-200">
                            <option value="">-- Tanpa Proyek (Umum) --</option>
                            @foreach($proyek as $p)
                                <option value="{{ $p->id_proyek }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="termin_wrapper" class="hidden">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Pilih Termin</label>
                        <select name="id_termin_proyek" id="id_termin_proyek" class="w-full rounded-xl border-gray-200">
                            <option value="">-- Pilih Termin --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nominal (Rp)</label>
                        <input type="number" name="nominal" id="nominal" required class="w-full rounded-xl border-gray-200 font-bold text-emerald-600" placeholder="0">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Metode Pembayaran</label>
                        <select name="id_metode_bayar" required class="w-full rounded-xl border-gray-200">
                            @foreach($metode as $m)
                                <option value="{{ $m->id_metode_bayar }}">{{ $m->nama_metode_bayar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Bukti Bayar (File)</label>
                        <input type="file" name="upload_bukti" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" required class="w-full rounded-xl border-gray-200" placeholder="Contoh: Pembayaran Termin 1 Proyek Pembangunan Jembatan"></textarea>
                    </div>

                    <div class="col-span-2 flex justify-end gap-3 mt-4 border-t pt-6">
                        <a href="{{ route('kas-masuk.index') }}" class="px-6 py-2 rounded-xl bg-gray-100 text-gray-600 font-bold">Batal</a>
                        <button type="submit" class="px-8 py-2 rounded-xl bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-200">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#id_proyek').on('change', function() {
                var idProyek = $(this).val();
                if(idProyek) {
                    $('#termin_wrapper').removeClass('hidden');
                    $.ajax({
                        url: '/get-termin/' + idProyek,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#id_termin_proyek').empty().append('<option value="">-- Pilih Termin --</option>');
                            $.each(data, function(key, value) {
                                $('#id_termin_proyek').append('<option value="'+ value.id_termin_proyek +'" data-nominal="'+ value.nominal +'">'+ value.nama_tipe_termin +' (Rp '+ value.nominal.toLocaleString() +')</option>');
                            });
                        }
                    });
                } else {
                    $('#termin_wrapper').addClass('hidden');
                    $('#id_termin_proyek').empty();
                }
            });

            // Auto-fill nominal pas termin dipilih
            $('#id_termin_proyek').on('change', function() {
                var nominal = $(this).find(':selected').data('nominal');
                if(nominal) $('#nominal').val(nominal);
            });
        });
    </script>
</x-app-layout>