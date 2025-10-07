<div class="bg-[#0f172a] rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
    <!-- Product Image -->
    <div class="aspect-square w-full relative">
        <img class="w-full h-full object-cover {{ $product->Stok < 1 ? 'opacity-50' : '' }}" 
             src="{{ asset('storage/' . $product->Gambar) }}" 
             alt="{{ $product->NamaProduk }}" />
        
        @if($product->Stok < 1)
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold transform rotate-45">
                    HABIS
                </span>
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-3 text-white">
        <!-- Product Name -->
        <h3 class="text-base font-medium mb-0.5 truncate">{{ $product->NamaProduk }}</h3>

        <!-- Stock Info -->
        <p class="text-xs mb-2 {{ $product->Stok < 1 ? 'text-red-400' : 'text-gray-400' }}">
            Stok: {{ $product->Stok }} {{ $product->Satuan }}
        </p>

        <!-- Price and Action -->
        <div class="flex items-center justify-between gap-2">
            <p class="text-lg font-bold">
                Rp {{ number_format($product->Harga, 0, ',', '.') }}
            </p>

            <!-- Add to Cart Button -->
            <form action="{{ route('cart.add', $product->ProdukID) }}" method="POST">
                @csrf
                <button type="submit" 
                        {{ $product->Stok < 1 ? 'disabled' : '' }}
                        class="bg-white text-[#0f172a] px-3 py-1.5 rounded text-sm font-medium transition-colors
                               {{ $product->Stok < 1 
                                   ? 'opacity-50 cursor-not-allowed' 
                                   : 'hover:bg-gray-100' }}">
                    {{ $product->Stok < 1 ? 'Habis' : 'Add' }}
                </button>
            </form>
        </div>
    </div>
</div>
