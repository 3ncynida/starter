<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produks = Produk::all();

        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
        {
            // ✅ Validasi input
            $validated = $request->validate([
                'NamaProduk' => 'required|string|max:255',
                'Harga'      => 'required|numeric|min:0',
                'Stok'       => 'required|integer|min:0',
                'Satuan'     => 'required|string|max:50',
                'Gambar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            // ✅ Simpan gambar kalau ada
            if ($request->hasFile('Gambar')) {
                // Simpan ke storage/app/public/produk
                $path = $request->file('Gambar')->store('produk', 'public');
                $validated['Gambar'] = $path;
            } else {
                // Kalau tidak upload gambar → default
                $validated['Gambar'] = 'produk/default.webp';
            }

            // ✅ Simpan produk ke database
            Produk::create($validated);

            return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
        }



    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        return view('admin.produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NamaProduk' => 'required|string|max:255',
            'Harga' => 'required|numeric',
            'Stok' => 'required|integer',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->all());

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
