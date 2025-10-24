<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Setting;
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
        $diskonPersen = $produk->DiskonPersen ?? 0;

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

        // Ambil pelanggan (kalau ada)
$pelangganId = session('cart_customer', null);
$pelanggan = Pelanggan::find($pelangganId);
$namaPelanggan = $pelanggan ? $pelanggan->NamaPelanggan : 'Umum';

        $subtotal = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        $totalDiskonPromo = 0;
        foreach ($cart as $item) {
            if ($item['promosi'] && $item['diskon_persen'] > 0) {
                $diskonNominal = ($item['harga_asli'] * $item['diskon_persen'] / 100) * $item['qty'];
                $totalDiskonPromo += $diskonNominal;
            }
        }

        $diskonPersenMember = $pelangganId ? (Setting::where('key', 'diskon_member')->value('value') ?? 0) : 0;
        $diskonNominalMember = ($subtotal * $diskonPersenMember) / 100;

        $grandTotal = $subtotal - $diskonNominalMember;

        // Validasi uang tunai
        $uangTunai = (float) ($request->UangTunai ?? 0);
        if ($uangTunai < $grandTotal) {
            return back()->with('error', 'Uang tunai kurang dari total yang harus dibayar!');
        }

        $kembalian = $uangTunai - $grandTotal;

        $pelangganId = session('cart_customer', null);
$pelanggan = Pelanggan::find($pelangganId);
$namaPelanggan = $pelanggan ? $pelanggan->NamaPelanggan : 'Umum';

        // Simpan penjualan
    $penjualan = Penjualan::create([
    'NamaPelanggan' => $namaPelanggan,
    'TanggalPenjualan' => now(),
    'TotalHarga' => $grandTotal,
     'Diskon' => $diskonNominalMember,
    'UangTunai' => $uangTunai,
    'Kembalian' => $kembalian,
]);

        foreach ($cart as $id => $item) {
            $hargaAsli = $item['harga_asli'];
            $hargaJual = $item['harga'];
            $diskonPromoNominal = ($hargaAsli - $hargaJual) * $item['qty'];

            DetailPenjualan::create([
                'PenjualanID' => $penjualan->PenjualanID,
                'produk_nama' => $item['nama'],
                'produk_harga_asli' => $hargaAsli,
                'produk_harga_jual' => $hargaJual,
                'diskon_promo_persen' => $item['diskon_persen'] ?? 0,
                'diskon_promo_nominal' => $diskonPromoNominal,
                'jumlah' => $item['qty'],
                'subtotal' => $hargaJual * $item['qty'],
            ]);

            Produk::where('ProdukID', $id)->decrement('Stok', $item['qty']);
        }

        // Bersihkan session keranjang
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
