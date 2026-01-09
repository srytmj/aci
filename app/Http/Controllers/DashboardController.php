<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Set Waktu Sekarang (Sesuaikan dengan data 2025/2026)
        $now = Carbon::now();
        $bulanIni = $now->format('m');
        $tahunIni = $now->format('Y');

        // 1. Hitung Total Proyek & Proyek Aktif (id_status 1 = Aktif berdasarkan doc lo)
        $totalProyek = DB::table('proyek')->count();
        $proyekAktif = DB::table('proyek')->where('status', 'aktif')->count();

        // 2. Hitung Saldo Kas (Semua Waktu)
        $totalMasuk = DB::table('kas_masuk')->sum('nominal') ?? 0;
        $totalKeluar = DB::table('kas_keluar')->sum('nominal') ?? 0;
        $saldoKas = $totalMasuk - $totalKeluar;

        // 3. Mutasi Khusus Bulan Ini (Format YYYY-MM-DD aman pake whereMonth)
        $kasMasukBulanIni = DB::table('kas_masuk')
            ->whereMonth('tanggal_masuk', $bulanIni)
            ->whereYear('tanggal_masuk', $tahunIni)
            ->sum('nominal') ?? 0;

        $kasKeluarBulanIni = DB::table('kas_keluar')
            ->whereMonth('tanggal_keluar', $bulanIni)
            ->whereYear('tanggal_keluar', $tahunIni)
            ->sum('nominal') ?? 0;

        // 4. Query Transaksi Terbaru (Yang kita buat sebelumnya)
        $kasMasukUnion = DB::table('kas_masuk')
            ->leftJoin('proyek', 'kas_masuk.id_proyek', '=', 'proyek.id_proyek')
            ->leftJoin('kategori_kas_masuk', 'kas_masuk.id_kategori', '=', 'kategori_kas_masuk.id_kategori')
            ->leftJoin('metode_bayar', 'kas_masuk.id_metode_bayar', '=', 'metode_bayar.id_metode_bayar')
            ->select('kas_masuk.no_form', 'kas_masuk.tanggal_masuk as tanggal', 'kas_masuk.nominal', 'kas_masuk.keterangan', 'proyek.nama', 'kategori_kas_masuk.nama_kategori as info_tambahan', 'metode_bayar.nama_metode_bayar', DB::raw("'Masuk' as tipe"));

        $transaksiTerbaru = DB::table('kas_keluar')
            ->leftJoin('proyek', 'kas_keluar.id_proyek', '=', 'proyek.id_proyek')
            ->leftJoin('vendor', 'kas_keluar.id_vendor', '=', 'vendor.id_vendor')
            ->leftJoin('metode_bayar', 'kas_keluar.id_metode_bayar', '=', 'metode_bayar.id_metode_bayar')
            ->select('kas_keluar.no_form', 'kas_keluar.tanggal_keluar as tanggal', 'kas_keluar.nominal', 'kas_keluar.keterangan', 'proyek.nama', 'vendor.nama as info_tambahan', 'metode_bayar.nama_metode_bayar', DB::raw("'Keluar' as tipe"))
            ->union($kasMasukUnion)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProyek',
            'proyekAktif',
            'saldoKas',
            'kasMasukBulanIni',
            'kasKeluarBulanIni',
            'transaksiTerbaru'
        ));
    }
}