<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyekController extends Controller
{
    public function index()
    {
        // Join ke tabel pemberi_proyek untuk ambil nama client
        $proyeks = DB::table('proyek')
            ->leftJoin('pemberi_proyek', 'proyek.id_pemberi', '=', 'pemberi_proyek.id_pemberi')
            ->select('proyek.*', 'pemberi_proyek.nama as nama_pemberi')
            ->get();

        return view('proyek.index', compact('proyeks'));
    }

    public function create()
    {
        $pemberis = DB::table('pemberi_proyek')->get();
        return view('proyek.create', compact('pemberis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:150',
            'id_pemberi' => 'required',
            'nilai_kontrak' => 'required|numeric',
            'jumlah_termin' => 'required|integer|min:1', // Tambahkan validasi jumlah termin
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'status' => 'required'
        ]);

        // Gunakan Transaction supaya data konsisten
        DB::beginTransaction();

        try {
            // 1. Insert ke tabel Proyek dan ambil ID-nya
            $id_proyek = DB::table('proyek')->insertGetId([
                'nama' => $request->nama,
                'id_pemberi' => $request->id_pemberi,
                'nilai_kontrak' => $request->nilai_kontrak,
                'jumlah_termin' => $request->jumlah_termin,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $request->status,
                'deskripsi' => $request->deskripsi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Looping buat data termin otomatis sebanyak jumlah_termin
            // Ambil ID tipe termin default (misal ID 1 adalah 'Termin' atau 'Belum Diatur')
            // Pastikan lo punya minimal 1 data di tabel tipe_termin agar tidak error
            $default_tipe = DB::table('tipe_termin')->first();
            $id_tipe_default = $default_tipe ? $default_tipe->id_tipe_termin : 1;

            for ($i = 1; $i <= $request->jumlah_termin; $i++) {
                DB::table('termin_proyek')->insert([
                    'id_proyek' => $id_proyek,
                    'id_tipe_termin' => $id_tipe_default,
                    'nominal' => 0, // Set 0 dulu, nanti diedit admin
                    'keterangan' => 'Termin Ke-' . $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('proyek.index')->with('success', 'Proyek dan ' . $request->jumlah_termin . ' termin berhasil didaftarkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $proyek = DB::table('proyek')->where('id_proyek', $id)->first();
        $pemberis = DB::table('pemberi_proyek')->get();

        return view('proyek.edit', compact('proyek', 'pemberis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:150',
            'id_pemberi' => 'required',
            'nilai_kontrak' => 'required|numeric',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'status' => 'required'
        ]);

        DB::table('proyek')->where('id_proyek', $id)->update([
            'nama' => $request->nama,
            'id_pemberi' => $request->id_pemberi,
            'nilai_kontrak' => $request->nilai_kontrak,
            'jumlah_termin' => $request->jumlah_termin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'updated_at' => now(),
        ]);

        return redirect()->route('proyek.index')->with('success', 'Data proyek berhasil diperbarui!');
    }

    public function destroy($id)
    {
        DB::table('proyek')->where('id_proyek', $id)->delete();
        return redirect()->route('proyek.index')->with('success', 'Data proyek berhasil dihapus!');
    }
}