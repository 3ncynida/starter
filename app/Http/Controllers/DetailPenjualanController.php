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
    // Ambil data penjualan beserta relasi pelanggan & detail
    $query = Penjualan::with(['pelanggan', 'detailPenjualan.produk'])
        ->orderBy('created_at', 'desc'); // 🔥 Menampilkan data terbaru di atas

    // 🔍 Fitur pencarian
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            // Cari berdasarkan nama pelanggan
            $q->whereHas('pelanggan', function ($q) use ($search) {
                $q->where('NamaPelanggan', 'like', '%' . $search . '%');
            })
            // atau berdasarkan nama produk
            ->orWhereHas('detailPenjualan.produk', function ($q) use ($search) {
                $q->where('NamaProduk', 'like', '%' . $search . '%');
            });
        });
    }

    // Ambil data terbaru
    $penjualan = $query->paginate(10)->withQueryString();

    // Kirim ke view
    return view('kasir.detail_penjualan.index', compact('penjualan'));
}



public function show($id)
{
    // Ambil pengaturan diskon member
    $diskon = Setting::get('diskon_member', 0);

    // Ambil data penjualan beserta detail dan produk
    $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.produk'])->findOrFail($id);

    // Hitung subtotal (total harga sebelum promo)
    $subtotal = $penjualan->detailPenjualan->sum(function ($detail) {
        return $detail->Jumlah * $detail->produk->Harga;
    });

    // Inisialisasi variabel promo
    $diskonPromo = 0;
    $persenPromo = 0;

    // Cek apakah ada produk yang sedang promosi
    $promosiAktif = $penjualan->detailPenjualan->first(function ($detail) {
        return $detail->produk->Promosi === true;
    });

    if ($promosiAktif) {
        $persenPromo = $promosiAktif->produk->DiskonPersen; // ambil kolom diskon persen produk
        $diskonPromo = ($persenPromo / 100) * $subtotal;
    }

    // Total setelah diskon promo
    $totalSetelahDiskonPromo = $subtotal - $diskonPromo;

    return view('kasir.detail_penjualan.show', [
        'penjualan' => $penjualan,
        'diskon' => $diskon,
        'details' => $penjualan->detailPenjualan,
        'diskonPromo' => $diskonPromo,
        'persenPromo' => $persenPromo,
        'subtotal' => $subtotal,
        'totalSetelahDiskonPromo' => $totalSetelahDiskonPromo,
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
