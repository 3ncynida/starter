<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PelangganController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
})->name("welcome");

Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

Route::get("/admin", function () {
    return "Halaman Admin";
})->middleware("role:admin");

Route::get("/kasir", function () {
    return "Halaman User";
})->middleware("role:kasir");

Route::post("/cart/set-customer", [CartController::class, "setCustomer"])->name("cart.set-customer");
Route::resource("users", App\Http\Controllers\UserController::class)->middleware("role:superAdmin");
Route::put("/setting", [App\Http\Controllers\SettingController::class, "update"])->name("settings.update")->middleware("role:admin");

// Admin routes
Route::prefix("admin")->middleware(["web", "auth", "role:admin"])->group(function () {
    Route::get("/produk", [App\Http\Controllers\ProdukController::class, "index"])->name("produk.index");
    Route::get("/produk/create", [App\Http\Controllers\ProdukController::class, "create"])->name("produk.create");
    Route::post("/produk", [App\Http\Controllers\ProdukController::class, "store"])->name("produk.store");
    Route::get("/produk/{produk}", [App\Http\Controllers\ProdukController::class, "show"])->name("produk.show");
    Route::get("/produk/{produk}/edit", [App\Http\Controllers\ProdukController::class, "edit"])->name("produk.edit");
    Route::put("/produk/{produk}", [App\Http\Controllers\ProdukController::class, "update"])->name("produk.update");
    Route::delete("/produk/{produk}", [App\Http\Controllers\ProdukController::class, "destroy"])->name("produk.destroy");
});

// Pelanggan routes
Route::resource("pelanggan", App\Http\Controllers\PelangganController::class);
Route::post("/pelanggan/{id}/activate-member", [PelangganController::class, "activateMember"])->name("pelanggan.activate-member");
Route::post("/pelanggan/{id}/deactivate-member", [PelangganController::class, "deactivateMember"])->name("pelanggan.deactivate-member");

Route::resource("penjualan", App\Http\Controllers\PenjualanController::class)->middleware("role:kasir");
Route::resource("detail-penjualan", App\Http\Controllers\DetailPenjualanController::class);

Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name("profile.edit");
    Route::patch("/profile", [ProfileController::class, "update"])->name("profile.update");
    Route::delete("/profile", [ProfileController::class, "destroy"])->name("profile.destroy");
});

Route::prefix("cart")->group(function () {
    Route::post("/add/{id}", [CartController::class, "add"])->name("cart.add");
    Route::post("/update-qty/{id}", [CartController::class, "updateQty"])->name("cart.updateQty");
    Route::delete("/remove/{id}", [CartController::class, "remove"])->name("cart.remove");
    Route::post("/clear", [CartController::class, "clear"])->name("cart.clear");
    Route::post("/checkout", [CartController::class, "checkout"])->name("cart.checkout");
});

// Route untuk gambar default produk
Route::get('/produk/default.png', function() {
    return response()->file(public_path('storage/produk/default.png'));
});

require __DIR__.'/auth.php';
