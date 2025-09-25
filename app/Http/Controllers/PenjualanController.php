<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::with('pelanggan')->get();
        $products = Produk::all();

        return view('kasir.penjualan.index', compact('penjualan', 'products'));
    }

public function store(Request $request)
{
    $request->validate([
        'TanggalPenjualan' => 'required|date',
        'TotalHarga' => 'required|numeric|min:0',
        'PelangganID' => 'nullable|exists:pelanggan,PelangganID'
    ]);

    $diskon = 0;

    // Kalau ada pelanggan, berarti member → diskon 10%
    if ($request->PelangganID) {
        $diskon = 0.10 * $request->TotalHarga;
    }

    $penjualan = \App\Models\Penjualan::create([
        'TanggalPenjualan' => $request->TanggalPenjualan,
        'PelangganID' => $request->PelangganID,
        'TotalHarga' => $request->TotalHarga - $diskon,
        'Diskon' => $diskon,
    ]);

    return redirect()->route('penjualan.index')
        ->with('success', 'Penjualan berhasil disimpan!');
}


    public function create()
    {
        $pelanggan = \App\Models\Pelanggan::all();
        $produk = \App\Models\Produk::all();

        return view('kasir.penjualan.create', compact('pelanggan', 'produk'));
    }

    public function edit($id)
    {
        $penjualan = \App\Models\Penjualan::findOrFail($id);
        $detail = \App\Models\DetailPenjualan::where('PenjualanID', $id)->first();
        $pelanggan = \App\Models\Pelanggan::all();
        $produk = \App\Models\Produk::all();

        return view('kasir.penjualan.edit', compact('penjualan', 'detail', 'pelanggan', 'produk'));
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
