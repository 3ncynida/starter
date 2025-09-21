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
