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

    // Fix total pendapatan by summing subtotal from detail_penjualan
    $totalPendapatan = DB::table('detail_penjualan')
        ->join('penjualan', 'detail_penjualan.PenjualanID', '=', 'penjualan.PenjualanID')
        ->sum('detail_penjualan.Subtotal');

    // Get total products
    $totalProduk = Produk::count();

    // Get total customers
    $totalPelanggan = Pelanggan::count();

    // Get recent sales with eager loading
    $recentSales = Penjualan::with('pelanggan')
        ->latest('TanggalPenjualan')
        ->take(5)
        ->get();

    // Get top selling products with total quantity sold
    $topProducts = DB::table('produk')
        ->leftJoin('detail_penjualan', 'produk.ProdukID', '=', 'detail_penjualan.ProdukID')
        ->select(
            'produk.*',
            DB::raw('COALESCE(SUM(detail_penjualan.JumlahProduk), 0) as total_sold'),
            DB::raw('COALESCE(SUM(detail_penjualan.Subtotal), 0) as total_revenue')
        )
        ->groupBy('produk.ProdukID')
        ->orderByDesc('total_revenue')
        ->take(5)
        ->get();

    // Get top customers with their total transactions
    $topCustomers = DB::table('pelanggan')
        ->leftJoin('penjualan', 'pelanggan.PelangganID', '=', 'penjualan.PelangganID')
        ->select(
            'pelanggan.*',
            DB::raw('COUNT(penjualan.PenjualanID) as total_transactions'),
            DB::raw('COALESCE(SUM(penjualan.TotalHarga), 0) as total_spent')
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