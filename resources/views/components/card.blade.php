<div class="bg-[#0f172a] rounded-xl overflow-hidden">
    <!-- Product Image -->
    <div class="aspect-square w-full">
        <img class="w-full h-full object-cover" 
             src="{{ asset('storage/' . $product->Gambar) }}" 
             alt="{{ $product->NamaProduk }}" />
    </div>

    <!-- Product Info -->
    <div class="p-4 text-white">
        <!-- Product Name -->
        <h3 class="text-lg font-medium mb-1">{{ $product->NamaProduk }}</h3>

        <!-- Stock Info -->
        <p class="text-gray-400 text-sm mb-4">
            Stok : {{ $product->Stok }} ({{ $product->Satuan }})
        </p>

        <!-- Price and Action -->
        <div class="flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold">
                    Rp {{ number_format($product->Harga, 0, ',', '.') }}
                </p>
            </div>

            <!-- Add to Cart Button -->
            <form action="{{ route('cart.add', $product->ProdukID) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="bg-white text-[#0f172a] px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                    Add to cart
                </button>
            </form>
        </div>
    </div>
</div>
