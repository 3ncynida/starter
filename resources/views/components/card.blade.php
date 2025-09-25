<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <!-- Gambar -->
    <div class="h-56 w-full">
        <a href="#">
            <img class="mx-auto h-full dark:hidden" src="{{ asset('storage/' . $product->Gambar) }}" 
     alt="{{ $product->NamaProduk }}" />
        </a>
    </div>

    <div class="pt-6">
        <!-- Diskon -->
        @if(!empty($product->discount))
            <span class="me-2 rounded bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-300">
                {{ $product->discount }}
            </span>
        @endif

        <!-- Nama -->
        <a href="#" class="block mt-2 text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">
            {{ $product->NamaProduk }}
        </a>

        <!-- Rating -->
        <div class="mt-2 flex items-center gap-2">
            <div class="flex items-center text-yellow-400">
                @for($i = 0; $i < 5; $i++)
                    <svg class="h-4 w-4 {{ $i < $product->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13.8 4.2a2 2 0 0 0-3.6 0L8.4 8.4l-4.6.3a2 2 0 0 0-1.1 3.5l3.5 3-1 4.4c-.5 1.7 1.4 3 2.9 2.1l3.9-2.3 3.9 2.3c1.5 1 3.4-.4 3-2.1l-1-4.4 3.4-3a2 2 0 0 0-1.1-3.5l-4.6-.3-1.8-4.2Z"/>
                    </svg>
                @endfor
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($product->rating, 1) }}</p>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">({{ $product->reviews }})</p>
        </div>

        <!-- Harga -->
        <div class="mt-4 flex items-center justify-between gap-4">
            <p class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white">
                Rp {{ number_format($product->Harga, 0, ',', '.') }}
            </p>
            <button type="button" class="inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                Add to cart
            </button>
        </div>
    </div>
</div>
