<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Tampilkan daftar produk.
     */
    public function index()
    {
        // Get all products for search functionality
        $allProducts = Produk::all();

        // Get paginated products for display
        $produks = Produk::when(
            request('q'),
            fn ($q, $s) => $q->where('NamaProduk', 'like', "%$s%")
        )
            ->orderBy('NamaProduk')
            ->paginate(10)
            ->withQueryString();

        return view('admin.produk.index', compact('produks', 'allProducts'));
    }

    /**
     * Tampilkan form tambah produk.
     */
    public function create()
    {
        return view('admin.produk.create');
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'NamaProduk' => 'required|string|max:255',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'Satuan' => 'required|string|max:50',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'Promosi' => 'nullable|boolean',
            'DiskonPersen' => 'nullable|numeric|min:0|max:100',
            'TanggalMulaiPromosi' => 'nullable|date',
            'TanggalSelesaiPromosi' => 'nullable|date|after_or_equal:TanggalMulaiPromosi',
        ]);

        // Simpan gambar
        if ($request->hasFile('Gambar')) {
            $validated['Gambar'] = $request->file('Gambar')->store('produk', 'public');
        } else {
            $validated['Gambar'] = 'produk/default.png';
        }

        // Simpan produk
        Produk::create($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Form edit produk.
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        return view('admin.produk.edit', compact('produk'));
    }

    /**
     * Update produk.
     */
    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'NamaProduk' => 'required|string|max:255',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'Promosi' => 'boolean',
            'DiskonPersen' => 'nullable|numeric|min:0|max:100',
            'TanggalMulaiPromosi' => 'nullable|date',
            'TanggalSelesaiPromosi' => 'nullable|date|after_or_equal:TanggalMulaiPromosi',
            'Satuan' => 'required|string|max:50',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        // Handle gambar
        if ($request->hasFile('Gambar')) {
            // Hapus gambar lama jika bukan default
            if ($produk->Gambar && $produk->Gambar !== 'produk/default.webp') {
                Storage::disk('public')->delete($produk->Gambar);
            }
            // Simpan gambar baru
            $validated['Gambar'] = $request->file('Gambar')->store('produk', 'public');
        }
        // Update produk
        $produk->update($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus gambar dari storage jika bukan default
        if ($produk->Gambar && $produk->Gambar !== 'produk/default.webp') {
            Storage::disk('public')->delete($produk->Gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
