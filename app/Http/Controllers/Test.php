<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class Test extends Controller
{
    public function index()
    {
        $products = Produk::all();

        return view('test', compact('products'));
    }
}
