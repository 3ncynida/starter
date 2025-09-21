<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Produk') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('produk.create') }}" 
                   class="px-4 py-2 bg-black-600 text-black rounded hover:bg-black-700">
                    + Tambah Produk
                </a>
            </div>

            <x-table>
                <x-slot name="head">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </x-slot>

                @forelse($produks as $p)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $p->NamaProduk }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($p->Harga, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $p->Stok }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('produk.edit', $p->ProdukID) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            |
                            <form action="{{ route('produk.destroy', $p->ProdukID) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Yakin mau hapus produk ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada produk tersedia.</td>
                    </tr>
                @endforelse
            </x-table>
        </div>
    </div>
</x-app-layout> 