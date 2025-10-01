<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get total sales count
        $totalPenjualan = Penjualan::count();

        // Get total revenue
        $totalPendapatan = Penjualan::sum('TotalHarga');

        // Get total products
        $totalProduk = Produk::count();

        // Get total customers
        $totalPelanggan = Pelanggan::count();

        // Get recent sales
        $recentSales = Penjualan::with('pelanggan')
            ->latest('TanggalPenjualan')
            ->take(5)
            ->get();

        // Get top selling products
        $topProducts = DB::table('produk')
            ->leftJoin('detail_penjualan', 'produk.ProdukID', '=', 'detail_penjualan.ProdukID')
            ->select('produk.*', DB::raw('COUNT(detail_penjualan.ProdukID) as total_sold'))
            ->groupBy('produk.ProdukID')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Get top customers
        $topCustomers = DB::table('pelanggan')
            ->leftJoin('penjualan', 'pelanggan.PelangganID', '=', 'penjualan.PelangganID')
            ->select(
                'pelanggan.*',
                DB::raw('COUNT(penjualan.PenjualanID) as total_transactions'),
                DB::raw('SUM(penjualan.TotalHarga) as total_spent')
            )
            ->groupBy('pelanggan.PelangganID')
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