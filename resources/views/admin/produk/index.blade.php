<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">{{ __('Daftar Produk') }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            {{-- Toolbar: Tambah + Pencarian --}}
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('produk.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-black px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah Produk</span>
                </a>

                {{-- Search --}}
                <div class="relative flex-1 sm:max-w-xs">
                    <label for="product-search" class="sr-only">Cari produk</label>
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </div>
                    <input type="search" id="product-search" placeholder="Cari produk..."
                        class="w-full rounded-lg border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm placeholder-gray-500 transition focus:border-black focus:ring-2 focus:ring-black/10" />
                </div>
            </div>

            {{-- Grid Produk --}}
            <div id="productGrid" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 auto-rows-fr">
                @foreach($produks as $produk)
                @php
                    $isPromo = $produk->Promosi && now()->between($produk->TanggalMulaiPromosi, $produk->TanggalSelesaiPromosi);
                    $hargaPromo = $isPromo ? $produk->Harga - ($produk->Harga * $produk->DiskonPersen / 100) : null;
                @endphp

                <div class="flex flex-col h-full">
                    <x-product-card-manage :product="$produk" />
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $produks->links() }}
            </div>
        </div>
    </div>

    @php
        $productsJson = json_encode($allProducts);
    @endphp

    <script>
(function() {
    const input = document.getElementById('product-search');
    const productGrid = document.getElementById('productGrid');
    const products = @json($allProducts);

    const template = product => {
        // Logika promo berdasarkan tanggal
        const isPromo = product.Promosi &&
            new Date() >= new Date(product.TanggalMulaiPromosi) &&
            new Date() <= new Date(product.TanggalSelesaiPromosi);

        const hargaPromo = isPromo
            ? product.Harga - (product.Harga * product.DiskonPersen / 100)
            : null;

        const isOutOfStock = product.Stok <= 0;
        const stokMenipis = product.Stok > 0 && product.Stok < 5;

        return `
            <div class="product-card" data-name="${product.NamaProduk.toLowerCase()}">
                <div class="bg-[#0f172a] rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow relative">
                    
                    <!-- LABEL DISKON -->
                    ${isPromo ? `
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md z-10">
                            Diskon ${product.DiskonPersen}%
                        </span>
                    ` : ''}
                    
                    <div class="aspect-square relative">
                        <img class="w-full h-full object-cover ${isOutOfStock ? 'opacity-50' : ''}" 
                             src="/storage/${product.Gambar}" 
                             alt="${product.NamaProduk}" 
                             onerror="this.onerror=null;this.src='/produk/default.png'" />
                        
                        <!-- LABEL HABIS -->
                        ${isOutOfStock ? `
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold transform rotate-45 shadow-md">
                                    HABIS
                                </span>
                            </div>
                        ` : ''}
                        
                        <!-- LABEL MENIPIS -->
                        ${stokMenipis && !isOutOfStock ? `
                            <span class="absolute top-2 right-2 bg-yellow-400 text-black text-xs font-bold px-2 py-1 rounded-full shadow-md z-10 animate-pulse">
                                Menipis
                            </span>
                        ` : ''}
                    </div>

                    <div class="p-3 text-white">
                        <h3 class="text-base font-medium mb-0.5 truncate">${product.NamaProduk}</h3>
                        <p class="text-xs mb-2 ${isOutOfStock ? 'text-red-400' : 'text-gray-400'}">
                            Stok: ${product.Stok} ${product.Satuan}
                        </p>

                        <!-- HARGA -->
                        ${isPromo ? `
                            <p class="text-sm line-through text-gray-400">
                                Rp ${Number(product.Harga).toLocaleString('id-ID')}
                            </p>
                            <p class="text-lg font-bold text-red-500">
                                Rp ${Number(hargaPromo).toLocaleString('id-ID')}
                            </p>
                        ` : `
                            <p class="text-lg font-bold">
                                Rp ${Number(product.Harga).toLocaleString('id-ID')}
                            </p>
                        `}
                    </div>
                </div>
                
                <!-- FORM TAMBAH KE KERANJANG -->
                <form action="/cart/add/${product.ProdukID}" method="POST" class="mt-2 flex gap-2">
                    @csrf
                    <input type="number" 
                           name="qty" 
                           value="1" 
                           min="1" 
                           max="${product.Stok}"
                           class="w-20 rounded-lg border-gray-300 text-sm focus:ring-black focus:border-black">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            ${isOutOfStock ? 'disabled' : ''}>
                        <i class="fas fa-cart-plus mr-1"></i> Tambah
                    </button>
                </form>
            </div>
        `;
    };

    // Search filter function
    const filter = (term) => {
        const q = (term || '').toLowerCase().trim();

        if (!q) {
            showPaginated();
            return;
        }

        // Hide pagination when searching
        const paginationElement = document.querySelector('.mt-6');
        if (paginationElement) {
            paginationElement.style.display = 'none';
        }

        // Filter and display matching products
        const filtered = products.filter(product =>
            product.NamaProduk.toLowerCase().includes(q)
        );

        productGrid.innerHTML = filtered.map(template).join('');
    };

    // Function to show paginated view
    const showPaginated = () => {
        const paginationElement = document.querySelector('.mt-6');
        if (paginationElement) {
            paginationElement.style.display = 'block';
        }
        location.reload();
    };

    // Search input handler with debounce
    let timeout;
    input?.addEventListener('input', (e) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const term = e.target.value;
            if (!term) {
                showPaginated();
                return;
            }
            filter(term);
        }, 300);
    });

    // Keep existing keyboard shortcuts
    window.addEventListener('keydown', (e) => {
        if (e.key === '/' && document.activeElement !== input) {
            e.preventDefault();
            input?.focus();
        }
        if (e.altKey && (e.key.toLowerCase() === 'c')) {
            const cb = document.getElementById('checkout-button');
            if (cb) cb.focus();
        }
        if (e.key === 'Escape' && document.activeElement === input) {
            input.value = '';
            showPaginated();
        }
    });
})();
    </script>
</x-app-layout>