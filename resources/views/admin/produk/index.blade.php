<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Produk') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Toolbar: Tambah + Pencarian --}}
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    {{-- tombol tambah dengan warna hijau "keranjang" --}}
                    <a href="{{ route('produk.create') }}"
                        class="inline-flex items-center gap-1 rounded-lg bg-black px-3 py-2 text-sm font-semibold text-white transition hover:bg-black/80">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Produk
                    </a>
                </div>

                {{-- Search --}}
                <div class="relative">
                    <label for="product-search" class="sr-only">Cari produk</label>
                    <input type="search" id="product-search" placeholder="Cari produk..."
                        class="w-full rounded-lg border-gray-300 px-4 py-2 text-sm focus:border-black focus:ring-black sm:w-64" />
                </div>
            </div>

{{-- Grid Kartu Produk --}}
<div id="productGrid" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
    @foreach($produks as $produk)
    <div class="product-card" data-name="{{ strtolower($produk->NamaProduk) }}">
        <div class="bg-white rounded-lg border shadow-sm hover:shadow-md transition-shadow overflow-hidden">
            <div class="aspect-square w-full relative">
                <img src="{{ asset('storage/' . $produk->Gambar) }}" alt="{{ $produk->NamaProduk }}"
                    class="w-full h-full object-cover">

                @if($produk->Stok < 1)
                <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                    <span class="bg-red-600 text-white px-3 py-1 rounded text-sm font-bold shadow-md">
                        HABIS
                    </span>
                </div>
                @endif
            </div>

            <div class="p-4">
                <h3 class="font-medium text-gray-900 mb-1 truncate">{{ $produk->NamaProduk }}</h3>
                <p class="text-sm text-gray-600 mb-2">Stok: {{ $produk->Stok }} {{ $produk->Satuan }}</p>
                <p class="text-lg font-bold text-gray-900 mb-3">
                    Rp {{ number_format($produk->Harga, 0, ',', '.') }}
                </p>

                <div class="flex gap-2">
                    <a href="{{ route('produk.edit', $produk->ProdukID) }}"
                        class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('produk.destroy', $produk->ProdukID) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>


            {{-- Pagination --}}
            <div class="mt-6">
                {{ $produks->links() }}
            </div>
        </div>
    </div>

    @php
    $productsJson = json_encode($allProducts);
    @endphp

    <script>
        (function () {
            const input = document.getElementById('product-search');
            const productGrid = document.getElementById('productGrid');
            const products = {!! $productsJson !!};

            const template = product => `
                <div class="product-card" data-name="${product.NamaProduk.toLowerCase()}">
                    <div class="bg-white rounded-lg border shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                        <div class="aspect-square w-full relative">
                            <img src="/storage/${product.Gambar}" 
                                 alt="${product.NamaProduk}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium text-gray-900 mb-1 truncate">${product.NamaProduk}</h3>
                            <p class="text-sm text-gray-600 mb-2">Stok: ${product.Stok} ${product.Satuan}</p>
                            <p class="text-lg font-bold text-gray-900 mb-3">
                                Rp ${Number(product.Harga).toLocaleString('id-ID')}
                            </p>
                            <div class="flex gap-2">
                                <a href="/produk/${product.ProdukID}/edit" 
                                   class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="/produk/${product.ProdukID}" method="POST" class="flex-1">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Search filter function
            const filter = (term) => {
                const q = term.toLowerCase().trim();

                // Hide pagination when searching
                const paginationDiv = document.querySelector('.mt-6');
                if (paginationDiv) {
                    paginationDiv.style.display = 'none';
                }

                // Filter and display matching products
                const filtered = products.filter(product =>
                    product.NamaProduk.toLowerCase().includes(q)
                );

                if (filtered.length === 0) {
                    productGrid.innerHTML = `
                        <div class="col-span-full">
                            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-10 w-10 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                                </svg>
                                <p class="text-sm text-gray-600">Tidak ada produk yang cocok dengan pencarian "${term}"</p>
                            </div>
                        </div>
                    `;
                } else {
                    productGrid.innerHTML = filtered.map(template).join('');
                }
            };

            // Reset search function
            const resetSearch = () => {
                const paginationDiv = document.querySelector('.mt-6');
                if (paginationDiv) {
                    paginationDiv.style.display = 'block';
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
                        resetSearch();
                        return;
                    }
                    filter(term);
                }, 300);
            });

            // Keyboard shortcuts
            window.addEventListener('keydown', (e) => {
                if (e.key === '/' && document.activeElement !== input) {
                    e.preventDefault();
                    input?.focus();
                }
                if (e.key === 'Escape' && document.activeElement === input) {
                    input.value = '';
                    resetSearch();
                }
            });
        })();
    </script>
</x-app-layout>
