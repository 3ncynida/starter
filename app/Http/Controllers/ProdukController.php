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
        // Get all products for search functionality
        $allProducts = Produk::all();
        
        // Get paginated products for display
        $produks = Produk::when(
            request('q'), 
            fn($q, $s) => $q->where('NamaProduk', 'like', "%$s%")
        )
        ->orderBy('NamaProduk')
        ->paginate(10)
        ->withQueryString();

        return view('admin.produk.index', compact('produks', 'allProducts'));
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
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'Satuan' => 'required|string|max:50',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'Satuan' => 'required|string|max:50',
        ]);

        $produk = Produk::findOrFail($id);
        
        // Siapkan data untuk update (tanpa gambar)
        $data = $request->only(['NamaProduk', 'Harga', 'Stok', 'Satuan']);
        
        // Handle gambar hanya jika ada file baru
        if ($request->hasFile('Gambar')) {
            // Simpan ke storage/app/public/produk
            $path = $request->file('Gambar')->store('produk', 'public');
            $data['Gambar'] = $path;
        }
        
        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
