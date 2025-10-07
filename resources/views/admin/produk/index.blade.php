<x-app-layout>
  <x-slot name="header">
    {{-- judul halaman --}}
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Daftar Produk') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      {{-- Toolbar: Tambah + Pencarian --}}
      <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        {{-- tombol tambah dengan warna hijau "keranjang" --}}
        <a
          href="{{ route('produk.create') }}"
          class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          + Tambah Produk
        </a>

        {{-- Search --}}
        <div class="relative w-full sm:w-80">
          <label for="product-search" class="sr-only">Cari produk</label>
          <input
            id="product-search"
            type="text"
            placeholder="Cari produk (tekan / untuk fokus)"
            aria-label="Cari produk"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500"
          />
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
          </div>
        </div>
      </div>

      {{-- Grid Kartu Produk --}}
      <div id="productGrid" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
        @forelse($produks as $p)
          {{-- gunakan kartu manage yang baru --}}
          <x-product-card-manage :product="$p" />
        @empty
          {{-- Empty state --}}
          <div class="col-span-full">
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-10 w-10 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M5 7l1.5 12h11L19 7M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2M10 11h4M9 15h6"/>
              </svg>
              <p class="text-sm text-gray-600">Belum ada produk.</p>
              <a href="{{ route('produk.create') }}" class="mt-3 inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                Tambah Produk
              </a>
            </div>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($produks instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-6 flex flex-col items-center justify-between gap-3 sm:flex-row">
          <p class="text-sm text-gray-600">
            Menampilkan <span class="font-medium">{{ $produks->firstItem() }}</span>–<span class="font-medium">{{ $produks->lastItem() }}</span>
            dari <span class="font-medium">{{ $produks->total() }}</span> produk
          </p>
          <div class="w-full sm:w-auto">
            {{ $produks->onEachSide(1)->links() }}
          </div>
        </div>
      @endif

      {{-- skrip pencarian client-side yg ringan --}}
      <script>
        (function () {
          const input = document.getElementById('product-search');
          const cards = document.querySelectorAll('#productGrid .product-card');

          // Fokus cepat dengan tombol "/"
          window.addEventListener('keydown', (e) => {
            if (e.key === '/') {
              e.preventDefault();
              input.focus();
            }
          });

          input?.addEventListener('input', () => {
            const q = input.value.trim().toLowerCase();
            cards.forEach(card => {
              const name = card.getAttribute('data-name') || '';
              card.style.display = name.includes(q) ? '' : 'none';
            });
          });
        })();
      </script>
    </div>
  </div>
</x-app-layout>
