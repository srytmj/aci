<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TerminProyekController extends Controller
{
    public function index()
    {
        $termins = DB::table('termin_proyek')
            ->join('proyek', 'termin_proyek.id_proyek', '=', 'proyek.id_proyek')
            ->join('tipe_termin', 'termin_proyek.id_tipe_termin', '=', 'tipe_termin.id_tipe_termin')
            ->select('termin_proyek.*', 'proyek.nama as nama_proyek', 'tipe_termin.nama_termin')
            ->orderBy('proyek.nama', 'asc')
            ->get();

        return view('termin.index', compact('termins'));
    }
    public function edit($id)
    {
        // Ambil data termin beserta info proyeknya untuk patokan nominal
        $termin = DB::table('termin_proyek')
            ->join('proyek', 'termin_proyek.id_proyek', '=', 'proyek.id_proyek')
            ->select('termin_proyek.*', 'proyek.nama as nama_proyek', 'proyek.nilai_kontrak')
            ->where('id_termin_proyek', $id)
            ->first();

        $tipe_termin = DB::table('tipe_termin')->get();

        return view('termin.edit', compact('termin', 'tipe_termin'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_tipe_termin' => 'required',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|max:255'
        ]);

        DB::table('termin_proyek')->where('id_termin_proyek', $id)->update([
            'id_tipe_termin' => $request->id_tipe_termin,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'updated_at' => now(),
        ]);

        return redirect()->route('termin.index')->with('success', 'Detail termin berhasil diperbarui!');
    }
}
