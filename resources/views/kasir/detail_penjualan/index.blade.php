<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Pelanggan</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Produk</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jumlah</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($detail as $d)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $d->penjualan->TanggalPenjualan }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $d->penjualan->pelanggan->NamaPelanggan }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $d->produk->NamaProduk }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $d->JumlahProduk }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">Rp {{ number_format($d->Subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                                        Belum ada data detail penjualan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
