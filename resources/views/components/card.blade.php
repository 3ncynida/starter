<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <!-- Gambar -->
    <div class="h-56 w-full">
        <a href="#">
            <img class="mx-auto h-full" src="{{ asset('storage/' . $product->Gambar) }}" 
            alt="{{ $product->NamaProduk }}" />
        </a>
    </div>

    <div class="pt-6">
        <!-- Diskon -->
        @if(!empty($product->discount))
            <span class="me-2 rounded bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                Diskon {{ $product->discount }}%
            </span>
        @endif

        <!-- Nama -->
        <a href="#" class="block mt-2 text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">
            {{ $product->NamaProduk }}
        </a>

        <!-- Stok -->
        <div class="mt-2 flex items-center gap-2">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Stok : {{ $product->Stok }} ({{ $product->Satuan }})
            </p>
        </div>

        <!-- Harga -->
        <div class="mt-4 flex items-center justify-between gap-4">
            <div>
                @if(!empty($product->discount))
                    @php
                        $hargaDiskon = $product->Harga - ($product->Harga * $product->discount / 100);
                    @endphp
                    <p class="text-sm line-through text-gray-500 dark:text-gray-400">
                        Rp {{ number_format($product->Harga, 0, ',', '.') }}
                    </p>
                    <p class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white">
                        Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                    </p>
                @else
                    <p class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white">
                        Rp {{ number_format($product->Harga, 0, ',', '.') }}
                    </p>
                @endif
            </div>

            <!-- Tombol Add to Cart -->
            <form action="{{ route('cart.add', $product->ProdukID) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Add to cart
                </button>
            </form>
        </div>
    </div>
</div>
