<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $allProducts = Produk::orderBy('NamaProduk')->get(); // For search functionality
        $products = Produk::orderBy('NamaProduk')->paginate(10)->withQueryString();
        $penjualan = Penjualan::with('pelanggan')->get();
        $pelanggan = Pelanggan::orderBy('NamaPelanggan')->get(); // ambil data pelanggan

        return view('kasir.penjualan.index', compact('products', 'penjualan', 'pelanggan', 'allProducts'));
    }

    public function store(Request $request)
{
    // ✅ Validasi input
    $validated = $request->validate([
        'PelangganID' => 'nullable|exists:pelanggan,PelangganID',
        'ProdukID' => 'required|exists:produk,ProdukID',
        'JumlahProduk' => 'required|integer|min:1',
        'UangTunai' => 'required|numeric|min:0',
    ]);

    // ✅ Ambil produk
    $produk = Produk::findOrFail($validated['ProdukID']);

    // ✅ Ambil pelanggan jika ada
    $pelanggan = null;
    $namaPelanggan = 'Umum';
    if (!empty($validated['PelangganID'])) {
        $pelanggan = Pelanggan::findOrFail($validated['PelangganID']);
        $namaPelanggan = $pelanggan->NamaPelanggan;
    }

    // ✅ Harga dasar
    $hargaAsli = $produk->Harga;
    $diskonPromoNominal = 0;
    $diskonPromoPersen = 0;

    // ✅ Cek apakah produk sedang promosi
    $sekarang = now()->toDateString();
    if (
        $produk->Promosi &&
        $produk->DiskonPersen > 0 &&
        $produk->TanggalMulaiPromosi &&
        $produk->TanggalSelesaiPromosi &&
        $sekarang >= $produk->TanggalMulaiPromosi &&
        $sekarang <= $produk->TanggalSelesaiPromosi
    ) {
        $diskonPromoPersen = $produk->DiskonPersen;
        $diskonPromoNominal = ($hargaAsli * $diskonPromoPersen) / 100;
    }

    // ✅ Harga jual setelah promo
    $hargaJual = $hargaAsli - $diskonPromoNominal;

    // ✅ Hitung subtotal (setelah promo)
    $subtotal = $hargaJual * $validated['JumlahProduk'];

    // ✅ Diskon member (kalau ada)
    $diskonPersenMember = 0;
    if (!empty($validated['PelangganID'])) {
        $diskonPersenMember = Setting::get('diskon_member', 0);
    }

    // ✅ Hitung diskon member nominal (dari subtotal setelah promo)
    $diskonMemberNominal = ($subtotal * $diskonPersenMember) / 100;

    // ✅ Total akhir (setelah promo dan member discount)
    $total = $subtotal - $diskonMemberNominal;

    // ✅ Hitung kembalian
    $uangTunai = $validated['UangTunai'];
    $kembalian = $uangTunai - $total;
    if ($kembalian < 0) {
        return back()->withErrors(['UangTunai' => 'Uang tunai tidak cukup untuk membayar total belanja.']);
    }

    // ✅ Simpan penjualan
    $penjualan = Penjualan::create([
        'PelangganID' => $validated['PelangganID'] ?? null,
        'pelanggan_nama' => $namaPelanggan,
        'TanggalPenjualan' => now(),
        'TotalHarga' => $total,
        'Diskon' => $diskonMemberNominal,
        'UangTunai' => $uangTunai,
        'Kembalian' => $kembalian,
    ]);

    // ✅ Simpan detail penjualan dengan diskon promo
    DetailPenjualan::create([
        'PenjualanID' => $penjualan->PenjualanID,
        'produk_nama' => $produk->NamaProduk,
        'produk_harga_asli' => $hargaAsli,
        'produk_harga_jual' => $hargaJual,
        'diskon_promo_persen' => $diskonPromoPersen,
        'diskon_promo_nominal' => $diskonPromoNominal,
        'jumlah' => $validated['JumlahProduk'],
        'subtotal' => $subtotal,
    ]);

    // ✅ Update stok
    $produk->decrement('Stok', $validated['JumlahProduk']);

    return redirect()->route('penjualan.show', $penjualan->PenjualanID)
        ->with('success', 'Penjualan berhasil disimpan.');
}


    public function edit($id)
    {
        $penjualan = \App\Models\Penjualan::findOrFail($id);
        $detail = \App\Models\DetailPenjualan::where('PenjualanID', $id)->first();
        $pelanggan = \App\Models\Pelanggan::all();
        $produk = \App\Models\Produk::all();

        return view('kasir.penjualan.edit', compact('penjualan', 'detail', 'pelanggan', 'produk'));
    }

    // Method untuk menyimpan pelanggan ke session cart
    public function setCartCustomer(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,PelangganID',
        ]);

        session(['cart_customer' => $request->pelanggan_id]);

        return redirect()->back()->with('success', 'Pelanggan berhasil dipilih');
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.produk'])->findOrFail($id);

        return view('kasir.penjualan.show', [
            'penjualan' => $penjualan,
            'details' => $penjualan->detailPenjualan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $penjualan = \App\Models\Penjualan::findOrFail($id);
        $detail = \App\Models\DetailPenjualan::where('PenjualanID', $id)->first();
        $produkLama = \App\Models\Produk::find($detail->ProdukID);
        $produkBaru = \App\Models\Produk::find($request->ProdukID);
        $jumlahLama = $detail->JumlahProduk;

        // Hitung subtotal baru
        $subtotalBaru = $produkBaru ? $produkBaru->Harga * $request->JumlahProduk : 0;

        // Update penjualan
        $penjualan->TanggalPenjualan = $request->TanggalPenjualan;
        $penjualan->PelangganID = $request->PelangganID;
        $penjualan->TotalHarga = $subtotalBaru;
        $penjualan->save();

        // Update detail penjualan
        $detail->ProdukID = $request->ProdukID;
        $detail->JumlahProduk = $request->JumlahProduk;
        $detail->Subtotal = $subtotalBaru;
        $detail->save();

        // Update stok produk
        if ($produkLama && $produkBaru) {
            if ($produkLama->ProdukID == $produkBaru->ProdukID) {
                // Jika produk sama, update stok berdasarkan selisih
                $selisih = $request->JumlahProduk - $jumlahLama;
                $produkBaru->Stok -= $selisih;
                if ($produkBaru->Stok < 0) {
                    $produkBaru->Stok = 0;
                }
                $produkBaru->save();
            } else {
                // Jika produk beda, kembalikan stok lama dan kurangi stok baru
                $produkLama->Stok += $jumlahLama;
                $produkLama->save();
                $produkBaru->Stok -= $request->JumlahProduk;
                if ($produkBaru->Stok < 0) {
                    $produkBaru->Stok = 0;
                }
                $produkBaru->save();
            }
        }

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil diupdate!');
    }
}
