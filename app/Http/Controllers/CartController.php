<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $cart = session()->get('cart', []);

        $requestQty = $request->qty ?? 1;
        $currentQty = isset($cart[$id]) ? $cart[$id]['qty'] : 0;
        $totalQty = $currentQty + $requestQty;

        if ($totalQty > $produk->Stok) {
            return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: '.$produk->Stok);
        }

        // Simpan info promosi
        $hargaAsli = $produk->Harga;
        $promosi = $produk->Promosi;
        $diskonPersen = $produk->DiskonPersen;

        // Hitung harga setelah diskon
        $hargaSetelahDiskon = $promosi && $diskonPersen > 0
            ? $hargaAsli - ($hargaAsli * $diskonPersen / 100)
            : $hargaAsli;

        $cart[$id] = [
            'nama' => $produk->NamaProduk,
            'harga_asli' => $hargaAsli,
            'harga' => $hargaSetelahDiskon,
            'qty' => $totalQty,
            'stok' => $produk->Stok,
            'promosi' => $promosi,
            'diskon_persen' => $diskonPersen,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
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
            'qty' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (! isset($cart[$id])) {
            return back()->with('error', 'Produk tidak ditemukan di keranjang!');
        }

        // Cek stok produk
        $produk = Produk::findOrFail($id);
        if ($request->qty > $produk->Stok) {
            return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: '.$produk->Stok);
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

    $pelangganId = session('cart_customer', null);

    // Hitung subtotal dari cart (harga sudah termasuk diskon promo)
    $subtotal = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

    // Hitung diskon member (jika ada)
    $diskonPersenMember = $pelangganId ? \App\Models\Setting::get('diskon_member', 0) : 0;
    $diskonNominalMember = ($subtotal * $diskonPersenMember) / 100;

    // Total akhir
    $grandTotal = $subtotal - $diskonNominalMember;

    // Validasi uang tunai
    $uangTunai = (float) ($request->UangTunai ?? 0);
    if ($uangTunai < $grandTotal) {
        return back()->with('error', 'Uang tunai kurang dari total yang harus dibayar!');
    }

    // Buat penjualan dulu (sebelum detail)
    $penjualan = \App\Models\Penjualan::create([
        'TanggalPenjualan' => now(),
        'PelangganID' => $pelangganId,
        'TotalHarga' => $grandTotal,
        'Diskon' => $diskonNominalMember,
        'UangTunai' => $uangTunai,
        'Kembalian' => $uangTunai - $grandTotal,
    ]);

    // Simpan detail penjualan & update stok
    foreach ($cart as $productId => $item) {
        \App\Models\DetailPenjualan::create([
            'PenjualanID' => $penjualan->PenjualanID,
            'ProdukID' => $productId,
            'JumlahProduk' => $item['qty'],
            'Harga' => $item['harga_asli'],
            'DiskonPromoNominal' => $item['harga_asli'] - $item['harga'],
            'DiskonPromoPersen' => $item['diskon_persen'],
            'Subtotal' => $item['harga'] * $item['qty'],
        ]);

        \App\Models\Produk::findOrFail($productId)->decrement('Stok', $item['qty']);
    }

    // Bersihkan session
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
