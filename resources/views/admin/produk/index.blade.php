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
                    <div class="bg-white rounded-lg border shadow-sm hover:shadow-md transition overflow-hidden relative flex flex-col h-full">
                        {{-- Label Promo --}}
                        @if($isPromo)
                        <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded">
                            PROMO {{ rtrim(rtrim($produk->DiskonPersen, '0'), '.') }}%
                        </div>
                        @endif

                        {{-- Gambar --}}
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

                        {{-- Info Produk --}}
                        <div class="p-4 flex flex-col flex-grow">
                            <div class="flex-grow">
                                <h3 class="font-medium text-gray-900 mb-1 truncate">{{ $produk->NamaProduk }}</h3>
                                <p class="text-sm text-gray-600 mb-2">Stok: {{ $produk->Stok }} {{ $produk->Satuan }}</p>

                                {{-- Harga --}}
                                <div class="price-container min-h-[3rem] flex flex-col justify-center">
                                    @if($isPromo)
                                        <p class="text-gray-500 line-through text-xs">Rp {{ number_format($produk->Harga, 0, ',', '.') }}</p>
                                        <p class="text-red-600 font-semibold text-lg">Rp {{ number_format($hargaPromo, 0, ',', '.') }}</p>
                                    @else
                                        {{-- Placeholder untuk menjaga tinggi konsisten --}}
                                        <div class="h-4"></div>
                                        <p class="text-lg font-bold text-gray-900">
                                            Rp {{ number_format($produk->Harga, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Tombol --}}
                            <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100">
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
                                        class="w-full inline-flex justify-center items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
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

    <style>
        .price-container {
            min-height: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</x-app-layout>