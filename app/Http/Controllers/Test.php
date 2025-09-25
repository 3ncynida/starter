<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class Test extends Controller
{
    public function index()
{
    $products = Produk::all();

    dd('products');

    return view('test', compact('products'));
}

}
