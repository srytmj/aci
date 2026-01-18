<x-app-layout>
    @section('title', 'Edit Kas Keluar')

    <div class="py-12" x-data="{ 
        isProyek: {{ $kas->id_proyek ? 'true' : 'false' }},
        kategoriUmum: @js($kategoriUmum),
        kategoriProyek: @js($kategoriProyek),
        kategoriAktif: [],
        nominal: '{{ old('nominal', $kas->nominal) }}',

        init() {
            // Set kategori awal berdasarkan data database
            if (this.isProyek) {
                this.kategoriAktif = this.kategoriProyek;
            } else {
                this.kategoriAktif = this.kategoriUmum;
            }
        },

        handleProyekChange(id) {
            if (id !== '') {
                this.isProyek = true;
                this.kategoriAktif = this.kategoriProyek;
            } else {
                this.isProyek = false;
                this.kategoriAktif = this.kategoriUmum;
            }
        }
    }" x-init="init()">
        
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-[2rem] overflow-hidden border border-gray-100 dark:border-gray-700">
                
                {{-- Header Gradient Rose/Red --}}
                <div class="px-8 py-8 bg-gradient-to-br from-rose-600 to-red-700 text-white relative">
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-70 mb-1">Formulir Pembaruan</p>
                            <h3 class="text-2xl font-black tracking-tighter uppercase">Edit Kas Keluar</h3>
                        </div>
                        <div class="text-right">
                            <span class="block text-[10px] font-bold opacity-70 uppercase">No. Referensi</span>
                            <span class="text-xl font-mono font-bold">{{ $kas->no_form }}</span>
                        </div>
                    </div>
                </div>

                <form id="formEditKas" action="{{ route('kas-keluar.update', $kas->id_kas) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        {{-- 1. Alokasi Proyek --}}
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">1. Alokasi Proyek (Opsional)</label>
                            <select name="id_proyek" 
                                @change="handleProyekChange($event.target.value)"
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 font-bold text-gray-700 transition-all">
                                <option value="">-- PENGELUARAN UMUM (NON-PROYEK) --</option>
                                @foreach($proyek as $p)
                                    <option value="{{ $p->id_proyek }}" {{ old('id_proyek', $kas->id_proyek) == $p->id_proyek ? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-[10px] text-gray-400">Ubah proyek jika pengeluaran ini ingin dibebankan ke proyek lain.</p>
                        </div>

                        {{-- 2. Kategori --}}
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">2. Kategori Pengeluaran</label>
                            <select name="id_kategori" required 
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 font-medium">
                                <option value="">-- Pilih Kategori --</option>
                                <template x-for="kat in kategoriAktif" :key="kat.id_kategori">
                                    <option :value="kat.id_kategori" 
                                            :selected="kat.id_kategori == '{{ old('id_kategori', $kas->id_kategori) }}'"
                                            x-text="kat.nama_kategori"></option>
                                </template>
                            </select>
                        </div>

                        {{-- 3. Tanggal --}}
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">3. Tanggal Bayar</label>
                            <input type="date" name="tanggal" required value="{{ old('tanggal', $kas->tanggal) }}"
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 font-bold">
                        </div>

                        <div class="md:col-span-2 border-t border-gray-50 my-2"></div>

                        {{-- 4. Vendor --}}
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-rose-500 uppercase mb-2 tracking-widest">4. Vendor / Penerima</label>
                            <select name="id_vendor" 
                                class="w-full border-2 border-rose-50 border-dashed bg-rose-50/30 rounded-2xl font-bold text-rose-700 focus:ring-4 focus:ring-rose-500/10">
                                <option value="">-- Tanpa Vendor (Langsung) --</option>
                                @foreach($vendor as $v)
                                    <option value="{{ $v->id_vendor }}" {{ old('id_vendor', $kas->id_vendor) == $v->id_vendor ? 'selected' : '' }}>{{ $v->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 5. Nominal --}}
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">5. Nominal Pengeluaran</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-black text-sm">Rp</span>
                                </div>
                                <input type="number" name="nominal" x-model="nominal" required
                                    class="w-full pl-12 pr-4 py-4 border-2 border-rose-100 rounded-2xl font-black text-2xl focus:ring-4 focus:ring-rose-500/10 transition-all outline-none text-rose-700">
                            </div>
                        </div>

                        {{-- Box Metode & Upload --}}
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                            <div>
                                <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest text-center">Metode Pembayaran</label>
                                <select name="id_metode_bayar" required class="w-full border-none rounded-xl font-bold shadow-sm">
                                    @foreach($metode as $m)
                                        <option value="{{ $m->id_metode_bayar }}" {{ old('id_metode_bayar', $kas->id_metode_bayar) == $m->id_metode_bayar ? 'selected' : '' }}>
                                            {{ $m->nama_metode_bayar }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest text-center">Update Bukti (Opsional)</label>
                                @if($kas->upload_bukti)
                                    <div class="mb-2 text-center">
                                        <a href="{{ asset('uploads/kas/' . $kas->upload_bukti) }}" target="_blank" class="text-[10px] font-bold text-rose-600 hover:underline">Lihat Bukti Saat Ini</a>
                                    </div>
                                @endif
                                <input type="file" name="upload_bukti" 
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-rose-600 file:text-white hover:file:bg-rose-700 transition-all">
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase mb-2 tracking-widest">Keterangan Pengeluaran</label>
                            <textarea name="keterangan" rows="3" required placeholder="Contoh: Pembelian material semen 50 sak..."
                                class="w-full border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500">{{ old('keterangan', $kas->keterangan) }}</textarea>
                        </div>
                    </div>

                    {{-- Footer Action --}}
                    <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-gray-100 pt-8">
                        <a href="{{ route('kas-keluar.index') }}" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-rose-600 transition-colors">Batal & Kembali</a>
                        <button type="submit" class="w-full md:w-auto px-12 py-5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.3em] shadow-xl shadow-rose-200 active:scale-95 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SweetAlert Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm Update
            const form = document.getElementById('formEditKas');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'SIMPAN PERUBAHAN?',
                    text: "Nominal dan Jurnal Umum akan diperbarui sesuai data ini.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48', // Rose-600
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'YA, PERBARUI!',
                    cancelButtonText: 'BATAL',
                    customClass: { popup: 'rounded-[2rem]' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Error Handling
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<ul class="text-left text-sm">@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#e11d48',
                customClass: { popup: 'rounded-[2rem]' }
            });
        @endif
        
        // Session Success/Error handled by Layout/Dashboard script usually, 
        // but if needed here:
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#e11d48',
                customClass: { popup: 'rounded-[2rem]' }
            });
        @endif
    </script>

    <style> [x-cloak] { display: none !important; } </style>
</x-app-layout>