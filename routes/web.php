<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Test;

Route::get('test', [Test::class, 'index'])->name('test');


Route::get('/admin', function () {
    return 'Halaman Admin';
})->middleware('role:admin');

Route::get('/kasir', function () {
    return 'Halaman User';
})->middleware('role:kasir');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('users', App\Http\Controllers\UserController::class)->middleware('role:superAdmin');

Route::resource('produk', App\Http\Controllers\ProdukController::class)->middleware('role:admin');
Route::resource('pelanggan', App\Http\Controllers\PelangganController::class);
Route::resource('penjualan', App\Http\Controllers\PenjualanController::class)->middleware('role:kasir');
Route::resource('detail-penjualan', App\Http\Controllers\DetailPenjualanController::class)->middleware('role:kasir');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
