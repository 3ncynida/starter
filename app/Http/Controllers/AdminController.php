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
        // Define this month's date range
        $startOfMonth = Carbon::now()->startOfMonth()->startOfDay();
        $endOfMonth = Carbon::now()->endOfMonth()->endOfDay();

        $totalPenjualan = Penjualan::whereBetween('TanggalPenjualan', [$startOfMonth, $endOfMonth])->count();

        $totalPendapatan = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.PenjualanID', '=', 'penjualan.PenjualanID')
            ->whereBetween('penjualan.TanggalPenjualan', [$startOfMonth, $endOfMonth])
            ->sum('detail_penjualan.Subtotal');

        // Get total products
        $totalProduk = Produk::count();

        // Get total customers
        $totalPelanggan = Pelanggan::count();

        // Get recent sales with eager loading
        $recentSales = Penjualan::with('pelanggan')
            ->select('penjualan.*')
            ->addSelect(DB::raw('(SELECT COALESCE(SUM(Subtotal), 0) 
                FROM detail_penjualan 
                WHERE detail_penjualan.PenjualanID = penjualan.PenjualanID) as total'))
            ->latest('TanggalPenjualan')
            ->take(5)
            ->get();

        // Get top selling products with total quantity sold
        $topProducts = DB::table('produk')
            ->leftJoin('detail_penjualan', 'produk.ProdukID', '=', 'detail_penjualan.ProdukID')
            ->select(
                'produk.ProdukID',
                'produk.NamaProduk',
                'produk.Harga',
                'produk.Stok',
                'produk.Gambar',
                'produk.created_at',
                'produk.updated_at',
                DB::raw('COALESCE(SUM(detail_penjualan.JumlahProduk), 0) as total_sold'),
                DB::raw('COALESCE(SUM(detail_penjualan.Subtotal), 0) as total_revenue')
            )
            ->groupBy(
                'produk.ProdukID',
                'produk.NamaProduk',
                'produk.Harga',
                'produk.Stok',
                'produk.Gambar',
                'produk.created_at',
                'produk.updated_at'
            )
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // Get top customers with their total transactions
        $topCustomers = DB::table('pelanggan')
            ->leftJoin('penjualan', 'pelanggan.PelangganID', '=', 'penjualan.PelangganID')
            ->leftJoin('detail_penjualan', 'penjualan.PenjualanID', '=', 'detail_penjualan.PenjualanID')
            ->select(
                'pelanggan.PelangganID',
                'pelanggan.NamaPelanggan',
                'pelanggan.Alamat',
                'pelanggan.NomorTelepon',
                'pelanggan.created_at',
                'pelanggan.updated_at',
                DB::raw('COUNT(DISTINCT penjualan.PenjualanID) as total_transactions'),
                DB::raw('COALESCE(SUM(detail_penjualan.Subtotal), 0) as total_spent')
            )
            ->groupBy(
                'pelanggan.PelangganID',
                'pelanggan.NamaPelanggan',
                'pelanggan.Alamat',
                'pelanggan.NomorTelepon',
                'pelanggan.created_at',
                'pelanggan.updated_at'
            )
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
