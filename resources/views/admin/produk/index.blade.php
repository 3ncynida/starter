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
                <a href="{{ route('produk.create') }}"
                    class="inline-flex items-center gap-1 rounded-lg bg-black px-3 py-2 text-sm font-semibold text-white transition hover:bg-black/80">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Produk
                </a>

                {{-- Search --}}
                <div class="relative">
                    <label for="product-search" class="sr-only">Cari produk</label>
                    <input type="search" id="product-search" placeholder="Cari produk..."
                        class="w-full rounded-lg border-gray-300 px-4 py-2 text-sm focus:border-black focus:ring-black sm:w-64" />
                </div>
            </div>

            {{-- Grid Produk --}}
            <div id="productGrid" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
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
                <div class="flex flex-col h-full">
                    <div class="product-card group rounded-xl border bg-white p-4 shadow-sm transition hover:shadow-md focus-within:shadow-md" data-name="${product.NamaProduk.toLowerCase()}">
                        <div class="relative h-40 w-full overflow-hidden rounded-lg bg-gray-50">
                            <img src="/storage/${product.Gambar}" 
                                 alt="${product.NamaProduk}"
                                 class="mx-auto h-full object-contain"
                                 onerror="this.onerror=null;this.src='/produk/default.png'">
                        </div>

                        <div class="mt-4">
                            <h3 class="min-h-[2.75rem] text-sm font-semibold text-gray-900 line-clamp-2">${product.NamaProduk}</h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Stok: <span class="font-medium text-gray-700">${product.Stok}</span> ${product.Satuan}
                            </p>
                            <p class="mt-2 text-lg font-bold text-gray-900">
                                Rp ${Number(product.Harga).toLocaleString('id-ID')}
                            </p>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <a href="/produk/${product.ProdukID}/edit"
                               class="inline-flex w-full items-center justify-center rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                Edit
                            </a>
                            <form action="/produk/${product.ProdukID}"
                                  method="POST"
                                  class="w-full"
                                  onsubmit="return confirm('Yakin ingin menghapus ' + product.NamaProduk + '?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            const filter = (term) => {
                const q = term.toLowerCase().trim();
                const paginationDiv = document.querySelector('.mt-6');
                
                if (paginationDiv) {
                    paginationDiv.style.display = 'none';
                }

                const filtered = products.filter(product =>
                    product.NamaProduk.toLowerCase().includes(q)
                );

                productGrid.innerHTML = filtered.length 
                    ? filtered.map(template).join('')
                    : `<div class="col-span-full">
                           <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center">
                               <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-10 w-10 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                               </svg>
                               <p class="text-sm text-gray-600">Tidak ada produk yang cocok dengan pencarian "${term}"</p>
                           </div>
                       </div>`;
            };

            const resetSearch = () => {
                const paginationDiv = document.querySelector('.mt-6');
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

    <style>
        .price-container {
            min-height: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</x-app-layout>