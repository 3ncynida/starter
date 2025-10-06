<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">

                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <strong>Ups!</strong> Ada masalah dengan input kamu.<br><br>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('produk.update', $produk->ProdukID) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Nama Produk --}}
                    <div>
                        <x-input-label for="NamaProduk" :value="__('Nama Produk')" />
                        <x-text-input id="NamaProduk" name="NamaProduk" type="text"
                                      class="mt-1 block w-full"
                                      value="{{ old('NamaProduk', $produk->NamaProduk) }}"
                                      required autofocus />
                        <x-input-error :messages="$errors->get('NamaProduk')" class="mt-2" />
                    </div>

                    {{-- Harga --}}
                    <div>
                        <x-input-label for="Harga" :value="__('Harga')" />
                        <x-text-input id="Harga" name="Harga" type="number"
                                      class="mt-1 block w-full"
                                      value="{{ old('Harga', $produk->Harga) }}"
                                      required />
                        <x-input-error :messages="$errors->get('Harga')" class="mt-2" />
                    </div>

                    {{-- Stok --}}
                    <div>
                        <x-input-label for="Stok" :value="__('Stok')" />
                        <x-text-input id="Stok" name="Stok" type="number"
                                      class="mt-1 block w-full"
                                      value="{{ old('Stok', $produk->Stok) }}"
                                      required />
                        <x-input-error :messages="$errors->get('Stok')" class="mt-2" />
                    </div>

                        <!-- Satuan -->
    <div>
        <x-input-label for="Satuan" :value="__('Satuan')" />
                <select name="Satuan" id="Satuan" class="w-full border rounded p-2" required>
                <option value="pcs">Pcs</option>
                <option value="kg">Kg</option>
                <option value="pack">Pack</option>
                <option value="dus">Dus</option>
            </select>
        <x-input-error class="mt-2" :messages="$errors->get('Satuan')" />
    </div>

<!-- Gambar -->
<div>
    <x-input-label for="Gambar" :value="__('Gambar Produk')" />
    <div class="mt-2">
        <img src="{{ asset('storage/' . $produk->Gambar) }}" alt="Preview Produk" class="w-32 h-32 object-cover rounded mb-2">
    </div>
    <input id="Gambar" name="Gambar" type="file" class="mt-1 block w-full border rounded" accept="image/*" />
    <p class="mt-1 text-sm text-gray-600">Biarkan kosong jika tidak ingin mengubah gambar</p>
    <x-input-error class="mt-2" :messages="$errors->get('Gambar')" />
</div>


                    {{-- Tombol --}}
                    <div class="flex items-center gap-4">
                        <x-primary-button>
                            {{ __('Update') }}
                        </x-primary-button>

                        <a href="{{ route('produk.index') }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
