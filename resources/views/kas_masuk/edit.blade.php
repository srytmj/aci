<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">

                <div class="bg-amber-500 px-8 py-6 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold italic tracking-tighter">EDIT KAS MASUK</h3>
                        <p class="text-xs opacity-80">Perbarui data penerimaan dana</p>
                    </div>
                    <span
                        class="bg-white/20 px-4 py-1 rounded-full text-xs font-mono font-bold">{{ $kas_masuk->no_form }}</span>
                </div>

                <form action="{{ route('kas-masuk.update', $kas_masuk->id_kas_masuk) }}" method="POST"
                    enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">No. Form</label>
                            <input type="text" name="no_form" value="{{ old('no_form', $kas_masuk->no_form) }}"
                                class="w-full rounded-xl bg-gray-50 border-gray-200 font-mono font-bold text-amber-700">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal
                                Penerimaan</label>
                            <input type="date" name="tanggal_masuk"
                                value="{{ old('tanggal_masuk', $kas_masuk->tanggal_masuk) }}" required
                                class="w-full rounded-xl border-gray-200">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kategori</label>
                            <select name="id_kategori_masuk" required class="w-full rounded-xl border-gray-200">
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->id_kategori_masuk }}"
                                        {{ $kas_masuk->id_kategori_masuk == $k->id_kategori_masuk ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Metode Bayar</label>
                            <select name="id_metode_bayar" required class="w-full rounded-xl border-gray-200">
                                @foreach ($metode as $m)
                                    <option value="{{ $m->id_metode_bayar }}"
                                        {{ $kas_masuk->id_metode_bayar == $m->id_metode_bayar ? 'selected' : '' }}>
                                        {{ $m->nama_metode_bayar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2 p-4 bg-amber-50 rounded-2xl border border-dashed border-amber-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-amber-600 uppercase mb-2 tracking-tighter">Proyek
                                        Terkait</label>
                                    <select name="id_proyek" id="id_proyek" class="w-full rounded-xl border-gray-200">
                                        <option value="">-- Penerimaan Umum --</option>
                                        @foreach ($proyek as $p)
                                            <option value="{{ $p->id_proyek }}"
                                                {{ $kas_masuk->id_proyek == $p->id_proyek ? 'selected' : '' }}>
                                                {{ $p->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-amber-600 uppercase mb-2 tracking-tighter">Termin</label>
                                    <select name="id_termin_proyek" id="id_termin_proyek"
                                        class="w-full rounded-xl border-gray-200">
                                        <option value="">-- Pilih Termin --</option>
                                        @foreach ($termin as $t)
                                            <option value="{{ $t->id_termin_proyek }}"
                                                data-nominal="{{ $t->nominal }}"
                                                {{ $kas_masuk->id_termin_proyek == $t->id_termin_proyek ? 'selected' : '' }}>
                                                {{ $t->nama_termin }} (Rp
                                                {{ number_format($t->nominal, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nominal (Rp)</label>
                            <input type="number" name="nominal" id="nominal"
                                value="{{ old('nominal', $kas_masuk->nominal) }}" required
                                class="w-full rounded-xl border-gray-200 text-lg font-black text-amber-600 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Ganti Bukti Transfer
                                (Kosongkan jika tidak ganti)</label>
                            <input type="file" name="upload_bukti"
                                class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-amber-50 file:text-amber-700">
                            @if ($kas_masuk->upload_bukti)
                                <p class="text-[10px] mt-1 text-gray-400 font-bold italic">File saat ini:
                                    {{ $kas_masuk->upload_bukti }}</p>
                            @endif
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="2" class="w-full rounded-xl border-gray-200">{{ old('keterangan', $kas_masuk->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('kas-masuk.index') }}"
                            class="px-6 py-3 rounded-xl bg-gray-100 text-gray-500 font-bold">Batal</a>
                        <button type="submit"
                            class="px-10 py-3 rounded-xl bg-amber-500 text-white font-bold shadow-lg shadow-amber-100 hover:bg-amber-600 transition transform active:scale-95">
                            Update Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#id_proyek').on('change', function() {
                var id_proyek = $(this).val();
                var terminSelect = $('#id_termin_proyek');
                if (id_proyek) {
                    terminSelect.html('<option value="">Memuat...</option>');
                    $.ajax({
                        url: '/get-termin-by-proyek/' + id_proyek,
                        type: 'GET',
                        success: function(data) {
                            terminSelect.html('<option value="">-- Pilih Termin --</option>');
                            $.each(data, function(key, val) {
                                terminSelect.append('<option value="' + val
                                    .id_termin_proyek + '" data-nominal="' + val
                                    .nominal + '">' + val.nama_termin + ' (Rp ' +
                                    val.nominal.toLocaleString() + ')</option>');
                            });
                        }
                    });
                } else {
                    terminSelect.html('<option value="">-- Pilih Proyek Dulu --</option>');
                }
            });

            $('#id_termin_proyek').on('change', function() {
                var nominal = $(this).find(':selected').data('nominal');
                if (nominal) $('#nominal').val(nominal);
            });
        });

        // INTEGRASI SWEETALERT2
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Update!',
                    text: "{!! addslashes(session('error')) !!}",
                    confirmButtonColor: '#e11d48'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal',
                    html: '<ul class="text-left text-sm">@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#f59e0b'
                });
            @endif
        });
    </script>
</x-app-layout>
