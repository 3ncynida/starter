<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Tentukan rentang tanggal bulan ini
        $startOfMonth = Carbon::now()->startOfMonth()->startOfDay();
        $endOfMonth = Carbon::now()->endOfMonth()->endOfDay();

        // Total penjualan bulan ini
        $totalPenjualan = Penjualan::whereBetween('TanggalPenjualan', [$startOfMonth, $endOfMonth])->count();

        // Total pendapatan bulan ini
        $totalPendapatan = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.PenjualanID', '=', 'penjualan.PenjualanID')
            ->whereBetween('penjualan.TanggalPenjualan', [$startOfMonth, $endOfMonth])
            ->sum('detail_penjualan.Subtotal');

        // Total produk
        $totalProduk = Produk::count();

        // Total pelanggan
        $totalPelanggan = Pelanggan::count();

        // ✅ Ambil 5 penjualan terbaru — langsung dari tabel penjualan (pakai NamaPelanggan string)
        $recentSales = Penjualan::select(
                'PenjualanID',
                'TanggalPenjualan',
                'NamaPelanggan',
                DB::raw('(SELECT COALESCE(SUM(Subtotal), 0) 
                    FROM detail_penjualan 
                    WHERE detail_penjualan.PenjualanID = penjualan.PenjualanID) as total')
            )
            ->latest('TanggalPenjualan')
            ->take(5)
            ->get();

        // Produk terlaris
        $topProducts = DB::table('produk')
            ->leftJoin('detail_penjualan', 'produk.ProdukID', '=', 'detail_penjualan.ProdukID')
            ->select(
                'produk.ProdukID',
                'produk.NamaProduk',
                'produk.Harga',
                'produk.Stok',
                'produk.Gambar',
                DB::raw('COALESCE(SUM(detail_penjualan.Jumlah), 0) as total_sold'),
                DB::raw('COALESCE(SUM(detail_penjualan.Subtotal), 0) as total_revenue')
            )
            ->groupBy('produk.ProdukID', 'produk.NamaProduk', 'produk.Harga', 'produk.Stok', 'produk.Gambar')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // ✅ Pelanggan teratas — dari tabel penjualan (pakai NamaPelanggan string)
        $topCustomers = DB::table('penjualan')
            ->leftJoin('detail_penjualan', 'penjualan.PenjualanID', '=', 'detail_penjualan.PenjualanID')
            ->select(
                'penjualan.NamaPelanggan',
                DB::raw('COUNT(DISTINCT penjualan.PenjualanID) as total_transactions'),
                DB::raw('COALESCE(SUM(detail_penjualan.Subtotal), 0) as total_spent')
            )
            ->whereNotNull('penjualan.NamaPelanggan')
            ->groupBy('penjualan.NamaPelanggan')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPenjualan',
            'totalPendapatan',
            'totalProduk',
            'totalPelanggan',
            'recentSales',
            'topProducts',
            'topCustomers'
        ));
    }
}
