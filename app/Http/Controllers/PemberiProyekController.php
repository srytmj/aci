<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemberiProyekController extends Controller
{
    public function index()
    {
        $pemberis = DB::table('pemberi_proyek')->get();
        return view('pemberi.index', compact('pemberis'));
    }

    public function create()
    {
        return view('pemberi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'nama' => 'required|max:150',
            'penanggung_jawab' => 'required|max:255',
        ]);

        DB::table('pemberi_proyek')->insert([
            'jenis' => $request->jenis,
            'nama' => $request->nama,
            'alamat' => $request->alamat ?? '-',
            'penanggung_jawab' => $request->penanggung_jawab,
            'no_telp' => $request->no_telp ?? '-',
            'email' => $request->email ?? '-',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('pemberi.index')->with('success', 'Data Pemberi Proyek berhasil ditambah!');
    }

    public function edit($id)
    {
        $pemberi = DB::table('pemberi_proyek')->where('id_pemberi', $id)->first();
        return view('pemberi.edit', compact('pemberi'));
    }

    public function update(Request $request, $id)
    {
        DB::table('pemberi_proyek')->where('id_pemberi', $id)->update([
            'jenis' => $request->jenis,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'penanggung_jawab' => $request->penanggung_jawab,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        return redirect()->route('pemberi.index')->with('success', 'Data berhasil diupdate!');
    }

    public function destroy($id)
    {
        DB::table('pemberi_proyek')->where('id_pemberi', $id)->delete();
        return redirect()->route('pemberi.index')->with('success', 'Data berhasil dihapus!');
    }
}