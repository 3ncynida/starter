{{-- Admin/Manage Product Card --}}
{{-- new reusable card with Edit & Hapus actions for the Produk index --}}
@props(['product'])

@php
  $isOut = (int)($product->Stok ?? 0) <= 0;
@endphp

<div
  class="product-card flex flex-col h-full group rounded-xl border bg-white p-4 shadow-sm transition hover:shadow-md focus-within:shadow-md {{ $isOut ? 'border-orange-300' : 'border-gray-200' }}"
  data-name="{{ strtolower($product->NamaProduk) }}"
  data-stock="{{ (int)($product->Stok ?? 0) }}"
>
  {{-- Gambar --}}
  <div class="relative h-40 w-full overflow-hidden rounded-lg bg-gray-50">
    {{-- HABIS badge --}}
    @if($isOut)
      <span class="pointer-events-none absolute left-2 top-2 rounded-md bg-orange-500 px-2 py-0.5 text-xs font-semibold text-white shadow-sm">Habis</span>
    @endif

    {{-- PROMO badge --}}
    @if($product->DiskonPersen > 0)
        <span class="pointer-events-none absolute right-2 top-2 rounded-md bg-red-500 px-2 py-0.5 text-xs font-semibold text-white shadow-sm">
            Diskon {{ number_format($product->DiskonPersen, 0) }}%
        </span>
    @endif

    {{-- ⚠️ Label Stok Menipis --}}
    @if($product->Stok > 0 && $product->Stok < 5)
        <span class="absolute top-2 right-2 bg-yellow-400 text-black text-xs font-bold px-2 py-1 rounded-full shadow-md z-10 animate-pulse">
            Menipis
        </span>
    @endif

    @if(!empty($product->Gambar))
      <img
        src="{{ asset('storage/' . $product->Gambar) }}"
        alt="{{ $product->NamaProduk }}"
        class="mx-auto h-full object-contain {{ $isOut ? 'opacity-60 grayscale' : '' }}"
        onerror="this.onerror=null;this.src='/produk/default.png'"
      />
    @else
      <img
        src="/produk/default.png"
        alt="{{ $product->NamaProduk }}"
        class="mx-auto h-full object-contain {{ $isOut ? 'opacity-60 grayscale' : '' }}"
      />
    @endif
  </div>

  {{-- Info --}}
  <div class="mt-4">
    {{-- ensure consistent title height --}}
    <h3 class="min-h-[2.75rem] text-sm font-semibold text-gray-900 line-clamp-2">{{ $product->NamaProduk }}</h3>

    {{-- stok label switches to HABIS style --}}
    @if($isOut)
      <p class="mt-1 text-xs font-medium text-orange-700">Stok: Habis</p>
    @else
      <p class="mt-1 text-xs text-gray-500">Stok: <span class="font-medium text-gray-700">{{ $product->Stok }}</span> {{ $product->Satuan }}</p>
    @endif

    @if($product->harga_aktif < $product->Harga)
        <p class="mt-2 text-sm line-through text-gray-500">
            Rp {{ number_format($product->Harga, 0, ',', '.') }}
        </p>
        <p class="text-lg font-bold text-red-600">
            Rp {{ number_format($product->harga_aktif, 0, ',', '.') }}
        </p>
    @else
        <p class="mt-2 text-lg font-bold text-gray-900">
            Rp {{ number_format($product->Harga, 0, ',', '.') }}
        </p>
    @endif
  </div>

  {{-- Aksi --}}
  <div class="mt-auto flex gap-2 pt-4">
    <a
      href="{{ route('produk.edit', $product->ProdukID) }}"
      aria-label="Edit {{ $product->NamaProduk }}"
    >
     <button
        class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
        aria-label="Hapus {{ $product->NamaProduk }}"
      >
      {{-- pencil icon --}}
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828zM4 14.5V17h2.5l8.377-8.377-2.5-2.5L4 14.5z" />
      </svg>
      Edit
      </button>

    </a>

    <form
      action="{{ route('produk.destroy', $product->ProdukID) }}"
      method="POST"
      class="flex-1"
      onsubmit="return confirm('Yakin ingin menghapus {{ $product->NamaProduk }}?')"
    >
      @csrf
      @method('DELETE')
      <button
        type="submit"
        class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition"
        aria-label="Hapus {{ $product->NamaProduk }}"
      >
        {{-- trash icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M8 9a1 1 0 012 0v6a1 1 0 11-2 0V9zm4 0a1 1 0 10-2 0v6a1 1 0 102 0V9z" clip-rule="evenodd" />
          <path d="M5 6h10l-.867 9.144A2 2 0 0111.142 17H8.858a2 2 0 01-1.991-1.856L6 6z" />
          <path d="M9 4a1 1 0 011-1h0a1 1 0 011 1h3a1 1 0 110 2H6a1 1 0 110-2h3z" />
        </svg>
        Hapus
      </button>
    </form>
  </div>
</div>