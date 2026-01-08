<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriKasController extends Controller
{
    public function index()
    {
        // Query untuk Kas Masuk
        $masuk = DB::table('kategori_kas_masuk')
            ->leftJoin('coa as coa_d', 'kategori_kas_masuk.id_coa_debit', '=', 'coa_d.id_coa')
            ->leftJoin('coa as coa_k', 'kategori_kas_masuk.id_coa_kredit', '=', 'coa_k.id_coa')
            ->select(
                'kategori_kas_masuk.*',
                'coa_d.nama_akun as nama_debit',
                'coa_d.kode_akun as kode_debit',
                'coa_k.nama_akun as nama_kredit',
                'coa_k.kode_akun as kode_kredit'
            )
            ->get();

        // Query untuk Kas Keluar
        $keluar = DB::table('kategori_kas_keluar')
            ->leftJoin('coa as coa_d', 'kategori_kas_keluar.id_coa_debit', '=', 'coa_d.id_coa')
            ->leftJoin('coa as coa_k', 'kategori_kas_keluar.id_coa_kredit', '=', 'coa_k.id_coa')
            ->select(
                'kategori_kas_keluar.*',
                'coa_d.nama_akun as nama_debit',
                'coa_d.kode_akun as kode_debit',
                'coa_k.nama_akun as nama_kredit',
                'coa_k.kode_akun as kode_kredit'
            )
            ->get();

        return view('kategori.index', compact('masuk', 'keluar'));
    }

    public function create()
    {
        // Ambil data COA buat dropdown mapping
        $coa = DB::table('coa')->orderBy('kode_akun', 'asc')->get();

        // Default jenis kita set kosong atau 'masuk'
        return view('kategori.create', compact('coa'));
    }

    public function store(Request $request)
    {
        $table = $request->jenis == 'masuk' ? 'kategori_kas_masuk' : 'kategori_kas_keluar';

        try {
            DB::table($table)->insert([
                'nama_kategori' => $request->nama_kategori,
                'id_coa_debit' => $request->id_coa_debit,
                'id_coa_kredit' => $request->id_coa_kredit,
                'deskripsi' => $request->deskripsi,
                'created_at' => now()
            ]);
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id, $jenis)
    {
        $table = $jenis == 'masuk' ? 'kategori_kas_masuk' : 'kategori_kas_keluar';
        $pk = $jenis == 'masuk' ? 'id_kategori_masuk' : 'id_kategori_keluar';

        $data = DB::table($table)->where($pk, $id)->first();
        $coa = DB::table('coa')->orderBy('kode_akun', 'asc')->get();

        return view('kategori.edit', compact('data', 'jenis', 'coa', 'pk'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_coa_debit' => 'required',
            'id_coa_kredit' => 'required',
            'jenis' => 'required|in:masuk,keluar'
        ], [
            'nama_kategori.required' => 'Nama kategori nggak boleh kosong, brok!',
            'id_coa_debit.required' => 'Akun Debit harus dipilih buat jurnal otomatis.',
            'id_coa_kredit.required' => 'Akun Kredit harus dipilih buat jurnal otomatis.',
        ]);

        $table = $request->jenis == 'masuk' ? 'kategori_kas_masuk' : 'kategori_kas_keluar';
        $pk = $request->jenis == 'masuk' ? 'id_kategori_masuk' : 'id_kategori_keluar';

        DB::beginTransaction();
        try {
            // 2. Cek apakah data yang mau diupdate ada
            $exists = DB::table($table)->where($pk, $id)->first();
            if (!$exists) {
                throw new \Exception("Data kategori tidak ditemukan di database!");
            }

            // 3. Eksekusi Update
            DB::table($table)->where($pk, $id)->update([
                'nama_kategori' => $request->nama_kategori,
                'id_coa_debit' => $request->id_coa_debit,
                'id_coa_kredit' => $request->id_coa_kredit,
                'deskripsi' => $request->deskripsi,
                'updated_at' => now()
            ]);

            DB::commit();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Return SweetAlert2 via session error
            return back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function destroy($id, $jenis)
    {
        // Tentukan tabel dan Primary Key berdasarkan jenis
        $table = $jenis == 'masuk' ? 'kategori_kas_masuk' : 'kategori_kas_keluar';
        $pk = $jenis == 'masuk' ? 'id_kategori_masuk' : 'id_kategori_keluar';

        try {
            // Cek dulu datanya ada atau nggak
            $exists = DB::table($table)->where($pk, $id)->exists();

            if (!$exists) {
                return back()->with('error', 'Data tidak ditemukan atau sudah dihapus!');
            }

            // Eksekusi hapus
            DB::table($table)->where($pk, $id)->delete();

            return redirect()->route('kategori.index')->with('success', 'Kategori ' . ucfirst($jenis) . ' berhasil dihapus!');

        } catch (\Exception $e) {
            // Balikin error ke SweetAlert2 kalau ada constraint (misal: kategori sudah dipake di transaksi)
            return back()->with('error', 'Gagal menghapus: Kategori ini mungkin sedang digunakan dalam transaksi.');
        }
    }
}