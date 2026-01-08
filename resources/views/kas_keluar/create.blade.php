<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Pengeluaran Kas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-rose-100">

                <div class="bg-rose-600 px-8 py-6 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold italic tracking-tighter">FORM KAS KELUAR</h3>
                        <p class="text-xs opacity-80">Catat pembayaran vendor, gaji, operasional, dll.</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-75">Saldo Anda Berkurang</p>
                    </div>
                </div>

                <form action="{{ route('kas-keluar.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">No. Dokumen</label>
                            <input type="text" name="no_form" value="{{ $no_form }}" readonly
                                class="w-full rounded-xl bg-gray-50 border-gray-200 font-mono font-bold text-rose-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Tanggal
                                Transaksi</label>
                            <input type="date" name="tanggal_keluar" value="{{ date('Y-m-d') }}" required
                                class="w-full rounded-xl border-gray-200 focus:ring-rose-500">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kategori
                                Pengeluaran</label>
                            <select name="id_kategori_keluar" required
                                class="w-full rounded-xl border-gray-200 focus:ring-rose-500">
                                <option value="">-- Pilih Jenis Pengeluaran --</option>
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->id_kategori_keluar }}">{{ $k->nama_kategori }} -
                                        {{ $k->deskripsi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2 p-5 bg-rose-50 rounded-2xl border border-dashed border-rose-300">
                            <p class="text-xs font-bold text-rose-500 uppercase mb-4 tracking-widest">Detail Peruntukan
                                (Opsional)</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Untuk Proyek
                                        Mana?</label>
                                    <select name="id_proyek" class="w-full rounded-xl border-gray-200 text-sm">
                                        <option value="">-- Umum / Non-Proyek --</option>
                                        @foreach ($proyek as $p)
                                            <option value="{{ $p->id_proyek }}">{{ $p->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Bayar Ke
                                        Vendor Siapa?</label>
                                    <select name="id_vendor" class="w-full rounded-xl border-gray-200 text-sm">
                                        <option value="">-- Tidak Ada Vendor Khusus --</option>
                                        @foreach ($vendor as $v)
                                            <option value="{{ $v->id_vendor }}">{{ $v->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Metode
                                Pembayaran</label>
                            <select name="id_metode_bayar" required class="w-full rounded-xl border-gray-200">
                                @foreach ($metode as $m)
                                    <option value="{{ $m->id_metode_bayar }}">{{ $m->nama_metode_bayar }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nominal Keluar
                                (Rp)</label>
                            <input type="number" name="nominal" required
                                class="w-full rounded-xl border-gray-200 text-lg font-black text-rose-600 focus:ring-rose-500"
                                placeholder="0">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Keterangan /
                                Keperluan</label>
                            <textarea name="keterangan" rows="2" required class="w-full rounded-xl border-gray-200 focus:ring-rose-500"
                                placeholder="Contoh: Pembelian semen 50 sak untuk cor lantai 2"></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Upload Bukti /
                                Nota</label>
                            <input type="file" name="upload_bukti"
                                class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-rose-100 file:text-rose-700 hover:file:bg-rose-200 transition">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('kas-keluar.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">Batal</a>
                        <button type="submit"
                            class="px-10 py-2.5 rounded-xl bg-rose-600 text-white font-bold shadow-lg shadow-rose-200 hover:bg-rose-700 transition transform active:scale-95">
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Error dari Session (Database/Controller)
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Simpan!',
                    text: "{!! addslashes(session('error')) !!}",
                    confirmButtonColor: '#e11d48',
                });
            @endif

            // 2. Error Validasi Laravel
            @if ($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Cek Inputan Anda!',
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

            // 3. Success Message
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
</x-app-layout>
