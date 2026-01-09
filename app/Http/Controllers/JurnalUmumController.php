<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        try {
            $bulan = $request->get('bulan', date('m'));
            $tahun = $request->get('tahun', date('Y'));

            $jurnals = DB::table('jurnal_umum')
                ->join('coa', 'jurnal_umum.id_coa', '=', 'coa.id_coa')
                // Jika tabelnya sudah digabung jadi satu tabel 'kas'
                ->leftJoin('kas', 'jurnal_umum.id_transaksi', '=', 'kas.id_kas')
                ->select(
                    'jurnal_umum.*',
                    'coa.kode_akun',
                    'coa.nama_akun',
                    'kas.no_form as no_ref' // Langsung ambil dari kolom no_form tabel kas
                )
                ->whereMonth('jurnal_umum.tanggal', $bulan)
                ->whereYear('jurnal_umum.tanggal', $tahun)
                ->orderBy('jurnal_umum.tanggal', 'asc')
                ->orderBy('jurnal_umum.id_jurnal', 'asc')
                ->get();

            return view('jurnal.index', compact('jurnals', 'bulan', 'tahun'));

        } catch (\Exception $e) {
            // Jika ada error, kirim pesan ke view untuk ditangkap SweetAlert2
            return back()->with('error', 'Gagal memuat Jurnal: ' . $e->getMessage());
        }
    }
}