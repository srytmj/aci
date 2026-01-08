<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">

                <div class="bg-emerald-600 px-8 py-6 text-white">
                    <h3 class="text-xl font-bold italic tracking-tighter">INPUT KAS MASUK</h3>
                    <p class="text-xs opacity-80">Catat penerimaan dana dari termin atau pendapatan lain</p>
                </div>

                <form action="{{ route('kas-masuk.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">No. Form</label>
                            <input type="text" name="no_form" value="{{ $no_form }}" readonly
                                class="w-full rounded-xl bg-gray-50 border-gray-200 font-mono font-bold text-emerald-700">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal
                                Penerimaan</label>
                            <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}" required
                                class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kategori</label>
                            <select name="id_kategori_masuk" required class="w-full rounded-xl border-gray-200">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->id_kategori_masuk }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Metode Bayar</label>
                            <select name="id_metode_bayar" required class="w-full rounded-xl border-gray-200">
                                @foreach ($metode as $m)
                                    <option value="{{ $m->id_metode_bayar }}">{{ $m->nama_metode_bayar }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2 p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-indigo-500 uppercase mb-2">Pilih Proyek
                                        (Jika ada)</label>
                                    <select name="id_proyek" id="id_proyek"
                                        class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                                        <option value="">-- Penerimaan Umum (Non-Proyek) --</option>
                                        @foreach ($proyek as $p)
                                            <option value="{{ $p->id_proyek }}">{{ $p->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-indigo-500 uppercase mb-2">Pilih
                                        Termin</label>
                                    <select name="id_termin_proyek" id="id_termin_proyek" disabled
                                        class="w-full rounded-xl border-gray-200 disabled:bg-gray-200">
                                        <option value="">-- Pilih Proyek Dulu --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nominal Diterima
                                (Rp)</label>
                            <input type="number" name="nominal" id="nominal" required
                                class="w-full rounded-xl border-gray-200 text-lg font-black text-emerald-600 focus:ring-emerald-500"
                                placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Upload Bukti
                                Transfer</label>
                            <input type="file" name="upload_bukti"
                                class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Keterangan
                                Tambahan</label>
                            <textarea name="keterangan" id="keterangan" rows="2" class="w-full rounded-xl border-gray-200"
                                placeholder="Contoh: Pelunasan termin 1 sesuai kontrak"></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('kas-masuk.index') }}"
                            class="px-6 py-3 rounded-xl bg-gray-100 text-gray-500 font-bold">Batal</a>
                        <button type="submit"
                            class="px-10 py-3 rounded-xl bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition transform active:scale-95">
                            Simpan Kas Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Saat Proyek dipilih
            $('#id_proyek').on('change', function() {
                var id_proyek = $(this).val();
                var terminSelect = $('#id_termin_proyek');

                if (id_proyek) {
                    terminSelect.prop('disabled', false).html('<option value="">Sedang memuat...</option>');

                    // Ambil data termin via AJAX
                    $.ajax({
                        url: '/get-termin-by-proyek/' + id_proyek,
                        type: 'GET',
                        success: function(data) {
                            terminSelect.html('<option value="">-- Pilih Termin --</option>');
                            $.each(data, function(key, val) {
                                // Kita simpan data nominal di atribut data-nominal
                                terminSelect.append('<option value="' + val
                                    .id_termin_proyek + '" data-nominal="' + val
                                    .nominal + '" data-ket="' + val.keterangan +
                                    '">' + val.nama_termin + ' (Rp ' + val.nominal
                                    .toLocaleString() + ')</option>');
                            });
                        }
                    });
                } else {
                    terminSelect.prop('disabled', true).html(
                        '<option value="">-- Pilih Proyek Dulu --</option>');
                    $('#nominal').val(0);
                }
            });

            // Saat Termin dipilih (Auto-fill Nominal & Keterangan)
            $('#id_termin_proyek').on('change', function() {
                var selected = $(this).find(':selected');
                var nominal = selected.data('nominal');
                var keterangan = selected.data('ket');

                if (nominal) {
                    $('#nominal').val(nominal);
                    $('#keterangan').val("Penerimaan " + selected.text() + " - " + $(
                        '#id_proyek option:selected').text());
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Notifikasi Error dari Session (Database/Exception)
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Ada Masalah!',
                    text: "{!! addslashes(session('error')) !!}",
                    confirmButtonColor: '#e11d48',
                });
            @endif

            // 2. Notifikasi Error Validasi Laravel (Input Kurang/Salah)
            @if ($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Cek Inputan!',
                    html: `
                    <div class="text-left text-sm">
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                `,
                    confirmButtonColor: '#f59e0b',
                });
            @endif

            // 3. Notifikasi Berhasil
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
</x-app-layout>
