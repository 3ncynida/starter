<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('TEST') }}
    </h2>
  </x-slot>
  <section class="bg-gray-50 py-8 antialiased">
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
      <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
        @foreach($products as $product) {{-- <- pastikan nama $product sama --}}
        <x-card :product="$product" />
        @endforeach
      </div>
    </div>
  </section>
</x-app-layout>