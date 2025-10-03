<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

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

Route::post('/cart/set-customer', [CartController::class, 'setCustomer'])->name('cart.set-customer');

Route::resource('users', App\Http\Controllers\UserController::class)->middleware('role:superAdmin');

Route::put('/setting', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update')->middleware('role:admin');

Route::resource('produk', App\Http\Controllers\ProdukController::class)->middleware('role:admin');
Route::resource('pelanggan', App\Http\Controllers\PelangganController::class);
Route::resource('penjualan', App\Http\Controllers\PenjualanController::class)->middleware('role:kasir');
Route::resource('detail-penjualan', App\Http\Controllers\DetailPenjualanController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('cart')->group(function () {
    Route::post('/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/set-customer', [CartController::class, 'setCustomer'])->name('cart.set-customer');
});

Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

require __DIR__.'/auth.php';
