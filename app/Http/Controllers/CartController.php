<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $cart = session()->get('cart', []);
        
        // Ambil quantity dari request, default 1 jika tidak ada
        $requestQty = $request->qty ?? 1;
        
        // Cek stok yang tersedia
        $currentQty = isset($cart[$id]) ? $cart[$id]['qty'] : 0;
        $totalQty = $currentQty + $requestQty;
        
        // Validasi stok
        if ($totalQty > $produk->Stok) {
            return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $produk->Stok);
        }

        if (isset($cart[$id])) {
            $cart[$id]['qty'] = $totalQty;
        } else {
            $cart[$id] = [
                'nama'  => $produk->NamaProduk,
                'harga' => $produk->Harga,
                'qty'   => $requestQty,
                'stok'  => $produk->Stok, // Tambah info stok
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Produk ditambahkan!');
    }

    // Tambahkan method remove() ini
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function updateQty(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        
        if (!isset($cart[$id])) {
            return back()->with('error', 'Produk tidak ditemukan di keranjang!');
        }

        // Cek stok produk
        $produk = Produk::findOrFail($id);
        if ($request->qty > $produk->Stok) {
            return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $produk->Stok);
        }

        // Update qty
        $cart[$id]['qty'] = $request->qty;
        session()->put('cart', $cart);

        return back()->with('success', 'Jumlah produk diperbarui!');
    }

    public function clear()
    {
        session()->forget('cart');
        return back();
    }

public function checkout(Request $request)
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->back()->with('error', 'Keranjang kosong!');
    }

    // Ambil pelanggan dari session (kalau tidak ada = NULL / Non Member)
    $pelangganId = session('cart_customer', null);

    // Hitung subtotal
    $subtotal = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

    // Hitung diskon jika member
    $diskonPersen = 0;
    if ($pelangganId) {
        $diskonPersen = \App\Models\Setting::get('diskon_member', 0);
    }
    
    // Hitung nominal diskon dan total setelah diskon
    $diskonNominal = ($subtotal * $diskonPersen) / 100;
    $total = $subtotal - $diskonNominal;

    // Buat transaksi penjualan
    $penjualan = Penjualan::create([
        'TanggalPenjualan' => now(),
        'PelangganID' => $pelangganId,
        'TotalHarga' => $total,
        'Diskon' => $diskonNominal,
    ]);

    // Masukkan detail penjualan dan update stok
    foreach ($cart as $productId => $item) {
        // Create detail penjualan
        DetailPenjualan::create([
            'PenjualanID' => $penjualan->PenjualanID,
            'ProdukID' => $productId,
            'JumlahProduk' => $item['qty'],
            'Subtotal' => $item['harga'] * $item['qty'],
        ]);

        // Update stok produk
        $produk = Produk::findOrFail($productId);
        $produk->decrement('Stok', $item['qty']);
    }

    // Kosongkan cart dan pelanggan di session
    session()->forget(['cart', 'cart_customer']);

    return redirect()->route('penjualan.index')->with('success', 'Checkout berhasil!');
}

public function setCustomer(Request $request)
{
    $request->validate([
        'pelanggan_id' => 'nullable|exists:pelanggan,PelangganID',
    ]);

    // Simpan ID pelanggan di session
    session(['cart_customer' => $request->pelanggan_id]);

    return back()->with('success', 'Pelanggan berhasil dipilih');
}

}