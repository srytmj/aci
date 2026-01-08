<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasMasukController extends Controller
{
    public function index()
    {
        $kas_masuks = DB::table('kas_masuk')
            ->leftJoin('kategori_kas_masuk', 'kas_masuk.id_kategori_masuk', '=', 'kategori_kas_masuk.id_kategori_masuk')
            ->leftJoin('proyek', 'kas_masuk.id_proyek', '=', 'proyek.id_proyek')
            ->leftJoin('metode_bayar', 'kas_masuk.id_metode_bayar', '=', 'metode_bayar.id_metode_bayar')
            ->select('kas_masuk.*', 'kategori_kas_masuk.nama_kategori', 'proyek.nama as nama_proyek', 'metode_bayar.nama_metode_bayar')
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        return view('kas_masuk.index', compact('kas_masuks'));
    }

    public function show($id)
    {
        try {
            $data = DB::table('kas_masuk')
                ->leftJoin('kategori_kas_masuk', 'kas_masuk.id_kategori_masuk', '=', 'kategori_kas_masuk.id_kategori_masuk')
                ->leftJoin('proyek', 'kas_masuk.id_proyek', '=', 'proyek.id_proyek')
                ->leftJoin('metode_bayar', 'kas_masuk.id_metode_bayar', '=', 'metode_bayar.id_metode_bayar')
                ->select('kas_masuk.*', 'kategori_kas_masuk.nama_kategori', 'proyek.nama as nama_proyek', 'metode_bayar.nama_metode_bayar')
                ->where('id_kas_masuk', $id)
                ->first();

            if (!$data) {
                return redirect()->route('kas-masuk.index')->with('error', 'Data penerimaan tidak ditemukan!');
            }

            return view('kas_masuk.show', compact('data'));
        } catch (\Exception $e) {
            return redirect()->route('kas-masuk.index')->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $kategori = DB::table('kategori_kas_masuk')->get();
        $proyek = DB::table('proyek')->where('status', 'aktif')->get();
        $metode = DB::table('metode_bayar')->get();

        // Auto Generate No Form KM-YYYYMMDD-001
        $date = date('Ymd');
        $count = DB::table('kas_masuk')->whereDate('created_at', date('Y-m-d'))->count();
        $no_form = 'KM-' . $date . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return view('kas_masuk.create', compact('kategori', 'proyek', 'metode', 'no_form'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_form' => 'required|unique:kas_masuk,no_form',
            'tanggal_masuk' => 'required|date',
            'id_kategori_masuk' => 'required',
            'id_metode_bayar' => 'required',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|max:255',
            'upload_bukti' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // 1. Ambil Data Kategori untuk mendapatkan Mapping COA Debit & Kredit
            $kategori = DB::table('kategori_kas_masuk')
                ->where('id_kategori_masuk', $request->id_kategori_masuk)
                ->first();

            if (!$kategori) {
                throw new \Exception("Kategori tidak ditemukan!");
            }

            if (!$kategori->id_coa_debit || !$kategori->id_coa_kredit) {
                throw new \Exception("Kategori ini belum memiliki mapping COA Debit/Kredit lengkap!");
            }

            // 2. Handle Upload File
            $fileName = null;
            if ($request->hasFile('upload_bukti')) {
                $fileName = time() . '_' . str_replace('/', '-', $request->no_form) . '.' . $request->upload_bukti->extension();
                $request->upload_bukti->move(public_path('uploads/kas_masuk'), $fileName);
            }

            // 3. Insert ke Tabel Kas Masuk
            $id_kas_masuk = DB::table('kas_masuk')->insertGetId([
                'no_form' => $request->no_form,
                'tanggal_masuk' => $request->tanggal_masuk,
                'id_kategori_masuk' => $request->id_kategori_masuk,
                'id_proyek' => $request->id_proyek,
                'id_metode_bayar' => $request->id_metode_bayar,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'upload_bukti' => $fileName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. OTOMATIS JURNAL UMUM (Berdasarkan Mapping Kategori)

            // Baris DEBIT
            DB::table('jurnal_umum')->insert([
                'id_coa' => $kategori->id_coa_debit, // Otomatis dari kategori
                'posisi_dr_cr' => 'dr',
                'tanggal' => $request->tanggal_masuk,
                'deskripsi' => $request->keterangan,
                'sumber_transaksi' => 'Kas Masuk',
                'id_transaksi' => $id_kas_masuk,
                'nominal' => $request->nominal,
                'created_at' => now(),
            ]);

            // Baris KREDIT
            DB::table('jurnal_umum')->insert([
                'id_coa' => $kategori->id_coa_kredit, // Otomatis dari kategori
                'posisi_dr_cr' => 'cr',
                'tanggal' => $request->tanggal_masuk,
                'deskripsi' => $request->keterangan,
                'sumber_transaksi' => 'Kas Masuk',
                'id_transaksi' => $id_kas_masuk,
                'nominal' => $request->nominal,
                'created_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('kas-masuk.index')->with('success', 'Transaksi Kas Masuk & Jurnal berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Return SWEETALERT2 via session error (sesuai instruksi khusus lo)
            return back()->withInput()->with('error', 'Gagal simpan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $kas_masuk = DB::table('kas_masuk')->where('id_kas_masuk', $id)->first();
            if (!$kas_masuk) {
                return redirect()->route('kas-masuk.index')->with('error', 'Data tidak ditemukan!');
            }

            $kategori = DB::table('kategori_kas_masuk')->get();
            $proyek = DB::table('proyek')->get();
            $metode = DB::table('metode_bayar')->get();

            // Ambil termin berdasarkan proyek yang terpilih di data kas_masuk
            $termin = [];
            if ($kas_masuk->id_proyek) {
                $termin = DB::table('termin_proyek')
                    ->join('tipe_termin', 'termin_proyek.id_tipe_termin', '=', 'tipe_termin.id_tipe_termin')
                    ->where('id_proyek', $kas_masuk->id_proyek)
                    ->select('termin_proyek.*', 'tipe_termin.nama_termin')
                    ->get();
            }

            return view('kas_masuk.edit', compact('kas_masuk', 'kategori', 'proyek', 'metode', 'termin'));
        } catch (\Exception $e) {
            return redirect()->route('kas-masuk.index')->with('error', 'Sistem Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_form' => 'required|unique:kas_masuk,no_form,' . $id . ',id_kas_masuk',
            'nominal' => 'required|numeric|min:1',
            'tanggal_masuk' => 'required|date',
            'id_kategori_masuk' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // 1. Ambil Kategori baru untuk Mapping COA (siapa tahu kategorinya diganti)
            $kategori = DB::table('kategori_kas_masuk')
                ->where('id_kategori_masuk', $request->id_kategori_masuk)
                ->first();

            if (!$kategori || !$kategori->id_coa_debit || !$kategori->id_coa_kredit) {
                throw new \Exception("Kategori tidak ditemukan atau Mapping COA belum lengkap!");
            }

            // 2. Siapkan data update untuk tabel kas_masuk
            $data = [
                'no_form' => $request->no_form,
                'tanggal_masuk' => $request->tanggal_masuk,
                'id_kategori_masuk' => $request->id_kategori_masuk,
                'id_proyek' => $request->id_proyek,
                'id_metode_bayar' => $request->id_metode_bayar,
                'id_termin_proyek' => $request->id_termin_proyek,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'updated_at' => now(),
            ];

            // Handle file upload jika ada yang baru
            if ($request->hasFile('upload_bukti')) {
                $fileName = time() . '_' . str_replace('/', '-', $request->no_form) . '.' . $request->upload_bukti->extension();
                $request->upload_bukti->move(public_path('uploads/kas_masuk'), $fileName);
                $data['upload_bukti'] = $fileName;
            }

            // 3. Update data Utama
            DB::table('kas_masuk')->where('id_kas_masuk', $id)->update($data);

            // 4. UPDATE JURNAL (Hapus yang lama, Insert yang baru)
            // Kita hapus berdasarkan sumber_transaksi dan id_transaksi biar spesifik
            DB::table('jurnal_umum')
                ->where('sumber_transaksi', 'Kas Masuk')
                ->where('id_transaksi', $id)
                ->delete();

            // Baris DEBIT Baru
            DB::table('jurnal_umum')->insert([
                'id_coa' => $kategori->id_coa_debit,
                'posisi_dr_cr' => 'dr',
                'tanggal' => $request->tanggal_masuk,
                'deskripsi' => $request->keterangan,
                'sumber_transaksi' => 'Kas Masuk',
                'id_transaksi' => $id,
                'nominal' => $request->nominal,
                'created_at' => now(),
            ]);

            // Baris KREDIT Baru
            DB::table('jurnal_umum')->insert([
                'id_coa' => $kategori->id_coa_kredit,
                'posisi_dr_cr' => 'cr',
                'tanggal' => $request->tanggal_masuk,
                'deskripsi' => $request->keterangan,
                'sumber_transaksi' => 'Kas Masuk',
                'id_transaksi' => $id,
                'nominal' => $request->nominal,
                'created_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('kas-masuk.index')->with('success', 'Transaksi dan Jurnal berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Return SWEETALERT2 via session error sesuai permintaan lo
            return back()->with('error', 'Gagal Update: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // 1. Cari datanya dulu
            $kas = DB::table('kas_masuk')->where('id_kas_masuk', $id)->first();

            if (!$kas) {
                return redirect()->route('kas-masuk.index')->with('error', 'Data kas tidak ditemukan!');
            }

            // 2. Hapus file bukti fisik jika ada di storage
            if ($kas->upload_bukti) {
                $filePath = public_path('uploads/kas_masuk/' . $kas->upload_bukti);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // 3. Hapus data dari database
            DB::table('kas_masuk')->where('id_kas_masuk', $id)->delete();

            DB::commit();
            return redirect()->route('kas-masuk.index')->with('success', 'Data transaksi berhasil dihapus permanen!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Return error pake SweetAlert2 (lewat session error)
            return redirect()->route('kas-masuk.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    // Tambahkan fungsi Ajax untuk ambil termin berdasarkan proyek
    public function getTerminByProyek($id)
    {
        $termin = DB::table('termin_proyek')
            ->join('tipe_termin', 'termin_proyek.id_tipe_termin', '=', 'tipe_termin.id_tipe_termin')
            ->where('id_proyek', $id)
            ->select('termin_proyek.*', 'tipe_termin.nama_termin')
            ->get();

        return response()->json($termin);
    }
}
