<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                <form action="{{ route('penjualan.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="TanggalPenjualan" class="block text-sm font-medium text-gray-700">Tanggal Penjualan</label>
                            <input type="date" name="TanggalPenjualan" id="TanggalPenjualan"
                                   value="{{ date('Y-m-d') }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" required>
                        </div>

                        <div>
                            <label for="PelangganID" class="block text-sm font-medium text-gray-700">Pelanggan (Member)</label>
                            <select name="PelangganID" id="PelangganID"
                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black">
                                <option value="">-- Non Member --</option>
                                @foreach($pelanggan as $p)
                                    <option value="{{ $p->PelangganID }}">{{ $p->NamaPelanggan }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika pelanggan bukan member.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="ProdukID" class="block text-sm font-medium text-gray-700">Produk</label>
                            <select name="ProdukID" id="ProdukID"
                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($produk as $p)
                                    <option value="{{ $p->ProdukID }}">
                                        {{ $p->NamaProduk }} ({{ $p->Satuan }}) - Stok: {{ $p->Stok }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="JumlahProduk" class="block text-sm font-medium text-gray-700">Jumlah Produk</label>
                            <input type="number" name="JumlahProduk" id="JumlahProduk" min="1"
                                   class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <a href="{{ route('penjualan.index') }}"
                           class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                            Kembali
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-black text-white hover:bg-gray-800">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
