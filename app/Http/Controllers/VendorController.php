<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index() {
        $vendors = DB::table('vendor')->get();
        return view('vendor.index', compact('vendors'));
    }

    public function create() {
        return view('vendor.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required|max:150',
            'penanggung_jawab' => 'required|max:255',
        ]);

        DB::table('vendor')->insert([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'penanggung_jawab' => $request->penanggung_jawab,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan!');
    }

    public function edit($id) {
        $vendor = DB::table('vendor')->where('id_vendor', $id)->first();
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id) {
        DB::table('vendor')->where('id_vendor', $id)->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'penanggung_jawab' => $request->penanggung_jawab,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        return redirect()->route('vendor.index')->with('success', 'Data vendor berhasil diperbarui!');
    }

    public function destroy($id) {
        DB::table('vendor')->where('id_vendor', $id)->delete();
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus!');
    }
}