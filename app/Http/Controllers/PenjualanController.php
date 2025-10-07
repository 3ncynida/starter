<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
public function index()
{
    $allProducts = Produk::orderBy('NamaProduk')->get(); // For search functionality
    $products  = Produk::orderBy('NamaProduk')->paginate(8)->withQueryString();
    $penjualan = Penjualan::with('pelanggan')->get();
    $pelanggan = Pelanggan::orderBy('NamaPelanggan')->get(); // ambil data pelanggan

    return view('kasir.penjualan.index', compact('products', 'penjualan', 'pelanggan', 'allProducts'));
}

public function store(Request $request)
{
    // ✅ Validasi input
    $validated = $request->validate([
        'PelangganID'   => 'nullable|exists:pelanggan,PelangganID', // boleh kosong (non-member)
        'ProdukID'      => 'required|exists:produk,ProdukID',
        'JumlahProduk'  => 'required|integer|min:1',
    ]);

    // ✅ Ambil produk
    $produk = Produk::findOrFail($validated['ProdukID']);

    // ✅ Hitung subtotal
    $subtotal = $produk->Harga * $validated['JumlahProduk'];

    // ✅ Ambil diskon dari settings
    $diskonPersen = 0;
    if (!empty($validated['PelangganID'])) {
        $diskonPersen = Setting::get('diskon_member', 0); // misal default 0
    }

    // ✅ Hitung total & diskon nominal
    $diskonNominal = ($subtotal * $diskonPersen) / 100;
    $total = $subtotal - $diskonNominal;

    // ✅ Simpan penjualan
    $penjualan = Penjualan::create([
        'PelangganID'       => $validated['PelangganID'] ?? null,
        'TanggalPenjualan'  => now(),
        'TotalHarga'        => $total,
        'Diskon'            => $diskonNominal, // pastikan field ada di tabel penjualan
    ]);

    // ✅ Simpan detail penjualan
    DetailPenjualan::create([
        'PenjualanID'  => $penjualan->PenjualanID,
        'ProdukID'     => $produk->ProdukID,
        'JumlahProduk' => $validated['JumlahProduk'],
        'Subtotal'     => $subtotal,
    ]);

    // ✅ Update stok
    $produk->decrement('Stok', $validated['JumlahProduk']);

    return redirect()->route('penjualan.index')
        ->with('success', 'Penjualan berhasil disimpan.');
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

    // Method untuk menyimpan pelanggan ke session cart
    public function setCartCustomer(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,PelangganID'
        ]);

        session(['cart_customer' => $request->pelanggan_id]);
        
        return redirect()->back()->with('success', 'Pelanggan berhasil dipilih');
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.produk'])->findOrFail($id);
        return view('kasir.penjualan.show', [
            'penjualan' => $penjualan,
            'details' => $penjualan->detailPenjualan
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
