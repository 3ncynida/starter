<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Setting;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;

class DetailPenjualanController extends Controller
{
public function index(Request $request)
{
    $query = DetailPenjualan::with(['penjualan.pelanggan', 'produk'])
        ->select('detail_penjualan.DetailID', 
                'detail_penjualan.PenjualanID',
                'detail_penjualan.ProdukID',
                'detail_penjualan.JumlahProduk',
                'detail_penjualan.Subtotal',
                'detail_penjualan.created_at',
                'detail_penjualan.updated_at')
        ->join('penjualan', 'detail_penjualan.PenjualanID', '=', 'penjualan.PenjualanID')
        ->groupBy(
            'detail_penjualan.DetailID',
            'detail_penjualan.PenjualanID',
            'detail_penjualan.ProdukID',
            'detail_penjualan.JumlahProduk',
            'detail_penjualan.Subtotal',
            'detail_penjualan.created_at',
            'detail_penjualan.updated_at'
        )
        ->orderBy('penjualan.TanggalPenjualan', 'desc')->latest();

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

    $detail = $query->paginate(10)->withQueryString();

    return view('kasir.detail_penjualan.index', compact('detail'));
}

public function show($id)
{
    $diskon = Setting::get('diskon_member', 0);
    $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.produk'])->findOrFail($id);
    return view('kasir.detail_penjualan.show', [
        'penjualan' => $penjualan,
        'diskon' => $diskon,
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
