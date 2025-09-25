<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Card Form -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Informasi Produk') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __("Isi detail produk yang ingin ditambahkan.") }}
                        </p>
                    </header>

<form method="POST" action="{{ route('produk.store') }}" enctype="multipart/form-data">

    @csrf

    <!-- Nama Produk -->
    <div>
        <x-input-label for="NamaProduk" :value="__('Nama Produk')" />
        <x-text-input id="NamaProduk" name="NamaProduk" type="text" class="mt-1 block w-full" :value="old('NamaProduk')" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('NamaProduk')" />
    </div>

    <!-- Harga -->
    <div>
        <x-input-label for="Harga" :value="__('Harga')" />
        <x-text-input id="Harga" name="Harga" type="number" step="0.01" class="mt-1 block w-full" :value="old('Harga')" required />
        <x-input-error class="mt-2" :messages="$errors->get('Harga')" />
    </div>

    <!-- Stok -->
    <div>
        <x-input-label for="Stok" :value="__('Stok')" />
        <x-text-input id="Stok" name="Stok" type="number" class="mt-1 block w-full" :value="old('Stok')" required />
        <x-input-error class="mt-2" :messages="$errors->get('Stok')" />
    </div>

    <!-- Gambar -->
    <div>
        <x-input-label for="Gambar" :value="__('Gambar Produk')" />
        <input id="Gambar" name="Gambar" type="file" class="mt-1 block w-full" accept="image/*">
        <x-input-error class="mt-2" :messages="$errors->get('Gambar')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Simpan') }}</x-primary-button>
        <a href="{{ route('produk.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
            {{ __('Batal') }}
        </a>
    </div>
</form>


                </section>
            </div>

        </div>
    </div>
</x-app-layout>
