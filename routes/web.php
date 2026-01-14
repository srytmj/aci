<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AksesController;
use App\Http\Controllers\LraController;
use App\Http\Controllers\PemberiProyekController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\TerminProyekController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\KategoriKasController;
use App\Http\Controllers\KasMasukController;
use App\Http\Controllers\KasKeluarController;
use App\Http\Controllers\JurnalUmumController;


// Route yang BISA diakses tanpa login (halaman depan/login)
Route::get('/', function () {
    return view('welcome');
});

// --- SEMUA ROUTE DI BAWAH INI HARUS LOGIN ---
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource Route buat User
    Route::resource('users', UserController::class);
    Route::resource('akses', AksesController::class);

    // Master Data
    Route::resource('lra', LraController::class);
    Route::get('/lra/laporan', [LraController::class, 'laporan'])->name('lra.laporan');
    Route::resource('pemberi', PemberiProyekController::class);
    Route::resource('proyek', ProyekController::class);
    Route::resource('termin', TerminProyekController::class);
    Route::resource('vendor', VendorController::class);
    Route::resource('coa', CoaController::class);
    Route::resource('kategori', KategoriKasController::class);

    // Transaksi
    Route::resource('kas-masuk', KasMasukController::class);

    Route::get('/api/proyek/{id}/termin', [App\Http\Controllers\KasMasukController::class, 'getTerminByProyek']);
    
    Route::resource('kas-keluar', KasKeluarController::class);

    Route::resource('jurnal', JurnalUmumController::class);

    Route::post('/switch-role', function (Illuminate\Http\Request $request) {
        session(['active_role_id' => $request->id_akses]);
        return back()->with('success', 'Akses dialihkan');
    })->name('switch.role');

});

// Route bawaan Laravel Breeze/Jetstream (Login, Register, dll)
require __DIR__ . '/auth.php';