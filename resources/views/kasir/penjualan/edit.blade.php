<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white p-6 shadow-sm rounded-xl border border-gray-100">
            <form action="{{ route('penjualan.update', $penjualan->PenjualanID) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="TanggalPenjualan">Tanggal Penjualan</label>
                        <input type="date" id="TanggalPenjualan" name="TanggalPenjualan"
                            value="{{ $penjualan->TanggalPenjualan }}"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="PelangganID">Pelanggan</label>
                        <select id="PelangganID" name="PelangganID"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" required>
                            @foreach($pelanggan as $p)
                            <option value="{{ $p->PelangganID }}"
                                {{ $penjualan->PelangganID == $p->PelangganID ? 'selected' : '' }}>
                                {{ $p->NamaPelanggan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="ProdukID">Produk</label>
                        <select id="ProdukID" name="ProdukID"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" required>
                            @foreach($produk as $pr)
                            <option value="{{ $pr->ProdukID }}"
                                {{ $detail->ProdukID == $pr->ProdukID ? 'selected' : '' }}>
                                {{ $pr->NamaProduk }} (Stok: {{ $pr->Stok }}) - Rp {{ number_format($pr->Harga, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="JumlahProduk">Jumlah Produk</label>
                        <input id="JumlahProduk" type="number" name="JumlahProduk"
                            value="{{ $detail->JumlahProduk }}"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" required>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('penjualan.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Kembali
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-black text-white hover:bg-gray-800">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>