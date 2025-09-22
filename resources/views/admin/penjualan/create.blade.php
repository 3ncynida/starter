<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form action="{{ route('penjualan.store') }}" method="POST">
                    @csrf

                    <!-- Tanggal Penjualan -->
                    <div class="mb-4">
                        <label for="TanggalPenjualan" class="block text-sm font-medium text-gray-700">Tanggal Penjualan</label>
                        <input type="date" name="TanggalPenjualan" id="TanggalPenjualan" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>

                    <!-- Pelanggan -->
                    <div class="mb-4">
                        <label for="PelangganID" class="block text-sm font-medium text-gray-700">Pelanggan</label>
                        <select name="PelangganID" id="PelangganID" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->PelangganID }}">{{ $p->NamaPelanggan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Produk -->
                    <div class="mb-4">
                        <label for="ProdukID" class="block text-sm font-medium text-gray-700">Produk</label>
                        <select name="ProdukID" id="ProdukID" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produk as $p)
                                <option value="{{ $p->ProdukID }}">{{ $p->NamaProduk }} (Stok: {{ $p->Stok }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jumlah Produk -->
                    <div class="mb-4">
                        <label for="JumlahProduk" class="block text-sm font-medium text-gray-700">Jumlah Produk</label>
                        <input type="number" name="JumlahProduk" id="JumlahProduk" min="1"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('penjualan.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Kembali</a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
