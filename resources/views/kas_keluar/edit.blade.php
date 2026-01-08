<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">

                <div class="bg-rose-600 px-8 py-6 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold italic tracking-tighter">EDIT KAS KELUAR</h3>
                        <p class="text-xs opacity-80">Perbarui rincian pengeluaran dana</p>
                    </div>
                    <span
                        class="bg-white/20 px-4 py-1 rounded-full text-xs font-mono font-bold">{{ $kas_keluar->no_form }}</span>
                </div>

                <form action="{{ route('kas-keluar.update', $kas_keluar->id_kas_keluar) }}" method="POST"
                    enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">No. Form</label>
                            <input type="text" name="no_form" value="{{ old('no_form', $kas_keluar->no_form) }}"
                                class="w-full rounded-xl bg-gray-50 border-gray-200 font-mono font-bold text-rose-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal Keluar</label>
                            <input type="date" name="tanggal_keluar"
                                value="{{ old('tanggal_keluar', $kas_keluar->tanggal_keluar) }}" required
                                class="w-full rounded-xl border-gray-200">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kategori</label>
                            <select name="id_kategori_keluar" required class="w-full rounded-xl border-gray-200">
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->id_kategori_keluar }}"
                                        {{ $kas_keluar->id_kategori_keluar == $k->id_kategori_keluar ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div
                            class="col-span-2 p-4 bg-rose-50 rounded-2xl border border-dashed border-rose-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-rose-600 uppercase mb-1">Proyek</label>
                                <select name="id_proyek" class="w-full rounded-xl border-gray-200 text-sm">
                                    <option value="">-- Umum --</option>
                                    @foreach ($proyek as $p)
                                        <option value="{{ $p->id_proyek }}"
                                            {{ $kas_keluar->id_proyek == $p->id_proyek ? 'selected' : '' }}>
                                            {{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-rose-600 uppercase mb-1">Vendor</label>
                                <select name="id_vendor" class="w-full rounded-xl border-gray-200 text-sm">
                                    <option value="">-- Tanpa Vendor --</option>
                                    @foreach ($vendor as $v)
                                        <option value="{{ $v->id_vendor }}"
                                            {{ $kas_keluar->id_vendor == $v->id_vendor ? 'selected' : '' }}>
                                            {{ $v->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Metode Bayar</label>
                            <select name="id_metode_bayar" required class="w-full rounded-xl border-gray-200">
                                @foreach ($metode as $m)
                                    <option value="{{ $m->id_metode_bayar }}"
                                        {{ $kas_keluar->id_metode_bayar == $m->id_metode_bayar ? 'selected' : '' }}>
                                        {{ $m->nama_metode_bayar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nominal (Rp)</label>
                            <input type="number" name="nominal" value="{{ old('nominal', $kas_keluar->nominal) }}"
                                required
                                class="w-full rounded-xl border-gray-200 text-lg font-black text-rose-600 focus:ring-rose-500">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Ganti Bukti (Kosongkan
                                jika tetap)</label>
                            <input type="file" name="upload_bukti"
                                class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-rose-50 file:text-rose-700">
                            @if ($kas_keluar->upload_bukti)
                                <p class="text-[10px] mt-1 text-gray-400 italic">File: {{ $kas_keluar->upload_bukti }}
                                </p>
                            @endif
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="2" class="w-full rounded-xl border-gray-200">{{ old('keterangan', $kas_keluar->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('kas-keluar.index') }}"
                            class="px-6 py-3 rounded-xl bg-gray-100 text-gray-500 font-bold hover:bg-gray-200 transition">Batal</a>
                        <button type="submit"
                            class="px-10 py-3 rounded-xl bg-rose-600 text-white font-bold shadow-lg shadow-rose-100 hover:bg-rose-700 transition transform active:scale-95">
                            Update Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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
                    title: 'Cek Kembali',
                    html: '<ul class="text-left text-sm">@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#f59e0b'
                });
            @endif
        });
    </script>
</x-app-layout>
