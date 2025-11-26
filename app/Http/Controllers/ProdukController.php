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
            fn($q, $s) => $q->where('NamaProduk', 'like', "%$s%")
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
            'NamaProduk' => 'required|string|max:255|unique:produk,NamaProduk',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'Promosi' => 'nullable|boolean',
            'DiskonPersen' => 'nullable|numeric|min:0|max:100',
            'TanggalMulaiPromosi' => 'nullable|date',
            'TanggalSelesaiPromosi' => 'nullable|date|after_or_equal:TanggalMulaiPromosi',
        ], [
            // Pesan error kustom dalam bahasa Indonesia
            'NamaProduk.unique' => 'Nama produk sudah digunakan. Silakan gunakan nama yang berbeda.',
            'NamaProduk.required' => 'Nama produk wajib diisi.',
            'NamaProduk.max' => 'Nama produk maksimal 255 karakter.',
            'Harga.required' => 'Harga wajib diisi.',
            'Harga.numeric' => 'Harga harus berupa angka.',
            'Harga.min' => 'Harga tidak boleh kurang dari 0.',
            'Stok.required' => 'Stok wajib diisi.',
            'Stok.integer' => 'Stok harus berupa bilangan bulat.',
            'Stok.min' => 'Stok tidak boleh kurang dari 0.',
            'Gambar.image' => 'File harus berupa gambar.',
            'Gambar.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'Gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'DiskonPersen.numeric' => 'Diskon harus berupa angka.',
            'DiskonPersen.min' => 'Diskon tidak boleh kurang dari 0.',
            'DiskonPersen.max' => 'Diskon tidak boleh lebih dari 100.',
            'TanggalMulaiPromosi.date' => 'Tanggal mulai promosi harus berupa tanggal yang valid.',
            'TanggalSelesaiPromosi.date' => 'Tanggal selesai promosi harus berupa tanggal yang valid.',
            'TanggalSelesaiPromosi.after_or_equal' => 'Tanggal selesai promosi harus setelah atau sama dengan tanggal mulai.',
        ]);

        // Validasi tambahan untuk mencegah nama produk yang sama (case insensitive)
        $namaProduk = $validated['NamaProduk'];
        $existingProduct = Produk::whereRaw('LOWER(NamaProduk) = ?', [strtolower($namaProduk)])->first();
        
        if ($existingProduct) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['NamaProduk' => 'Nama produk sudah digunakan. Silakan gunakan nama yang berbeda.']);
        }

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
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'NamaProduk' => 'required|string|max:255|unique:produk,NamaProduk,' . $produk->ProdukID . ',ProdukID',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'Promosi' => 'boolean',
            'DiskonPersen' => 'nullable|numeric|min:0|max:100',
            'TanggalMulaiPromosi' => 'nullable|date',
            'TanggalSelesaiPromosi' => 'nullable|date|after_or_equal:TanggalMulaiPromosi',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            // Pesan error kustom dalam bahasa Indonesia
            'NamaProduk.unique' => 'Nama produk sudah digunakan. Silakan gunakan nama yang berbeda.',
            'NamaProduk.required' => 'Nama produk wajib diisi.',
            'NamaProduk.max' => 'Nama produk maksimal 255 karakter.',
            'Harga.required' => 'Harga wajib diisi.',
            'Harga.numeric' => 'Harga harus berupa angka.',
            'Harga.min' => 'Harga tidak boleh kurang dari 0.',
            'Stok.required' => 'Stok wajib diisi.',
            'Stok.integer' => 'Stok harus berupa bilangan bulat.',
            'Stok.min' => 'Stok tidak boleh kurang dari 0.',
            'Gambar.image' => 'File harus berupa gambar.',
            'Gambar.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'Gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'DiskonPersen.numeric' => 'Diskon harus berupa angka.',
            'DiskonPersen.min' => 'Diskon tidak boleh kurang dari 0.',
            'DiskonPersen.max' => 'Diskon tidak boleh lebih dari 100.',
            'TanggalMulaiPromosi.date' => 'Tanggal mulai promosi harus berupa tanggal yang valid.',
            'TanggalSelesaiPromosi.date' => 'Tanggal selesai promosi harus berupa tanggal yang valid.',
            'TanggalSelesaiPromosi.after_or_equal' => 'Tanggal selesai promosi harus setelah atau sama dengan tanggal mulai.',
        ]);

        // Validasi tambahan untuk mencegah nama produk yang sama (case insensitive) saat update
        $namaProduk = $validated['NamaProduk'];
        $existingProduct = Produk::whereRaw('LOWER(NamaProduk) = ?', [strtolower($namaProduk)])
            ->where('ProdukID', '!=', $produk->ProdukID)
            ->first();
        
        if ($existingProduct) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['NamaProduk' => 'Nama produk sudah digunakan. Silakan gunakan nama yang berbeda.']);
        }

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