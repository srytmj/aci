<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PemberiProyekController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CoaController;

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
    Route::resource('vendor', VendorController::class);
    Route::resource('coa', CoaController::class);
    // // Transaksi
    // Route::get('/transaksi/kas-masuk', [KasMasukController::class, 'index'])->name('kas-masuk.index');
    // Route::get('/transaksi/kas-keluar', [KasKeluarController::class, 'index'])->name('kas-keluar.index');

});

// Route bawaan Laravel Breeze/Jetstream (Login, Register, dll)
require __DIR__.'/auth.php';