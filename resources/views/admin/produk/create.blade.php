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

                    <form method="POST" action="{{ route('produk.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
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

                        <!-- Satuan -->
                        <div>
                            <x-input-label for="Satuan" :value="__('Satuan')" />
                            <select name="Satuan" id="Satuan" class="w-full border rounded p-2" required>
                                <option value="pcs">Pcs</option>
                                <option value="kg">Kg</option>
                                <option value="pack">Pack</option>
                                <option value="dus">Dus</option>
                                <option value="sisir">Sisir</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('Satuan')" />
                        </div>

                        <!-- Gambar -->
                        <div>
                            <x-input-label for="Gambar" :value="__('Gambar Produk')" />
                            <input id="Gambar" name="Gambar" type="file" class="mt-1 block w-full border rounded" />
                            <x-input-error class="mt-2" :messages="$errors->get('Gambar')" />
                        </div>

                        <!-- 🔥 Bagian Promosi -->
                        <div class="border-t border-gray-200 pt-4 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">
                                {{ __('Promosi Produk') }}
                            </h3>

                            <!-- Aktifkan Promosi -->
                            <div class="flex items-center">
                                <input type="checkbox" id="Promosi" name="Promosi" value="1" {{ old('Promosi') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="Promosi" class="ml-2 text-sm text-gray-700">Aktifkan promosi untuk produk ini</label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('Promosi')" />

                            <!-- Diskon Persen -->
                            <div class="mt-4">
                                <x-input-label for="DiskonPersen" :value="__('Diskon (%)')" />
                                <x-text-input id="DiskonPersen" name="DiskonPersen" type="number" step="0.01" min="0" max="100" class="mt-1 block w-full" :value="old('DiskonPersen')" />
                                <x-input-error class="mt-2" :messages="$errors->get('DiskonPersen')" />
                            </div>

                            <!-- Tanggal Mulai -->
                            <div class="mt-4">
                                <x-input-label for="TanggalMulaiPromosi" :value="__('Tanggal Mulai Promosi')" />
                                <x-text-input id="TanggalMulaiPromosi" name="TanggalMulaiPromosi" type="date" class="mt-1 block w-full" :value="old('TanggalMulaiPromosi')" />
                                <x-input-error class="mt-2" :messages="$errors->get('TanggalMulaiPromosi')" />
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="mt-4">
                                <x-input-label for="TanggalSelesaiPromosi" :value="__('Tanggal Selesai Promosi')" />
                                <x-text-input id="TanggalSelesaiPromosi" name="TanggalSelesaiPromosi" type="date" class="mt-1 block w-full" :value="old('TanggalSelesaiPromosi')" />
                                <x-input-error class="mt-2" :messages="$errors->get('TanggalSelesaiPromosi')" />
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="flex items-center gap-4 mt-6">
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
