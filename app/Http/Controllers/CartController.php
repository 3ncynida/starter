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

    public function clear()
    {
        session()->forget('cart');
        return back();
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (!$cart) return back()->with('error', 'Keranjang kosong!');

        $penjualan = Penjualan::create([
            'TanggalPenjualan' => now(),
            'PelangganID'      => $request->PelangganID ?? null,
            'TotalHarga'       => 0,
        ]);

        $total = 0;
        foreach ($cart as $id => $item) {
            $subtotal = $item['harga'] * $item['qty'];
            DetailPenjualan::create([
                'PenjualanID'  => $penjualan->PenjualanID,
                'ProdukID'     => $id,
                'JumlahProduk' => $item['qty'],
                'Subtotal'     => $subtotal,
            ]);
            $total += $subtotal;
        }

        $penjualan->update(['TotalHarga' => $total]);
        session()->forget('cart');

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil disimpan!');
    }

    public function setCustomer(Request $request)
{
    if ($request->pelanggan_id) {
        session(['cart_customer' => $request->pelanggan_id]);
    } else {
        session()->forget('cart_customer');
    }

    return back()->with('success', 'Pelanggan berhasil dipilih.');
}

}

