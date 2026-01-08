<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $jurnals = DB::table('jurnal_umum')
            ->join('coa', 'jurnal_umum.id_coa', '=', 'coa.id_coa')
            // Join ke Kas Masuk hanya jika sumber_transaksi adalah 'Kas Masuk'
            ->leftJoin('kas_masuk', function ($join) {
                $join->on('jurnal_umum.id_transaksi', '=', 'kas_masuk.id_kas_masuk')
                    ->where('jurnal_umum.sumber_transaksi', '=', 'Kas Masuk');
            })
            // Join ke Kas Keluar hanya jika sumber_transaksi adalah 'Kas Keluar'
            ->leftJoin('kas_keluar', function ($join) {
                $join->on('jurnal_umum.id_transaksi', '=', 'kas_keluar.id_kas_keluar')
                    ->where('jurnal_umum.sumber_transaksi', '=', 'Kas Keluar');
            })
            ->select(
                'jurnal_umum.*',
                'coa.kode_akun',
                'coa.nama_akun',
                // Gabungkan no_form dari kedua tabel sumber
                DB::raw('COALESCE(kas_masuk.no_form, kas_keluar.no_form) as no_ref')
            )
            ->whereMonth('jurnal_umum.tanggal', $bulan)
            ->whereYear('jurnal_umum.tanggal', $tahun)
            ->orderBy('jurnal_umum.tanggal', 'asc')
            ->orderBy('jurnal_umum.id_jurnal', 'asc')
            ->get();

        return view('jurnal.index', compact('jurnals', 'bulan', 'tahun'));
    }
}