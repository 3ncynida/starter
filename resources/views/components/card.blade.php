<div class="bg-[#0f172a] rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col h-[320px]">
    <!-- Product Image -->
    <div class="aspect-square w-full relative">
        <img class="w-full h-full object-cover {{ $product->Stok < 1 ? 'opacity-50' : '' }}" 
             src="{{ asset('storage/' . $product->Gambar) }}" 
             alt="{{ $product->NamaProduk }}" />

        <!-- Promo Badge -->
        @if($isPromo)
            <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded">
                PROMO {{ number_format($product->DiskonPersen, 0) }}%
            </div>
        @endif

        <!-- Stock Out Overlay -->
        @if($product->Stok < 1)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="bg-red-500 text-white px-3 py-1 rounded text-sm font-bold">
                    HABIS
                </span>
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-3 text-white flex flex-col justify-between flex-grow">
        <!-- Product Name and Stock -->
        <div>
            <h3 class="text-sm font-medium truncate">{{ $product->NamaProduk }}</h3>
            <p class="text-xs {{ $product->Stok < 1 ? 'text-red-400' : 'text-gray-400' }}">
                Stok: {{ $product->Stok }}
            </p>
        </div>

        <!-- Price and Button -->
        <div class="flex items-end justify-between mt-2">
            <div class="flex flex-col justify-center min-h-[36px]">
                @if($isPromo)
                    <p class="text-[11px] text-gray-400 line-through leading-tight">
                        Rp {{ number_format($product->Harga, 0, ',', '.') }}
                    </p>
                    <p class="text-sm font-bold text-red-400 leading-tight">
                        Rp {{ number_format($hargaPromo, 0, ',', '.') }}
                    </p>
                @else
                    <!-- Spacer biar sejajar -->
                    <div class="h-[13px]"></div>
                    <p class="text-sm font-bold">
                        Rp {{ number_format($product->Harga, 0, ',', '.') }}
                    </p>
                @endif
            </div>

            <!-- Add Button -->
            <form action="{{ route('cart.add', $product->ProdukID) }}" method="POST">
                @csrf
                <button type="submit"
                        {{ $product->Stok < 1 ? 'disabled' : '' }}
                        class="bg-white text-[#0f172a] px-3 py-1 rounded text-xs font-medium transition-colors min-w-[55px]
                               {{ $product->Stok < 1 
                                   ? 'opacity-50 cursor-not-allowed' 
                                   : 'hover:bg-gray-100' }}">
                    {{ $product->Stok < 1 ? 'Habis' : 'Add' }}
                </button>
            </form>
        </div>
    </div>
</div>
