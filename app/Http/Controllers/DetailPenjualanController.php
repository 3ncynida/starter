<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DetailPenjualanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data penjualan beserta relasi pelanggan & detail
        $query = Penjualan::with(['pelanggan', 'detailPenjualan'])
            ->orderBy('created_at', 'desc'); // 🔥 Menampilkan data terbaru di atas

        // 🔍 Fitur pencarian
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // Cari berdasarkan nama pelanggan
                $q->whereHas('pelanggan', function ($q) use ($search) {
                    $q->where('NamaPelanggan', 'like', '%' . $search . '%');
                })
                    // atau berdasarkan nama produk
                    ->orWhereHas('detailPenjualan', function ($q) use ($search) {
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

        // Ambil data penjualan beserta detail
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan', 'user'])->findOrFail($id);

        // Hitung subtotal
        $subtotal = $penjualan->detailPenjualan->sum('Subtotal');

        // Inisialisasi variabel promo
        $diskonPromo = 0;
        $persenPromo = 0;

        // Hitung total diskon promo
        $diskonPromo = $penjualan->detailPenjualan->sum(function ($detail) {
            return ($detail->DiskonPromoNominal ?? 0) * $detail->JumlahProduk;
        });

        // Hitung rata-rata persen diskon
        $persenPromo = $penjualan->detailPenjualan->avg('DiskonPromoPersen') ?? 0;

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

    public function cetakPDFPerBulan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        $bulan = $request->bulan;
        $tanggalAwal = Carbon::parse($bulan)->startOfMonth();
        $tanggalAkhir = Carbon::parse($bulan)->endOfMonth();

        // Ambil data penjualan
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan', 'user'])
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->orWhereBetween('TanggalPenjualan', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik
        $totalPenjualan = $penjualan->count();
        $totalPendapatan = $penjualan->sum('TotalHarga');
        $totalProdukTerjual = 0;
        $produkTerjual = [];

        foreach ($penjualan as $pj) {
            foreach ($pj->detailPenjualan as $detail) {
                $totalProdukTerjual += $detail->JumlahProduk;
                
                if (!isset($produkTerjual[$detail->ProdukID])) {
                    $produkTerjual[$detail->ProdukID] = [
                        'nama' => $detail->NamaProduk,
                        'jumlah' => 0,
                        'subtotal' => 0
                    ];
                }
                $produkTerjual[$detail->ProdukID]['jumlah'] += $detail->JumlahProduk;
                $produkTerjual[$detail->ProdukID]['subtotal'] += $detail->Subtotal;
            }
        }

        // Urutkan produk terlaris
        usort($produkTerjual, function($a, $b) {
            return $b['jumlah'] - $a['jumlah'];
        });

        $data = [
            'penjualan' => $penjualan,
            'bulan' => $bulan,
            'namaBulan' => Carbon::parse($bulan)->translatedFormat('F Y'),
            'tanggalAwal' => $tanggalAwal->format('d/m/Y'),
            'tanggalAkhir' => $tanggalAkhir->format('d/m/Y'),
            'totalPenjualan' => $totalPenjualan,
            'totalPendapatan' => $totalPendapatan,
            'totalProdukTerjual' => $totalProdukTerjual,
            'produkTerjual' => array_slice($produkTerjual, 0, 10),
            'title' => 'Laporan Penjualan Bulanan'
        ];

        // ✅ Gunakan facade Pdf (bukan PDF)
        $pdf = Pdf::loadView('kasir.detail_penjualan.cetak_pdf_bulanan', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . $bulan . '.pdf');
    }

}
