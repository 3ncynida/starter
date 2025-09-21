<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Tombol Tambah --}}
            <div class="mb-4">
                <a href="{{ route('produk.create') }}"
                   class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">
                    + Tambah Produk
                </a>
            </div>

            <!-- Tabel --><div class="bg-white shadow-sm rounded-lg">
    <x-table class="w-full">
        <x-slot name="head">
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
        </x-slot>

        @foreach($pelanggan as $item)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">{{ $item->NamaPelanggan }}</td>
                <td class="px-6 py-4">{{ $item->Alamat }}</td>
                <td class="px-6 py-4">{{ $item->NomorTelepon }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('pelanggan.edit', $item->PelangganID) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                </td>
            </tr>
        @endforeach
    </x-table>
</div>

        </div>
    </div>
</x-app-layout>
