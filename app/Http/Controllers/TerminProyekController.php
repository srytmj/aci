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
    /**
     * Menampilkan form edit
     */
    public function edit($id)
    {
        // Ambil data termin beserta data proyek terkait
        $termin = DB::table('termin_proyek')
            ->join('proyek', 'termin_proyek.id_proyek', '=', 'proyek.id_proyek')
            ->select('termin_proyek.*', 'proyek.nama as nama_proyek', 'proyek.nilai_kontrak')
            ->where('id_termin_proyek', $id)
            ->first();

        if (!$termin) {
            return redirect()->route('termin.index')->with('error', 'Data termin tidak ditemukan!');
        }

        $tipe_termin = DB::table('tipe_termin')->get();

        return view('termin.edit', compact('termin', 'tipe_termin'));
    }

    /**
     * Update data termin
     */
    public function update(Request $request, $id)
    {
        // Validasi input dasar
        $request->validate([
            'id_tipe_termin' => 'required',
            'nominal' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        try {
            // Ambil data termin dan join ke proyek buat cek nilai kontrak
            $termin = DB::table('termin_proyek')
                ->join('proyek', 'termin_proyek.id_proyek', '=', 'proyek.id_proyek')
                ->select('termin_proyek.*', 'proyek.nilai_kontrak')
                ->where('id_termin_proyek', $id)
                ->first();

            // VALIDASI: Cek apakah nominal baru melebihi nilai kontrak proyek
            // Kita hitung total termin lain (selain yang ini) di proyek tersebut
            $totalTerminLain = DB::table('termin_proyek')
                ->where('id_proyek', $termin->id_proyek)
                ->where('id_termin_proyek', '!=', $id)
                ->sum('nominal');

            if (($totalTerminLain + $request->nominal) > $termin->nilai_kontrak) {
                $sisaBolehInput = $termin->nilai_kontrak - $totalTerminLain;
                return back()->with('error', "Nominal gagal diupdate! Total termin akan melebihi Nilai Kontrak. Sisa limit: Rp " . number_format($sisaBolehInput, 0, ',', '.'));
            }

            // Eksekusi Update (Kecuali STATUS, status tidak boleh diubah di sini)
            DB::table('termin_proyek')->where('id_termin_proyek', $id)->update([
                'id_tipe_termin' => $request->id_tipe_termin,
                'nominal' => $request->nominal,
                'due_date' => $request->due_date,
                'keterangan' => $request->keterangan,
                'updated_at' => now(),
            ]);

            return redirect()->route('termin.index')->with('success', 'Data termin berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }
}
