<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Produk') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            {{-- Toolbar: Tambah + Pencarian + Filter --}}
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                {{-- Tombol Tambah Produk --}}
                <a href="{{ route('produk.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Produk Baru
                </a>

                {{-- Search dan Filter --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    {{-- Search --}}
                    <div class="relative">
                        <input type="search" id="product-search" placeholder="Cari produk..."
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-black focus:ring-black sm:w-64" />
                    </div>

                    {{-- Filter Stok --}}
                    <select id="stock-filter" class="rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-black focus:ring-black">
                        <option value="">Semua Stok</option>
                        <option value="available">Stok Tersedia</option>
                        <option value="out">Stok Habis</option>
                    </select>

                    {{-- Filter Promo --}}
                    <select id="promo-filter" class="rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-black focus:ring-black">
                        <option value="">Semua Produk</option>
                        <option value="promo">Sedang Promo</option>
                    </select>
                </div>
            </div>

            {{-- Statistik Cepat --}}
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-lg bg-white p-4 shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                        </div>
                        <div class="rounded-full bg-blue-100 p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-4 shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Stok Tersedia</p>
                            <p class="text-2xl font-bold text-green-600">{{ $availableProducts }}</p>
                        </div>
                        <div class="rounded-full bg-green-100 p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-4 shadow-sm border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Produk Promo</p>
                            <p class="text-2xl font-bold text-red-600">{{ $promoProducts }}</p>
                        </div>
                        <div class="rounded-full bg-red-100 p-3">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid Produk --}}
            @if($produks->count() > 0)
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
                    @foreach($produks as $produk)
                        <x-card :product="$produk" />
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada produk</h3>
                    <p class="mt-2 text-sm text-gray-500">Mulai dengan menambahkan produk pertama Anda.</p>
                    <div class="mt-6">
                        <a href="{{ route('produk.create') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Produk Pertama
                        </a>
                    </div>
                </div>
            @endif

            {{-- Pagination --}}
            @if($produks->count() > 0)
                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan {{ $produks->firstItem() }} hingga {{ $produks->lastItem() }} dari {{ $produks->total() }} hasil
                    </div>
                    <div>
                        {{ $produks->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- JavaScript untuk Search dan Filter --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('product-search');
            const stockFilter = document.getElementById('stock-filter');
            const promoFilter = document.getElementById('promo-filter');
            const productCards = document.querySelectorAll('.product-card');

            function filterProducts() {
                const searchTerm = searchInput.value.toLowerCase();
                const stockValue = stockFilter.value;
                const promoValue = promoFilter.value;

                productCards.forEach(card => {
                    const productName = card.querySelector('h3').textContent.toLowerCase();
                    const stockText = card.querySelector('.text-xs').textContent;
                    const hasPromo = card.querySelector('.bg-red-600') !== null;
                    const isOutOfStock = stockText.includes('Stok: 0');

                    let matchesSearch = productName.includes(searchTerm);
                    let matchesStock = true;
                    let matchesPromo = true;

                    // Filter stok
                    if (stockValue === 'available') {
                        matchesStock = !isOutOfStock;
                    } else if (stockValue === 'out') {
                        matchesStock = isOutOfStock;
                    }

                    // Filter promo
                    if (promoValue === 'promo') {
                        matchesPromo = hasPromo;
                    }

                    // Tampilkan/sembunyikan card berdasarkan filter
                    if (matchesSearch && matchesStock && matchesPromo) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterProducts);
            stockFilter.addEventListener('change', filterProducts);
            promoFilter.addEventListener('change', filterProducts);

            // Focus search dengan shortcut '/'
            document.addEventListener('keydown', function(e) {
                if (e.key === '/' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                    searchInput.focus();
                }
            });
        });
    </script>
</x-app-layout>