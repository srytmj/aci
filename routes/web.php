<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
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

    Route::resource('pemberi', PemberiProyekController::class);

    Route::resource('proyek', ProyekController::class);
    Route::resource('termin', TerminProyekController::class);
    Route::resource('vendor', VendorController::class);
    Route::resource('coa', CoaController::class);

    Route::get('/kategori/delete/{id}/{jenis}', [KategoriKasController::class, 'destroy'])->name('kategori.destroy');
    Route::get('/kategori/{id}/edit/{jenis}', [KategoriKasController::class, 'edit'])->name('kategori.edit');
    Route::resource('kategori', KategoriKasController::class);

    // Transaksi
    Route::resource('kas-masuk', KasMasukController::class);
    Route::get('/get-termin-by-proyek/{id}', [KasMasukController::class, 'getTerminByProyek']);

    Route::resource('kas-keluar', KasKeluarController::class);

    Route::resource('jurnal', JurnalUmumController::class);


});

// Route bawaan Laravel Breeze/Jetstream (Login, Register, dll)
require __DIR__ . '/auth.php';