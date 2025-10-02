<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Penjualan #') . $penjualan->PenjualanID }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-800">Informasi Penjualan</h3>
                            <p class="text-gray-600">
                                <span class="font-medium">Tanggal:</span>
                                {{ \Carbon\Carbon::parse($penjualan->TanggalPenjualan)->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-gray-600">
                                <span class="font-medium">Pelanggan:</span>
                                {{ $penjualan->pelanggan->NamaPelanggan ?? 'Non Member' }}
                            </p>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-gray-600">
                                <span class="font-medium">Total:</span>
                                <span class="text-lg font-bold text-gray-900">
                                    Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}
                                </span>
                            </p>
                            @if($penjualan->Diskon > 0)
                                <p class="text-gray-600">
                                    <span class="font-medium">Diskon:</span>
                                    <span class="text-green-600">- Rp {{ number_format($penjualan->Diskon, 0, ',', '.') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800">Detail Produk</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="sticky top-0 bg-slate-900 text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($details as $index => $detail)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $detail->produk->NamaProduk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($detail->produk->Harga, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $detail->JumlahProduk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Rp {{ number_format($detail->Subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50">
                                    <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Subtotal</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($details->sum('Subtotal'), 0, ',', '.') }}</td>
                                </tr>
                                @if($penjualan->Diskon > 0)
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Diskon Member</td>
                                        <td class="px-6 py-4 text-sm font-medium text-green-600">- Rp {{ number_format($penjualan->Diskon, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="bg-gray-50">
                                    <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-gray-50 px-6 py-4">
                    <a href="{{ route('detail-penjualan.index') }}"
                       class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                        Kembali
                    </a>
                    <button onclick="window.print()"
                            class="rounded-md border border-gray-300 px-4 py-2 text-xs font-medium text-gray-800 hover:bg-gray-100">
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
