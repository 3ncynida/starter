<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white p-6 shadow-sm rounded-lg">
            <form action="{{ route('penjualan.update', $penjualan->PenjualanID) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-2">Tanggal Penjualan</label>
                    <input type="date" name="TanggalPenjualan"
                           value="{{ $penjualan->TanggalPenjualan }}"
                           class="w-full border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Pelanggan</label>
                    <select name="PelangganID" class="w-full border-gray-300 rounded-lg" required>
                        @foreach($pelanggan as $p)
                            <option value="{{ $p->PelangganID }}"
                                {{ $penjualan->PelangganID == $p->PelangganID ? 'selected' : '' }}>
                                {{ $p->NamaPelanggan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Produk</label>
                    <select name="ProdukID" class="w-full border-gray-300 rounded-lg" required>
                        @foreach($produk as $pr)
                            <option value="{{ $pr->ProdukID }}"
                                {{ $detail->ProdukID == $pr->ProdukID ? 'selected' : '' }}>
                                {{ $pr->NamaProduk }} (Stok: {{ $pr->Stok }}) - Rp {{ number_format($pr->Harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Jumlah Produk</label>
                    <input type="number" name="JumlahProduk"
                           value="{{ $detail->JumlahProduk }}"
                           class="w-full border-gray-300 rounded-lg" required>
                </div>

<a href="{{ route('penjualan.index') }}"
   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
    Kembali
</a>

<button type="submit"
        class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
    Update
</button>

            </form>
        </div>
    </div>
</x-app-layout>
