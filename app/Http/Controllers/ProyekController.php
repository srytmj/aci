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
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'status' => 'required'
        ]);

        DB::table('proyek')->insert([
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

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil didaftarkan!');
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