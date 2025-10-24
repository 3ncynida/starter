<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Setting;
use Illuminate\Http\Request;

class DetailPenjualanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data penjualan + pelanggan + detail
        $query = Penjualan::with(['pelanggan', 'detailPenjualan'])
            ->orderBy('created_at', 'desc');

        // 🔍 Fitur pencarian berdasarkan nama pelanggan atau produk
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('pelanggan', function ($sub) use ($search) {
                    $sub->where('NamaPelanggan', 'like', "%{$search}%");
                })
                ->orWhereHas('detailPenjualan', function ($sub) use ($search) {
                    $sub->where('produk_nama', 'like', "%{$search}%");
                });
            });
        }

        $penjualan = $query->paginate(10)->withQueryString();

        return view('kasir.detail_penjualan.index', compact('penjualan'));
    }

    public function show($id)
    {
        // Ambil pengaturan diskon member
        $diskon = Setting::get('diskon_member', 0);

        // Ambil data penjualan dan detail (tanpa relasi produk)
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan'])->findOrFail($id);

        // Hitung subtotal dari kolom langsung
        $subtotal = $penjualan->detailPenjualan->sum(function ($detail) {
            return $detail->jumlah * $detail->produk_harga_jual;
        });

        // Cek promo
        $diskonPromo = 0;
        $persenPromo = 0;

        $promosiAktif = $penjualan->detailPenjualan->first(function ($detail) {
            return ($detail->diskon_promo_persen ?? 0) > 0;
        });

        if ($promosiAktif) {
            $persenPromo = $promosiAktif->diskon_promo_persen;
            $diskonPromo = ($persenPromo / 100) * $subtotal;
        }

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
        // Ambil data detail penjualan
        $detail = DetailPenjualan::with(['penjualan.pelanggan'])->findOrFail($id);

        return view('kasir.detail_penjualan.form', compact('detail'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'produk_nama' => 'required|string',
            'produk_harga_jual' => 'required|numeric|min:0',
        ]);

        $detail = DetailPenjualan::findOrFail($id);

        $subtotal = $request->produk_harga_jual * $request->jumlah;

        $detail->update([
            'produk_nama' => $request->produk_nama,
            'produk_harga_jual' => $request->produk_harga_jual,
            'jumlah' => $request->jumlah,
            'subtotal' => $subtotal,
        ]);

        return redirect()
            ->route('detail-penjualan.index')
            ->with('success', 'Detail penjualan berhasil diperbarui');
    }
}
