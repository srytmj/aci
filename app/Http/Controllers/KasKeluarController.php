<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class KasKeluarController extends Controller
{
    public function index()
    {
        $kas_keluar = DB::table('kas_keluar')
            ->leftJoin('kategori_kas_keluar', 'kas_keluar.id_kategori_keluar', '=', 'kategori_kas_keluar.id_kategori_keluar')
            ->leftJoin('proyek', 'kas_keluar.id_proyek', '=', 'proyek.id_proyek')
            ->leftJoin('vendor', 'kas_keluar.id_vendor', '=', 'vendor.id_vendor')
            ->leftJoin('metode_bayar', 'kas_keluar.id_metode_bayar', '=', 'metode_bayar.id_metode_bayar')
            ->select(
                'kas_keluar.*',
                'kategori_kas_keluar.nama_kategori',
                'proyek.nama as nama_proyek',
                'vendor.nama as nama_vendor',
                'metode_bayar.nama_metode_bayar'
            )
            ->orderBy('kas_keluar.tanggal_keluar', 'desc')
            ->get();

        return view('kas_keluar.index', compact('kas_keluar'));
    }

    public function show($id)
    {
        try {
            $data = DB::table('kas_keluar')
                ->leftJoin('kategori_kas_keluar', 'kas_keluar.id_kategori_keluar', '=', 'kategori_kas_keluar.id_kategori_keluar')
                ->leftJoin('proyek', 'kas_keluar.id_proyek', '=', 'proyek.id_proyek')
                ->leftJoin('vendor', 'kas_keluar.id_vendor', '=', 'vendor.id_vendor')
                ->leftJoin('metode_bayar', 'kas_keluar.id_metode_bayar', '=', 'metode_bayar.id_metode_bayar')
                ->select('kas_keluar.*', 'kategori_kas_keluar.nama_kategori', 'proyek.nama as nama_proyek', 'vendor.nama as nama_vendor', 'metode_bayar.nama_metode_bayar')
                ->where('id_kas_keluar', $id)
                ->first();

            if (!$data) {
                return redirect()->route('kas-keluar.index')->with('error', 'Data tidak ditemukan!');
            }

            return view('kas_keluar.show', compact('data'));
        } catch (\Exception $e) {
            return redirect()->route('kas-keluar.index')->with('error', 'Gagal memuat detail: ' . $e->getMessage());
        }
    }

    public function create()
    {
        // Generate No Form Otomatis: KK-YYYYMM-001
        $bulanRomawi = date('m');
        $tahun = date('Y');
        $count = DB::table('kas_keluar')->whereYear('created_at', $tahun)->whereMonth('created_at', $bulanRomawi)->count();
        $no_form = 'KK-' . $tahun . $bulanRomawi . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        $kategori = DB::table('kategori_kas_keluar')->get();
        $proyek = DB::table('proyek')->where('status', 'aktif')->get();
        $vendor = DB::table('vendor')->get(); // Pastikan tabel vendor sudah ada
        $metode = DB::table('metode_bayar')->get();

        return view('kas_keluar.create', compact('no_form', 'kategori', 'proyek', 'vendor', 'metode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_form' => 'required|unique:kas_keluar,no_form',
            'tanggal_keluar' => 'required|date',
            'id_kategori_keluar' => 'required',
            'id_metode_bayar' => 'required',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|max:255',
            'upload_bukti' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // 1. Handle File Upload
            $fileName = null;
            if ($request->hasFile('upload_bukti')) {
                $fileName = time() . '_' . str_replace('/', '-', $request->no_form) . '.' . $request->upload_bukti->extension();
                $request->upload_bukti->move(public_path('uploads/kas_keluar'), $fileName);
            }

            // 2. Insert Kas Keluar & Ambil ID-nya
            $id_kas_keluar = DB::table('kas_keluar')->insertGetId([
                'no_form' => $request->no_form,
                'tanggal_keluar' => $request->tanggal_keluar,
                'id_kategori_keluar' => $request->id_kategori_keluar,
                'id_proyek' => $request->id_proyek,
                'id_vendor' => $request->id_vendor,
                'id_metode_bayar' => $request->id_metode_bayar,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'upload_bukti' => $fileName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Insert Jurnal Umum (Double Entry)

            // Baris DEBIT (Beban/Biaya bertambah)
            // Kita asumsikan ID COA 40 (misal: Beban Operasional/Proyek)
            DB::table('jurnal_umum')->insert([
                'id_coa' => 40,
                'posisi_dr_cr' => 'dr',
                'tanggal' => $request->tanggal_keluar,
                'deskripsi' => $request->keterangan,
                'sumber_transaksi' => 'Kas Keluar',
                'id_transaksi' => $id_kas_keluar,
                'nominal' => $request->nominal,
                'created_at' => now(),
            ]);

            // Baris KREDIT (Kas/Bank berkurang)
            // Pakai ID COA 12 (Akun Kas Utama)
            DB::table('jurnal_umum')->insert([
                'id_coa' => 12,
                'posisi_dr_cr' => 'cr',
                'tanggal' => $request->tanggal_keluar,
                'deskripsi' => $request->keterangan,
                'sumber_transaksi' => 'Kas Keluar',
                'id_transaksi' => $id_kas_keluar,
                'nominal' => $request->nominal,
                'created_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('kas-keluar.index')->with('success', 'Pengeluaran & Jurnal berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            // SweetAlert2 akan menangkap session 'error' ini
            return back()->withInput()->with('error', 'Gagal simpan Jurnal Keluar: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $kas_keluar = DB::table('kas_keluar')->where('id_kas_keluar', $id)->first();
            if (!$kas_keluar) {
                return redirect()->route('kas-keluar.index')->with('error', 'Data tidak ditemukan!');
            }

            $kategori = DB::table('kategori_kas_keluar')->get();
            $proyek = DB::table('proyek')->get();
            $vendor = DB::table('vendor')->get();
            $metode = DB::table('metode_bayar')->get();

            return view('kas_keluar.edit', compact('kas_keluar', 'kategori', 'proyek', 'vendor', 'metode'));
        } catch (\Exception $e) {
            return redirect()->route('kas-keluar.index')->with('error', 'Sistem Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_form' => 'required|unique:kas_keluar,no_form,' . $id . ',id_kas_keluar',
            'nominal' => 'required|numeric|min:1',
            'tanggal_keluar' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'no_form' => $request->no_form,
                'tanggal_keluar' => $request->tanggal_keluar,
                'id_kategori_keluar' => $request->id_kategori_keluar,
                'id_proyek' => $request->id_proyek,
                'id_vendor' => $request->id_vendor,
                'id_metode_bayar' => $request->id_metode_bayar,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'updated_at' => now(),
            ];

            if ($request->hasFile('upload_bukti')) {
                // Hapus file lama jika ada
                $oldFile = DB::table('kas_keluar')->where('id_kas_keluar', $id)->value('upload_bukti');
                if ($oldFile && file_exists(public_path('uploads/kas_keluar/' . $oldFile))) {
                    unlink(public_path('uploads/kas_keluar/' . $oldFile));
                }

                $fileName = time() . '_' . str_replace('/', '-', $request->no_form) . '.' . $request->upload_bukti->extension();
                $request->upload_bukti->move(public_path('uploads/kas_keluar'), $fileName);
                $data['upload_bukti'] = $fileName;
            }

            DB::table('kas_keluar')->where('id_kas_keluar', $id)->update($data);

            DB::commit();
            return redirect()->route('kas-keluar.index')->with('success', 'Data Kas Keluar berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal Update: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = DB::table('kas_keluar')->where('id_kas_keluar', $id)->first();

            if (!$data) {
                return back()->with('error', 'Data tidak ditemukan!');
            }

            // Hapus file fisik
            if ($data->upload_bukti) {
                $path = public_path('uploads/kas_keluar/' . $data->upload_bukti);
                if (file_exists($path))
                    unlink($path);
            }

            DB::table('kas_keluar')->where('id_kas_keluar', $id)->delete();

            DB::commit();
            return redirect()->route('kas-keluar.index')->with('success', 'Data berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hapus data: ' . $e->getMessage());
        }
    }
}