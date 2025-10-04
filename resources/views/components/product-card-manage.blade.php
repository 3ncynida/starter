{{-- Admin/Manage Product Card --}}
{{-- new reusable card with Edit & Hapus actions for the Produk index --}}
@props(['product'])

<div
  class="product-card group rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md focus-within:shadow-md"
  data-name="{{ strtolower($product->NamaProduk) }}"
>
  {{-- Gambar --}}
  <div class="h-40 w-full overflow-hidden rounded-lg bg-gray-50">
    @if(!empty($product->Gambar))
      <img
        src="{{ asset('storage/' . $product->Gambar) }}"
        alt="{{ $product->NamaProduk }}"
        class="mx-auto h-full object-contain"
      />
    @else
      <img
        src="{{ asset('/placeholder.svg?height=160&width=240') }}"
        alt="{{ $product->NamaProduk }}"
        class="mx-auto h-full object-contain"
      />
    @endif
  </div>

  {{-- Info --}}
  <div class="mt-4">
    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2">{{ $product->NamaProduk }}</h3>
    <p class="mt-1 text-xs text-gray-500">Stok: <span class="font-medium text-gray-700">{{ $product->Stok }}</span> {{ $product->Satuan }}</p>

    <p class="mt-2 text-lg font-bold text-gray-900">
      Rp {{ number_format($product->Harga, 0, ',', '.') }}
    </p>
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
