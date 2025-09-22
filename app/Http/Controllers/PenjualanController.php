<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::with('pelanggan')->get();
        return view('admin.penjualan.index', compact('penjualan'));
    }
    public function store(Request $request)
    {
        // Ambil harga produk dan hitung subtotal
        $produk = \App\Models\Produk::find($request->ProdukID);
        $subtotal = $produk ? $produk->Harga * $request->JumlahProduk : 0;

        // Simpan data penjualan
        $penjualan = Penjualan::create([
            'TanggalPenjualan' => $request->TanggalPenjualan,
            'PelangganID' => $request->PelangganID,
            'TotalHarga' => $subtotal
        ]);


        // Simpan detail penjualan
        \App\Models\DetailPenjualan::create([
            'PenjualanID' => $penjualan->PenjualanID,
            'ProdukID' => $request->ProdukID,
            'JumlahProduk' => $request->JumlahProduk,
            'Subtotal' => $subtotal
        ]);

        // Kurangi stok produk
        if ($produk) {
            $produk->Stok = max(0, $produk->Stok - $request->JumlahProduk);
            $produk->save();
        }

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil disimpan!');
    }
    public function create()
    {
        $pelanggan = \App\Models\Pelanggan::all();
        $produk = \App\Models\Produk::all();
        return view('admin.penjualan.create', compact('pelanggan', 'produk'));
    }
    public function edit($id)
    {
        $penjualan = \App\Models\Penjualan::findOrFail($id);
        $detail = \App\Models\DetailPenjualan::where('PenjualanID', $id)->first();
        $pelanggan = \App\Models\Pelanggan::all();
        $produk = \App\Models\Produk::all();
        return view('admin.penjualan.edit', compact('penjualan', 'detail', 'pelanggan', 'produk'));
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
                if ($produkBaru->Stok < 0) $produkBaru->Stok = 0;
                $produkBaru->save();
            } else {
                // Jika produk beda, kembalikan stok lama dan kurangi stok baru
                $produkLama->Stok += $jumlahLama;
                $produkLama->save();
                $produkBaru->Stok -= $request->JumlahProduk;
                if ($produkBaru->Stok < 0) $produkBaru->Stok = 0;
                $produkBaru->save();
            }
        }

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil diupdate!');
    }
}