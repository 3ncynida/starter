<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form action="{{ route('pelanggan.update', $pelanggan->PelangganID) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label for="NamaPelanggan" class="block text-sm font-medium text-gray-700">
                            Nama
                        </label>
                        <input type="text" name="NamaPelanggan" id="NamaPelanggan"
                               value="{{ old('NamaPelanggan', $pelanggan->NamaPelanggan) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('NamaPelanggan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label for="Alamat" class="block text-sm font-medium text-gray-700">
                            Alamat
                        </label>
                        <textarea name="Alamat" id="Alamat" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('Alamat', $pelanggan->Alamat) }}</textarea>
                        @error('Alamat')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

{{-- Telepon --}}
<div class="mb-4">
    <label for="NomorTelepon" class="block text-sm font-medium text-gray-700">
        Telepon
    </label>
    <input type="text" name="NomorTelepon" id="NomorTelepon"
           value="{{ old('NomorTelepon', $pelanggan->NomorTelepon) }}"
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
    @error('NomorTelepon')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>


                    {{-- Tombol --}}
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('pelanggan.index') }}"
                           class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
