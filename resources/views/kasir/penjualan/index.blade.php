<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex gap-6">
            <!-- Main Content (Left Side) -->
            <div class="flex-1">
                <!-- Action Bar -->
                <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <a href="{{ route('penjualan.create') }}"
                       class="inline-flex items-center justify-center rounded-lg px-4 py-2 bg-black text-white shadow hover:bg-gray-800 transition">
                        + Tambah Penjualan
                    </a>

                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="fas fa-shopping-cart"></i>
                        <span>{{ session('cart') ? count(session('cart')) : 0 }} items di keranjang</span>
                    </div>
                </div>

                <!-- Search Produk (client-side) -->
                <div class="mb-5">
                    <label for="product-search" class="sr-only">Cari produk</label>
                    <div class="relative">
                        <input
                            id="product-search"
                            type="search"
                            placeholder="Cari produk (tekan / untuk fokus)"
                            class="w-full rounded-xl border border-gray-300 bg-white/70 px-4 py-2.5 pr-10 text-sm outline-none focus:border-black focus:ring-1 focus:ring-black"
                            aria-label="Cari produk"
                        />
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
                <!-- end Search -->

                <!-- Produk Section -->
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Produk</h3>
                <div class="grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        <!-- Bungkus kartu dengan data-name untuk filter -->
                        <div class="product-card" data-name="{{ strtolower($product->NamaProduk) }}">
                            <x-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Cart Section (Right Side - Sticky) -->
            <div class="w-full md:w-80 md:flex-shrink-0">
                <div class="md:sticky md:top-6">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <!-- Cart Header -->
                        <div class="p-4 border-b bg-gray-50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Keranjang Belanja</h3>
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span class="bg-black text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                        {{ count(session('cart')) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div class="max-h-96 overflow-y-auto">
                            @if(session('cart') && count(session('cart')) > 0)
                                <ul class="divide-y divide-gray-100">
                                    @php $total = 0; @endphp
                                    @foreach(session('cart') as $id => $item)
                                        @php $subtotal = $item['harga'] * $item['qty']; $total += $subtotal; @endphp
                                        <li class="p-4 hover:bg-gray-50 transition-colors">
                                            <div class="flex justify-between gap-3">
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $item['nama'] }}</h4>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-medium">
                                                            Qty: {{ $item['qty'] }}
                                                        </span>
                                                        <span class="text-xs text-gray-500">
                                                            @ Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                    <div class="text-sm font-bold text-gray-900">
                                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="p-8 text-center">
                                    <i class="fas fa-shopping-cart text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-sm text-gray-600 font-medium">Keranjang kosong</p>
                                    <p class="text-xs text-gray-400 mt-1">Tambahkan produk untuk melanjutkan</p>
                                </div>
                            @endif
                        </div>

                        <!-- Cart Footer -->
                        @if(session('cart') && count(session('cart')) > 0)
                            <div class="border-t p-4 bg-gray-50 space-y-3">
                                <form action="{{ route('cart.set-customer') }}" method="POST" class="space-y-2">
                                    @csrf
                                    <label class="block text-sm font-medium text-gray-700">Pilih Pelanggan</label>
                                    <select name="pelanggan_id" class="w-full rounded-lg border-gray-300 text-sm focus:ring-black focus:border-black" aria-label="Pilih pelanggan">
                                        <option value="">-- Non Member --</option>
                                        @foreach($pelanggan as $p)
                                            <option value="{{ $p->PelangganID }}" {{ session('cart_customer') == $p->PelangganID ? 'selected' : '' }}>
                                                {{ $p->NamaPelanggan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white py-2.5 px-4 rounded-lg text-sm font-medium transition-colors">
                                        Simpan Pelanggan
                                    </button>
                                </form>

                                <div class="flex flex-col gap-2 py-3 border-t border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-700">Subtotal:</span>
                                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>

                                    @if(session('cart_customer'))
                                        @php
                                            $diskonPersen = App\Models\Setting::get('diskon_member', 0);
                                            $diskonNominal = ($total * $diskonPersen) / 100;
                                            $totalSetelahDiskon = $total - $diskonNominal;
                                        @endphp
                                        <div class="flex items-center justify-between text-green-600">
                                            <span class="text-sm">Diskon Member ({{ $diskonPersen }}%):</span>
                                            <span class="text-sm font-medium">- Rp {{ number_format($diskonNominal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                                            <span class="text-sm font-semibold text-gray-700">Total Akhir:</span>
                                            <span class="text-xl font-bold text-gray-900">Rp {{ number_format($totalSetelahDiskon, 0, ',', '.') }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-semibold text-gray-700">Total:</span>
                                            <span class="text-xl font-bold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-2">
                                    <form action="{{ route('cart.checkout') }}" method="POST">
                                        @csrf
                                        <button id="checkout-button" type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg text-sm font-semibold transition">
                                            <i class="fas fa-credit-card mr-1"></i> Checkout
                                        </button>
                                    </form>
                                    <form action="{{ route('cart.clear') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-lg text-sm font-medium transition">
                                            <i class="fas fa-trash mr-1"></i> Kosongkan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar */
        .max-h-96::-webkit-scrollbar {
            width: 6px;
        }
        .max-h-96::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .max-h-96::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }
        .max-h-96::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>

    <script>
        (function () {
            const input = document.getElementById('product-search');
            const cards = Array.from(document.querySelectorAll('.product-card'));

            // Fokus cepat: tekan '/' untuk fokus ke search
            window.addEventListener('keydown', (e) => {
                if (e.key === '/' && document.activeElement !== input) {
                    e.preventDefault();
                    input?.focus();
                }
                // Alt + C = fokus tombol checkout
                if (e.altKey && (e.key.toLowerCase() === 'c')) {
                    const cb = document.getElementById('checkout-button');
                    if (cb) cb.focus();
                }
                // Escape = kosongkan pencarian
                if (e.key === 'Escape' && document.activeElement === input) {
                    input.value = '';
                    filter('');
                }
            });

            const filter = (term) => {
                const q = (term || '').toLowerCase().trim();
                cards.forEach((el) => {
                    const name = (el.getAttribute('data-name') || '').toLowerCase();
                    el.style.display = name.includes(q) ? '' : 'none';
                });
            };

            input?.addEventListener('input', (e) => filter(e.target.value));
        })();
    </script>
</x-app-layout>
