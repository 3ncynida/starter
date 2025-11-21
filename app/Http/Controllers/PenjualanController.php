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
        $pelanggan = Pelanggan::all();

        // Filter hanya yang member aktif menggunakan method checkMembershipStatus()
        $pelangganAktif = $pelanggan->filter(function ($p) {
            return $p->checkMembershipStatus();
        });

        $diskon = \App\Models\Setting::get('diskon_member', 0);

        return view('kasir.penjualan.index', compact('products', 'penjualan', 'pelanggan', 'allProducts', 'pelangganAktif', 'diskon'));
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
