<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">{{ __('Daftar Produk') }}</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola dan pantau seluruh produk Anda</p>
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
        (function () {
            const input = document.getElementById('product-search');
            const productGrid = document.getElementById('productGrid');
            const products = {!! $productsJson !!};

const template = product => `
                <div class="flex flex-col h-full rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-lg hover:border-gray-300 focus-within:shadow-lg" data-name="${product.NamaProduk.toLowerCase()}">
                    <div class="relative h-40 w-full overflow-hidden rounded-lg bg-gray-50">
                        <img src="/storage/${product.Gambar}" 
                             alt="${product.NamaProduk}"
                             class="mx-auto h-full object-contain"
                             onerror="this.onerror=null;this.src='/produk/default.png'">
                    </div>

                    <!-- Info section expands to fill available space -->
                    <div class="mt-4 flex-1 flex flex-col justify-start">
                        <h3 class="h-[2.75rem] text-sm font-semibold text-gray-900 line-clamp-2">${product.NamaProduk}</h3>
                        <p class="mt-2 text-xs text-gray-600">
                            Stok: <span class="font-bold text-gray-900">${product.Stok}</span> ${product.Satuan}
                        </p>
                        <p class="mt-3 text-lg font-bold text-gray-900">
                            Rp ${Number(product.Harga).toLocaleString('id-ID')}
                        </p>
                    </div>

                    <!-- Button container pushed to bottom with consistent height -->
                    <div class="mt-4 flex gap-2">
                        <a href="/admin/produk/${product.ProdukID}/edit"
                           class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 active:scale-95 transition h-10">
                            Edit
                        </a>
                        <form action="/produk/${product.ProdukID}"
                              method="POST"
                              class="flex-1"
                              onsubmit="return confirm('Yakin ingin menghapus ${product.NamaProduk}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full h-10 inline-flex items-center justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700 active:scale-95 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            `;


            const filter = (term) => {
                const q = term.toLowerCase().trim();
                const paginationDiv = document.querySelector('.mt-8');
                
                if (paginationDiv) {
                    paginationDiv.style.display = 'none';
                }

                const filtered = products.filter(product =>
                    product.NamaProduk.toLowerCase().includes(q)
                );

                productGrid.innerHTML = filtered.length 
                    ? filtered.map(template).join('')
                    : `<div class="col-span-full">
                           <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-gray-50 p-12 text-center">
                               <svg xmlns="http://www.w3.org/2000/svg" class="mb-3 h-12 w-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                               </svg>
                               <p class="text-sm font-medium text-gray-700">Tidak ada produk yang cocok</p>
                               <p class="text-xs text-gray-500 mt-1">Coba cari dengan kata kunci lain</p>
                           </div>
                       </div>`;
            };

            const resetSearch = () => {
                const paginationDiv = document.querySelector('.mt-8');
                if (paginationDiv) {
                    paginationDiv.style.display = 'block';
                }
                location.reload();
            };

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
