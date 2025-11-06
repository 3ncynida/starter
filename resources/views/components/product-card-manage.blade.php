{{-- Admin/Manage Product Card --}}
{{-- new reusable card with Edit & Hapus actions for the Produk index --}}
@props(['product'])

@php
  $isOut = (int)($product->Stok ?? 0) <= 0;
@endphp

<div
  // add conditional border color when habis and data-stock for future filters
  class="product-card group rounded-xl border bg-white p-4 shadow-sm transition hover:shadow-md focus-within:shadow-md {{ $isOut ? 'border-orange-300' : 'border-gray-200' }}"
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
    @if($product->harga_aktif < $product->Harga)
        @php
            $persenDiskon = round((($product->Harga - $product->harga_aktif) / $product->Harga) * 100);
        @endphp
        <span class="pointer-events-none absolute right-2 top-2 rounded-md bg-red-500 px-2 py-0.5 text-xs font-semibold text-white shadow-sm">
            Diskon -{{ $persenDiskon }}%
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
  <div class="mt-4 flex items-center gap-2">
    <a
      href="{{ route('produk.edit', $product->ProdukID) }}"
      class="inline-flex w-full items-center justify-center rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
      aria-label="Edit {{ $product->NamaProduk }}"
    >
      Edit
    </a>

    <form
      action="{{ route('produk.destroy', $product->ProdukID) }}"
      method="POST"
      class="w-full"
      onsubmit="return confirm('Yakin ingin menghapus {{ $product->NamaProduk }}?')"
    >
      @csrf
      @method('DELETE')
      <button
        type="submit"
        class="inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
        aria-label="Hapus {{ $product->NamaProduk }}"
      >
        Hapus
      </button>
    </form>
  </div>
</div>
