<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LraController extends Controller
{
    public function index()
    {
        $lras = DB::table('lra')->orderBy('jenis', 'asc')->get();
        return view('lra.index', compact('lras'));
    }

    public function show(Request $request)
    {
        try {
            $selectedProyek = $request->get('proyek_id');

            // 1. Ambil list proyek untuk dropdown
            $listProyek = DB::table('proyek')->orderBy('nama', 'asc')->get();

            // 2. Hitung Anggaran (Nilai Kontrak)
            $queryAnggaran = DB::table('proyek');
            if ($selectedProyek) {
                $queryAnggaran->where('id_proyek', $selectedProyek);
            }
            $totalAnggaranProyek = $queryAnggaran->sum('nilai_kontrak');

            // 3. Ambil Master LRA
            $masterLra = DB::table('lra')->get();
            $dataLra = [
                'pendapatan' => $masterLra->where('jenis', 'pendapatan'),
                'pengeluaran' => $masterLra->where('jenis', 'pengeluaran'),
            ];

            return view('lra.laporan', compact('totalAnggaranProyek', 'dataLra', 'listProyek', 'selectedProyek'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat Laporan LRA: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'keterangan' => 'required|string|max:255',
                'persentase' => 'required|numeric|min:0|max:100',
                'jenis' => 'required|in:pendapatan,pengeluaran',
            ]);

            DB::table('lra')->insert([
                'keterangan' => $request->keterangan,
                'persentase' => $request->persentase,
                'jenis' => $request->jenis,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Master LRA berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('lra')->where('id_lra', $id)->delete();
            return back()->with('success', 'Data LRA berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data.');
        }
    }

    public function laporan(Request $request)
    {
        try {
            $selectedProyek = $request->get('proyek_id');

            // 1. Ambil list proyek untuk dropdown
            $listProyek = DB::table('proyek')->orderBy('nama', 'asc')->get();

            // 2. Hitung Anggaran (Nilai Kontrak)
            $queryAnggaran = DB::table('proyek');
            if ($selectedProyek) {
                $queryAnggaran->where('id_proyek', $selectedProyek);
            }
            $totalAnggaranProyek = $queryAnggaran->sum('nilai_kontrak');

            // 3. Ambil Master LRA
            $masterLra = DB::table('lra')->get();
            $dataLra = [
                'pendapatan' => $masterLra->where('jenis', 'pendapatan'),
                'pengeluaran' => $masterLra->where('jenis', 'pengeluaran'),
            ];

            return view('lra.laporan', compact('totalAnggaranProyek', 'dataLra', 'listProyek', 'selectedProyek'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat Laporan LRA: ' . $e->getMessage());
        }
    }
}