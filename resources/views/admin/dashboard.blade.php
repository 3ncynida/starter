
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Penjualan Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-blue-500 p-3">
                                <i class="fas fa-shopping-cart text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Penjualan</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pendapatan Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-green-500 p-3">
                                <i class="fas fa-money-bill text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                                <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Produk Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-yellow-500 p-3">
                                <i class="fas fa-box text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Produk</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalProduk }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pelanggan Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-purple-500 p-3">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalPelanggan }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Sales Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Penjualan Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#0f172a]">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Pelanggan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentSales as $sale)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $sale->TanggalPenjualan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $sale->pelanggan->NamaPelanggan ?? 'Non-Member' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($sale->TotalHarga, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Selesai
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Top Products -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk Terlaris</h3>
                        <div class="space-y-4">
                            @foreach($topProducts as $product)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="{{ asset('storage/' . $product->Gambar) }}" alt="{{ $product->NamaProduk }}" 
                                             class="w-12 h-12 rounded-lg object-cover">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $product->NamaProduk }}</p>
                                            <p class="text-sm text-gray-500">{{ $product->total_sold }} terjual</p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">Rp {{ number_format($product->Harga, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Customers -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pelanggan Teratas</h3>
                        <div class="space-y-4">
                            @foreach($topCustomers as $customer)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 rounded-full bg-gray-100 p-3">
                                            <i class="fas fa-user text-gray-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $customer->NamaPelanggan }}</p>
                                            <p class="text-sm text-gray-500">{{ $customer->total_transactions }} transaksi</p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>