<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;

class DetailPenjualanController extends Controller
{
public function index(Request $request)
{
    $query = DetailPenjualan::with(['penjualan.pelanggan', 'produk'])
        ->orderBy('created_at', 'desc');

    // Handle search
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('penjualan.pelanggan', function($q) use ($search) {
                $q->where('NamaPelanggan', 'like', '%' . $search . '%');
            })
            ->orWhereHas('produk', function($q) use ($search) {
                $q->where('NamaProduk', 'like', '%' . $search . '%');
            });
        });
    }

    $detail = $query->paginate(10)->withQueryString(); // Add withQueryString to maintain search parameter in pagination links

    return view('kasir.detail_penjualan.index', compact('detail'));
}

public function show($id)
{
    $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.produk'])->findOrFail($id);
    return view('kasir.detail_penjualan.show', [
        'penjualan' => $penjualan,
        'details' => $penjualan->detailPenjualan
    ]);
}

    public function edit($id)
    {
        $detail = DetailPenjualan::with(['penjualan.pelanggan', 'produk'])->findOrFail($id);
        $produk = Produk::all();

        return view('kasir.detail_penjualan.form', compact('detail', 'produk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'JumlahProduk' => 'required|integer|min:1',
            'ProdukID' => 'required|exists:produk,ProdukID',
        ]);

        $detail = DetailPenjualan::findOrFail($id);
        $produk = Produk::findOrFail($request->ProdukID);

        // Hitung subtotal baru
        $subtotal = $produk->Harga * $request->JumlahProduk;

        $detail->update([
            'ProdukID' => $request->ProdukID,
            'JumlahProduk' => $request->JumlahProduk,
            'Subtotal' => $subtotal,
        ]);

        return redirect()
            ->route('detail-penjualan.index')
            ->with('success', 'Detail penjualan berhasil diperbarui');
    }
}
