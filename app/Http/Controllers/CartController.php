<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;

class CartController extends Controller
{
    public function add($id)
    {
        $produk = Produk::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                'nama'  => $produk->NamaProduk,
                'harga' => $produk->Harga,
                'qty'   => 1,
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

    // Buat transaksi penjualan
    $penjualan = Penjualan::create([
        'TanggalPenjualan' => now(),
        'PelangganID' => $pelangganId, // ✅ ambil dari session
        'Total' => collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']),
    ]);

    // Masukkan detail penjualan
    foreach ($cart as $productId => $item) {
        DetailPenjualan::create([
            'PenjualanID' => $penjualan->PenjualanID,
            'ProdukID' => $productId,
            'JumlahProduk' => $item['qty'],
            'Subtotal' => $item['harga'] * $item['qty'],
        ]);
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