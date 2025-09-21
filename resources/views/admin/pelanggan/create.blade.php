<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pelanggan Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Card Form -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Informasi Pelanggan') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __("Isi detail pelanggan yang ingin ditambahkan.") }}
                        </p>
                    </header>

                    <form method="POST" action="{{ route('pelanggan.store') }}" class="mt-6 space-y-6">
                        @csrf

                        <!-- Nama Pelanggan -->
                        <div>
                            <x-input-label for="NamaPelanggan" :value="__('Nama Pelanggan')" />
                            <x-text-input id="NamaPelanggan" name="NamaPelanggan" type="text" class="mt-1 block w-full" :value="old('NamaPelanggan')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('NamaPelanggan')" />
                        </div>

                        <!-- Alamat -->
                        <div>
                            <x-input-label for="Alamat" :value="__('Alamat')" />
                            <textarea id="Alamat" name="Alamat" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('Alamat') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('Alamat')" />
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <x-input-label for="NomorTelepon" :value="__('Nomor Telepon')" />
                            <x-text-input id="NomorTelepon" name="NomorTelepon" type="text" class="mt-1 block w-full" :value="old('NomorTelepon')" />
                            <x-input-error class="mt-2" :messages="$errors->get('NomorTelepon')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                            <a href="{{ route('pelanggan.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </form>
                </section>
            </div>

        </div>
    </div>
</x-app-layout>
