<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $allProducts = Produk::orderBy('NamaProduk')->get(); // For search functionality
        $products = Produk::orderBy('NamaProduk')->paginate(10)->withQueryString();
        $penjualan = Penjualan::with('pelanggan')->get();
        $pelanggan = Pelanggan::all();

        // Filter hanya yang member aktif menggunakan method checkMembershipStatus()
        $pelangganAktif = $pelanggan->filter(function ($p) {
            return $p->checkMembershipStatus();
        });

        $diskon = \App\Models\Setting::get('diskon_member', 0);

        return view('kasir.penjualan.index', compact('products', 'penjualan', 'pelanggan', 'allProducts', 'pelangganAktif', 'diskon'));
    }
}
