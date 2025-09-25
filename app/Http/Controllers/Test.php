<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class Test extends Controller
{
    public function index()
{
    $products = Produk::all();

    return view('test', compact('products'));
}

}
