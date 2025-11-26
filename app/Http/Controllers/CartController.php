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
        // Ambil produk berdasarkan ID atau gagal jika tidak ada
        $produk = Produk::findOrFail($id);

        // Ambil data cart dari session, atau buat array kosong jika belum ada
        $cart = session()->get('cart', []);

        // Jumlah permintaan qty dari input (default 1 jika tidak dikirim)
        $requestQty = $request->qty ?? 1;

        // Ambil qty sebelumnya dari cart jika sudah ada
        $currentQty = isset($cart[$id]) ? $cart[$id]['qty'] : 0;

        // Hitung total qty jika produk ditambahkan lagi
        $totalQty = $currentQty + $requestQty;

        $stokBaik = $produk->Stok - ($produk->StokBusuk ?? 0);

        // Validasi stok sebelum menambahkan produk - hanya stok baik yang bisa dijual
        if ($totalQty > $stokBaik) {
            return back()->with('error', 'Stok tidak mencukupi! Stok baik tersedia: '.$stokBaik);
        }

        // Simpan info promosi produk
        $hargaAsli = $produk->Harga;
        $promosi = $produk->Promosi;
        $diskonPersen = $produk->DiskonPersen;

        // Hitung harga setelah diskon jika ada promosi
        $hargaSetelahDiskon = $promosi && $diskonPersen > 0
            ? $hargaAsli - ($hargaAsli * $diskonPersen / 100)
            : $hargaAsli;

        // Simpan ke dalam cart (session)
        $cart[$id] = [
            'nama' => $produk->NamaProduk,
            'harga_asli' => $hargaAsli,
            'harga' => $hargaSetelahDiskon,
            'qty' => $totalQty,
            'stok' => $produk->Stok,
            'stok_busuk' => $produk->StokBusuk ?? 0,
            'stok_baik' => $stokBaik,
            'promosi' => $promosi,
            'diskon_persen' => $diskonPersen,
        ];

        // Masukkan kembali cart ke session
        session()->put('cart', $cart);

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    // Hapus produk dari cart berdasarkan ID
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        // Cek apakah produk ada di cart, jika iya hapus
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function updateQty(Request $request, $id)
    {
        // Validasi input qty agar minimal bernilai 1 dan integer
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        // Ambil cart dari session
        $cart = session()->get('cart', []);

        // Jika produk tidak ada di cart, tampilkan error
        if (! isset($cart[$id])) {
            return back()->with('error', 'Produk tidak ditemukan di keranjang!');
        }

        // Ambil data produk terbaru dari database untuk cek stok
        $produk = Produk::findOrFail($id);
        
        $stokBaik = $produk->Stok - ($produk->StokBusuk ?? 0);
        
        if ($request->qty > $stokBaik) {
            return back()->with('error', 'Stok tidak mencukupi! Stok baik tersedia: '.$stokBaik);
        }

        // Jika stok mencukupi, update qty di cart
        $cart[$id]['qty'] = $request->qty;
        session()->put('cart', $cart);

        return back()->with('success', 'Jumlah produk diperbarui!');
    }

    // Menghapus seluruh cart dari session
    public function clear()
    {
        session()->forget('cart');

        return back();
    }

    public function checkout(Request $request)
    {
        // Ambil cart dari session
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // Ambil ID pelanggan dari session jika ada
        $pelangganId = session('cart_customer', null);

        // Hitung subtotal dari harga per item dikali qty
        $subtotal = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        // Siapkan diskon member (default 0)
        $diskonPersenMember = 0;

        // Jika pelanggan punya membership aktif, ambil persen diskon dari setting
        if ($pelangganId) {
            $pelanggan = \App\Models\Pelanggan::find($pelangganId);
            if ($pelanggan && $pelanggan->checkMembershipStatus()) {
                $diskonPersenMember = \App\Models\Setting::get('diskon_member', 0);
            }
        }
        $diskonNominalMember = ($subtotal * $diskonPersenMember) / 100;

        // Hitung total akhir setelah diskon member
        $grandTotal = $subtotal - $diskonNominalMember;

        // Validasi pembayaran uang tunai
        $uangTunai = (float) ($request->UangTunai ?? 0);
        if ($uangTunai < $grandTotal) {
            return back()->with('error', 'Uang tunai kurang dari total yang harus dibayar!');
        }

        foreach ($cart as $productId => $item) {
            $produk = Produk::find($productId);
            $stokBaik = $produk->Stok - ($produk->StokBusuk ?? 0);
            if ($item['qty'] > $stokBaik) {
                return back()->with('error', 'Stok produk '.$item['nama'].' tidak mencukupi lagi!');
            }
        }

        // Buat record penjualan (header transaksi)
        $penjualan = \App\Models\Penjualan::create([
            'TanggalPenjualan' => now(),
            'PelangganID' => $pelangganId,
            'user_id' => auth()->id(),
            'TotalHarga' => $grandTotal,
            'DiskonMember' => $diskonNominalMember,
            'UangTunai' => $uangTunai,
            'Kembalian' => $uangTunai - $grandTotal,
        ]);

        // Simpan detail transaksi dan kurangi stok produk
        foreach ($cart as $productId => $item) {
            $produk = Produk::find($productId);
            \App\Models\DetailPenjualan::create([
                'PenjualanID' => $penjualan->PenjualanID,
                'ProdukID' => $productId,
                'NamaProduk' => $produk ? $produk->NamaProduk : $item['nama'],
                'JumlahProduk' => $item['qty'],
                'Harga' => $item['harga_asli'],
                'DiskonPromoNominal' => $item['harga_asli'] - $item['harga'],
                'DiskonPromoPersen' => $item['diskon_persen'],
                'Subtotal' => $item['harga'] * $item['qty'],
            ]);

            // Kurangi stok berdasarkan qty yang dibeli (hanya stok baik)
            \App\Models\Produk::findOrFail($productId)->decrement('Stok', $item['qty']);
        }

        // Hapus data cart dan customer dari session
        session()->forget(['cart', 'cart_customer']);

        return redirect()->route('penjualan.index')->with('success', 'Checkout berhasil!');
    }

    // Pilih pelanggan untuk dimasukkan ke sesi cart_customer
    public function setCustomer(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,PelangganID',
        ]);

        // Simpan ID pelanggan ke session
        session(['cart_customer' => $request->pelanggan_id]);

        // Jika request melalui AJAX, kirim response JSON
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pelanggan berhasil dipilih']);
        }

        return back()->with('success', 'Pelanggan berhasil dipilih');
    }
}
