<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('pelanggan.create') }}" 
                   class="px-4 py-2 bg-black-600 text-black rounded hover:bg-black-700">
                    + Tambah Pelanggan
                </a>
            </div>
            <x-table>
                <x-slot name="head">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </x-slot>

                @foreach($pelanggan as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->PelangganID }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->NamaPelanggan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->Alamat }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->NomorTelepon }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('pelanggan.edit', $item->PelangganID) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            |
                            <form action="{{ route('pelanggan.destroy', $item->PelangganID) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Hapus pelanggan ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </div>
    </div>
</x-app-layout>
