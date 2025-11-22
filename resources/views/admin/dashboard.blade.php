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
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-indigo-600 p-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-slate-500">Total Penjualan (bulan ini)</p>
                                <p class="text-2xl font-semibold text-slate-900">{{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pendapatan Card -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-emerald-600 p-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-slate-500">Total Pendapatan (bulan ini)</p>
                                <p class="text-2xl font-semibold text-slate-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Produk Card -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-amber-600 p-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-slate-500">Total Produk</p>
                                <p class="text-2xl font-semibold text-slate-900">{{ $totalProduk }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pelanggan Card -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-md bg-rose-600 p-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-slate-500">Total Pelanggan</p>
                                <p class="text-2xl font-semibold text-slate-900">{{ $totalPelanggan }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recent Sales Table -->
            <div class="bg-white overflow-hidden rounded-xl border border-slate-200 shadow-sm mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Penjualan Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-900 sticky top-0 z-10">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Pelanggan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($recentSales as $sale)
                                <tr class="odd:bg-white even:bg-slate-50 hover:bg-slate-100/70 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                        {{ $sale->TanggalPenjualan }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                        {{ $sale->NamaPelanggan ?? 'Non-Member' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($sale->total, 0, ',', '.') }}
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
                @php
                $maxSold = max(1, ($topProducts instanceof \Illuminate\Support\Collection ? $topProducts->max('total_sold') : collect($topProducts)->max('total_sold')));
                $maxSpent = max(1, ($topCustomers instanceof \Illuminate\Support\Collection ? $topCustomers->max('total_spent') : collect($topCustomers)->max('total_spent')));
                @endphp
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Produk Terlaris</h3>
                        <div class="space-y-4">
                            @if($topProducts->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-slate-500">Belum ada data produk terlaris</p>
                            </div>
                            @else
                            @foreach($topProducts as $product)
                            @php
                            $pct = round(($product->total_sold / $maxSold) * 100);
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ asset('storage/' . $product->Gambar) }}" alt="{{ $product->NamaProduk }}" class="w-12 h-12 rounded-lg object-cover" onerror="this.onerror=null;this.src='/produk/default.png'">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-slate-900">{{ $product->NamaProduk }}</p>
                                        <p class="text-sm text-slate-500">{{ $product->total_sold }} terjual</p>
                                        <div class="mt-2 w-36">
                                            <div class="h-2 bg-slate-100 rounded-full">
                                                <div class="h-2 bg-blue-600 rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end min-w-[120px]">
                                    @if(isset($product->harga_aktif) && $product->harga_aktif !== null && $product->harga_aktif < $product->Harga)
                                        @php
                                        $persenDiskon = round((($product->Harga - $product->harga_aktif) / $product->Harga) * 100);
                                        @endphp
                                        <span class="text-xs font-bold text-red-600 mb-1">Diskon -{{ $persenDiskon }}%</span>
                                        <span class="text-sm line-through text-gray-400 block">Rp {{ number_format($product->Harga, 0, ',', '.') }}</span>
                                        <span class="text-lg font-bold text-red-600 block">Rp {{ number_format($product->harga_aktif, 0, ',', '.') }}</span>
                                        @else
                                        <span class="text-lg font-bold">Rp {{ number_format($product->Harga, 0, ',', '.') }}</span>
                                        @endif
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Top Customers -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Pelanggan Teratas</h3>
                        <div class="space-y-4">
                            @if($topCustomers->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-slate-500">Belum ada data pelanggan</p>
                            </div>
                            @else
                            @foreach($topCustomers as $customer)
                            @php
                            $pct = round(($customer->total_spent / $maxSpent) * 100);
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-slate-900">{{ $customer->NamaPelanggan }}</p>
                                        <p class="text-sm text-slate-500">{{ $customer->total_transactions }} transaksi</p>
                                        <div class="mt-2 w-36">
                                            <div class="h-2 bg-slate-100 rounded-full">
                                                <div class="h-2 bg-blue-600 rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-slate-900">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</p>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>